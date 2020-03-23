<?php
namespace FrontC\Logic;

/**
 * Class CenterLogic
 * @package FrontC\Logic
 * 个人中心 部分功能逻辑层
 */
class CenterLogic extends FrontBaseLogic {

    /**
     * @param array $request
     * @return bool
     * 修改信息
     */
//    function modifyInfo($request = []) {
//        //判断参数空
//        if(empty($request['data']))
//            return $this->setLogicInfo('提交数据不能为空！', false);
//        //data数据转化
//        $data_arr = json_decode($_REQUEST['data'], 'array');
//        if(empty($data_arr))
//            return $this->setLogicInfo('数据解析出错！', false);
//        //匿名函数 获取要修改的字段
//        $func = function($field) {
//            switch($field) {
//                case 1: return 'nickname'; break;
//                default : return false; break;
//            }
//        };
//        //循环要修改的字段
//        foreach($data_arr as $data) {
//            if(empty($data['field']) || empty($data['value']))
//                return $this->setLogicInfo('参数错误！', false);
//            //获取字段名称
//            $field = $func($data['field']);
//            if(!$field)
//                return $this->setLogicInfo('字段出错！', false);
//            //修改数组
//            $upd_data[$field] = $data['value'];
//        }
//        //最后修改时间
//        $upd_data['update_time'] = NOW_TIME;
//
//        if(!M('Member')->where(array('id'=>$request['m_id']))->data($upd_data)->save()) {
//            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
//        }
//        return $this->setLogicInfo('修改成功！', true);
//    }

    /**
     * @param array $request
     * @return array|bool|string
     * 修改资料
     */
    public function modifyInfo($request = []) {
        //创建数据
        $data = M('Member')->create($request);
        if(!$data) {
            return $this->setLogicInfo(M('Member')->getError(), false);
        }
        //修改信息
        $result = M('Member')->where(['id'=>$request['m_id']])->data($data)->save();
        if($result === false) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('保存成功！', true);
    }

