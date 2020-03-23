<?php
namespace Bms\Controller;

/**
 * Class SystemController
 * @package Bms\Controller
 * 一些系统操作控制器 清缓存 提示
 */
class SystemController extends BmsBaseController
{

    /**
     * 清楚一些 缓存
     */
    public function clearCache()
    {
        S('TotalStat_Cache', null);
        S('genderPie', null);
        $this->success('清除成功！');
    }

    /**
     * 未处理/未完成  提示信息
     */
    function tip()
    {
        $not_delivery_order = M('OrderInfo')->where(array('status'=>2))->count();
        $not_assign_order = M('OrderInfo')->where(array('status'=>0))->count();
        $data = array('not_delivery_order'=>$not_delivery_order,'not_assign_order'=>$not_assign_order);
        $this->success('', '', true, $data);
    }

    /**
     * 获取验证码
     * 参数 account  unique_code
     */
    public function getVerify()
    {
        $result = api('Verify/getVerify', [I('request.account'), I('request.unique_code')]);
        if($result === true) {
            $this->success('发送成功！');
        } else {
            $this->error($result);
        }
    }
}
