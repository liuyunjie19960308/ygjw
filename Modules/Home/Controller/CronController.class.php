<?php
namespace Home\Controller;

/**
 * Class CronController
 * @package Home\Controller
 * 计划任务
 * m--分钟  h--小时  具体时间
 */
class CronController extends HomeBaseController {

    /**
     * 每分钟执行
     */
    public function cron_1m_BAK() {
        do {
            //确认收货
            $order_list = $this->getOrderStatus3();
            $this->confirmOrder($order_list);
            sleep(1);
        } while (!empty($order_list));

        do {   
            //确认收货
            $order_list = $this->getOrderStatus3Itg();
            $this->confirmOrder($order_list);
            sleep(1);
        } while (!empty($order_list));

        do {
            //执行退款
            $order_list = $this->getOrderStatus7();
            $this->agreeRefund($order_list);
            sleep(1);
        } while (!empty($order_list));

        do {
            //返利订单退款
            $order_list = $this->getRbtOrderStatus7();
            $this->agreeRbtRefund($order_list);
            sleep(1);
        } while (!empty($order_list));
    }
	
	
	/**
     * 每分钟执行
     */
    public function cron_1m() {
        
        //确认收货
        $order_list_3 = $this->getOrderStatus3();
        $this->confirmOrder($order_list_3);
            

       
            //确认收货
            $order_list_3_itg = $this->getOrderStatus3Itg();
            $this->confirmOrder($order_list_3_itg);
            

        
            //执行退款
            $order_list_7 = $this->getOrderStatus7();
            $this->agreeRefund($order_list);
            

        
            //返利订单退款
            $order_list_7_rbt = $this->getRbtOrderStatus7();
            $this->agreeRbtRefund($order_list_7_rbt);
            
    }

    /**
     * @return mixed
     * 获取订单列表  未支付  超过支付时间
     */
    public function getOrderList() {
        $where['status']        = 1;
        $where['create_time']   = array('exp', '<' . (NOW_TIME - C('CANCEL_ORDER')) . '');
		return M('OrderInfo')->field('id,status')->where($where)->order('id desc')->select();
        //return M('OrderInfo')->field('id,status')->where($where)->page(0, 50)->order('id desc')->select();
    }

    /**
     * @param $order_list
     * 取消订单
     */
    public function cancelOrder($order_list) {
        if(empty($order_list)) {
            return false;
        }
        foreach($order_list as $order) {
            //验证实时状态 是否可进行此操作
            if(!D('FrontC/OrderInfo', 'Service')->checkStatus(0, array(1), $order)) {
                continue;
            }
            $data['status'] = 10; //10 取消状态
            $data['cancel_order_time'] = NOW_TIME; //取消时间
            //修改订单信息
            if(!M('OrderInfo')->where(array('id'=>$order['id']))->data($data)->save()) {
                continue;
            }
            //更新订单商品库存
            D('FrontC/OrderInfo', 'Service')->updOrderGoodsStock($order['id']);
        }
    }

    /**
     * @return mixed
     * 获取待收货的订单列表
     */
    public function getOrderStatus3() {
        $where['order_type']    = 1;
        $where['status']        = 3;
        $where['delivery_time'] = ['exp', '<' . (NOW_TIME - C('AUTO_DELAY') * 86400) . ''];
		return M('OrderInfo')->field('id,order_type,delivery_time,status')->where($where)->order('id desc')->select();
        //return M('OrderInfo')->field('id,order_type,delivery_time,status')->where($where)->page(0, 30)->order('id desc')->select();
    }

    /**
     * @return mixed
     * 获取待收货的订单列表
     */
    public function getOrderStatus3Itg() {
        $where['order_type']    = 2;
        $where['status']        = 3;
        $where['delivery_time'] = ['exp', '<' . (NOW_TIME - C('ITG_AUTO_DELAY') * 86400) . ''];
		return M('OrderInfo')->field('id,order_type,delivery_time,status')->where($where)->order('id desc')->select();
        //return M('OrderInfo')->field('id,order_type,delivery_time,status')->where($where)->page(0, 30)->order('id desc')->select();
    }

