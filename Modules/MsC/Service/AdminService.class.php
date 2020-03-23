<?php
namespace MsC\Service;

/**
 * Class AdminService
 * @package MsC\Service
 *
 */
class AdminService extends MscBaseService {

    /**
     * @param array $param
     * @return mixed
     * 管理员信息  总站和分站
     */
    function adminInfo($param = array()) {
        //操作者ID
        $data['admin_id']       = TERMINAL == 'bms' ? AID : LOGIN_ID;
        //操作者类型
        $data['admin_type']     = TERMINAL == 'bms' ? 1 : 2;

        return $data;
    }
}