    /**
     * @param array $request
     * @return bool
     * 修改密码
     */
    public function modifyPassword($request = []) {
        //判断参数
        if(empty($request['password'])) {
            return $this->setLogicInfo('请输入原密码！', false);
        } if(empty($request['new_password'])) {
            return $this->setLogicInfo('请输入新密码！', false);
        } if(strlen($request['new_password']) < 6 || strlen($request['new_password']) > 18) {
            return $this->setLogicInfo('新密码长度在6--18位之间！', false);
        } if($request['re_new_password'] != $request['new_password']) {
            return $this->setLogicInfo('确认新密码与新密码不一致！', false);
        }
        //获取原密码
        $password = M('Member')->where(['id'=>$request['m_id']])->getField('password');
        //验证原密码是否正确
        if(!($password == MD5($request['password']))) {
            return $this->setLogicInfo('原密码不正确！', false);
        }
        //如果未修改
        if($password == MD5($request['new_password'])) {
            return $this->setLogicInfo('修改成功！', true);
        }
        //修改
        $data['password']   = MD5($request['new_password']);
        $data['password_visible'] = $request['new_password'];
        $where['id']        = $request['m_id'];
        //判断成败
        if(!M('Member')->where($where)->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('修改成功！', true);
    }

    /**
     * @param array $request
     * @return bool
     * 修改账号
     */
    public function modifyAccount($request = []) {
        if(empty($request['account']) || empty($request['new_account']) || empty($request['verify']))
            return $this->setLogicInfo('参数错误！', false);
        //验证原手机号和用户ID的一致性
        if(!M('Member')->where(['id'=>$request['m_id'],'account'=>$request['account']])->count()) {
            return $this->setLogicInfo('非法修改！', false);
        }
        //验证新手机号 验证码 新手机号收验证码 用 register
        $result = api('Verify/checkVerify', [$request['new_account'], $request['verify'], 'register']);
        if($result !== true) {
            return $this->setLogicInfo($result, false);
        }
        //账号一致 直接修改成功
        if($request['new_account'] == $request['account']) {
            return $this->setLogicInfo('修改成功！', true);
        }
        $data['account']    = $request['new_account'];
        $data['mobile']     = $request['new_account'];
        $data['update_time'] = NOW_TIME;

        if(!M('Member')->where(['id'=>$request['m_id']])->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('修改成功！', true);
    }

    /**
     * @param array $request
     * @return bool
     * 修改支付密码
     */
    public function modifyPayPass($request = []) {
        if(empty($request['pay_pass'])) {
            return $this->setLogicInfo('请输入支付密码！', false);
        } if(!preg_match('/^\d{6}$/', $request['pay_pass'])) {
            return $this->setLogicInfo('支付密码格式为6位的数字！', false);
        } if($request['re_pay_pass'] != $request['pay_pass']) {
            return $this->setLogicInfo('确认密码与支付密码不一致！', false);
        }
        //修改
        $data['pay_pass']   = MD5($request['pay_pass']);
        $data['update_time']= NOW_TIME;
        $where['id']        = $request['m_id'];
        //判断成败
        if(false === M('Member')->where($where)->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('设置成功！', true);
    }

    /**
     * @param array $request
     * @return array
     * 我的积分记录
     */
    public function integralRecords($request = []) {
        $param['where']['itg_rec.user_id']      = $request['m_id'];
        $param['where']['itg_rec.user_type']    = 1;

        $result = D('FrontC/Finance', 'Service')->integralRecords($param);

        return $result;
    }

    /**
     * @param array $request
     * @return array
     * 余额明细
     */
    public function balanceRecords($request = []) {
        $param['where']['bal_rec.user_id']      = $request['m_id'];
        $param['where']['bal_rec.user_type']    = 1;

        if($request['flag'] == 1) {  //邀请收益
            $param['where']['bal_t.trend'] = 4;
        }

        $result = D('FrontC/Finance', 'Service')->balanceRecords($param);

        return $result;
    }

    /**
     * @param array $request
     * @return array
     * 试用金明细
     */
    public function trialRecords($request = []) {
        $param['where']['trial_rec.user_id']      = $request['m_id'];
        $param['where']['trial_rec.user_type']    = 1;

        $result = D('FrontC/Finance', 'Service')->trialRecords($param);

        return $result;
    }

    /**
     * @param array $request
     * @return bool
     * 提现
     */
    public function withdraw($request = []) {

        $result = D('FrontC/Finance', 'Service')->withdraw($request['m_id'],1,'Member',C('DB_PREFIX').'member',$request);

        if(!$result) {
            return $this->setLogicInfo(D('FrontC/Finance', 'Service')->getServiceInfo(), false);
        }

        return $this->setLogicInfo(D('FrontC/Finance', 'Service')->getServiceInfo(), true);
    }

    /**
     * @param array $request
     * @return array
     * 提现记录
     */
    public function withdrawRecords($request = []) {
        $param['where']['withdraw.user_id']     = $request['m_id'];
        $param['where']['withdraw.user_type']   = 1;

        $result = D('FrontC/Finance', 'Service')->withdrawRecords($param);

        return $result;
    }

    /**
     * [myCoupons 我的优惠券]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-26T14:18:14+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function myCoupons($request = [])
    {
        $param['where']['u_cpn.user_id'] = $request['m_id'];

        $result = D('FrontC/Coupon', 'Service')->memCoupons($param);

        if (!$result) {
            return $this->setLogicInfo(D('FrontC/Coupon', 'Service')->getServiceInfo(), false);
        }

        return $result;
    }

    /**
     * @param array $request
     * @return bool
     * 绑定互联
     */
    public function interconnect($request = []) {
        if(empty($request['openid']) || empty($request['platform']))
            return $this->setLogicInfo('参数错误！', false);

        if(M('Interconnect')->where(['m_id'=>$request['m_id'],'platform'=>$request['platform'],'openid'=>$request['openid']])->count()) {
            return $this->setLogicInfo('绑定成功！', true);
        }
        $conn_data['m_id']   = $request['m_id'];
        $conn_data['openid'] = $request['openid'];
        $conn_data['platform'] = $request['platform'];
        if(!M('Interconnect')->data($conn_data)->add()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }
        return $this->setLogicInfo('绑定成功！', true);
    }

    /**
     * @param array $request
     * @return bool
     * app同步回调操作
     */
    public function appCallback($request = []) {
        $order = M('RechargeOrder')->where(['id'=>$request['recharge_order_id']])->field('status')->find();
        if($order['status'] == 0) {
            return $this->setLogicInfo('支付失败！', false);
        }
        return $this->setLogicInfo('支付成功！', true);
    }
}