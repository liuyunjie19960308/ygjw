<?php
namespace FrontC\Model;

/**
 * 
 */
class UserProfileModel extends FrontBaseModel
{

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = [
        // ['unique_code', 'checkUnique', '该标识已经存在！', self::VALUE_VALIDATE, 'callback', self::MODEL_BOTH, ['unique_code']],
        
        ['name', 'require', '请输入您的姓名！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['id_num', 'require', '请输入身份证号！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['id_num', 'check_credit_no', '身份证号不正确！', self::VALUE_VALIDATE, 'function', self::MODEL_BOTH],
        ['mobile', 'require', '请输入手机号码！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['mobile', 'check_mobile', '手机号码格式不正确！', self::MUST_VALIDATE, 'function', self::MODEL_BOTH],
        //['agency', 'require', '请输入工作单位/学校名称！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        //['email', 'require', '请输入邮箱地址！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        //['email', 'check_email', '邮箱地址格式不正确！', self::MUST_VALIDATE, 'function', self::MODEL_BOTH],
        //['profession', 'require', '请选择您的职业！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['classes', 'require', '请选择报名的类目！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['password', 'require', '请输入登录密码！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['password', '6,18', '密码长度在6--18位！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH],
        ['re_password', 'require', '请输入确认密码！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['division', 'require', '请选择赛区！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        //['is_training', 'require', '是否参见红人培训！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
        ['avatar', 'require', '请上传您的照片！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
    ];

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = [])
    {
        $Page = null;
        // 是否分页
        if (!empty($param['page_size'])) {
            // 数据总数
            $total = $this->alias('user_profile')->where($param['where'])->count();
            // 创建分页对象
            $Page  = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        }
        //获取数据
        $list = $this->alias('user_profile')
                     ->field('user_profile.id user_id,user_profile.sn,user_profile.name,user_profile.division,user_profile.classes')
                     ->where($param['where'])
                     ->order($param['order'])
                     ->join([

                     ])
                     ->limit($Page->firstRow, $Page->listRows)
                     ->select();
        // 返回数据
        return ['list' => $list,'page' => $Page == null ? '' : $Page->show()];
    }
}
