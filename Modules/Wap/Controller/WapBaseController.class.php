<?php
namespace Wap\Controller;
use FrontC\Controller\FrontBaseController;

/**
 * Class WapBaseController
 * @package Wap\Controller
 * 控制器父类
 */
class WapBaseController extends FrontBaseController
{

    /**
     * 每个控制器方法执行前 先执行该方法
     */
    protected function _initialize()
    {
        // 执行 父类_initialize()的方法体
        parent::_initialize();
        // if (I('request.debug') != 'toocms' && session('debug') != 'toocms') {
        //     echo "网站建设中..."; exit;
        // } else {
        //     session('debug', 'toocms');
        // }
        // 站点状态
        if (C('WEB_SITE_CLOSE') == 0 && I('request.admin') != 'asdfghjkl') {
            echo '站点关闭中...'; exit;
        }
        // 判断载体 微信还是浏览器
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        // 判断是否微信中访问
        if (strpos($user_agent, 'MicroMessenger') === false) {
            define('CARRIER', 'mbrowser');
        } else {
            define('CARRIER', 'wechat');
        }
        // 识别设备进行跳转
        if (!is_mobile()) {
            redirect('http://www.zghaosg.com');
        }
    }

    /**
     * [setForward 设置登录回跳]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-29T17:09:11+0800
     */
    public function setForward()
    {
        cookie('__forward__', U('' . CONTROLLER_NAME . '/' . ACTION_NAME . '', $_REQUEST));
    }

    /**
     * 验证登陆
     * 黑暗中的武者
     */
    protected function checkLogin()
    {
        //判断账号是否为空
        if (!M_ID) {
            if (IS_AJAX) {
                $this->error('请先登录！', U('Passport/login'), true, ['code'=>10000]);
            } else {
                redirect(U('Passport/login'));
            }
        }
        //判断账号是否禁用
        if (M('Member')->where(['id' => M_ID])->getField('status') != 1) {
            session(null);
            $this->error('您的账号未开通或以禁用，如有疑问请联系管理员！', U('Passport/login'));
        }
    }

    /**
     * 访问的方法不存在 调用
     */
    protected function _empty()
    {
        redirect(U('System/error404'));
    }

    /**
     * [setWxShare 设置微信分享]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-08T15:13:46+0800
     */
    public function setWxShare()
    {
        $ticket = api('WeChat/getSign');
        //dump($ticket);
        $this->assign('noncestr',  $ticket['noncestr']);
        $this->assign('timestamp', $ticket['timestamp']);
        $this->assign('signature', $ticket['sign']);
    }
}
