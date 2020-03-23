<?php
namespace MsC\Service;

/**
 * Class UserService
 * @package MsC\Service
 * 用户信息处理
 */
class UserService extends MscBaseService
{

    /**
     * @var array
     * 用户类型
     */
    public $user_types = [
        1 => '普通用户',
        2 => '店铺',
    ];

    /**
     * @param int $user_id
     * @param $user_type
     * @return mixed
     * 不同类型用户信息返回
     */
    public function userInfo($user_id = 0, $user_type = 1)
    {
        $user_info = [
            'user_type' => '',
            'icon'      => '',
            'show_name' => '',
        ];

        if ($user_type == 1) {
            $user_info['user_type'] = '个人';
            $user_info['icon']      = '<a data-original-title="个人" href="javascript:void(0)" class="tip-right black-1"><i class="fa fa-user"></i></a>';
            $info = M('Member')->where(['id'=>$user_id])->field('account')->find();
            $user_info['show_name'] = $info['account'];
        } elseif ($user_type == 2) {
            $user_info['user_type'] = '店铺';
            $user_info['icon']      = '<a data-original-title="店铺" href="javascript:void(0)" class="tip-right black-1"><i class="fa fa-home"></i></a>';
            $info = M('Shop')->where(['id'=>$user_id])->field('shop_name')->find();
            $user_info['show_name'] = $info['shop_name'];
        } else {

        }

        return $user_info;
    }
}
