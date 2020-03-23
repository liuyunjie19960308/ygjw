<?php
namespace Bms\Controller;

/**
 * Class RegionController
 * @package Bms\Controller
 * 开通地区 控制器
 * 逻辑层、服务层、数据层 在Msc模块存放   将与Region模块共用
 */
class RegionController extends BmsBaseController {

    /**
     * 初始化执行
     * 每个控制器方法执行前 先执行该方法
     */
    protected function _initialize() {
        //执行 父类_initialize()的方法体
        parent::_initialize();
        //逻辑层对象
        self::$logicObject = D('MsC/Region', 'Logic');
    }

    /**
     * 获取地区列表
     */
    function getRegions() {
        $param['where']['parent_id'] = I('request.region_id');
        $this->ajaxReturn(['status'=>1,'info'=>'','data'=>D('Common/Region','Service')->select($param)], JSON);
    }
}