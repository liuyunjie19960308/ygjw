<?php
namespace FrontC\Core\Login;

/**
 * 账号对象
 */
class Account
{
    /**
     * [$Account 账号]
     * @var string
     */
    private $account  = '';

    /**
     * [$idNum 身份证号]
     * @var string
     */
    private $idNum    = '';

    /**
     * [$Password 密码]
     * @var string
     */
    private $password = '';

    /**
     * [$verify 验证码]
     * @var string
     */
    private $verify = '';
    
    /**
     * [$Openid 第三方互联openid]
     * @var string
     */
    private $openid   = '';

    /**
     * [$Platform 平台类型]
     * @var string
     */
    private $platform = '';

    /**
     * [__construct 构造函数]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:18:46+0800
     */
    public function __construct()
    {

    }

    /**
     * [setAccount 设置账号]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:18:51+0800
     * @param    string                   $account [账号]
     */
    public function setAccount($account = '')
    {
        $this->account = $account;
    }

    /**
     * [getAccount 获取账号]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:07+0800
     * @return   [type]                   [description]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * [setIdNum description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T15:16:29+0800
     * @param    string                   $idNum [description]
     */
    public function setIdNum($idNum = '')
    {
        $this->idNum = $idNum;
    }

    /**
     * [getIdNum description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T15:16:31+0800
     * @return   [type]                   [description]
     */
    public function getIdNum()
    {
        return $this->idNum;
    }

    /**
     * [setAccount 设置验证码]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:18:51+0800
     * @param    string                   $account [账号]
     */
    public function setVerify($verify = '')
    {
        $this->verify = $verify;
    }

    /**
     * [getAccount 获取账号]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:07+0800
     * @return   [type]                   [description]
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * [setPassword 设置密码]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:15+0800
     * @param    string                   $password [密码]
     */
    public function setPassword($password = '')
    {
        $this->password = $password;
    }

    /**
     * [getPassword 获取密码]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:02+0800
     * @return   [type]                   [description]
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * [setOpenid openid]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:11+0800
     * @param    string                   $openid [openid]
     */
    public function setOpenid($openid = '')
    {
        $this->openid = $openid;
    }

    /**
     * [getOpenid 获取openid]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:18:58+0800
     * @return   [type]                   [description]
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * [setPlatform 设置平台类型]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:19:11+0800
     * @param    string                   $openid [openid]
     */
    public function setPlatform($platform = '')
    {
        $this->platform = $platform;
    }

    /**
     * [getPlatform 获取平台类型]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:18:58+0800
     * @return   [type]                   [description]
     */
    public function getPlatform()
    {
        return $this->platform;
    }
}
