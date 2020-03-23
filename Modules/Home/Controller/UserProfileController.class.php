<?php
namespace Home\Controller;

/**
 * 选手公式
 */
class UserProfileController extends HomeBaseController
{

    /**
     * [index description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:59:34+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $result = D('FrontC/UserProfile', 'Logic')->getList(I('request.'));
        //dump($result);
        $this->assign('profiles', $result['list']);
        $this->assign('page', $result['page']);
        $this->display('index');
    }

    /**
     * [add 添加报名]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-15T11:10:56+0800
     */
    public function add()
    {
        $result = D('FrontC/UserProfile', 'Logic')->add(I('request.'));

        if (!$result) {
            $this->error(D('FrontC/UserProfile', 'Logic')->getLogicInfo(),'',true);
        }
        $this->success('报名成功！','',true);
    }

    /**
     * [center 中心]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-18T15:08:36+0800
     * @return   [type]                   [description]
     */
    public function center()
    {
        $profile = M('UserProfile')->where(['id'=>session('user.user_id')])->find();
        if (!$profile || $profile['type'] != 1) {
            redirect('/');
        }
        // 获取头像
        $profile['avatar_path'] = api('File/getFiles', array($profile['avatar'], array('abs_url')))[0]['abs_url'];

        $this->assign('profile', $profile);
        $this->display('center');
    }

    /**
     * [criccenter 评委中心]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-18T15:25:03+0800
     * @return   [type]                   [description]
     */
    public function criccenter()
    {
        $profile = M('UserProfile')->where(['id'=>session('user.user_id')])->find();
        if (!$profile || $profile['type'] != 2) {
            redirect('/');
        }
        $this->assign('profile', $profile);
        $this->display('criccenter');
    }
}
