<?php

function VodClassLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    //var_dump($class);
    //var_dump($path);
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'uploader'. DIRECTORY_SEPARATOR . $path . '.php';
    //var_dump($file);
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('VodClassLoader');

require_once  __DIR__ . DIRECTORY_SEPARATOR . 'aliyun-php-sdk-core' . DIRECTORY_SEPARATOR . 'Config.php';
require_once  __DIR__ . DIRECTORY_SEPARATOR . 'aliyun-php-sdk-oss' .DIRECTORY_SEPARATOR . 'autoload.php';

