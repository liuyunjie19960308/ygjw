<?php
namespace FrontC\Core\Login;

use FrontC\Core\Login\Login;

/**
 * openid登录类
 */
class OpenidLogin extends Login
{
    /**
     * [login description]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-21T14:04:10+0800
     * @return   [type]                   [description]
     */
    public function login()
    {
        //获取openid
        $openid = $this->account->getOpenid();
        //如果openid不存在返回错误信息
        if(empty($openid)) {
            return $this->response->setInfo(false, 'openid参数为空！');
        }
        //获取platform
        $platform = $this->account->getPlatform();
        if(empty($platform)) {
            return $this->response->setInfo(false, 'platform参数为空！');
        }
        //查询第三方绑定表 获取用户ID
        $m_id = M('Interconnect')->where(['openid'=>$openid])->getField('m_id');
        //是否存在绑定信息
        if(!$m_id) {
            //创建新账号
            $m_id = $this->register($openid, $platform, $code,$head,$nickname);
            if(!$m_id) {
                return false;
            }
        }
        //这里如果传了用户的头像和昵称
        if(!empty($head)  || !empty($nickname)){
            //更新用户的昵称和头像
            M("Interconnect")->where(['m_id'=>$m_id])->data(['platform_head'=>empty($head)?'':$head,'platform_nickname'=>empty($nickname)?'':$nickname])->save();           
        }


        //查询用户信息
        $member = M('Member')->where(['id'=>$m_id])->field('id m_id,status')->find();
        //验证用户状态
        if(!$this->checkStatus($member['status'])) {
            return false;
        }

        $member = array_merge($member);
        //更新用户登录信息
        $this->updLoginInfo($m_id);

        return $this->response->setInfo(true, '登录成功！', $member);
    }
}
