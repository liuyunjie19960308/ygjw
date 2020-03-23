<?php
namespace FrontC\Model;

/**
 * 
 */
class AgencyModel extends FrontBaseModel
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T11:53:32+0800
     * @param    array                    $param [description]
     * @return   [type]                          [description]
     */
    public function getList($param = [])
    {
        //数据总数
        $total  = $this->alias('agency')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'agency');
        //获取数据
        $list   = $this->alias('agency')
                       ->field('agency.id agency_id,agency.title,agency.agency,agency.tel')
                       ->where($param['special_where'])
                       ->select();
        //返回记录 根据ID顺序排序
        return array('list'=>sort_by_array($param['ids_for_sort'], $list, 'agency_id'), 'page'=>$Page->show());
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function findRow($param = []) {
        return $this->alias('news')
            ->field('news.id news_id,news.title,news.short_desc,news.content,news.cover,news.create_time')
            ->where($param['where'])
            ->find();
    }
}