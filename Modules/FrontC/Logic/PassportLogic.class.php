<?php
namespace FrontC\Logic;

use \FrontC\Core\Login\Account;
use \FrontC\Core\Login\IdNumLogin;
use \FrontC\Core\Response;

/**
 * Class PassportLogic
 * @package FrontC\Logic
 * 登陆注册 处理逻辑层
 */
class PassportLogic extends FrontBaseLogic
{

    /**
     * [doLogin 用户登录]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T14:23:51+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function doLogin($request = [])
    {
        // 账号对象
        $Account  = new Account();
        // 返回信息对象
        $Response = new Response();
        // 
        $Account->setIdNum($request['id_num']);
        $Account->setPassword($request['password']);

        //这里可以根据访问类型 初始化不同的登录类
        //初始化登录类
        $Login = new IdNumLogin($Account, $Response);

        // 执行登录
        $result = $Login->login();
        // 登录失败
        if (false === $result) {
            return $this->setLogicInfo($Response->getInfo(), false);
        }
        // 前端
        if (TERMINAL == 'wap' || TERMINAL == 'home') {
            // 设置session
            $this->setSession($result['user_id']);
        }
        // 登录成功返回
        return $result;
    }

    /**
     * [autoLogin 保存手机号自动登录]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-29T17:16:28+0800
     * @return   [type]                   [description]
     */
    public function autoLogin()
    {
        $account = cookie('__auto_account__');
        if (empty($account) || session('member') != null) {
            return false;
        }
        $mem = M('Member')->where(['account'=>$account])->field('id m_id,status')->find();
        if (!empty($mem) && $mem['status'] == 1) {
            $this->setSession($mem['m_id']);
        } else {
            cookie('__auto_account__', null);
        }
    }

    /**
     * @param $user_id
     * 设置session
     */
    public function setSession($user_id)
    {
        /* 记录登录SESSION和COOKIES */
        $session = [
            'user_id'  => $user_id,
        ];
        session('user', $session);
        session('user_sign', data_auth_sign($session));
    }

    /**
     * [_setAuto description]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-29T17:18:51+0800
     * @param    [type]                   $account [description]
     */
    private function _setAuto($account)
    {
        cookie('__auto_account__', $account, 86400*3);
    }

    /**
     * @param array $request
     * @return array|bool
     * 找回密码
     */
    public function doFindPass($request = [])
    {
        // 参数判空
        if (empty($request['name'])) {
            return $this->setLogicInfo('请输入姓名！', false);
        }
        if (empty($request['mobile'])) {
            return $this->setLogicInfo('请输入手机号码！', false);
        }
        if (empty($request['id_num'])) {
            return $this->setLogicInfo('请输入身份证号！', false);
        }
        if (empty($request['division'])) {
            return $this->setLogicInfo('请选择赛区！', false);
        }
        // if ((empty($request['province_name']) || empty($request['city_name']) || empty($request['area_name'])) && empty($request['college_division'])) {
        //     return $this->setLogicInfo('请选择赛区！', false);
        // }

        // 请输入密码
        if (empty($request['password'])) {
            return $this->setLogicInfo('请输入密码！', false);
        }
        // 请输入确认密码
        if (empty($request['re_password'])) {
            return $this->setLogicInfo('请输入确认密码！', false);
        }
        // if(strlen($password) < 6 || strlen($password) > 18) {
        //     return $this->setLogicInfo('密码长度在6-18位之间！', false);
        // } if(strlen($re_password) < 6 || strlen($re_password) > 18) {
        //     return $this->setLogicInfo('确认密码长度在6-18位之间！', false);
        // }
        if ($request['password'] != $request['re_password']) {
            return $this->setLogicInfo('确认密码和密码不一致！', false);
        }
        // // 判断账号格式
        // if(!preg_match(C('MOBILE'), $request['account'])) {
        //     return $this->setLogicInfo('账号格式不正确！', false);
        // }

        // 判断账号是否注册过
        $user = M('UserProfile')->where(['id_num'=>$request['id_num']])->field('name,mobile,province_name,city_name,area_name,college_division')->find();
        
        if (!$user) {
            return $this->setLogicInfo('身份证号不存在！', false);
        }

        if ($user['name'] != $request['name']) {
            return $this->setLogicInfo('姓名不匹配！', false);
        }
        if ($user['mobile'] != $request['mobile']) {
            return $this->setLogicInfo('手机号码不匹配！', false);
        }

        $where['id_num']        = $request['id_num'];
        $where['mobile']        = $request['mobile'];

        $data['password']       = MD5($request['password']);
        //$data['password_visible'] = $request['password'];

        if (false === M('UserProfile')->where($where)->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('密码已重置，请重新登陆！', true);
    }
}
