<?php
namespace Bms\Model;
class QualificationsModel extends BmsBaseModel
{
//
//    /**
//     * @var array
//     * 自动验证规则
//     */
//    protected $_validate = [
//        ['unique_code', '/^[a-zA-Z]\w{0,39}$/', '标识必须为英文！', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH],
//        ['unique_code', '1,30', '标识长度不能超过30个字符！', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH],
//        ['unique_code', 'checkUnique', '该标识已经存在！', self::VALUE_VALIDATE, 'callback', self::MODEL_BOTH, ['unique_code']],
//        ['title', 'require', '请输入文章标题！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
//        ['title', '1,60', '文章标题长度1--60字符！', self::MUST_VALIDATE, 'length', self::MODEL_BOTH],
//        ['art_cate_id', 'require', '请选择文章分类！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
//        ['content', 'require', '请输入文章内容！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH],
//        //array('link', '/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', '连接地址非法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
//    ];
//
//    /**
//     * @var array
//     * 自动完成规则
//     */
//    protected $_auto = [
//        ['create_time', 'time', self::MODEL_INSERT, 'function'],
//        ['update_time', 'time', self::MODEL_INSERT, 'function'],
//        ['update_time', 'time', self::MODEL_UPDATE, 'function'],
//    ];

    /**
     * @param array $param  查询搜索
     * @return array
     */
    public function search($where=[])
    {
        $pages = 5;

        $count = $this->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count, $pages);// 实例化分页类 传入总记录数和每页显示的记录数(15)
        $Page->lastSuffix = false;
        $Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>%TOTAL_PAGE%</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('last', '末页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $data['show'] = $Page->show();// 分页显示输出


        $data['list'] = $this
            ->order('sort desc')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
//        echo $this->getLastSql();die;
        return $data;
    }
    /**
     * 模型添加方法
     * @param  $param
     * @return array
     */
    public function model_add($post){
        return $this->add($post);
    }

    /**
     * 模型详情方法
     * @param  $id
     * @return array
     */
    public function model_desc($field='*',$where=[]){
        return $this->field($field)->where($where)->find();
    }

    /**
     * 模型修改方法
     * @param  $id
     * @return string
     */
    public function model_update($id,$post){
        $res=$this->where($id)->save($post);
        return $res;
    }
    /**
     * 模型删除某条数据方法
     * @param  $id
     * @return string
     */
    public function model_delete($id=[]){
        $res=$this->where($id)->delete();

        return $res;
    }

}