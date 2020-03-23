<?php
namespace Wap\Controller;

use FrontC\Core\Redis\Redis;

/**
 * Class SystemController
 * @package Wap\Controller
 * 系统控制器
 */
class SystemController extends WapBaseController
{
    /**
     * [error404 description]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-01T15:48:14+0800
     * @return   [type]                   [description]
     */
    public function error404()
    {
        $this->display('error404');
    }

    /**
     * [payError 支付失败]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-01T15:47:35+0800
     * @return   [type]                   [description]
     */
    public function payError()
    {
        $this->display('payError');
    }

    /**
     * [paySuccess 支付成功]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-01T15:47:43+0800
     * @return   [type]                   [description]
     */
    public function paySuccess()
    {
        if (!empty(I('request.order_sn'))) {
            $coupon_list = M('UserCoupon')->where(['user_id'=>M_ID,'channel_sn'=>I('request.order_sn')])->select();

            array_walk($coupon_list, 'FrontC\Service\CouponService::couponDataFactory', []);

            $this->assign('list', $coupon_list);
        }

        $this->display('paySuccess');
    }

    /**
     * [aliPayCallback 支付宝同步回调]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-18T19:21:40+0800
     * @return   [type]                   [description]
     */
    public function aliPayCallback()
    {
        D('Pay/Pay', 'Logic')->aliPay();
    }

    /**
     * [zixun 免费咨询]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-20T18:11:53+0800
     * @return   [type]                   [description]
     */
    public function zixun()
    {
        $request = I('request.');
        if (empty($request['cate'])) {
            $this->error('请选择您需要的服务！');
        }
        if (empty($request['mobile'])) {
            $this->error('请输入电话号码！');
        }
        if (!preg_match(C('MOBILE'), $request['mobile'])) {
            $this->error('请输入正确格式的电话号码！');
        }
        $data = [
            'iwant'       => $request['cate'],
            'mobile'      => $request['mobile'],
            'ip'          => get_client_ip(1),
            'create_time' => NOW_TIME,
        ];
        if (!M('Consultation')->data($data)->add()) {
            $this->error('系统繁忙，稍后重试！');
        }
        $this->success('提交成功，我们会尽快与您联系！');
    }

    /**
     * [addVisitRecords 添加用户访问记录]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-04T16:45:21+0800
     */
    public function addVisitRecords()
    {
        try {
            $Redis = Redis::getInstance();
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
        // redis实例
        $redisInstance = $Redis->redisInstance();
        // 键值
        $unique = M_ID?:get_client_ip(1);
        // 获取hash数据
        $record = $redisInstance->hGetAll($unique); //返回的是数组 没有值返回空数组
        // 如果存在记录到数据库
        if (!empty($record)) {
            $record['out_time']   = NOW_TIME; // 出时间
            $record['stay_time']  = NOW_TIME - $record['in_time']; // 停留时间
            $record['page_url']   = substr($record['page_url'], strpos($record['page_url'], '.com') + 4);//str_replace(C('NOW_HOST'), '', $record['page_url']); // 页面路径
            $page_info            = coreO('Visit', 'Analysis')->pageInfo($record['page_url']); // 页面信息
            $record['page_title'] = $page_info['page_title'];
            $record['page_code']  = $page_info['page_code'];
            $record['carrier']    = CARRIER;
            //TODO 这里可以加到redis队列里 异步处理
            M('VisitRecords')->data($record)->add();
        }

        $redisInstance->hSet($unique, 'ip', get_client_ip(1));
        $redisInstance->hSet($unique, 'ip_plaintext', get_client_ip());
        $redisInstance->hSet($unique, 'm_id', M_ID?:0);
        $redisInstance->hSet($unique, 'page_url', I('request.page_url'));
        $redisInstance->hSet($unique, 'in_time', NOW_TIME);

        $Redis->close();
    }
}
