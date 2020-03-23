<?php
namespace Wap\Controller;

/**
 * Class CenterController
 * @package Wap\Controller
 * 个人中心控制器
 */
class CenterController extends WapBaseController
{

    /**
     * 每个控制器方法执行前 先执行该方法
     */
    protected function _initialize()
    {
        //执行 父类_initialize()的方法体
        parent::_initialize();
        //验证登陆
        $this->checkLogin();
    }

    /**
     * [index 个人中心主页]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-13T18:47:45+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        // 获取用户信息
        $this->assign('info', D('FrontC/Member', 'Service')->getInfo('', M_ID, '4,5'));
        //页面标题
        $this->assign('title', '个人中心');
        $this->display('index');
    }

    /**
     * 个人信息
     */
    public function myInfo()
    {
        $this->assign('info', D('FrontC/Member', 'Service')->getInfo('', M_ID));

        $this->assign('title', '个人信息');
        $this->display('myInfo');
    }

    /**
     * 修改文本信息
     * 详细描述：
     * 特别注意：
     * POST参数：*m_id(用户ID) *field(字段标记 1--昵称) *value(字段值)
     */
    public function modifyInfo()
    {
        if (!IS_POST) {
            $this->display('modifyInfo');
        } else {
            $result = D('FrontC/Center', 'Logic')->modifyInfo(array_merge(I('request.'), ['m_id'=>M_ID]));
            if (!$result) {
                $this->error(D('FrontC/Center', 'Logic')->getLogicInfo());
            } else {
                $this->success(D('FrontC/Center', 'Logic')->getLogicInfo(), U('Center/index'));
            }
        }
    }

    /**
     * [DOrders 设计订单列表]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-24T18:51:44+0800
     */
    public function DOrders()
    {
        $this->assign('type', 'D');
        $this->display('myOrders');
    }

    /**
     * [Morders 商标订单]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-24T18:51:59+0800
     */
    public function Morders()
    {
        $this->assign('type', 'M');
        $this->display('myOrders');
    }

    /**
     * [getOrders 获取订单列表数据]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-24T18:52:08+0800
     * @return   [type]                   [description]
     */
    public function getOrders()
    {
        $result = D('FrontC/OrderInfo', 'Logic')->orderList(array_merge(I('request.'), ['m_id'=>M_ID]));
        $this->success('', '', true, $result['list']);
    }

    /**
     * [detail 订单详情]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-24T18:52:24+0800
     * @return   [type]                   [description]
     */
    public function detail()
    {
        $result = D('FrontC/OrderInfo', 'Logic')->orderDetail(I('request.'));
        if ($result == false) {
            redirect(U('System/error404'));
        }
        // 订单号加密
        $result['order_sn_ciphertext'] = think_encrypt($result['order_sn']);
        //dump($result);
        $this->assign('order', $result);
        // 可用优惠券列表
        $this->assign('coupon_list', D('FrontC/Coupon', 'Service')->getEnableCoupon(M_ID, $result));
        // 获取关注信息
        $this->assign('subscribe', D('FrontC/Member', 'Service')->isSubscribe(M_ID));

        $this->display('detail');
    }

    

    /**
     * [myCoupons 我的券]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-26T14:30:47+0800
     * @return   [type]                   [description]
     */
    public function myCoupons()
    {
        $result = D('FrontC/Center', 'Logic')->myCoupons(array_merge(I('request.'), ['m_id'=>M_ID]));
        $this->assign('list', $result);
        $this->display('myCoupons');
    }

    /**
     * 我的优惠券
     * 详细描述：
     * 特别注意：
     * POST参数：*m_id(用户ID) p
     */
    function getMyCoupons()
    {
        $result = D('FrontC/Center', 'Logic')->myCoupons(I('request.'));
        if(empty($result)) {
            $this->error(D('FrontC/Center', 'Logic')->getLogicInfo());
        }
        $this->success('', '', true, $result);
    }

    /**
     * 退出登录
     */
    function logout()
    {
        if (is_login()) {
            session('member',null);
            session('member_sign',null);
            session(null);
            session('[destroy]');
            cookie('__auto_account__', null);
            $this->success('退出成功！', U('Passport/login'));
        } else {
            $this->redirect(U('Passport/login'));
        }
    }
}
