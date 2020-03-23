<?php
/**
 * 发送相关配置
 */
return [
    /***** 短信服务配置 *****/
    'SMS_DRIVER'    => 'Juchen', //巨辰短信平台驱动
    'SMS_SIGN'      => 'logoba',
    'SMS_ACCOUNT'   => 'test2017',
    'SMS_PASSWORD'  => 'toocms2017',
    //'SMS_DRIVER'    => 'tencent', //腾讯云短信平台驱动
    //'SMS_ACCOUNT'   => '1400029651', //appid
    //'SMS_PASSWORD'  => 'ecbde820a030aaa5b1e2a4f93f1e3bed', //appkey

    /***** 邮件服务器配置 *****/
    'SMTP_HOST'     => 'smtp.163.com',
    'SMTP_PORT'     => '25',
    'SMTP_USER'     => '',
    'SMTP_PASS'     => '',
    'FROM_EMAIL'    => '',
    'FROM_NAME'     => '',
    'REPLY_NAME'    => '',
    'REPLY_EMAIL'   => '',


    'JPUSH_APP_KEY_2' => 'fd5fc1d9331b260cf2ee5ae6',//
    'JPUSH_MASTER_SECRET_2' => 'e90bc137fe816d0cbb0642b3',//
    /** 普通用户 **/
    'JPUSH_APP_KEY_1' => 'c38a96649502da6cf77bd4d0',//AppKey
    'JPUSH_MASTER_SECRET_1' => '49a98f49f97abc25f7e0d85f',//Master Secret
];