<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * CustomerInfo控制器
 * 后台行为的 增删改查
 */
class CustomerInfoController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('customer_info');
        $data=$model->search();
        $this->assign([
            'content_header'=>'客户垂询列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('CustomerInfo/index');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('customer_info');
        $arr=[
            'name'=>$post['name'],
            'title'=>$post['title'],
            'phone'=>$post['phone'],
            'email'=>$post['email'],
            'g_id'=>$post['g_id'],
            'company'=>$post['company'],
            'desc'=>$post['desc'],
            'add_time'=>date('Y-m-d H:i:s', time()),

        ];
        $data=$model->model_add($arr);
        if($data) {
            $this->json_retrun('200','添加成功');
        } else {
            $this->json_retrun('201','失败');
        }
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('customer_info');
        $data=$model->model_desc('*',['id'=>$id]);

        $this->assign([
            'content_header'=>'客户垂询详情',
            'list'=>$data,
        ]);
        $this->display('CustomerInfo/update');

    }


    /**
     *  多条删除数据
     *
     */
    public function  deleteAll(){
        $post=I('post.ids');
        $ids = join(',', $post);

        $model=D('customer_info');
        $data=$model->model_delete(['id'=>['in', $ids]]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }
    /**
     *  多条删除数据
     *
     */
    public function  deleteOne(){
        $id=I('get.ids');
        $model=D('customer_info');
        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
