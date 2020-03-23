<?php
namespace Bms\Logic;

/**
 * Class NewsLogic
 * @package Bms\Logic
 * 资讯
 */
class NewsLogic extends BmsBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    function getList($request = array()) {
        //标题模糊查询
        if(!empty($request['title'])) {
            $param['where']['news.title'] = array('LIKE','%'.$request['title'].'%');
        }
        //排序
        $param['order'] = 'news.sort ASC,news.id DESC';
        //返回数据
        return D('News')->getList($param);
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     */
    protected function processData($data = array(), $request = array()) {
        if(empty($data['id'])) {
            //作者
            $data['admin_id'] = AID;
        }
        //替换 被特殊处理的内容
        $data['content'] = $_POST['content'];
        return $data;
    }

    /**
     * @param array $request
     * @return mixed
     */
    function findRow($request = array()) {
        //ID条件
        if(!empty($request['id'])) {
            $param['where']['news.id'] = $request['id'];
        } else {
            $this->setLogicInfo('参数错误！'); return false;
        }
        //别名
        $param['alias'] = 'news';
        //查询的字段
        $param['field'] = 'news.*';
        //关联表
        //$param['join']  = array('LEFT JOIN '.C('DB_PREFIX').'article_category art_cate ON art_cate.id = art.art_cate_id',);

        $row = D('News')->findRow($param);

        if(!$row) {
            $this->setLogicInfo('未查到此记录！'); return false;
        }
        //获取封面文件
        $row['cover'] = api('File/getFiles',array($row['cover']));
        //获取轮播
        //$row['pictures'] = api('File/getFiles',array($row['pictures']));

        //返回数据
        return $row;
    }

    /**
     * @param array $request
     * @return boolean
     * 彻底删除前执行 将数据存入回收站
     */
//    protected function beforeRemove($request = array()) {
//        if(api('Recycle/recovery',array($request,'Article','title'))) {
//            return true;
//        }
//        $this->setLogicInfo('删除失败！');  return false;
//    }
}