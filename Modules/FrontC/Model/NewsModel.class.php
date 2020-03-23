<?php
namespace FrontC\Model;

/**
 * Class NewsModel
 * @package FrontC\Model
 *
 */
class NewsModel extends FrontBaseModel {

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = []) {
        //数据总数
        $total  = $this->alias('news')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'news');
        //获取数据
        $list   = $this->alias('news')
            ->field('news.id news_id,news.title,news.short_desc,news.cover,news.create_time')
            ->where($param['special_where'])
            ->select();
        //返回记录 根据ID顺序排序
        return array('list'=>sort_by_array($param['ids_for_sort'], $list, 'news_id'), 'page'=>$Page->show());
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