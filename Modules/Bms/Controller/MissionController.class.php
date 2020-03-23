<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Mission控制器
 * 后台行为的 增删改查
 */
class MissionController extends BmsBaseController {

    public function mission_index(){
            $Model=D('mission');
            $data=$Model->model_desc('*',['id'=>1]);
            $this->assign([
                'content_header'=>'公司介绍',
                'list'=>$data,
            ]);
            $this->display('Mission/index');
    }
    /**
     *  公司介绍修改
     *
     */
    public function mission_update(){
        $post=I('post.');
        $Model=D('mission');
        $data=[
          'content'=>$post['content'],
          'images'=>$post['images'],
          'title'=>$post['title'],
        ];
        $res=$Model->model_update(['id'=>1],$data);
        if ($res){
            $this->json_retrun('200','成功');
        }else{
            $this->json_retrun('201','失败');
        }

    }

}
