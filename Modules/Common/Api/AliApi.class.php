<?php
namespace Common\Api;

/**
 * Class AliApi
 * @package Common\Api
 * 支付宝相关
 * 黑暗中的武者
 */
class AliApi extends BaseApi{

    /**
     * @param string $out_trade_no 本站订单号 *
     * @param int $total_fee 支付金额 *
     * @param string $notify_url 异步回调 *
     * @param string $subject 订单名称 *
     * @param string $body 订单描述
     * @param string $show_url 需展示的商品或者地址
     * @return string
     * APP参数签名  旧版
     * 黑暗中的武者
     */
    public static function sign($out_trade_no = '', $total_fee = 0, $notify_url = '', $subject = '', $body = '', $show_url = '') {
        //判断参数是否合法 必填项是否为空
        if(empty($out_trade_no) || empty($total_fee) || empty($subject) || empty($notify_url) || empty($body)) {
            return false;
        }
        //引入参数处理方法
        vendor('Pay.AliPay.JSDZPC.lib.alipay_core#function');
        //引入配置参数
        self::config2C(); $alipay_config = C('ALIPAY_CONFIG_API');
        //构造要请求的参数数组
        $parameter = [
            'notify_url'        => '"' . $notify_url . '"',    //服务器异步回调通知地址
            'service'           => '"mobile.securitypay.pay"',                      //服务类型
            'partner'           => '"' . trim($alipay_config['partner']) . '"',     //合作身份者id
            'payment_type'      => '"1"',                                           //支付类型不能修改
            'seller_id'         => '"' . $alipay_config['seller_email'] . '"',      //收款支付宝账号
            'out_trade_no'      => '"' . get_vc(2,2) . '-' . $out_trade_no . '"',   //本站订单号 必填
            'subject'           => '"' . $subject . '"',                            //订单名称 必填
            'total_fee'         => '"' . $total_fee . '"',                          //付款金额 必填
            'body'              => '"' . $body . '"',                               //订单描述
            '_input_charset'    => '"' . trim(strtolower($alipay_config['input_charset'])) . '"', //字符编码格式
            'it_b_pay'          => '"30m"',                                         //支付过期时间
        ];
        //除去待签名参数数组中的空值和签名参数
        $para_filter    = paraFilter($parameter);
        //$para_sort = argSort($para_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $para_str       = createLinkstring($para_filter);
        //$prestr = '&notify_url=http://www.xxx.net&_input_charset=utf-8&body=呵呵&out_trade_no=1463723452265162&partner=2088121900317297&payment_type=1&seller_id=2088121900317297&service=mobile.securitypay.pay&subject=呵呵&total_fee=1';
        return ['link_string'=>$para_str, 'private'=>$alipay_config['private']];
    }

    /**
     * @param string $out_trade_no
     * @param int $total_fee
     * @param string $notify_url
     * @param string $subject
     * @param string $body
     * @param string $show_url
     * @return array
     * APP支付 返回签名  开放平台新版
     */
    public static function newSign($out_trade_no = '', $total_fee = 0, $notify_url = '', $subject = '', $body = '', $show_url = '') {
        Vendor('Pay.AliPay.alipay2.AopSdk');
        //引入配置参数
        self::config2C();

        $aop = new \AopClient;
        $aop->gatewayUrl         = 'https://openapi.alipay.com/gateway.do';
        $aop->appId              = C('APP_ID');
        $aop->rsaPrivateKey      = C('RSA_PRIVATE_KEY');
        $aop->format             = 'json';
        $aop->charset            = 'utf-8';
        $aop->signType           = 'RSA2';
        $aop->alipayrsaPublicKey = C('ALIPAY_RSA_PUBLIC_KEY');
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();

        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        // $bizcontent = [
        //     "body"            => "App支付",
        //     "subject"         => "App支付",
        //     "out_trade_no"    => $out_trade_no,
        //     "timeout_express" => "10m",
        //     "total_amount"    => $total_fee,
        //     "project_code"    => "QUICK_MSECURITY_PAY"
        // ];

        // $bizcontent = json_encode($bizcontent);

        $bizcontent = "{\"body\":\"{$body}\","
            . "\"subject\": \"{$subject}\","
            . "\"out_trade_no\": \"{$out_trade_no}\","
            . "\"timeout_express\": \"10m\","
            . "\"total_amount\": \"{$total_fee}\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";

        // $bizcontent = '{"body":"App支付","subject": "App支付","out_trade_no": "{$out_trade_no}","timeout_express": "30m","total_amount": "{$total_fee}","product_code":"QUICK_MSECURITY_PAY"}';

        $request->setNotifyUrl($notify_url);
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        // echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
        return [
            'sign'  => $response,
            'sn'    => $out_trade_no
        ];
    }

