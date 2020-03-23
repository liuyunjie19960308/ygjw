<?php
namespace Bms\Model;

/**
 * 
 */
class TraineesModel extends BmsBaseModel
{

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
            ['name', 'require', '请输入姓名！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            ['short_desc', 'require', '请输入简介！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['division', 'require', '请输入赛区！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['honor', 'require', '请输入荣誉！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            ['show_cover', 'require', '请上传展示封面！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['title', 'require', '请输入案例标题！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['title', '1,60', '案例标题长度1-60字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH],
            //['description', 'require', '请输入案例描述！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['description', '1,300', '案例描述长度1-300字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH],
            //['industry', 'require', '请选择行业！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            //['color', 'checkColor', '请选择色系！', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH],
            //['style', 'require', '请选择风格！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],

            //array('account', '0,15', '账号长度在0--15位！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
            //array('target_rule', 'checkTargetRule', '启动页广告跳转规则必须为网址！', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
            //array('url', '/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', '连接地址非法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        ];
    }

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
     * [getList 查询列表]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-15T13:22:56+0800
     * @param    array                    $param [description]
     * @return   [type]                          [description]
     */
    public function getList($param = [])
    {
        //数据总数
        $total  = $this->alias('trainees')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'trainees');
        //获取数据
        $list   = $this->alias('trainees')
                       ->field('trainees.id,trainees.name,trainees.short_desc,trainees.show_cover,trainees.sort,trainees.update_time,trainees.status')
                       ->where($param['special_where'])
                       // ->join([
                       //      'LEFT JOIN '.C('DB_PREFIX').'member m ON m.id = logo_case.m_id',
                       // ])
                       ->select();
        //返回记录 根据ID顺序排序
        return ['list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show()];
    }
}
