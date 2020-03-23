<?php
namespace Bms\Model;

/**
 * Class NavigationModel
 * @package Bms\Model
 * 导航
 */
class NavigationModel extends BmsBaseModel {

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = array (
        array('name', 'require', '请输入导航名称！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '1,4', '导航名称不能超过4个字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        //array('name', 'checkUnique', '导航名称已存在！', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH, array('name')),
        array('icon', 'require', '请上传导航图标！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('target_rule', 'require', '请选择跳转规则！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * @var array
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
    );

    /**
     * @param array $param
     * @return array
     * 获取列表 公用方法
     */
    public function getList($param = []) {
        //数据总数
        $total  = $this->alias('nav')->where($param['where'])->count();
        //获取分类对象
        $Page   = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        //数据列表
        $list   = $this->alias('nav')
            ->where($param['where'])
            ->field('nav.id,nav.name,nav.sort,nav.target_rule,nav.remark,nav.update_time,nav.status,file.abs_url')
            ->join([
                'LEFT JOIN '.C('DB_PREFIX').'file file ON file.id = nav.icon',
            ])
            ->order('sort ASC,id DESC')
            ->limit($Page->firstRow, $Page->listRows)
            ->select();

        return ['list' => $list, 'page' => $Page->show()];
    }
}