    /**
     * @param string $out_biz_no
     * @param string $payee_account
     * @param string $open_name
     * @param int $amount
     * @param string $remark
     * @return array
     * 单笔转账
     */
    public function toAccountTransfer($out_biz_no = '', $payee_account = '', $open_name = '', $amount = 0, $remark = '') {
        Vendor('Pay.AliPay.alipay2.AopSdk');
        //引入配置参数
        self::config2C();

        $aop = new \AopClient();
        $aop->gatewayUrl            = 'https://openapi.alipay.com/gateway.do';
        $aop->appId                 = C('APP_ID');
        $aop->rsaPrivateKey         = C('RSA_PRIVATE_KEY');
        $aop->alipayrsaPublicKey    = C('ALIPAY_RSA_PUBLIC_KEY');
        $aop->apiVersion            = '1.0';
        $aop->signType              = 'RSA2';
        $aop->postCharset           = 'utf-8';
        $aop->format                = 'json';
        $request = new \AlipayFundTransToaccountTransferRequest ();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"{$out_biz_no}\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"{$payee_account}\"," .
            "\"amount\":\"{$amount}\"," .
            "\"payer_show_name\":\"\"," .
            "\"payee_real_name\":\"{$open_name}\"," .
            "\"remark\":\"{$remark}\"" .
            "}");
        try {
            $result = $aop->execute ($request);
        } catch(\Exception $e) {
            //验签错误 抛出异常内容
            //check sign Fail! [sign=rm1rwO87T/s/wAuwGH974bVM5NKLpR/4DzRawBjw6pulJrpC3WVSBY3tx/qtVyb5hfDikCbRbEXUtPnllhFiinVpsvVCVcjzfTz/5SJwK9lqlxhV1K/STe7sSsxom7yCwwPSU2sc+DJ9yNZkbSObjZcQjdy7ahc0h/QEAztubVyP6NG+O65uEMLstQL6Tp0nhHrh2fuu17h8zx2QQGr6HUrzukAVvwRExqSlNgcu1yklr8pc45ks2w1sXHjYt4fuaIkUxFRdnEDyp8emowIUBOJw5cLMJpZGZ+z6H1VHWvRRFbz/ZpChxgqzAhnCd6Zv1eS4mFhE3gUetvzrNXcuvw==, signSourceData={"code":"10000","msg":"Success","order_id":"20180109110070001502990056052569","out_biz_no":"3142321423432","pay_date":"2018-01-09 11:49:32"}]
            return self::response(0, $e->getMessage());
        }
        //$result 成功结果
//        {
//            "alipay_fund_trans_toaccount_transfer_response":
//            {
//                "code":"10000",
//                "msg":"Success",
//                "order_id":"20180109110070001502990056256299",
//                "out_biz_no":"59",
//                "pay_date":"2018-01-09 14:38:47"
//            },
//            "sign":"Fe899IfEeGsBxZO6+dDq6gdHO+pSNuaUVnYinHrgniuOG+UwN8LysMTrT1SHrq4dZY0LUlVYIA0tI5gFRL4AL50il\/PuNKgjaaiJTliUxZuNIpN9jtERfdiiYJ8698bU6yacOJddvlViEE5qIyTiHZKql8tlB\/X82OTTTpa8UY+exO8qjN5kh6eH4mtb9VhHCMelf14CE817ncbYc7kicl1dpxXA1E2pr5TMUlbsGPycbwbST4M99e\/UkZLFA49hGG+9xekLdJJi60Gsj57zPQT\/j7q08+oYATNk7rp8cXkMcFSKhbBg0vTzm3PYSpbNe+wmVRYKz2lNuLSRSuP6ZQ=="
//        }
        //$result 失败结果
//        {
//            "alipay_fund_trans_toaccount_transfer_response":
//            {
//                "code":"40004",
//                "msg":"Business Failed",
//                "sub_code":"EXCEED_LIMIT_SM_MIN_AMOUNT",
//                "sub_msg":"\u5355\u7b14\u6700\u4f4e\u8f6c\u8d26\u91d1\u989d0.1\u5143",
//                "out_biz_no":"59"
//            },
//            "sign":"g3n80Hp0ghoesNXgkZfUWSD6zPIyBiyc7i\/v0biRsO6D4tWsWU0YaozPTE9ip0eG1mQ8klv56OYAy2+TsuuvEDpXMlG9Yd+qZqoOKSPtj3C7pk6BDtjk4hz7NqVK+sfehaURNAjOnAEqPJk95xq5JwAMMHo5oB1BTVU4lVG120LPShTeicsXovsu9hyoukSnbmmXljHag2n5i3Jvm7+i4I\/FeWBqbi0hNrkHaCwCtYr1lJ47qWnBjvmz9QmOVX6n3OaGdgIZVyHUuCbT1Hrb5i0ru2KPvVPFhu5ZnCQ9pcRKd+PiDOkpMn95RIMZqcaWcdON\/cVnxQQ+ikxcc+8wJw=="
//        }

