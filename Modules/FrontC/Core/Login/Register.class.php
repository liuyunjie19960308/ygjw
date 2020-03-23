<?php
namespace FrontC\Core\Login;

use FrontC\Core\Response;

/**
 * 用户注册类
 */
class Register
{

    /**
     * [$data description]
     * @var array
     */
    private $data = [];

    /**
     * [__construct 构造函数]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T13:33:28+0800
     * @param    Account                  $account [账号对象]
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * [register 创建账号]
     * @Author   黑暗中的武者
     * @DateTime 2018-08-07T11:47:32+0800
     * @return   [type]                             [description]
     */
    public function register()
    {
        // 注册是否关闭
        if (C('USER_ALLOW_REGISTER') == 0) {
            return $this->response->setInfo(false, '开放注册已关闭！');
        }
        // 如果存在邀请码，验证邀请码是否存在
        if (!empty($this->data['code'])) {
            $user_id = M('Member')->where(['member_sn' => $code])->getField('id');
            if (!$user_id) {
                return $this->response->setInfo(false, '邀请码不存在！');
            }
        }
        // 创建用户数据
        $this->data['register_ip'] = get_client_ip(1);
        $this->data['create_time'] = NOW_TIME;
        $this->data['update_time'] = NOW_TIME;
        // 注册用户
        $result = M('Member')->data($this->data)->add();
        if (!$result) {
            return $this->response->setInfo(false, '系统繁忙，稍后重试！');
        }

        // 存在openid 记录互联登录绑定信息
        // if (!empty($this->data['openid'])) {
        //     $oauth_data['m_id']              = $result;
        //     $oauth_data['openid']            = $this->data['openid'];
        //     $oauth_data['platform']          = $this->data['platform'];
        //     //$oauth_data['platform_head']     = empty($this->data['platform_head'])?'':$this->data['platform_head'];
        //     //$oauth_data['platform_nickname'] = empty($this->data['platform_nickname'])?'':$this->data['platform_nickname'];
        //     M('MemberOauth')->data($oauth_data)->add();
        // }

        //存在邀请码 创建推荐关系
        if ($user_id) {
            //创建推荐关系
            D('FrontC/Distribution', 'Service')->addRelation($result, $user_id, $code);
        }

        //用户编号添加
        call_procedure('_generate_code_', [$result, 'm']);

        //返回用户信息
        return $result;
    }

    /**
     * [setData description]
     * @Author   黑暗中的武者
     * @DateTime 2019-08-13T14:53:41+0800
     * @param    [type]                   $field [description]
     * @param    [type]                   $value [description]
     */
    public function setData($field, $value)
    {
        $this->data[$field] = $value;
    }
}
