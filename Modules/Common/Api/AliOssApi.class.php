<?php
namespace Common\Api;

/**
 * 
 */
class AliOssApi extends BaseApi
{
    /**
     * [$accessKeyId description]
     * @var string
     */
    private static $accessKeyId     = "LTAIRW55FPl0iHIB";

    /**
     * [$accessKeySecret description]
     * @var string
     */
    private static $accessKeySecret = "CnukN8XZrtl8wAFezfLO1jHyJkVciA";

    /**
     * [$endpoint 访问域名 地域节点]
     * @var string
     */
    private static $endpoint        = "http://oss-cn-qingdao.aliyuncs.com";

    /**
     * [$bucket description]
     * @var string
     */
    private static $bucket          = "logoba2019";

    /**
     * [upload 上传文件]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-15T12:02:09+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public static function upload($request = [])
    {
        if (is_file(__DIR__ . '/aliyun-oss/autoload.php')) {
            require_once __DIR__ . '/aliyun-oss/autoload.php';
        }
        if (is_file(__DIR__ . '/aliyun-oss/vendor/autoload.php')) {
            require_once __DIR__ . '/aliyun-oss/vendor/autoload.php';
        }

        // 文件名称
        $object   = $request['savepath'] . $request['savename'];
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        $filePath = "." . $request['path'];

        try {
            $ossClient  = new \OSS\OssClient(self::$accessKeyId, self::$accessKeySecret, self::$endpoint);
            $result     = $ossClient->uploadFile(self::$bucket, $object, $filePath);
        } catch(\OSS\Core\OssException $e) {
            M('Debug')->data(['content'=>$e->getMessage()])->add();
            return self::response(0, $e->getMessage());
        }
        return self::response(1, 'seccess', ['oss_request_url'=>$result['oss-request-url']]);
    }
}
