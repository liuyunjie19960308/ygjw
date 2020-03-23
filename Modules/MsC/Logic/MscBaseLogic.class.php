<?php
namespace MsC\Logic;
use Common\Base\BaseLogic;

/**
 * Class MscBaseLogic
 * @package Msc\Logic
 * 管理端公共逻辑层
 */
class MscBaseLogic extends BaseLogic
{

    /**
     * @param string $info
     * @param bool $flag
     * @param array $return
     * @return array|bool|string
     * 设置提示信息
     */
    protected function setLogicInfo($info = '', $flag = false, $return = [])
    {
        $this->logicInfo = $info;
        if ($flag == false) {
            return false;
        }
        if ($flag == true && !empty($return)) {
            return $return;
        }
        return true;
    }
}