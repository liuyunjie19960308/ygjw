<?php
namespace MsC\Model;

/**
 * Class UserExamineRecordsModel
 * @package MsC\Model
 * 用户审核记录
 */
class UserExamineRecordsModel extends MscBaseModel {

    /**
     * @param int $user_id
     * @param int $user_type
     * @param int $result
     * @return mixed
     * 审核记录
     */
    function getRecords($user_id = 0,$user_type = 2,$result = 2) {
        return M('UserExamineRecords')->alias('user_exa_rec')
            ->where(array('user_id'=>$user_id,'user_type'=>$user_type,'result'=>$result))
            ->field('user_exa_rec.remark,user_exa_rec.create_time,admin.account')
            ->join(array(
                'LEFT JOIN '.C('DB_PREFIX').'administrator admin ON admin.id = user_exa_rec.admin_id',
            ))
            ->select();
    }
}