<?php
namespace Bms\Controller;

class TechnologyController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('technology');
        $data=$model->get_tree();
        $this->assign([
            'content_header'=>'技术标题类型列表',
            'list'=>$data,
        ]);
        $this->display('Technology/index');

    }

    public function  Technology_add(){
        $model=D('technology');
        $data=$model->get_tree([]);
        $this->assign([
            'Privilege'=>$data,
            'content_header'=>'技术标题类型增加',
        ]);
        $this->display('Technology/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('technology');
        $data=$model->model_desc('*',['id'=>$id]);
        $Privilege_id= $model ->category_id($id);
        $Privilege= $model ->get_tree([]);
        $data['icon'] = api('File/getFiles',[$data['icon']]);
        $this->assign([
            'content_header'=>'技术标题类型修改',
            'Privilege_id'=>$Privilege_id,
            'Privilege'=>$Privilege,
            'list'=>$data,
        ]);
        $this->display('Technology/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('technology');
        $arr=[
            'name'=>$post['name'],
            'icon'=>$post['icon']?$post['icon']:'',
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
        $model=D('technology');
        $arr=[
            'name'=>$post['name'],
            'icon'=>$post['icon']?$post['icon']:'',
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

        $model=D('technology');
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
        $model=D('technology');

        $data=$model->model_delete(['id'=>$id]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }


}
