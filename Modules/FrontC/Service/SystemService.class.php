<?php
namespace FrontC\Service;

/**
 * Class SystemService
 * @package FrontC\Service
 * 系统部分内容获取服务层
 */
class SystemService extends FrontBaseService {

    /**
     * @param array $param
     * @return array
     * 获取取消、退款、投诉...原因
     */
    function getReasons($param = []) {
        if(empty($param['type'])) {
            return [];
        }
        return $this->_reason($param['type']);
    }

    /**
     * @return array|mixed
     * 获取退款原因内容
     */
    private function _reason($type) {
        //获取缓存数据
        $list = S('Reason_'.$type.'_Cache');
        //不存在缓存 查找数据库
        if(!$list) {
            $list = M('Reason')->field('reason')->where(['type'=>$type])->select();
            //计入缓存
            S('Reason_'.$type.'_Cache', $list);
        }
        if(!$list)
            return [];
        return $list;
    }

    /**
     * @param array $request
     * @return array
     * 根据标识符获取配置信息
     */
    function getConfig($request = []) {
        //配置数组
        $configs = [
            1 => C('SITE_MOBILE'),
            2 => C('EASEMOB_KEFU'),
            3 => C('WITHDRAW_EXPLAIN'),
            4 => '收取' . C('WITHDRAW_SERVICE_FEE') . '%服务费，提现金额必须为整数',
            5 => '收取' . C('EXCHANGE_SERVICE_FEE') . '%手续费',
            6 => '申请退款，收取' . C('RBT_REFUND_SERVICE_FEE') . '%手续费',
        ];
        //判断标识符是一个还是多个
        $identifiers = false === strpos($request['config_identify'], ',') ? $request['config_identify'] : explode(',', $request['config_identify']);
        //如果是一个
        if(!is_array($identifiers))
            return array($identifiers => empty($configs[$identifiers]) ? '' : $configs[$identifiers]);
        //如果是多个
        $result = array();
        //循环获取
        foreach($identifiers as $ide) {
            $result[$ide] = empty($configs[$ide]) ? '' : $configs[$ide];
        }
        return $result;
    }

    /**
     * @param $pictures
     * @return array
     * 获取图片绝对地址
     */
    function getPictures($pictures) {
        //获取图片
        $files = api('File/getFiles', array($pictures, array('id', 'abs_url')));
        //未查到信息
        if(empty($files))
            return array();
        //取出绝对地址 合成数组
        return array_column($files, 'abs_url');
    }

    /**
     * @param array $files
     * @return string
     */
    function getFileIds($files = array()) {
        if(empty($files))
            return '';
        $pictures = '';
        foreach($files as $file) {
            $pictures .= $file['id'] . ',';
        }
        return substr($pictures, 0, -1);
    }

    /**
     * @param array $request
     * @return bool
     * 意见反馈
     */
    function feedback($request = array()) {
        if(empty($request['content'])) {
            return $this->setServiceInfo('请输入反馈内容！', false);
        }
        $data = array(
            'contact'       => empty($request['contact']) ? '' : $request['contact'],
            'content'       => $request['content'],
            'ip'            => get_client_ip(1),
            'create_time'   => NOW_TIME,
        );
        if(!M('Feedback')->data($data)->add()) {
            return $this->setServiceInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setServiceInfo('反馈成功！', true);
    }

    /**
     * @return array|mixed
     * 获取金融机构信息列表
     */
    public function getFinanceAgencyList() {
        //获取缓存数据
        $list = S('FinanceAgency_Cache');
        //不存在缓存 查找数据库
        if(!$list) {
//            $list = M('FinanceAgency')->alias('agency')
//                ->field('agency.name agency_name,agency.short agency_short,file.abs_url agency_logo')->where(['agency.status'=>1])->join([
//                'LEFT JOIN ' . C('DB_PREFIX') . 'file file ON file.id = agency.logo',
//            ])->select();
            $list = M('FinanceAgency')->alias('agency')
                ->field('agency.name agency_name,agency.short agency_short')
                ->where(['agency.status'=>1])
                ->select();
            foreach($list as $key => $value) {
                $list[$key]['agency_logo'] = C('FILE_HOST') . '/Public/Static/img/bank_icons/' . $value['agency_short'] . '.png';
            }
            //计入缓存
            S('FinanceAgency_Cache', $list);
        }
        if(!$list)
            return [];
        return $list;
    }
    public function getFinanceAgency($agency_type = 0) {
        if(empty($agency_type))
            return false;
        if($agency_type == 1) {
            $short = 'ALIPAY';
        }
        if($agency_type == 2) {
            $short = 'WECHAT';
        }
        //通过验证获取银行信息
        $list = $this->getFinanceAgencyList();
        foreach($list as $agency) {
            if($agency['agency_short'] == $short) {
                return $agency;
            }
        }
        return false;
    }

    /**
     * @param int $number
     * @return array
     * 根据卡号获取银行信息
     */
    public function getBankByNumber($number = 0) {
        if(empty($number) || strlen($number) < 16)
            return false;
        /**
         * 根据卡号获取验证信息
         * 成功：{"bank":"ABC","validated":true,"cardType":"DC","key":"6228480028161845179","messages":[],"stat":"ok"}
         * 失败：{"validated":false,"key":"622848002816184517","stat":"ok","messages":[{"errorCodes":"CARD_BIN_NOT_MATCH","name":"cardNo"}]}
         */
        $text = file_get_contents('https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo='.$number.'&cardBinCheck=true');
        //转化为数组
        $valid_info = json_decode($text, true);
        //var_dump($valid_info);
        //验证失败
        if($valid_info['validated'] == false)
            return false;
        //通过验证获取银行信息
        $banks = $this->getBanks();
        foreach($banks as $bank) {
            if($bank['bank_short'] == $valid_info['bank']) {
                return $bank;
            }
        }
        return false;
    }

    /**
     * @param int $customer_type
     * @return array|mixed
     * 获取客服信息
     */
    public function getCustomerService($customer_type = 0, $ids = []) {
        //获取缓存数据
        //$list = S('CustomerService_Cache');
        //不存在缓存 查找数据库
        //if(!$list) {
        if(!empty($customer_type)) {
            $list = M('CustomerService')->field('account_type,account')->where(['status'=>1,'customer_type'=>$customer_type])->order('sort ASC')->select();
        }
        if(!empty($ids)) {
            $list = M('CustomerService')->field('account_type,account')->where(['status'=>1,'id'=>['IN', $ids]])->order('sort ASC')->select();
        }
            //计入缓存
            //S('CustomerService_Cache', $list);
        //}

        $result = [];

        foreach($list as $value) {
            if($value['account_type'] == 1) {
                $result['easemob_kefu'] = empty($value['account']) ? '' : $value['account'];
            }
            if($value['account_type'] == 2) {
                $result['qq_kefu'][]    = empty($value['account']) ? '' : $value['account'];
            }
            if($value['account_type'] == 3) {
                $result['phone_kefu']   = empty($value['account']) ? '' : $value['account'];
            }
        }

        return $result;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getExpressName($code = '') {
        $library = [
            'huitongkuaidi' => '百世汇通',
            'debangwuliu'   => '德邦物流',
            'shentong'      => '申通',
            'shunfeng'      => '顺丰',
            'shunfengen'    => '顺丰',
            'zhongtong'     => '中通',
            'yuantong'      => '圆通',
            'yunda'         => '韵达',
            'yuntongkuaidi' => '运通',
        ];

        return empty($library[$code]) ? '其他物流' : $library[$code];
    }
}