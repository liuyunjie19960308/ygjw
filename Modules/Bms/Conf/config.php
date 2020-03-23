<?php
/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */
//常量
define('TERMINAL', 'bms');
//配置
return array(

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__'    => __ROOT__ . '/Public/Static',
        '__IMG__'       => __ROOT__ . '/Public/' . MODULE_NAME . '/img',
        '__CSS__'       => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'        => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__LUI__'        => __ROOT__ . '/Public/' . MODULE_NAME . '/layui',
        '__KE__'        => __ROOT__ . '/Public/' . MODULE_NAME . '/KindEditor',
    ),

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'sx2017_bms', //session前缀
    'COOKIE_PREFIX'  => 'sx2017_bms_', // Cookie前缀 避免冲突
    'VAR_SESSION_ID' => 'session_id',	//修复上传插件无法传递session_id的bug

    /* 后台错误页面模板 */
    //'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Public/error.html', // 默认错误跳转对应的模板文件
    //'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html', // 默认成功跳转对应的模板文件
    //'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'View/Public/exception.html',// 异常页面的模板文件

    //扩展配置
    'LOAD_EXT_CONFIG'       => 'menus',
    'LOAD_EXT_FILE'         => '',

    //是否开发者模式
    'IS_DEVELOPER'          => true,

    //数据备份执行文件的路径  绝对路径  不要用 \ 反斜杠路径
    'BACKUP_DB_EXEC_PATH'   => '/home/wwwroot/linan/Data/manual_backup_db',
    //数据还原执行文件的路径  绝对路径
    //'RESTORE_DB_EXEC_PATH'  => '',

    /* 表单令牌验证 */
    'TOKEN_ON'      => false,       // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    => '__hash__',  // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    => 'md5',       //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>  true,       //令牌验证出错后是否重置令牌 默认为true


    'NOW_HOST' => 'http://bms.zghaosg.com',

    'DEFAULT_IMG_750X330' => 'http://file.zghaosg.com/Uploads/default.png',
);