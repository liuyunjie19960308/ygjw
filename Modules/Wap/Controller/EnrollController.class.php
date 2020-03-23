<?php
namespace Wap\Controller;

/**
 * 报名
 */
class EnrollController extends WapBaseController
{

    /**
     * [index description]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-19T18:18:14+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $this->display('index');
    }

    /**
     * [signUp description]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-13T17:48:58+0800
     * @return   [type]                   [description]
     */
    public function signUp()
    {
        // 职业
        $this->assign('professions', C('PROFESSION'));
        // 类目
        $this->assign('classes', C('CLASSES'));
        // 高效赛区
        $this->assign('colleges', C('COLLEGES'));

        $this->assign('art', D('FrontC/Article','Logic')->artInfo(['flag'=>'notice_2']));
        $this->display('signUp');
    }

    public function acation()
    {
        $this->assign('art', D('FrontC/Article','Logic')->artInfo(['flag'=>'notice_1']));

        // 类目
        $this->assign('classes', C('CLASSES'));
        // 高效赛区
        $this->assign('colleges', C('COLLEGES'));

        $this->display('acation');
    }
}
