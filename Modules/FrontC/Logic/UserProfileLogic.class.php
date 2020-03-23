<?php
namespace FrontC\Logic;

/**
 * 
 */
class UserProfileLogic extends FrontBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = [])
    {
        // 获取数据
        $result = D('FrontC/UserProfile', 'Service')->getList($param, $request);

        return $result;
    }

    /**
     * [add 添加报名]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-15T11:17:20+0800
     * @param    array                    $request [description]
     */
    public function add($request = [])
    {
        // 
        $data = D('FrontC/UserProfile')->create($request);

        if (!$data) {
            return $this->setLogicInfo(D('FrontC/UserProfile')->getError(), false);
        }

        // 密码加密
        $data['password']    = MD5($request['password']);
        $data['update_time'] = NOW_TIME;

        // // 根据身份证号判断
        // if (M('UserProfile')->where(['type'=>$request['type'],'id_num'=>$request['id_num']])->count()) {
        //     $result = M('UserProfile')->where(['id_num'=>$request['id_num']])->data($data)->save();
        //     $m_id = M('UserProfile')->where(['id_num'=>$request['id_num']])->getField('id');
        // } else {
        //     $data['create_time'] = NOW_TIME;
        //     $data['register_ip'] = get_client_ip(1);
        //     //$data['status'] = 0;
        //     $result = M('UserProfile')->data($data)->add();
        //     $m_id = $result;
        //     // 设置编号
        //     $sn = $this->fill_noid($result);

        //     M('UserProfile')->where(['id'=>$result])->data(['sn'=>$sn])->save();
        // }
        
        $data['create_time'] = NOW_TIME;
        $data['register_ip'] = get_client_ip(1);
        $data['laiyuan']     = CARRIER;

        $result = M('UserProfile')->data($data)->add();
        $m_id = $result;
        // 设置编号
        $sn = $this->fill_noid($result);

        M('UserProfile')->where(['id'=>$result])->data(['sn'=>$sn])->save();

        if (!$result) {
            return $this->setLogicInfo('报名失败，稍后重试！', false);
        }

        // 删除原来的
        M('UserProfile')->where(['id_num'=>$request['id_num'],'id'=>['LT',$result]])->delete();

        D('FrontC/Passport', 'Logic')->setSession($m_id);

        return $this->setLogicInfo('报名成功！', true);
        // 
    }

    function fill_noid($id = '') {
        if (empty($id)) {
            return '0'; 
        }
        while(strlen($id) < 6) {
            $id = '0' . $id;
        }
        return $id;
    }
}
