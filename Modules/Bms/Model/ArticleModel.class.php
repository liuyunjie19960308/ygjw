<?php
namespace Bms\Model;

/**
 * Class ArticleModel
 * @package Bms\Model
 * 文章咨询模型
 */
class ArticleModel extends BmsBaseModel
{

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = [
        ['unique_code', '/^[a-zA-Z]\w{0,39}$/', '标识必须为英文！', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH],
        ['unique_code', '1,30', '标识长度不能超过30个字符！', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH],
        ['unique_code', 'checkUnique', '该标识已经存在！', self::VALUE_VALIDATE, 'callback', self::MODEL_BOTH, ['unique_code']],
        ['title', 'require', '请输入文章标题！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['title', '1,60', '文章标题长度1--60字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH],
        ['art_cate_id', 'require', '请选择文章分类！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['content', 'require', '请输入文章内容！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        //array('link', '/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', '连接地址非法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    ];

    /**
     * @var array
     * 自动完成规则
     */
    protected $_auto = [
        ['create_time', 'time', self::MODEL_INSERT, 'function'],
        ['update_time', 'time', self::MODEL_INSERT, 'function'],
        ['update_time', 'time', self::MODEL_UPDATE, 'function'],
    ];

    /**
     * @param array $param  综合条件参数
     * @return array
     */
    function getList($param = array()) {
        //数据总数
        $total  = $this->alias('art')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        //关联条件
        $join   = array(
            'LEFT JOIN '.C('DB_PREFIX').'article_category art_cate ON art.art_cate_id = art_cate.id',
        );
        //生成ID查询条件
        $param = $this->specialSearch($param, $Page, 'art', !empty($_REQUEST['title']) ? $join : array());
        //获取数据
        $list  = $this->alias('art')
                      ->field('art.id,art.title,art.art_cate_id,art.sort,art.view,art.collections,art.status,art.update_time,art.is_best,
                      art_cate.name cate_name,art_cate.status cate_status')
                      ->where($param['special_where'])
                      ->join($join)
                      ->select();
        //返回数据
        return array('list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show());
    }
}