<?php
namespace FrontC\Core\Promote;

/**
 * 分享
 */
class Share
{
    /**
     * [getShareCode 获取分享码]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-24T10:00:12+0800
     * @param    integer                  $m_id  [description]
     * @param    string                   $scope [description]
     * @return   [type]                          [description]
     */
    public function getShareCode($m_id = 0, $scope = '')
    {
        if (empty($m_id) || empty($scope)) {
            return false;
        }
        $where['scope']       = $scope;
        $where['m_id']        = $m_id;
        $where['create_time'] = ['between', strtotime(date('Y-m-d')) . "," . strtotime(date('Y-m-d') . '23:59:59')];
        // 查看今天有没有码 有的话直接返回
        $code = M('ShareCode')->where($where)->find();
        if ($code) {
            return $code['code'];
        }
        // 今天没有码 就新创建一个
        $code = think_encrypt(com_create_guid());
        $data = [
            'm_id'        => $m_id,
            'code'        => $code,
            'scope'       => $scope,
            'create_time' => NOW_TIME,
        ];
        if (!M('ShareCode')->data($data)->add()) {
            return false;
        }
        return $code;
    }

    /**
     * [click 点击分享链接]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-24T10:13:28+0800
     * @return   [type]                   [description]
     */
    public function click($scope = '', $code = '')
    {
        if (!empty($scope) && !empty($code)) {
            // 如果不是在微信中
            if (CARRIER != 'wechat') {
                echo '请在微信浏览器中打开！'; exit;
            }
            // 是不是有效的code
            $share = M('ShareCode')->where(['code'=>$code])->find();
            if (empty($share) || ($share['create_time'] < strtotime(date('Y-m-d')))) {
                return false;
            }
            // 一个分享链接只能获取一次抽奖机会
            if ($share['click'] > 0) {
                return false;
            }
            // // 如果已经点击过
            // if (M('ShareCodeClick')->where(['openid'=>session('openid'),'code'=>$code])->count()) {
            //     return false;
            // }
            // 增加点击记录
            $data = [
                'openid'      => session('openid'),
                'code'        => $code,
                'create_time' => NOW_TIME,
            ];
            if (!M('ShareCodeClick')->data($data)->add()) {
                return false;
            }
            // 增加抽奖次数
            M('ShareCode')->where(['code'=>$code])->setInc('click');
        }
    }
}
