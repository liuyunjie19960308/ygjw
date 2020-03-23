<?php
namespace FrontC\Service;

/**
 * 
 */
class AgencyService extends FrontBaseService
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T11:54:05+0800
     * @param    [type]                   $custom_param [description]
     * @param    array                    $request      [description]
     * @return   [type]                                 [description]
     */
    public function getList($custom_param, $request =[])
    {
        // 状态必须为正常状态
        $param['where']['agency.status'] = 1;
        // 默认排序
        $param['order'] = 'agency.sort ASC,agency.id DESC';
        //每页数量
        //$param['page_size'] = 8;
        // 是否有外部其他自定义条件  如果有替换条件
        if (!empty($custom_param)) {
            $param = $this->customParam($param, $custom_param);
        }
        // 调用数据模型层方法获取数据
        $result = D('FrontC/Agency')->getList($param);
        // 数据列表 //分页信息
        $list = $result['list']; $page = $result['page'];
        // 如果没有数据返回空数组
        if (empty($list)) {
            return [];
        }
        ///处理列表数据
        // foreach($list as &$value) {

        // }
        return $list;
    }
}
