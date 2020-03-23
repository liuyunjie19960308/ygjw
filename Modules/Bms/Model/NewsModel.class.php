<?php
namespace Bms\Model;

/**
 * Class NewsModel
 * @package Bms\Model
 * 资讯
 */
class NewsModel extends BmsBaseModel {

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = array (
        array('title', 'require', '请输入资讯标题！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,30', '资讯标题长度1--30字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('short_desc', 'require', '请输入资讯简述！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('short_desc', '1,30', '资讯简述长度1--30字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('cover', 'require', '请上传资讯封面！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '请输入资讯详细内容！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('link', '/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', '连接地址非法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * @var array
     * 自动完成规则
     */
    protected $_auto = array (
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
    );

    /**
     * @param array $param  综合条件参数
     * @return array
     */
    function getList($param = array()) {
        //数据总数
        $total  = $this->alias('news')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        //关联条件
//        $join   = array(
//            'LEFT JOIN '.C('DB_PREFIX').'article_category art_cate ON art.art_cate_id = art_cate.id',
//        );
        //生成ID查询条件
        $param = $this->specialSearch($param, $Page, 'news');
        //获取数据
        $list  = $this->alias('news')
                      ->field('news.id,news.title,news.short_desc,news.sort,news.view,news.status,news.update_time,news.cover,file.abs_url cover_path')
                      ->where($param['special_where'])
                      ->join([
                          'LEFT JOIN ' . C('DB_PREFIX') . 'file file ON file.id = news.cover',
                      ])
                      ->select();
        //返回数据
        return array('list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show());
    }
}