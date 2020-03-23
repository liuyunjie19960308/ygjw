<?php
namespace Common\Service;
use Common\Base\BaseService;

/**
 * Class FileService
 * @package Common\Service
 * 文件表 数据服务层
 */
class FileService extends BaseService {

    /**
     * @param array $custom_param
     * @return array
     * 获取文件列表
     */
    public function select($custom_param = []) {
        //表别名
        $param['alias']                 = 'file';
        //关联的表 file_extend
        $param['join']                  = [
            'LEFT JOIN ' . C('DB_PREFIX') . 'file_extend file_ext ON file_ext.file_id = file.id',
        ];
        //查询的字段
        $param['field']                 = 'file.*,file_ext.description';   //排序

        //是否有外部其他自定义条件  如果有替换条件
        if(!empty($custom_param)) {
            $param = $this->customParam($param, $custom_param);
        }

        $result = D('File')->getList($param);

        if(empty($result['list'])) {
            return [];
        }

        //处理图片信息
        foreach($result['list'] as &$value) {
            //判断 是否 需要获取 缩略图地址
            if(!isset($value['thumb_prefix']) && !isset($value['thumb_suffix'])) {
                continue;
            }
            //获取缩略图地址 数组
            $value = $this->_getThumb($value);
        }

        return $result;
    }

    /**
     * @param $file
     * @return mixed
     * 获取缩略图地址
     */
    private function _getThumb($file) {
        //缩略图前缀
        $prefixes	=	explode(',', $file['thumb_prefix']);
        //缩略图后缀
        $suffixes   =   explode(',', $file['thumb_suffix']);
        //使用 前缀/后缀 数量多的 缀名
        $length     =   count($prefixes) >= count($suffixes) ? count($prefixes) : count($suffixes);
        // 生成图像缩略图地址
        for($i = 0; $i < $length; $i++) {
            $prefix =   isset($prefixes[$i]) ? $prefixes[$i] : $prefixes[0];//前缀
            $suffix =   isset($suffixes[$i]) ? $suffixes[$i] : $suffixes[0];//后缀
            //相对路径
            $file['thumbs'][]      = dirname($file['path']) . '/' . $prefix . basename($file['path'], '.' . $file['ext']) . $suffix . '.' . $file['ext'];
            //绝对路径
            $file['abs_thumbs'][]  = dirname($file['abs_url']) . '/' . $prefix . basename($file['abs_url'], '.' . $file['ext']) . $suffix . '.' . $file['ext'];;
        }
        //释放数据
        unset($file['thumb_prefix'], $file['thumb_suffix'], $file['ext']);

        return $file;
    }

    /**
     * @param $id
     * @param $thumb
     * 加载图片资源
     */
    public function loadImg($id, $thumb) {
        if(empty($id)) {
            echo ''; exit;
        }
        //根据文件ID 获取图片信息
        $image = api('File/getFiles', [$id, ['id', 'mime', 'path']])[0];
        //未获取到图片信息，输出空字符串
        if(empty($image)) {
            echo ''; exit;
        }
        //输出头信息
        header("Content-type:{$image['mime']}");
        //初始化图像处理类
        $Img = new \Think\Image();
        //打开图片文件异常，输出空字符串
        try {
            $Img->open('.' . $image['path']);
        } catch(\Exception $e) {
            echo ''; exit;
        }

        // 缩略图
//        if(isset($thumb)) {
//            preg_match('/^\d+(_\d+(_\d)?)?$/', $thumb, $res);
//            if(!empty($res)) {
//                $px = $res[0];
//                $pxa = explode('_', $px);
//
//                $params = [];
//                $params[0] = $pxa[0];
//                switch(count($pxa)) {
//                    case 1:
//                        $params[1] = $pxa[0];
//                        break;
//                    case 2:
//                        $params[1] = $pxa[1];
//                        break;
//                    case 3:
//                        $params[1] = $pxa[1];
//                        $params[2] = $pxa[2];
//                        break;
//                }
//                call_user_func_array([$Img, 'thumb'], $params);
//            }
//        }
        try {
            //输出图片资源异常，输出空字符串
            $Img->save();
        } catch(\Exception $e) {
            echo ''; exit;
        }
        exit;
    }
}