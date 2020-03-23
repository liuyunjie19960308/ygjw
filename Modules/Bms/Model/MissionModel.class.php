<?php
namespace Bms\Model;
class MissionModel extends BmsBaseModel
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



}
