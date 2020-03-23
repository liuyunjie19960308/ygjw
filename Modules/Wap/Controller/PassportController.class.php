<?php
namespace Wap\Controller;

/**
 * 
 */
class PassportController extends WapBaseController
{

    public function login()
    {
        $this->display('login');
    }

    /**
     * [doLogin 提交登录]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:56:14+0800
     * @return   [type]                   [description]
     */
    public function doLogin()
    {

        $result = D('FrontC/Passport', 'Logic')->doLogin(I('request.'));

        if (!$result) {
            $this->error(D('FrontC/Passport', 'Logic')->getLogicInfo(),'',true);
        }

        $this->success('登录成功！','',true,$result);
    }

    /**
     * [findPass 找回密码页]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:57:43+0800
     * @return   [type]                   [description]
     */
    public function findPass()
    {
        // 高效赛区
        $this->assign('colleges', C('COLLEGES'));
        $this->display('findPass');
    }

    /**
     * [doFindPass 执行找密码]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:57:53+0800
     * @return   [type]                   [description]
     */
    public function doFindPass()
    {

        $result = D('FrontC/Passport', 'Logic')->doFindPass(I('request.'));

        if (!$result) {
            $this->error(D('FrontC/Passport', 'Logic')->getLogicInfo(),'',true);
        }

        $this->success('修改成功，请重新登录！','',true);
    }

}
