<?php
namespace MsC\Logic;

/**
 * Class UserExamineRecordsLogic
 * @package MsC\Logic
 * 审核记录表
 */
class UserExamineRecordsLogic extends MscBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    function getList($request = array()) {}

    /**
     * @param array $request
     * @return mixed
     */
    function findRow($request = array()) {}

    /**
     * @param array $request
     * @return bool|mixed|void
     */
    function update($request = array()) {
        //创建添加数据
        $data = array(
            'user_id'       => is_numeric($request['user_id']) ? $request['user_id'] : implode(',', $request['user_id']),
            'user_type'     => $request['user_type'],
            'remark'        => $request['remark'],
            'create_time'   => NOW_TIME,
            'result'        => empty($request['result']) ? 2 : $request['result'],
        );
        //合并管理者信息
        $data = array_merge($data, D('MsC/Admin','Service')->adminInfo());

        M('UserExamineRecords')->data($data)->add();
    }
}