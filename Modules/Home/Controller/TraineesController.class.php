<?php
namespace Home\Controller;

/**
 * 明星学员
 */
class TraineesController extends HomeBaseController
{

    /**
     * [index 明星选手列表]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:59:34+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        // 明星选手
        $traineess = D('FrontC/Trainees', 'Logic')->getList();
        //dump($traineess);
        $this->assign('traineess', $traineess);
        $this->display('index');
    }

    /**
     * [detail 明细选手详情]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:29:44+0800
     * @return   [type]                   [description]
     */
    public function detail()
    {
        // 明星选手
        $trainees = D('FrontC/Trainees', 'Logic')->getDetail(I('request.'));
        
        $this->assign('trainees', $trainees);
        $this->display('detail');
    }
}
