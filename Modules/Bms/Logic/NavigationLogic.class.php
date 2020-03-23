<?php
namespace Bms\Logic;

/**
 * Class NavigationLogic
 * @package Bms\Logic
 * 导航
 */
class NavigationLogic extends BmsBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    function getList($request = array()) {
        if(!empty($request['rbt_shop_show'])) {
            $param['where']['rbt_shop_show'] = $request['rbt_shop_show'];
        }
        if(!empty($request['itg_shop_show'])) {
            $param['where']['itg_shop_show'] = $request['itg_shop_show'];
        }
        //返回数据
        return D('Navigation')->getList($param);
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     * 处理提交数据 进行加工或者添加其他默认数据
     */
    protected function processData($data = array(),$request = array()) {
        //验证跳转规则及参数
        if(!D('MsC/TargetRule', 'Service')->targetCheck($request['rbt_shop_show'],$request['itg_shop_show'],$request['target_rule'],$request['param'])) {
            return $this->setLogicInfo(D('MsC/TargetRule', 'Service')->getServiceInfo(), false);
        }
        return $data;
    }

    /**
     * @param array $request
     * @return mixed
     * 详情
     */
    function findRow($request = array()) {
        //参数判断
        if(!empty($request['id'])) {
            $param['where']['id'] = $request['id'];
        } else {
            $this->setLogicInfo('参数错误！'); return false;
        }
        //获取数据
        $row = D('Navigation')->findRow($param);
        if(!$row) {
            $this->setLogicInfo('未查到此记录！'); return false;
        }
        //图标
        $row['icon'] = api('File/getFiles',array($row['icon']));

        return $row;
    }

    /**
     * @param int $result
     * @param array $request
     * @return boolean
     * 新增、更新、修改状态、删除后执行
     */
    protected function afterAll($result = 0, $request = array()) {
        //清缓存
        S('Navigation_Cache', null);
        return true;
    }
}