    /**
     * @param $order_list
     * 完成订单
     */
    public function confirmOrder($order_list) {
        if(empty($order_list)) {
            return false;
        }
        foreach($order_list as $order) {
            //判断参数空
            if(empty($order['id'])) {
                continue;
            }
            //验证实时状态 是否可进行此操作
            if(!D('FrontC/OrderInfo', 'Service')->checkStatus(0, [3], $order)) {
                continue;
            }
            //如果是返利订单，验证是否到时间可以进行确认收货操作  delivery_time
            if($order['order_type'] == 1 && ($order['delivery_time'] + C('CONFIRM_DELAY') * 86400) >= NOW_TIME) {
                continue;
            }

            $data['status']         = 4; //4 签收已完成
            $data['receiving_time'] = NOW_TIME; //签收时间
            $data['who_receiving_order'] = -1; //系统自动收货标识

            //修改订单信息
            if(!M('OrderInfo')->where(['id'=>$order['id']])->data($data)->save()) {
                continue;
            }
            //M('Debug')->data(['content'=>'完成：' . $order['id']])->add();
            //完成订单后相关操作
            D('FrontC/OrderInfo', 'Service')->afterSignFor($order['id']);
        }
    }

    /**
     * @return mixed
     * 获取售后中的订单列表
     */
    public function getOrderStatus7() {
        $where['order_type']        = 2;
        $where['refund_status']     = 7;
        $where['shop_attitude']     = 0;
        $where['apply_refund_time'] = ['exp', '<' . (NOW_TIME - C('AUTO_REFUND_SETTLE') * 86400) . ''];
		return M('OrderRefundRecords')->field('id,order_id,refund_status,shop_attitude')->where($where)->order('id desc')->select();
        //return M('OrderRefundRecords')->field('id,order_id,refund_status,shop_attitude')->where($where)->page(0, 30)->order('id desc')->select();
    }

    /**
     * @param array $refund_records
     * @return bool
     * 同意退款相关操作
     * 黑暗中的武者
     */
    public function agreeRefund($refund_records = []) {
        if(empty($refund_records)) {
            return false;
        }
        foreach($refund_records as $refund) {
            //获取订单信息
            $order_info = M('OrderInfo')->where(['id' => $refund['order_id']])->field('m_id,order_sn,pay_itg_amounts,pay_amounts,status')->find();
            //验证实时状态 是否可进行此操作
            if (!D('FrontC/OrderInfo', 'Service')->checkStatus(0, [7], $order_info)) {
                continue;
            }
            //退款状态不为7  商家同意或者拒绝 不能再次操作
            if ($refund['refund_status'] != 7 || $refund['shop_attitude'] != 0) {
                continue;
            }

            //TODO 如果是已完成后退款 验证店铺剩余积分 是否 满足退款

            //修改退款申请记录
            $refund_data = [
                'shop_attitude'         => 1, //同意
                'who_agree_refund'      => -1, //系统自动退款
                'refund_status'         => 8, //完成
                'finish_refund_time'    => NOW_TIME,
                'refund_amounts'        => $order_info['pay_amounts'],
                'refund_itg_amounts'    => D('Common/Goods', 'Service')->price2ItgPrice($order_info['pay_amounts']),
            ];

            if (!M('OrderRefundRecords')->where(['id' => $refund['id']])->data($refund_data)->save()) {
                continue;
            }

            //执行退款
            D('Shop/OrderInfo', 'Service')->afterAgreeRefund($refund['order_id'], $order_info['pay_amounts']);

            //发送消息提醒
            $param = [
                'receiver'      => $order_info['m_id'],
                'user_type'     => 1,
                'unique_code'   => 'shop_agree_refund_notify',
                'replaces'      => ['order_sn'=>$order_info['order_sn']],
                'param'         => $refund['order_id'],
                'synchronous'   => 1
            ];

            api('Send/sendMsg', [$param]);
        }
    }

    /**
     * @return mixed
     * 获取售后中的订单列表
     */
    public function getRbtOrderStatus7() {
        $where['order_type']        = 1;
        $where['refund_status']     = 7;
        $where['apply_refund_time'] = ['exp', '<' . (NOW_TIME - C('RBT_AUTO_REFUND_SETTLE') * 86400) . ''];
		return M('OrderRefundRecords')->field('id,order_id,refund_status,shop_attitude')->where($where)->order('id desc')->select();
        //return M('OrderRefundRecords')->field('id,order_id,refund_status,shop_attitude')->where($where)->page(0, 30)->order('id desc')->select();
    }

