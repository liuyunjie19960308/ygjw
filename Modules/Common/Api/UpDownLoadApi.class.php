<?php
namespace Common\Api;

/**
 * Class TreeApi
 * @package Common\Api
 * 文件上传、下载服务
 */
class UpDownLoadApi {
    /**
     * @var string
     * 接收信息
     */
    protected static $apiMsg = '';

    /**
     * @param array $request
     * @return array
     * 上传图片
     */
    public static function upload($request = array()) {
        //TODO: 用户登录检测
        //返回标准数据
        $response   = array('status' => 1, 'info' => '上传成功', 'data' => '', 'url' => '');
        //判断上传函数
        $type       = empty($request['type']) ? strtoupper('picture') : strtoupper($request['type']);
        //上传驱动类型
        $driver     = C(''.$type.'_UPLOAD_DRIVER');
        //执行上传 返回上传信息
        $info = self::_doUpload(
            $_FILES,                            //所有文件
            C(''.$type.'_UPLOAD'),              //上传配置信息
            C(''.$type.'_UPLOAD_DRIVER'),       //上传驱动类型
            C("UPLOAD_{$driver}_CONFIG"),   //上传驱动配置
            $request                            //其他信息  设置上传路径等其他信息
        ); //TODO:上传到远程服务器

        ///判断成功还是失败
        if(is_array($info)) {
            foreach ($info as $key => &$value) {
                //处理图片路径 在模板里的url路径
                $value['path'] = substr(C('PICTURE_UPLOAD.rootPath'), 1).$value['savepath'].$value['savename'];
                //绝对路径
                //$value['abs_url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $value['path'];
                //$scheme = empty($_SERVER['REQUEST_SCHEME']) ? 'http' : $_SERVER['REQUEST_SCHEME'];
                //绝对路径
                //$value['abs_url'] = $scheme . '://' . $_SERVER['HTTP_HOST'] . $value['path'];
                $value['abs_url'] = C('FILE_HOST') . $value['path'];
                //已经存在文件记录
                if(isset($value['id']) && is_numeric($value['id'])) {
                    //文件下载路径
                    $value['download_url'] = U('UpDownLoad/download',array('id'=>$value['id']));
                    //合并上传信息和标准信息
                    $temp_response[$key]   = array_merge($value, $response);
                    //进入下一循环
                    continue;
                }
                // 是否传到云
                if ($request['is_oss'] == 1 || !isset($request['is_oss'])) {
                    // 传到云
                    //$res   = self::upToOss($value);
                    //$value = array_merge($value, $res);
                }
                //文件类型
                $value['mime'] = $value['type'];
                //创建时间
                $value['create_time'] = NOW_TIME;
                //记录文件信息
                if($id = M('File')->add($value)) {
                    $value['id'] = $id;
                    //文件下载路径
                    $value['download_url'] = U('UpDownLoad/download',array('id'=>$value['id']));
                } else {
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                    unset($info[$key]);
                }
                //合并上传信息和标准信息
                $temp_response[$key] = array_merge($value, $response);
            }
            $response = $temp_response;
        } else {
            $response['status'] = 0;
            //错误信息
            $response['info']   = $info;
        }

        return $response;
    }

