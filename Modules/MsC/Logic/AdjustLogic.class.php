<?php
namespace MsC\Logic;

/**
 * Class AdjustLogic
 * @package MsC\Logic
 * 调整用户资料逻辑层
 */
class AdjustLogic extends MscBaseLogic {


    /**
     * @param array $request
     * @return bool
     * 执行调整
     */
    function doAdjust($request = []) {
        //隐藏参数判空
        if(empty($request['model']) || empty($request['ids'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        //判断必填参数是否填写
        if(empty($request['field'])) {
            return $this->setLogicInfo('请选择要调整的资料！', false);
        } if(empty($request['adjust_value'])) {
            return $this->setLogicInfo('请输入调整额度！', false);
        } if(empty($request['rule'])) {
            return $this->setLogicInfo('请选择调整规则！', false);
        } if(empty($request['reason'])) {
            return $this->setLogicInfo('请输入调整原因！', false);
        }
        //根据model 判断要修改的表
        switch($request['model']) {
            case 'Member'   : return $this->_adjustM($request); break;
            case 'Shop'     : return $this->_adjustS($request); break;
        }
    }

    /**
     * @param array $param
     * @return bool
     * 普通用户调整
     */
    private function _adjustM($param = []) {
        //获取用户资料
        $user_info = M('Member')->where(['id'=>$param['ids']])->field($param['field'])->find();
        if(!$user_info) {
            return $this->setLogicInfo('调整失败！-Code:1', false);
        }
        //修改用户积分 或者 余额
        if(!$this->_rule($param)) {
            return false;
        }
        //获取订单信息
        $order_info = $this->_getOrderInfo($param['order_sn']);
        //判断添加余额记录还是积分记录
        if($param['field'] == 'balance') {
            if(!empty($order_info)) {
                D('FrontC/Finance', 'Service')->addBalanceRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['balance'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],0,$param['reason']);
            } else {
                D('FrontC/Finance', 'Service')->addBalanceRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['balance'],1,0,'',0,0,$param['reason']);
            }
        } elseif($param['field'] == 'integral') {
            if(!empty($order_info)) {
                D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['integral'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],0,$param['reason']);
            } else {
                D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['integral'],1,0,'',0,0,$param['reason']);
            }
        } elseif($param['field'] == 'trial') {
            if(!empty($order_info)) {
                //D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['integral'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],0,$param['reason']);
                D('FrontC/Finance', 'Service')->addTrialRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['trial'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],$param['reason']);
            } else {
                //D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['integral'],1,0,'',0,0,$param['reason']);
                D('FrontC/Finance', 'Service')->addTrialRecords($param['ids'],1,$param['rule'],15,$param['adjust_value'],$user_info['trial'],1,0,'',0,$param['reason']);
            }
        }
        //发送站内信

        return true;
    }

    /**
     * @param array $param
     * @return bool
     * 店铺用户调整
     */
    private function _adjustS($param = []) {
        //获取用户资料
        $shop_info = M('Shop')->where(['id'=>$param['ids']])->field($param['field'])->find();
        if(!$shop_info) {
            return $this->setLogicInfo('调整失败！-Code:1', false);
        }
        //修改用户积分 或者 余额
        if(!$this->_rule($param)) {
            return false;
        }
        //获取订单信息
        $order_info = $this->_getOrderInfo($param['order_sn']);
        //判断添加余额记录还是积分记录
        if($param['field'] == 'balance') {
            if(!empty($order_info)) {
                D('FrontC/Finance', 'Service')->addBalanceRecords($param['ids'],2,$param['rule'],15,$param['adjust_value'],$shop_info['balance'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],0,$param['reason']);
            } else {
                D('FrontC/Finance', 'Service')->addBalanceRecords($param['ids'],2,$param['rule'],15,$param['adjust_value'],$shop_info['balance'],1,0,'',0,0,$param['reason']);
            }
        } elseif($param['field'] == 'integral') {
            if(!empty($order_info)) {
                D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],2,$param['rule'],15,$param['adjust_value'],$shop_info['integral'],1,$order_info['order_id'],$order_info['order_sn'],$order_info['order_type'],0,$param['reason']);
            } else {
                D('FrontC/Finance', 'Service')->addIntegralRecords($param['ids'],2,$param['rule'],15,$param['adjust_value'],$shop_info['integral'],1,0,'',0,0,$param['reason']);
            }
        }
        //发送站内信

        return true;
    }

    /**
     * @param $param
     * @return bool
     * 根据调整规则修改用户资料
     */
    private function _rule($param) {
        //修改用户积分 或者 余额  1加 2减
        if($param['rule'] == 1) {
            $result = M($param['model'])->where(['id'=>$param['ids']])->setInc($param['field'], $param['adjust_value']);
        } else {
            $result = M($param['model'])->where(['id'=>$param['ids']])->setDec($param['field'], $param['adjust_value']);
        }
        //是否修改成功
        if(!$result) {
            return $this->setLogicInfo('调整失败！-Code:2', false);
        }
        return true;
    }

    /**
     * @param $order_sn
     * @return array|mixed
     * 获取订单信息
     */
    private function _getOrderInfo($order_sn = '') {
        if(empty($order_sn))
            return [];
        $order_info = M('OrderInfo')->where(['order_sn'=>$order_sn])->field('id order_id,order_type,order_sn')->find();
        if(!$order_info) {
            return [];
        }
        return $order_info;
    }
}