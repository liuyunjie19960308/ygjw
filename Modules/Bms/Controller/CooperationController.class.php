<?php
namespace Bms\Controller;

class CooperationController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('cooperation');
        $data=$model->search();
        $this->assign([
            'content_header'=>'合作伙伴类别列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('Cooperation/index');

    }

    public function  Cooperation_add(){
        $this->assign([
            'content_header'=>'合作伙伴类别增加',
        ]);
        $this->display('Cooperation/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('cooperation');

        $data=$model->model_desc('*',['id'=>$id]);
        $data['images'] = api('File/getFiles',[$data['images']]);
        $this->assign([
            'content_header'=>'Cooperation修改',
            'list'=>$data,
        ]);
        $this->display('Cooperation/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('cooperation');
        $arr=[
            'name'=>$post['name'],
            'images'=>$post['images'],
            'sort'=>intval($post['sort']),
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
        $model=D('cooperation');
        $arr=[
            'name'=>$post['name'],
            'images'=>$post['images'],
            'sort'=>intval($post['sort']),
        ];;
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

        $model=D('cooperation');
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
        $model=D('cooperation');

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

        $model=D('cooperation');
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
        $model=D('cooperation');
        $data=$model->model_status($value,['id'=>['in',$ids]]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
