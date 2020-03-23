<?php
namespace Bms\Controller;

/**
 * Class IndexController
 * @package Bms\Controller
 * 首页控制器
 */
class IndexController extends BmsBaseController {

    /**
     * 首页
     */
    public function index() {
        //$result = api('AliOss/upload');
        //dump($result);
        //exit;
        //api('UpDownLoad/upload', [I('request.')]);

        // $total_1 = M('Member')->where([])->count();
        // $total_2 = M('Shop')->where([])->count();
        // $total_3 = M('Goods')->where(['goods_type'=>1])->count();
        // $total_4 = M('Goods')->where(['goods_type'=>2])->count();
        // $total_5 = M('OrderInfo')->where(['order_type'=>1])->count();
        // $total_6 = M('OrderInfo')->where(['order_type'=>2])->count();
        // $total_7 = M('OrderComplainRecords')->where([])->count();

        // $this->assign('total_1', $total_1);
        // $this->assign('total_2', $total_2);
        // $this->assign('total_3', $total_3);
        // $this->assign('total_4', $total_4);
        // $this->assign('total_5', $total_5);
        // $this->assign('total_6', $total_6);
        // $this->assign('total_7', $total_7);


        // //相关数量线状统计
        // $this->assign('member_day_quantity_line', D('Statistics', 'Logic')->usersQuantityLine([], ['unit'=>'d','start_date'=>date('Y-m-d',strtotime('-10 Day')),'end_date'=>date('Y-m-d')]));
        // $this->assign('shop_day_quantity_line', D('Statistics', 'Logic')->shopsQuantityLine([], ['unit'=>'d','start_date'=>date('Y-m-d',strtotime('-10 Day')),'end_date'=>date('Y-m-d')]));

        $this->display('index');
    }
}