    /**
     * [upToOss 传到云]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-15T15:25:13+0800
     * @param    array                    $file_info [description]
     * @return   [type]                              [description]
     */
    private function upToOss($file_info = [])
    {
        $result = api('AliOss/upload', [$file_info]);
        if ($result['status'] == 1) {
            // 删除本地文件
            return $result['data'];
        } else {
            return ['oss_request_url' => ''];
        }
    }

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @param  array  $request  其他信息
     * @return array           文件上传成功后的信息
     */
    private static function _doUpload($files, $setting, $driver = 'Local', $config = null, $request = array()) {
        //回调函数 判断文件数据库记录是否存在 如果存在，判断文件是否存在 如果存在直接返回数据库记录 不存在就上传 返回上传信息
        $setting['callback'] = array(new \Common\Api\UpDownLoadApi(), 'isFile');
        //删除垃圾文件记录 本地文件不存在数据库记录存在
        $setting['removeTrash'] = array(new \Common\Api\UpDownLoadApi(), 'removeTrash');
        //上传配置信息
        $setting = self::_returnSetting($request, $setting);
        //初始化上传类
        $Upload = new \Think\Upload($setting, $driver, $config);
        //执行上传
        $info   = $Upload->upload($files);
        //判断是否上传成功
        if($info) {
            //文件上传成功，返回文件信息
            // "name"   :   "2014-08-12_162223.png",
            // "type"   :   "application/octet-stream",
            // "size"   :   64483,
            // "key"    :   "download",
            // "ext"    :   "png",
            // "md5"    :   "dc113e5f7a34ee0f4410877cd9132522",
            // "sha1"   :   "f06bb91a1edf648770efb1e54c7e9d11e3a83a0b",
            // "savename"   :   "55f66b9a82544.png",
            // "savepath"   :   "2015-09-14/",
            // "thumb_prefix"   :   "",
            // "thumb_suffix"   :   "",
            return $info;
        } else {
            return $Upload->getError();
        }
    }

    /**
     * @param $request
     * @param $setting
     * 配置信息 处理每个上传不同的配置信息
     */
    private function _returnSetting($request, $setting) {
        //保存路径
        !empty($request['save_path'])       ? $setting['savePath']      = $request['save_path'].'/' : '';
        //后缀名
        !empty($request['exts'])            ? $setting['exts']          = $request['exts']          : '';
        //文件大小
        !empty($request['max_size'])        ? $setting['maxSize']       = $request['max_size']      : '';
        //是否加上水印
        isset($request['is_water'])         ? $setting['isWater']       = $request['is_water']      : '';
        //是否生成缩略图
        isset($request['is_thumb'])         ? $setting['isThumb']       = $request['is_thumb']      : '';
        //缩略图宽度
        !empty($request['thumb_width'])     ? $setting['thumbMaxWidth'] = $request['thumb_width']   : '';
        //缩略图高度
        !empty($request['thumb_height'])    ? $setting['thumbMaxHeight']= $request['thumb_height']  : '';
        //缩略图前缀
        !empty($request['thumb_prefix'])    ? $setting['thumbPrefix']   = $request['thumb_prefix']  : '';
        //缩略图后缀
        !empty($request['thumb_suffix'])    ? $setting['thumbSuffix']   = $request['thumb_suffix']  : '';

        return $setting;
    }

    /**
     * 下载指定文件
     * @param  integer $id   文件ID
     * @param  $callback  回调函数
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public static function download($id, $callback = null, $args = null) {
        //获取下载文件信息
        $file = M('File')->where(array('id'=>$id))->find();
        if(!$file) {
            self::setApiMsg('不存在该文件！');
            return false;
        }
        //下载文件
        switch ($file['location']) {
            case 0:
                //下载本地文件
                $file['rootpath'] = C('ATTACHMENT_UPLOAD.rootPath');
                return self::_downLocalFile($file, $callback, $args);
            default:
                self::setApiMsg('不支持的文件存储类型！');
                return false;
        }
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private static function _downLocalFile($file, $callback = null, $args = null) {
        //判断文件是否存在
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])) {
            //调用回调函数新增下载数
            is_callable($callback) && call_user_func($callback, $args);

            //执行下载 //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['mime']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
                //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            self::setApiMsg('文件已被删除！');
            return false;
        }
    }

    /**
     * @param $file 文件信息
     * @return boolean  文件信息， false - 不存在该文件
     * @throws \Exception
     * 检测当前上传的文件是否存在记录
     */
    public static function isFile($file) {
        if(empty($file['md5'])) {
            throw new \Exception('缺少参数:md5');
        }
        //查找文件
        $where = array('md5' => $file['md5'],'sha1'=>$file['sha1']);
        return M('File')->where($where)->find();
    }

    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     */
    public static function removeTrash($data) {
        M('File')->where(array('id'=>$data['id']))->delete();
    }

    /**
     * @param string $msg
     * @return string
     * 设置信息
     */
    final protected static function setApiMsg($msg = '') {
        self::$apiMsg = $msg;
    }

    /**
     * @return string
     * 获取信息
     */
    final public static function getApiMsg() {
        return self::$apiMsg;
    }
}