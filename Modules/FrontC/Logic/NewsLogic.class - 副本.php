<?php
namespace FrontC\Logic;

/**
 * Class NewsLogic
 * @package FrontC\Logic
 *
 */
class NewsLogic extends FrontBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    function getNews($request = []) {
        //分类查询
//        if(!empty($request['art_cate_id'])) {
//            $param['where']['art.art_cate_id'] = $request['art_cate_id'];
//        }
        //排序
        $param['order'] = $this->_getSort($request['sort']);
        //获取数据
        $result = D('FrontC/News', 'Service')->getNews($param, $request);

        return $result;
    }

    /**
     * @param int $sort
     * @return string
     * 选择排序条件
     */
    private function _getSort($sort = 0) {
        switch($sort) {
            case 1: return 'art.id DESC'; break;
            case 2: return 'art.view DESC'; break;
            case 3: return 'art.collections DESC'; break;
            case 4: return 'art.collections ASC'; break;
            default: return 'news.sort ASC,news.id DESC'; break;
        }
    }

    private function _getFlagID($flag) {
        switch($flag) {
            case 'about': return 3; break;
            case 'rule_1': return 4; break;
            case 'rule_2': return 5; break;
            default: return 0; break;
        }
    }

    /**
     * @param array $request
     * @return array
     * 详情
     */
    function newsInfo($request = []) {
        if(empty($request['news_id']) && empty($request['flag'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        //查询条件
        $param['where']['news.id'] = $request['news_id'];
        if(!empty($request['flag'])) {
            $param['where']['news.id'] = $this->_getFlagID($request['flag']);
        }
        //查找
        $row = D('FrontC/News')->findRow($param);
        if(!$row) {
            return $this->setLogicInfo('资讯已不存在！', false);
        }

        $row['content'] = path2abs($row['content']);

        $row['cover_path']    = api('File/getFiles', [$row['cover'], ['abs_url']])[0]['abs_url'];
        //时间处理
        $row['create_time']   = fuzzy_date($row['create_time']);

        //$row['pictures'] = api('File/getFiles', array($row['pictures'], array('id', 'abs_url')));

        D('FrontC/News', 'Service')->setRead($request['m_id'], $request['news_id']);

        //增加浏览量
        M('News')->where(['id'=>$request['news_id']])->setInc('view');

        return $row;
    }
}