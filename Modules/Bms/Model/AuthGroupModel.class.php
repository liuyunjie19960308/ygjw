<?php
namespace Bms\Model;

/**
 * 分组权限 模型
 */

class AuthGroupModel extends BmsBaseModel {

    /**
     * @var array
     * 自动验证规则
     */
    protected $_validate = array (
        array('name', 'require', '请输入组名称！', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', 'checkUnique', '该组名已经存在！', self::EXISTS_VALIDATE, 'callback', self::MODEL_BOTH, array('name')),
        array('name', '1,15', '组名长度不能超过15个字符！', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('description', 'require', '请输入组描述！', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('description', '1,90', '描述长度不能超过90个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * @var array
     * 自动完成规则
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
    );
}