<?php
namespace Bms\Controller;
/**
 * @package Bms\Controller
 * Policy控制器
 *
 */
class PolicyController extends BmsBaseController {
    public function policy_info(){
        $data=M('policy')->find();

        $this->assign([
            'content_header'=>'企业隐私政策',
            'list'=>$data,
        ]);
        $this->display('Policy/policy_update');
    }
    public function policy_update(){
        $post=I('post.');
        $Model=M('policy');
        $arr=[
            'policy_title'=>$post['policy_title'],
            'policy_statement_content'=>$post['policy_statement_content'],
            'status'=>1
        ];
        $res=$Model->where(['id'=>1])->save($arr);
        if ($res){
            return $this->json_retrun('200','操作成功');
        }else{
            return $this->json_retrun('201','操作失败');
        }

    }
    public function statement_info(){
        $data=M('statement')->find();
        $this->assign([
            'list'=>$data,
            'content_header'=>'网站法律声明',
        ]);
        $this->display('Policy/statement_update');
    }
    public function statement_update(){
        $post=I('post.');
        $Model=M('statement');
        $arr=[
            'enterprise_title'=>$post['enterprise_title'],
            'enterprise_statement_content'=>$post['enterprise_statement_content'],
            'status'=>1
        ];
        $res=$Model->where(['id'=>1])->save($arr);
        if ($res){
            return $this->json_retrun('200','操作成功');
        }else{
            return $this->json_retrun('201','操作失败');
        }

    }
}
