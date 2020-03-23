<?php
namespace FrontC\Service;
use Common\Base\BaseService;

/**
 * Class FrontBaseService
 * @package FrontC\Service
 * 前端集体服务层父类
 *  Api
 *  Home
 *  Wap
 */
class FrontBaseService extends BaseService
{

    /**
     * @param string $info
     * @param bool $flag
     * @param array $return
     * @return array|bool|string
     * 设置提示信息
     */
    protected function setServiceInfo($info = '', $flag = false, $return = [])
    {
        $this->serviceInfo = $info;
        if ($flag == false) {
            return false;
        }
        if ($flag == true && !empty($return)) {
            return $return;
        }
        return true;
    }
}
