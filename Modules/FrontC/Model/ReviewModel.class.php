<?php
namespace FrontC\Model;

/**
 * 
 */
class ReviewModel extends FrontBaseModel
{

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = [])
    {
        //数据总数
        $total  = $this->alias('review')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'review');
        //获取数据
        $list   = $this->alias('review')
                       ->field('review.id review_id,review.title,review.cover,review.link_url,review.video_id')
                       ->where($param['special_where'])
                       ->select();
        //返回记录 根据ID顺序排序
        return array('list'=>sort_by_array($param['ids_for_sort'], $list, 'review_id'), 'page'=>$Page->show());
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function findRow($param = []) {
        return $this->alias('review')
                    ->field('review.id review_id,review.title,review.video_id,review.content,review.create_time')
                    ->where($param['where'])
                    ->find();
    }
}
