<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * EnterpriseContact控制器
 * 后台行为的 增删改查
 */
class EnterpriseContactController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('enterprise_contact');
        $data=$model->search();
        $this->assign([
            'content_header'=>'公司所有联系',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('EnterpriseContact/index');

    }

    public function  EnterpriseContact_add(){
        $this->assign([
            'content_header'=>'增加联系地址',
        ]);
        $this->display('EnterpriseContact/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('enterprise_contact');
        $data=$model->model_desc('*',['id'=>$id]);

        $this->assign([
            'content_header'=>'修改公司联系地址',
            'list'=>$data,
        ]);
        $this->display('EnterpriseContact/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('enterprise_contact');
        $arr=[
            'images'=>$post['images'],
            'name'=>$post['name'],
            'phone'=>$post['phone'],
            'title'=>$post['title'],
            'place'=>$post['place'],
            'customer'=>$post['customer'],
            'mailbox'=>intval($post['mailbox']),
            'sort'=>intval($post['sort'])
        ];
        $data=$model->model_add($arr);
        if($data) {
            $this->json_retrun('200','添加成功');
        } else {
            $this->json_retrun('201','失败');
        }
    }

    /**
     *  修改数据
     *
     */
    public function  update(){
        $post=I('post.');
        $model=D('enterprise_contact');
        $arr=[
            'images'=>$post['images'],
            'name'=>$post['name'],
            'phone'=>$post['phone'],
            'title'=>$post['title'],
            'place'=>$post['place'],
            'customer'=>$post['customer'],
            'mailbox'=>intval($post['mailbox']),
            'sort'=>intval($post['sort'])
        ];
        $data=$model->model_update(['id'=>$post['id']],$arr);
        if($data) {
            $this->json_retrun('200','修改成功');
        } else {
            $this->json_retrun('201','失败');
        }
    }
    /**
 *  多条删除数据
 *
 */
    public function  deleteAll(){
        $post=I('post.ids');
        $ids = join(',', $post);

        $model=D('enterprise_contact');
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
        $model=D('enterprise_contact');

        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
