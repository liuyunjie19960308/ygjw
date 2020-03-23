<?php
/**
 * 本项目自定义函数
 */

/**
 * [get_logo_attr_value 获取logo属性值]
 * @Author   黑暗中的武者
 * @DateTime 2019-06-15T15:56:08+0800
 * @param    [type]                   $val   [description]
 * @param    [type]                   $field [description]
 * @return   [type]                          [description]
 */
function get_logo_attr_value($val, $field)
{
    $attr_list = S('Attr_Cache');
    
    $attr_str = '';
    // 将属性ID字符串转换成数组
    $values = explode(',', $val);
    foreach ($values as $value) {
        $attr_str .= $attr_list[$field]['attr_values'][$value]['attr_value'] . '/';
    }
    return substr($attr_str, 0, -1);
}

/**
 * @param $status
 * @return bool|string
 * 获取数据的状态操作
 */
function dp_status_name($status)
{
    switch ($status) {
        case 0  : return    '展示中 ヽ(ー_ー)ノ';     break;
        case 1  : return    '已选中 (*^▽^*)';      break;
        case 2  : return    '有意向 (✪ω✪)';        break;
        case 9  : return    '无情PASSo (╥﹏╥)o';    break;
        default : return    '--';       break;
    }
}

/**
 * [coreO description]
 * @Author   黑暗中的武者
 * @DateTime 2019-09-04T11:31:21+0800
 * @param    [type]                   $class [description]
 * @param    [type]                   $layer [description]
 * @return   [type]                          [description]
 */
function coreO($class, $layer)
{
    $class = '\FrontC\Core\\'.$layer.'\\'.$class.'';
    if (!class_exists($class)) {
        throw new Exception("{$class} now found");
    }
    return new $class();
}