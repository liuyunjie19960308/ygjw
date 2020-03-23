<?php
namespace Wap\Controller;

/**
 * Class VerifyController
 * @package Wap\Controller
 * 验证码控制器
 * 一、根据账号、标识获取验证码
 * 二、验证验证码是否存在或者过期
 */
class VerifyController extends WapBaseController
{

    /**
     * 获取验证码
     * 参数 account  unique_code
     */
    public function getVerify()
    {
        $result = api('Verify/getVerify', [I('request.account'), I('request.unique_code')]);
        if($result === true) {
            $this->success('发送成功！');
        } else {
            $this->error($result);
        }
    }

    /**
     * 验证验证码
     * 参数 account verify unique_code
     */
    public function checkVerify()
    {
        //验证短信验证码
        $result = api('Verify/checkVerify', [I('request.account'), I('request.verify'), I('request.unique_code')]);
        if($result === true) {
            $this->success('验证通过！');
        } else {
            $this->error($result);
        }
    }
}
