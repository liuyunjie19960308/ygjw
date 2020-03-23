<?php
namespace MsC\Service;

/**
 * Class TargetRuleService
 * @package MsC\Service
 * 跳转规则处理服务层
 */
class TargetRuleService extends MscBaseService {

    /**
     * @param int $rbt_shop_show
     * @param int $itg_shop_show
     * @param int $target_rule
     * @param string $param
     * @return array
     * 验证跳转规则及参数
     */
    public function targetCheck($rbt_shop_show = 0, $itg_shop_show = 0, $target_rule = 0, $param = '') {
        //商城类型显示条件
        if($rbt_shop_show == 1) {
            $where['rbt_shop_show'] = 1;
            $mall = '返利商城';
        } if($itg_shop_show == 1) {
            $where['itg_shop_show'] = 1;
            $mall = '积分商城';
        }
        //分类
        if($target_rule == 2) {
            if(!M('GoodsCategory')->where(array_merge(['id'=>$param], $where))->count()) {
                return $this->setServiceInfo('跳转规则-请输'.$mall.'存在的商品分类ID！', false);
            }
        }
        //专题
        if($target_rule == 4) {
            if(!M('Special')->where(array_merge(['id'=>$param], $where))->count()) {
                return $this->setServiceInfo('跳转规则-请输'.$mall.'存在的专题ID！', false);
            }
        }
        //商品
        if($target_rule == 6) {
            if($rbt_shop_show == 1) {
                $goods_type = 1;
            } if($itg_shop_show == 1) {
                $goods_type = 2;
            }
            if(!M('Goods')->where(['id'=>$param,'goods_type'=>$goods_type])->count()) {
                return $this->setServiceInfo('跳转规则-请输'.$mall.'存在的商品ID！', false);
            }
        }

        return $this->setServiceInfo('通过！', true);
    }

    /**
     * @param int $target_rule
     * @param string $param
     * @return array|bool|string
     * 淘宝优惠券模块跳转规则验证
     */
    public function tbkTargetCheck($target_rule = 0, $param = '') {
        if(!in_array($target_rule, [1,8,9])) {
            return $this->setServiceInfo('对应关系指只能选择1、8、9！', false);
        }
        return $this->setServiceInfo('通过！', true);
    }


    /**
     * @param $target_rule
     * @param $param
     * @return string
     * 根据跳转规则及参数 获取 商城类型  订单状态 ...
     */
    public function targetParam($target_rule, $param) {
        $extras = [
            'target_rule'   => $target_rule,
            'param'         => $param,
            'mall_type'     => '0',
            'order_status'  => '0',
        ];
        if($target_rule == 4) { //专题商品列表 获取商城类型
            $special = M('Special')->where(['id'=>$param])->field('rbt_shop_show,itg_shop_show')->find();
            if($special) {
                $extras['mall_type'] = $special['rbt_shop_show'] == 1 ? '1' : '2';
            }
        }
//        if($target_rule == 3) { //品牌商品列表 获取商城类型
//            $extras['mall_type'] = 1;
//        }
        if($target_rule == 6) { //商品详情 获取商城类型
            $goods = M('Goods')->where(['id'=>$param])->field('goods_type')->find();
            if($goods) {
                $extras['mall_type'] = $goods['goods_type'];
            }
        }
        if($target_rule == 9) { //订单列表 获取商城类型 订单状态
            $order = M('OrderInfo')->where(['id'=>$param])->field('order_type,status')->find();
            if($order) {
                $extras['mall_type'] = $order['order_type'];
                $extras['order_status'] = $order['status'];
            }
        }
        if($target_rule == 10) { //订单列表 获取商城类型 订单状态
            $order = M('OrderInfo')->where(['id'=>$param])->field('order_type,status')->find();
            if($order) {
                $extras['mall_type'] = $order['order_type'];
                $extras['order_status'] = $order['status'];
            }
        }

        return $extras;
    }
}