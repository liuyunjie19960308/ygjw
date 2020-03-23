<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Spirit控制器
 * 后台行为的 增删改查
 */
class SpiritController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('spirit');
        $data=$model->search();
        $this->assign([
            'content_header'=>'精神列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('Spirit/index');

    }

    public function  Spirit_add(){
        $this->assign([
            'content_header'=>'增加精神',
        ]);
        $this->display('Spirit/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('Spirit');
        $data=$model->model_desc('*',['id'=>$id]);

        $this->assign([
            'content_header'=>'修改精神',
            'list'=>$data,
        ]);
        $this->display('Spirit/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('spirit');
        $arr=[
            'icon'=>$post['icon'],
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
        $model=D('spirit');
        $arr=[
            'icon'=>$post['icon'],
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

        $model=D('spirit');
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
        $model=D('spirit');

        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
