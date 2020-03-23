<?php
namespace Bms\Logic;

/**
 * Class StatisticsLogic
 * @package Bms\Logic
 * 相关统计
 */
class StatisticsLogic extends BmsBaseLogic {

    /**
     * 各模块的总数统计
     */
    public function totalStat() {
        //各模块总数统计
        $total_stat = S('TotalStat_Cache');
        if(!$total_stat) {
            $total_stat = call_procedure('_total_stat_');
            S('TotalStat_Cache',$total_stat,3600);
        }
        return $total_stat;
    }

    /********************* 线状/柱形 统计 *************************/

    /**
     * @param array $request
     * @param array $date_param 时间参数
     * @return array
     * 用户数量统计
     */
    public function usersQuantityLine($request = [], $date_param = []){
        //折线图数据 查询条件及对象
        $line_parameter = [
            ['title'=>'会员数量','where'=>[],'obj'=>D('Member')],
        ];
        return $this->createLine($date_param,$line_parameter);
    }

    /**
     * @param array $request
     * @param $date_param
     * @return array
     * 店铺数量统计
     */
    public function shopsQuantityLine($request = [], $date_param = []){
        //折线图数据 查询条件及对象
        $line_parameter = [
            ['title'=>'店铺数量','where'=>[],'obj'=>D('Shop')],
        ];
        return $this->createLine($date_param, $line_parameter);
    }

    /********************* 饼状统计 *************************/

    /**
     * @param array $request
     * 男女比例饼状图
     * @return array
     */
    public function genderPie($request = []) {
        //饼状图数据查询条件
        $pie_parameter = [
            ['title'=>'男','where'=>['gender'=>1],'obj'=>D('Member')],
            ['title'=>'女','where'=>['gender'=>2],'obj'=>D('Member')],
        ];
        $pie_data = api('Statistics/getPieData', [$pie_parameter, 'genderPie']); //'genderPie'为缓存名称
        //创建饼状图
        return api('Statistics/createPie', [$pie_data]);
    }

    /**
     * @param $date_param
     * @param $line_parameter
     * @return array
     * 创建线型统计相关数据
     */
    public function createLine($date_param, $line_parameter) {
        //获取数据
        $line_data  = api('Statistics/getLineDataByDate', [$date_param,$line_parameter]);
        $line       = api('Statistics/createLine', [$line_data]);
        //横坐标赋值时间
        $x = api('Statistics/createXByDate', [$date_param]);

        return ['line'=>$line, 'x'=>$x];
    }

    /**
     * @param $date_param
     * @param $line_parameter
     * @return array
     * 创建单点线型统计相关数据
     */
//    public function createPointLine($line_parameter) {
//        //获取数据
//        $line_data  = api('Statistics/getLineDataByPoint', [$line_parameter]);
//        $line       = api('Statistics/createLine', [$line_data]);
//        //横坐标赋值时间
//        //$x = api('Statistics/createXByDate', ['']);
//
//        return ['line'=>$line, 'x'=>['x'=>'0','step'=>1]];
//    }
}