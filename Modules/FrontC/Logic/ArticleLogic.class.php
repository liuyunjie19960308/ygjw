<?php
namespace FrontC\Logic;

/**
 * Class ArticleLogic
 * @package FrontC\Logic
 * 文章 逻辑层
 */
class ArticleLogic extends FrontBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getArticles($request = [])
    {
        // 分类查询
        if (!empty($request['art_cate_id'])) {
            $param['where']['art.art_cate_id'] = $request['art_cate_id'];
        }
        // 排序
        $param['order'] = $this->_getSort($request['sort']);
        // 获取数据
        $result = D('FrontC/Article', 'Service')->getArticles($param, $request);

        return $result;
    }

    /**
     * @param int $sort
     * @return string
     * 选择排序条件
     */
    private function _getSort($sort = 0)
    {
        switch($sort) {
            case 1: return 'art.id DESC'; break;
            case 2: return 'art.view DESC'; break;
            case 3: return 'art.collections DESC'; break;
            case 4: return 'art.collections ASC'; break;
            default: return 'art.sort ASC'; break;
        }
    }

    /**
     * @param array $request
     * @return array
     * 文章详情
     */
    public function artInfo($request = [])
    {
        if (empty($request['art_id']) && empty($request['flag'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        // 查询条件
        if (!empty($request['art_id'])) {
            $param['where']['art.id'] = $request['art_id'];
        }
        if (!empty($request['flag'])) {
            $param['where']['art.unique_code'] = $request['flag'];
        }
        // 查找
        $row = D('FrontC/Article')->findRow($param);
        if (!$row) {
            return $this->setLogicInfo('文章已不存在！', false);
        }
        //轮播为空  设置封面
//        if(empty($row['pictures'])) {
//            $row['pictures'] = $row['cover'];
//        }
        $row['content'] = path2abs($row['content']);
        //$row['pictures'] = api('File/getFiles', [$row['pictures'], ['id', 'abs_url']]);
        //是否收藏
        //$row['is_coll'] = D('FrontC/Article', 'Service')->isColl($request['m_id'],$request['art_id']);
        //关联商品获取
        //$row['relation_goods'] = D('FrontC/Goods')->getRelationGoods($row['relation_goods']);
        return $row;
    }
}
