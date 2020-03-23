<?php
namespace FrontC\Core\Login;

use FrontC\Core\Login\Login;

/**
 * 短信验证码登录类
 */
class VerifyLogin extends Login
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
        $account = $this->account->getAccount();
        $verify  = $this->account->getVerify();

        if (empty($account)) {
            return $this->response->setInfo(false, '请输入手机号！');
        }
        if (empty($verify)) {
            return $this->response->setInfo(false, '请输入验证码！');
        }
        // 验证验证码
        $check_res = api('Verify/checkVerify', [$account, $verify, 'login']);
        if ($check_res !== true) {
            return $this->response->setInfo(false, $check_res);
        }

        // 查询用户信息
        $member = M('Member')->where(['account'=>$account])->field('id m_id,status')->find();

        // 如果查不到用户就进行注册
        if (empty($member)) {
            // 进行注册
            $Register  = new \FrontC\Core\Login\Register($this->response);
            // 设置账号数据
            $Register->setData('account',  $account);
            $Register->setData('mobile',   $account);
            $Register->setData('nickname', D('FrontC/Member', 'Service')->accountFormat($account));
            $Register->setData('openid',   $this->account->getOpenid());
            $Register->setData('platform', $this->account->getPlatform());

            $m_id = $Register->register();

            if (!$m_id) {
                return false;
            }

            $member = ['m_id'=>$m_id, 'status'=>1];
        }

        // 验证用户状态
        if(!$this->checkStatus($member['status'])) {
            return false;
        }

        // 绑定oauth
        if (CARRIER == 'wechat') {
            // 是否已经绑定了openid
            D('FrontC/Passport', 'Logic')->createOauth(2, $member['m_id'], ['openid'=>$this->account->getOpenid()]);
        }

        // 更新用户登录信息
        $this->updLoginInfo($member['m_id']);

        return $this->response->setInfo(true, '登录成功！', $member);
    }
}
