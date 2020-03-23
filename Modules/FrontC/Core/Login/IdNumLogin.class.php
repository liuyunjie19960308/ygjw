<?php
namespace FrontC\Core\Login;

use FrontC\Core\Login\Login;

/**
 * 身份证号
 */
class IdNumLogin extends Login
{
    
    /**
     * [login 登录]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:42:51+0800
     * @return   [type]                   [description]
     */
    public function login()
    {
        // 获取登录参数
        $idNum    = $this->account->getIdNum();
        $password = $this->account->getPassword();

        if (empty($idNum)) {
            return $this->response->setInfo(false, '请输入身份证号！');
        }
        if (empty($password)) {
            return $this->response->setInfo(false, '请输入登陆密码！');
        }

        // 查询用户信息
        $member = M('UserProfile')->where(['id_num'=>$idNum])->field('id user_id,password,type,status')->find();

        // 如果查不到用户
        if (empty($member)) {
            return $this->response->setInfo(false, '未查到该身份信息！');
        }

        if ($member['password'] != md5($password)) {
            return $this->response->setInfo(false, '密码错误！');
        }

        // // 验证用户状态
        // if(!$this->checkStatus($member['status'])) {
        //     return false;
        // }

        // // 更新用户登录信息
        // $this->updLoginInfo($member['m_id']);
        unset($member['password'], $member['status']);
        
        return $this->response->setInfo(true, '登录成功！', $member);
    }
}
