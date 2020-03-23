<?php
namespace MsC\Logic;

/**
 * Class XdataExamineRecordsLogic
 * @package MsC\Logic
 * 非平台录入数据审核记录表
 * （店铺版块  店铺分类 ...）
 */
class XdataExamineRecordsLogic extends MscBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = []) {}

    /**
     * @param array $request
     * @return mixed
     */
    public function findRow($request = []) {}

    /**
     * @param array $request
     * @return bool|mixed|void
     */
    public function update($request = []) {
        //创建数据
        $data = M('XdataExamineRecords')->create($request);
        //创建添加数据
        $data['create_time'] = NOW_TIME;

        //合并管理者信息
        $data = array_merge($data, D('MsC/Admin','Service')->adminInfo());

        M('XdataExamineRecords')->data($data)->add();
    }
}