        //获取返回值字段名称
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        //获取状态码
        $resultCode = $result->$responseNode->code;

        if(!empty($resultCode) && $resultCode == 10000) {
            return self::response(1, $result->$responseNode->msg, ['trade_no'=>$result->$responseNode->order_id,'out_biz_no'=>$result->$responseNode->out_biz_no]);
        } else {
            return self::response(0, $result->$responseNode->sub_msg);
        }
    }

    /**
     * @param string $out_trade_no 本站订单号 *
     * @param int $total_fee 支付金额 *
     * @param string $notify_url 异步回调 *
     * @param string $return_url 页面同步回调 *
     * @param string $subject 订单名称 *
     * @param string $body 订单描述
     * @param string $show_url 需展示的商品或者地址
     * @return string
     * 网站支付 参数提交跳转
     * 黑暗中的武者
     */
    public static function webPay($out_trade_no = '', $total_fee = 0, $notify_url = '', $return_url = '', $subject = '', $body = '', $show_url = '') {
        //判断参数是否合法 必填项是否为空
        if(empty($out_trade_no) || empty($total_fee) || empty($return_url) || empty($notify_url) || empty($body) || empty($subject)) {
            return false;
        }
        //引入支付宝各接口请求提交类
        vendor('Pay.AliPay.JSDZPC.lib.alipay_submit#class');
        //引入配置参数
        self::config2C(); $alipay_config = C('ALIPAY_CONFIG_HOME');
        //初始化该类
        $Submit = new \AlipaySubmit($alipay_config);
        //构造要请求的参数数组
        $parameter = [
            "service"            => "create_direct_pay_by_user",
            "partner"            => trim($alipay_config['partner']),
            "payment_type"       => "1",
            "notify_url"         => $notify_url,
            "return_url"         => $return_url, //页面跳转同步通知页面路径
            "seller_email"       => $alipay_config['seller_email'],
            "out_trade_no"       => $out_trade_no,
            "subject"            => $subject,
            "total_fee"          => $total_fee,
            "body"               => $body,
            "show_url"           => $show_url,
            "anti_phishing_key"  => '', //防钓鱼时间戳 若要使用请调用类文件submit中的query_timestamp函数 有DONDocument类支持
            "exter_invoke_ip"    => get_client_ip(), //客户端的IP地址
            "_input_charset"     => trim(strtolower($alipay_config['input_charset'])),
        ];
        //var_dump($parameter);exit;
        //调用提交方法
        $html_text = $Submit->buildRequestForm($parameter, "post", "支付跳转中...");
        echo $html_text;
    }

    /**
     * [pagepay description]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-17T18:36:21+0800
     * @param    string                   $out_trade_no [description]
     * @param    integer                  $total_fee    [description]
     * @param    string                   $notify_url   [description]
     * @param    string                   $return_url   [description]
     * @param    string                   $subject      [description]
     * @param    string                   $body         [description]
     * @param    string                   $show_url     [description]
     * @return   [type]                                 [description]
     */
    public static function pagepay($out_trade_no = '', $total_fee = 0, $subject = '', $body = '', $show_url = '')
    {
        Vendor("Pay.AliPay.alipay2.pagepay.service.AlipayTradeService");
        Vendor("Pay.AliPay.alipay2.pagepay.buildermodel.AlipayTradePagePayContentBuilder");

        //引入配置参数
        self::config2C(); $alipay_config = C('ALIPAY_CONFIG_HOME');

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($out_trade_no);
        //订单名称，必填
        $subject      = trim($subject );
        //付款金额，必填
        $total_amount = trim($total_fee);
        //商品描述，可空
        $body         = trim($body);

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($alipay_config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$alipay_config['return_url'],$alipay_config['notify_url']);

        //输出表单
        var_dump($response);
    }

    /**
     * [pagepay description]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-17T18:36:21+0800
     * @param    string                   $out_trade_no [description]
     * @param    integer                  $total_fee    [description]
     * @param    string                   $notify_url   [description]
     * @param    string                   $return_url   [description]
     * @param    string                   $subject      [description]
     * @param    string                   $body         [description]
     * @param    string                   $show_url     [description]
     * @return   [type]                                 [description]
     */
    public static function wappay($out_trade_no = '', $total_fee = 0, $subject = '', $body = '', $show_url = '')
    {
        Vendor("Pay.AliPay.alipay2.wappay.service.AlipayTradeService");
        Vendor("Pay.AliPay.alipay2.wappay.buildermodel.AlipayTradeWapPayContentBuilder");

        //引入配置参数
        self::config2C(); $alipay_config = C('ALIPAY_CONFIG_WAP');

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $out_trade_no;
        //订单名称，必填
        $subject      = $subject;
        //付款金额，必填
        $total_amount = $total_fee;
        //商品描述，可空
        $body         = $body;

        //超时时间
        $timeout_express="1m";

        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $payResponse = new \AlipayTradeService($alipay_config);
        $result = $payResponse->wapPay($payRequestBuilder, $alipay_config['return_url'], $alipay_config['notify_url']);

        return true;
    }

    /**
     * @param string $trade_no
     * @param float $refund_fee
     * @param string $notify_url
     * 退款操作
     */
    public static function refund($trade_no = '', $refund_fee = 0.0, $notify_url = '') {
        header('Content-type:text/html; charset=utf-8');
        //判断参数是否合法 必填项是否为空
        if(empty($trade_no) || empty($refund_fee) || empty($notify_url)) {
            return false;
        }
        //引入支付宝各接口请求提交类
        vendor('Pay.AliPay.JSDZPC.lib.alipay_submit#class');
        //引入配置参数
        self::config2C(); C('ALIPAY_CONFIG_API.sign_type', strtoupper('MD5')); $alipay_config = C('ALIPAY_CONFIG_API');
        //初始化该类
        $Submit = new \AlipaySubmit($alipay_config);
        //构造要请求的参数数组，无需改动
        $parameter = [
            "service"           => "refund_fastpay_by_platform_pwd",
            "partner"           => trim($alipay_config['partner']),
            "notify_url"	    => $notify_url,     //服务器异步通知页面路径
            "seller_email"	    => $alipay_config['seller_email'],   //卖家支付宝帐户
            "refund_date"	    => date('Y-m-d H:i:s',NOW_TIME),    //退款当天日期 必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
            "batch_no"	        => date('Ymd',NOW_TIME).get_vc(10,2),   //批次号 必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
            "batch_num"	        => '1',  //退款笔数 必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
            "detail_data"	    => '' . $trade_no . '^' . $refund_fee . '^用户退款',    //退款详细数据 交易退款数据集的格式为：原付款支付宝交易号^退款总金额^退款理由；
            "_input_charset"	=> trim($alipay_config['input_charset'])
        ];
        //建立请求
        $html_text = $Submit->buildRequestForm($parameter, "get", "正在跳转...");
        echo $html_text;
    }

    /**
     * @param int $flag
     * 加载配置信息到 C
     */
    public static function config2C($flag = 0) {
        C(load_config(CONF_PATH . 'ali' . CONF_EXT));
    }
}