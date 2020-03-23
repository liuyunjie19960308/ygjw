<?php
namespace Home\Controller;

/**
 * 招商
 */
class BussinessController extends HomeBaseController
{

    /**
     * [index description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:59:34+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        // 素材
        $materials = D('FrontC/Material', 'Logic')->getList(['cate'=>1]);
        //dump($materials);
        $this->assign('materials', $materials);
        $this->display('index');
    }
}
