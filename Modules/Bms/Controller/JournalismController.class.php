<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Journalism控制器
 * 后台行为的 增删改查
 */
class JournalismController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('Journalism');
        $data=$model->search();
        $this->assign([
            'content_header'=>'列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('Journalism/index');

    }

    public function  Journalism_add(){
        $model=D('Menus');
        $this->assign([
            'content_header'=>'新闻增加',
        ]);
        $this->display('Journalism/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('Journalism');
        $data=$model->model_desc('*',['id'=>$id]);
        $this->assign([
            'content_header'=>'新闻修改',
            'list'=>$data,
        ]);
        $this->display('Journalism/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('Journalism');
        $arr=[
            'journalism_title'=>$post['journalism_title'],
            'journalism_content'=>$post['journalism_content'],
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
     *  修改数据
     *
     */
    public function  update(){
        $post=I('post.');
        $model=D('Journalism');
        $arr=[
            'journalism_title'=>$post['journalism_title'],
            'journalism_content'=>$post['journalism_content'],
            'add_time'=>date('Y-m-d H:i:s', time()),
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

        $model=D('Journalism');
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
        $model=D('Journalism');

        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }
    /**
     *  单改状态
     *
     */
    public function  statusOne(){
        $id=I('get.id');
       $value=I('get.value');

        $model=D('Journalism');
        $data=$model->model_status($value,['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }
    /**
     *  多改状态
     *
     */
    public function  statusAll(){
         $ids=I('post.ids');
       $value=I('get.value');
         $ids = join(',', $ids);
        $model=D('Journalism');
        $data=$model->model_status($value,['id'=>['in',$ids]]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
