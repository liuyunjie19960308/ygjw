<?php
namespace FrontC\Model;

/**
 * Class ArticleModel
 * @package FrontC\Model
 * 文章数据层
 */
class ArticleModel extends FrontBaseModel {

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = []) {
        //数据总数
        $total  = $this->alias('art')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'art');
        //获取数据
        $list   = $this->alias('art')
            ->field('art.id art_id,art.title,art.short_desc,art.cover')
            ->where($param['special_where'])
            ->select();
        //返回记录 根据ID顺序排序
        return ['list'=>sort_by_array($param['ids_for_sort'], $list, 'art_id'), 'page'=>$Page->show()];
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function findRow($param = []) {
        return $this->alias('art')
            ->field('art.id art_id,art.title,art.short_desc,art.content,art.create_time')
            ->where($param['where'])
            ->find();
    }
}