    /**
     * @param array $refund_records
     * @return bool
     * 同意退款相关操作
     * 黑暗中的武者
     */
    public function agreeRbtRefund($refund_records = []) {
        if(empty($refund_records)) {
            return false;
        }
        foreach($refund_records as $refund) {
            //获取订单信息
            $order_info = M('OrderInfo')->where(['id' => $refund['order_id']])->field('m_id,order_sn,order_type,pay_amounts,trial_amounts,status')->find();
            //验证实时状态 是否可进行此操作
            if (!D('FrontC/OrderInfo', 'Service')->checkStatus(0, [7], $order_info)) {
                continue;
            }
            //退款状态不为7  商家同意或者拒绝 不能再次操作
            if ($refund['refund_status'] != 7) {
                continue;
            }

            //TODO 如果是已完成后退款 验证店铺剩余积分 是否 满足退款

            //修改退款申请记录
            $refund_data = [
                'who_agree_refund'      => -1, //系统自动退款
                'refund_status'         => 8, //完成
                'finish_refund_time'    => NOW_TIME,
                //'refund_amounts'        => $order_info['pay_amounts'],
                //'refund_itg_amounts'    => D('Common/Goods', 'Service')->price2ItgPrice($order_info['pay_amounts']),
            ];

            //执行退款
            //D('Shop/OrderInfo', 'Service')->afterAgreeRefund($refund['order_id'], $order_info['pay_amounts']);

            //获取用户信息
            $user_info = M('Member')->where(['id'=>$order_info['m_id']])->field('balance,trial')->find();
            //给用户加退款金额
        if(!empty($order_info['pay_amounts']) && $order_info['pay_amounts'] != 0.00) {
            //计算退款手续费
            $service_fee    = sprintf('%.2f', $order_info['pay_amounts'] * (C('RBT_REFUND_SERVICE_FEE') / 100));
            $refund_amounts = sprintf('%.2f', $order_info['pay_amounts'] - $service_fee);

            $refund_data['refund_amounts'] = $refund_amounts;
            $refund_data['service_fee']    = $service_fee;

            M('Member')->where(['id'=>$order_info['m_id']])->setInc('balance', $refund_amounts);
            D('FrontC/Finance', 'Service')->addBalanceRecords($order_info['m_id'],1,1,14,$refund_amounts,$user_info['balance'],1,$refund['order_id'],$order_info['order_sn'],$order_info['order_type'],$service_fee);
        }
        //给用户加红包金额
        //添加试用金记录
        if(!empty($order_info['trial_amounts']) && $order_info['trial_amounts'] != 0.00) {
            M('Member')->where(['id'=>$order_info['m_id']])->setInc('trial', $order_info['trial_amounts']);
            D('FrontC/Finance', 'Service')->addTrialRecords($order_info['m_id'],1,1,8,$order_info['trial_amounts'],$user_info['trial'],1,$refund['order_id'],$order_info['order_sn'],$order_info['order_type']);
        }

            // //给用户加退款金额
            // M('Member')->where(['id'=>$order_info['m_id']])->setInc('balance', $order_info['pay_amounts']);
            // D('FrontC/Finance', 'Service')->addBalanceRecords($order_info['m_id'],1,1,14,$order_info['pay_amounts'],$user_info['balance'],1,$refund['order_id'],$order_info['order_sn'],$order_info['order_type']);
            // //给用户加红包金额
            // M('Member')->where(['id'=>$order_info['m_id']])->setInc('trial', $order_info['trial_amounts']);
            // //添加试用金记录
            // if(!empty($order_info['trial_amounts']) && $order_info['trial_amounts'] != 0.00) {
            //     D('FrontC/Finance', 'Service')->addTrialRecords($order_info['m_id'],1,1,8,$order_info['trial_amounts'],$user_info['trial'],1,$refund['order_id'],$order_info['order_sn'],$order_info['order_type']);
            // }

            if (!M('OrderRefundRecords')->where(['id' => $refund['id']])->data($refund_data)->save()) {
                continue;
            }

            //发送消息提醒
            $param = [
                'receiver'      => $order_info['m_id'],
                'user_type'     => 1,
                'unique_code'   => 'bms_agree_refund_notify',
                'replaces'      => ['order_sn'=>$order_info['order_sn']],
                'param'         => $refund['order_id'],
                'synchronous'   => 1
            ];

            api('Send/sendMsg', [$param]);
        }
    }
}