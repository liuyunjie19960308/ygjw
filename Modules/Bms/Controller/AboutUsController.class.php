<?php
namespace Bms\Controller;
/**
 * Class ActionController
 * @package Bms\Controller
 * Banner控制器
 * 后台行为的 增删改查
 */
class AboutUsController extends BmsBaseController {

    /**
     *  查询搜索
     *
     */
    public function  lists(){
        //面包屑导航
        $model=D('Banner');
        $data=$model->search();
        $this->assign([
            'content_header'=>'Banner列表',
            'list'=>$data['list'],
            'page'=>$data['show'],
        ]);
        $this->display('Banner/index');

    }

    public function  Banner_add(){
        $model=D('Menus');
        $data=$model->get_tree();
        $this->assign([
            'content_header'=>'Banner增加',
            'Privilege'=>$data,
        ]);
        $this->display('Banner/add');
    }
    /**
     * 显示数据
     *
     */
    public function  desc(){
        $id=I('get.id');
        $model=D('Banner');
        $Menus=D('Menus');
        $data=$model->model_desc('*',['id'=>$id]);
        $Privilege_id= $Menus ->category_id($id);
        $Privilege= $Menus ->get_tree([]);
        $this->assign([
            'content_header'=>'Banner修改',
            'Privilege_id'=>$Privilege_id,
            'Privilege'=>$Privilege,
            'list'=>$data,
        ]);
        $this->display('Banner/update');

    }
    /**
     *  增加数据
     *
     */
    public function  add(){
        $post=I('post.');
        $model=D('Banner');
        $arr=[
            'banner_name'=>$post['banner_name'],
            'banner_image_img'=>$post['banner_image_img'],
            'banner_sort'=>intval($post['banner_sort']),
            'banner_type'=>intval($post['banner_type']),
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
        $model=D('Banner');
        $arr=[
            'banner_name'=>$post['banner_name'],
            'banner_image_img'=>$post['banner_image_img'],
            'banner_sort'=>intval($post['banner_sort']),
            'banner_type'=>intval($post['banner_type']),
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

        $model=D('Banner');
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
        $model=D('Banner');

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

        $model=D('Banner');
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
        $model=D('Banner');
        $data=$model->model_status($value,['id'=>['in',$ids]]);
        if($data) {
            $this->success('成功！');
        } else {
            $this->error('失败');
        }
    }

    public function doUpload()
    {


        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     'Public/Bms/Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
          $url=$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/Public/Bms/Uploads/' . $info['file']['savename'];
            $this->json_retrun('1','成功',$url);
        }
    }

}
