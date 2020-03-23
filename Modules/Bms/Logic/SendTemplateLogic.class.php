<?php
namespace Bms\Logic;

/**
 * Class SendTemplateLogic
 * @package Bms\Logic
 * 发信模板 逻辑层
 */
class SendTemplateLogic extends BmsBaseLogic {

    /**
     * @param array $request
     * @return array
     */
    public function getList($request = []) {
        //排序条件
        $param['order']     = 'id DESC';
        //页码
        $param['page_size'] = C('LIST_ROWS');
        //查询的字段
        $param['field']     = 'id,cate,unique_code,description,type,status';
        //返回数据
        return D('SendTemplate')->getList($param);
    }

    /**
     * @return mixed
     * 获取模板信息 自定义模板
     */
    public function getSendTemplate() {
        return D('SendTemplate')->where(['cate'=>2])->field('id,unique_code,description,type,status')->select();
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     * 模板内容 根据类型判断是否过滤
     */
    protected function processData($data = [], $request = []) {
        $data['template'] = $data['type'] == 1 || $data['type'] == 3 || $data['type'] == 4 ? filter_html($_POST['template']) : $_POST['template'];
        return $data;
    }

    /**
     * @param array $request
     * @return boolean
     * 修改状态前执行方法
     */
    protected function beforeSetField($request = []) {
        if($request['field'] == 'status' && D('SendTemplate')->where(['id'=>['IN',$request['ids']],'cate'=>1])->find()) {
            $this->setLogicInfo('查到您的操作对象中存在系统模板，操作禁止！'); return false;
        }
        return true;
    }

    /**
     * @param array $request
     * @return boolean
     * 彻底删除前执行 将数据存入回收站
     */
    protected function beforeRemove($request = []) {
        if(D('SendTemplate')->where(['id'=>['IN',$request['ids']],'cate'=>1])->find()) {
            $this->setLogicInfo('查到您的操作对象中存在系统模板，操作禁止！'); return false;
        }
        //回收站处理
        if(api('Recycle/recovery', [$request, 'SendTemplate', 'unique_code'])) {
            return true;
        }
        $this->setLogicInfo('删除失败！');  return false;
    }

    /**
     * @return array
     */
    public function getPushTargetRules() {
        return [
            '1' => '网址',
            //'2' => '分类商品列表',
            //'3' => '搜索商品列表',
            '4' => '专题商品列表',
            '5' => '品牌商品列表',
            '6' => '商品详情',
            '7' => '店铺主页',
            '8' => '资讯详情',
            '9' => '用户订单详情',
            '10' => '商家订单详情',
        ];
        //优惠券列表
    }
}