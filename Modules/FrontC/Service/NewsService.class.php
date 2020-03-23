<?php
namespace FrontC\Service;

/**
 * Class NewsService
 * @package FrontC\Service
 *
 */
class NewsService extends FrontBaseService {

    /**
     * @param $custom_param
     * @param array $request
     * @return array
     */
    function getNews($custom_param, $request =[]) {
        //状态必须为正常状态
        $param['where']['news.status'] = 1;
        //默认分类
        //$param['where']['news.art_cate_id'] = ['exp','>2'];
        //默认排序
        $param['order'] = 'news.sort ASC,news.id DESC';
        //每页数量
        $param['page_size'] = 8;
        //是否有外部其他自定义条件  如果有替换条件
        if(!empty($custom_param))
            $param = $this->customParam($param, $custom_param);
        //调用数据模型层方法获取数据
        $result = D('FrontC/News')->getList($param);
        //数据列表 //分页信息
        $list = $result['list']; $page = $result['page'];
        //如果没有数据返回空数组
        if(empty($list))
            return [];
        //处理列表数据
        foreach($list as &$value) {
            $value['cover_path']    = api('File/getFiles', [$value['cover'], ['abs_url']])[0]['abs_url'];
            //时间处理
            $value['create_time']   = fuzzy_date($value['create_time']);
            //是否已读
            $value['is_read']       = $this->isRead($request['m_id'], $value['news_id']);
        }
        return $list;
    }

    /**
     * @param int $m_id
     * @param int $news_id
     * @return int
     * 是否读过该资讯
     */
    function isRead($m_id = 0, $news_id = 0) {
        if(M('NewsReadRecords')->where(['m_id'=>$m_id, 'news_id'=>$news_id])->count())
            return '1';
        return '0';
    }

    /**
     * @param int $m_id
     * @param int $news_id
     * @return int
     * 设为已读
     */
    function setRead($m_id = 0, $news_id = 0) {
        if(M('NewsReadRecords')->where(['m_id'=>$m_id, 'news_id'=>$news_id])->count())
            return false;

        $data = [
            'm_id'      => $m_id,
            'news_id'   => $news_id,
            'create_time' => NOW_TIME
        ];

        M('NewsReadRecords')->data($data)->add();
    }
}