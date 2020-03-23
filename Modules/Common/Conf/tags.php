<?php
/**
 * 初始化事件
 */
return [
	'app_init'      => ['Common\Behavior\InitHook'], //外部插件导入事件
    'app_begin'     => ['Behavior\CheckLang'], //语言包检查引入事件
    'view_filter'   => ['Behavior\TokenBuild'] //创建令牌表单
];