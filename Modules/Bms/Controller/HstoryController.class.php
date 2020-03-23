<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Hstory控制器
 * 后台行为的 增删改查
 */
class HstoryController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('hstory');
        $data=$model->search();
        $this->assign([
            'content_header'=>'公司资质列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('Hstory/index');

    }

    public function  Hstory_add(){
        $this->assign([
            'content_header'=>'增加公司历史介绍',
        ]);
        $this->display('Hstory/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('hstory');
        $data=$model->model_desc('*',['id'=>$id]);

        $this->assign([
            'content_header'=>'修改公司历史介绍',
            'list'=>$data,
        ]);
        $this->display('Hstory/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('hstory');
        $arr=[
            'title'=>$post['title'],
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
        $model=D('hstory');
        $arr=[
            'title'=>$post['title'],
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

        $model=D('hstory');
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
        $model=D('hstory');

        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
