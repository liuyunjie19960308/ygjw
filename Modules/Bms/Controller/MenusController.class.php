<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * 导航栏控制器
 * 后台行为的 增删改查
 */
class MenusController extends BmsBaseController {

//    /**
//     *  查询搜索
//     *
//     */
//    public function  lists(){
//        $post=I('post.');
//        //面包屑导航
//        $model=D('Menus');
//        $data=$model->search();
//
//
//        $this->assign([
//            'content_header'=>'技术标题类型列表',
//            'list'=>$data['list'],
//            'page'=>$data['show'],
//        ]);
//        $this->display('Menus/index');
//
//    }
    /**
     *  显示
     *
     */
    public function  lists(){
        $model=D('Menus');
        $data=$model->get_tree();
        $this->assign([
            'content_header'=>'导航栏列表',
            'list'=>$data,
        ]);
        $this->display('Menus/index');
    }
    public function  menus_add(){
        $model=D('Menus');
        $data=$model->get_tree([]);

        $this->assign([
            'content_header'=>'导航栏增加',
            'Privilege'=>$data,
        ]);
        $this->display('Menus/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('Menus');
        $data=$model->model_desc('*',['id'=>$id]);
        $Privilege_id= $model ->category_id($id);

        $Privilege= $model ->get_tree([]);
        $this->assign([
            'content_header'=>'导航栏修改',
            'Privilege_id'=>$Privilege_id,
            'Privilege'=>$Privilege,
            'list'=>$data,
        ]);
        $this->display('Menus/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('Menus');
        $arr=[
            'name'=>$post['name'],
            'url'=>$post['url'],
            'sort'=>intval($post['sort']),
            'pid'=>intval($post['pid']),

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
        $model=D('Menus');
        $arr=[
            'name'=>$post['name'],
            'url'=>$post['url'],
            'sort'=>intval($post['sort']),
            'pid'=>intval($post['pid']),

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

        $model=D('Menus');
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
        $model=D('Menus');
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
        $model=D('Menus');
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

        $model=D('Menus');
        $data=$model->model_status($value,['id'=>['in',$ids]]);

        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }
}
