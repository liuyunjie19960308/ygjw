<?php
namespace Bms\Model;

/**
 * Class MemberModel
 * @package Bms\Model
 * 会员模型
 */
class MemberModel extends BmsBaseModel {

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = [];

    /**
     * 初始化处理
     */
    public function _initialize() {
        //自动验证规则
        $this->_validate = [
            array('account', 'require', '请输入登陆账号！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('account', '/^0?(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9]|19[0-9])[0-9]{8}$/', '账号格式不正确！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
            array('account', 'checkUnique', '账号已经存在！', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH, ['account']),
            array('password', '6,18', '密码长度在6-18个字符！', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        ];
    }

    /**
     * @var array
     * 自动完成规则
     */
    protected $_auto = [
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
    ];

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = []) {
        //数据总数
        $total  = $this->alias('m')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'm');
        //获取数据
        $list   = $this->alias('m')
            ->field('m.id,m.member_sn,m.level,m.account,m.nickname,m.avatar,m.balance,m.integral,m.login_times,m.last_login_time,m.status')
            ->where($param['special_where'])
            ->join([
                //'LEFT JOIN ' . C('DB_PREFIX') . 'file file ON file.id = m.avatar',,file.abs_url avatar_path
            ])
            ->select();
        //返回记录 根据ID顺序排序
        return ['list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show()];
    }

    /**
     * @param array $param
     * @return array|mixed
     * 获取详情
     */
    public function findRow($param = []) {
        return $this->alias('m')
            ->field('m.*,file.path avatar_path')
            ->where($param['where'])
            ->join([
                'LEFT JOIN '.C('DB_PREFIX').'file file ON file.id = m.avatar',
            ])
            ->find();
    }
}