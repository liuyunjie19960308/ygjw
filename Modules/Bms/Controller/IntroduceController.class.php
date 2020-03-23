<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Introduce控制器
 * 后台行为的 增删改查
 */
class IntroduceController extends BmsBaseController {

    public function introduce_index(){
            $Model=D('introduce');
            $data=$Model->model_desc('*',['id'=>1]);
            $this->assign([
                'content_header'=>'公司介绍',
                'list'=>$data,
            ]);
            $this->display('Introduce/index');
    }
    /**
     *  公司介绍修改
     *
     */
    public function introduce_update(){
        $post=I('post.');
        $Model=D('introduce');
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
