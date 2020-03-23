<?php
namespace Bms\Controller;

/**
 * Class UpDownLoadController
 * @package Bms\Controller
 * 文件上传、下载控制器
 */
class UpDownLoadController extends BmsBaseController {

    /**
     * 上传文件
     */
    function upload() {
        $result = api('UpDownLoad/upload',array(I('request.')));
        $this->ajaxReturn(array_dimension($result) == 1 ? $result : $result['fileData']);
    }

    /**
     * @param null $id
     * 下载文件
     */
    function download($id = null) {
        if(empty($id) || !is_numeric($id)) {
            $this->error('参数错误！');
        }
        if(!api('UpDownLoad/download', array($id))) {
            $this->error(api('UpDownLoad/getApiMsg'));
        }
    }

    /**
     * [获取阿里云视频上传凭证]
     * @return [type] [description]
     */
    public function getUploadRequest() {
        $request = I('request.');
        //获取凭证
        $result = tryUploadLocalVideo('Video',$request['name']);
        if (!empty($result['UploadAddress'])) {
            $this->ajaxReturn(['flag'=>'success','data'=>$result]);
        }else{
            $this->ajaxReturn(['flag'=>'error','message'=>$result]);
        }
        // 返回信息
        
    }

        /**
     * [获取阿里云视频播放地址]
     * @return [type] [description]
     */
    public function getVideoAddress() {
        $request = I('request.');
        //获取凭证
        $info = getPlayInfo($request['video_id']);
        $this->ajaxReturn(['src'=>$info['PlayInfoList']['PlayInfo'][0]['PlayURL'],'poster'=>$info['PlayInfoList']['PlayInfo'][0]['PlayURL']]);
        // 返回信息
    }

    public function getUserId(){
        C(load_config(CONF_PATH . 'alivod' . CONF_EXT));
        $this->ajaxReturn(C('USER_ID'));
    }
}