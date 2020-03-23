<?php
namespace Bms\Model;

/**
 * 
 */
class AuditionAddressModel extends BmsBaseModel
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
            ['agency_id', 'require', '请选择赛区！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            ['title', 'require', '请输入地点名称！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            ['address', 'require', '请输入地址！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
            ['tel', 'require', '请输入电话！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
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
        $total  = $this->alias('audition')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'audition');
        //获取数据
        $list   = $this->alias('audition')
                       ->field('audition.id,audition.title,audition.address,audition.tel,audition.sort,audition.update_time,audition.status,agency.title agency_title')
                       ->where($param['special_where'])
                       ->join([
                            'LEFT JOIN '.C('DB_PREFIX').'agency agency ON agency.id = audition.agency_id',
                       ])
                       ->select();
        //返回记录 根据ID顺序排序
        return ['list'=>sort_by_array($param['ids_for_sort'], $list), 'page'=>$Page->show()];
    }
}
