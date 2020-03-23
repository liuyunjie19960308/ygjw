<?php
namespace FrontC\Service;

/**
 * 
 */
class MaterialService extends FrontBaseService
{

    /**
     * [getNews description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T14:08:30+0800
     * @param    [type]                   $custom_param [description]
     * @param    array                    $request      [description]
     * @return   [type]                                 [description]
     */
    public function getList($custom_param, $request =[])
    {
        // 状态必须为正常状态
        $param['where']['material.status'] = 1;
        // 默认排序
        $param['order'] = 'material.sort ASC,material.id DESC';
        // 每页数量
        //$param['page_size'] = 8;
        // 是否有外部其他自定义条件  如果有替换条件
        if (!empty($custom_param)) {
            $param = $this->customParam($param, $custom_param);
        }
        // 调用数据模型层方法获取数据
        $result = D('FrontC/Material')->getList($param);
        // 数据列表 //分页信息
        $list = $result['list']; $page = $result['page'];
        // 如果没有数据返回空数组
        if (empty($list)) {
            return [];
        }
        // 处理列表数据
        foreach ($list as &$value) {
            $value['pc_show'] = api('File/getFiles', [$value['pc_show'], ['abs_url']])[0]['abs_url'];
        }
        return $list;
    }
}
