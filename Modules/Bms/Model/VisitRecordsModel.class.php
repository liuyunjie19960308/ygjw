<?php
namespace Bms\Model;

/**
 * 
 */
class VisitRecordsModel extends BmsBaseModel
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-11T15:09:51+0800
     * @param    array                    $param [description]
     * @return   [type]                          [description]
     */
    public function getList($param = [])
    {
        //数据总数
        $total  = $this->alias('visit_rec')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'visit_rec');
        //获取数据
        $list   = $this->alias('visit_rec')
                      ->field('visit_rec.id,visit_rec.ip,visit_rec.ip_plaintext,visit_rec.page_url,visit_rec.page_title,visit_rec.page_code,
                        visit_rec.stay_time,visit_rec.in_time,visit_rec.out_time,visit_rec.city,visit_rec.carrier,m.account')
                      ->where($param['special_where'])
                      ->join([
                          'LEFT JOIN '.C('DB_PREFIX').'member m ON m.id = visit_rec.m_id',
                      ])
                      ->select();
        //返回记录 根据ID顺序排序
        return array('list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show());
    }
}