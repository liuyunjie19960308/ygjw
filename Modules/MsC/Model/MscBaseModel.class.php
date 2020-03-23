<?php
namespace MsC\Model;
use Common\Base\BaseModel;

/**
 * Class MscBaseModel
 * @package MsC\Model
 */
class MscBaseModel extends BaseModel {

    /**
     * @return string
     * 分页样式
     */
    protected function getPageTheme() {
        return '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%';
    }

    /**
     * @param $value
     * @return bool
     * 验证地区ID是否存在
     */
    protected function checkRegionId($value) {
        if(!M('Region')->where(array('id'=>$value))->count())
            return false;
    }
}