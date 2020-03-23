<?php
namespace FrontC\Core\Login;

use FrontC\Core\Login\Account;
use FrontC\Core\Response;

/**
 * 登录抽象类
 */
abstract class Login
{
    /**
     * [$Account 账号对象]
     * @var null
     */
    protected $account  = null;

    /**
     * [$Response 返回信息对象]
     * @var null
     */
    protected $response = null;

    /**
     * [__construct 构造函数]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:33:28+0800
     * @param    Account                  $account [账号对象]
     */
	public function __construct(Account $account, Response $response)
	{
        $this->account  = $account;
        $this->response = $response;
	}

    /**
     * [login 登录抽象方法]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:41:42+0800
     * @return   [type]                   [description]
     */
    abstract function login();

    /**
     * [checkStatus 验证用户状态]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:47:41+0800
     * @param    integer                  $status [description]
     * @return   [type]                           [description]
     */
    protected function checkStatus($status = 0)
    {
        //用户状态判断
        if($status != 1) {
            return $this->response->setInfo(false, '您的账号未开通或以禁用，如有疑问请联系管理员！');
        }
        return true;
    }

    /**
     * [updLoginInfo 更新登陆信息]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T14:27:25+0800
     * @param    integer                  $m_id [description]
     * @return   [type]                         [description]
     */
    protected function updLoginInfo($m_id = 0) {
        //更新登录信息
        $data = [
            'login_times'     => ['exp', '`login_times`+1'],
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        ];
        M('Member')->where(['id'=>$m_id])->data($data)->save();
    }
}