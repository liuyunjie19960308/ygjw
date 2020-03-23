<?php
namespace Bms\Logic;

/**
 * Class AdvertLogic
 * @package Bms\Logic
 * 广告逻辑层
 */
class AdvertLogic extends BmsBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = [])
    {
        // 排序条件
        $param['order']     = 'id DESC';
        // 页码
        $param['page_size'] = C('LIST_ROWS');
        // 查询的字段
        $param['field']     = 'id,position,description,picture,start_time,end_time,sort,status,target_rule';
        // 返回数据
        $result = D('Advert')->getList($param);

        foreach ($result['list'] as $key => &$value) {
            $value['picture'] = api('File/getFiles', array($value['picture'], ['abs_url']))[0]['abs_url'];
        }

        return $result;
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function findRow($request = [])
    {
        if (!empty($request['id'])) {
            $param['where']['id'] = $request['id'];
        } else {
            return $this->setLogicInfo('参数错误！'); return false;
        }
        // 获取数据
        $row = D('Advert')->findRow($param);
        if (!$row) {
            $this->setLogicInfo('未查到此记录！'); return false;
        }
        // 获取广告图
        $row['picture'] = api('File/getFiles', array($row['picture']));
        // 返回数据
        return $row;
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     * 处理提交数据 进行加工或者添加其他默认数据
     */
    protected function processData($data = [], $request = [])
    {
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time']   = strtotime($data['end_time'] . ' 23:59:59');
        
        // if ($request['position'] == 1) {
        //     if (empty($data['param'])) {
        //         return $this->setLogicInfo('请输入跳转的网址参数！');
        //     }
        // }
        // //验证跳转规则及参数
        // if(!D('MsC/TargetRule', 'Service')->targetCheck($this->_a($request),$this->_b($request),$request['target_rule'],$request['param'])) {
        //     return $this->setLogicInfo(D('MsC/TargetRule', 'Service')->getServiceInfo(), false);
        // }
        return $data;
    }
    private function _a($request) {
        if(in_array($request['position'], [2,4,6])) {
            return 1;
        }
        return 0;
    }
    private function _b($request) {
        if(in_array($request['position'], [3,5,7])) {
            return 1;
        }
        return 0;
    }

    /**
     * @param int $result
     * @param array $request
     * @return boolean
     * 新增、更新、修改状态、删除后执行
     */
    protected function afterAll($result = 0, $request = [])
    {
        // 清缓存
        S('Advert_Cache', null);
        return true;
    }
}
