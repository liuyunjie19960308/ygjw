<?php
namespace Wap\Controller;

/**
 * 赛区
 */
class AgencyController extends WapBaseController
{

    /**
     * [index description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:59:34+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        // 赛区
        $agencys = D('FrontC/Agency', 'Logic')->getList();
        $this->assign('agencys', $agencys);

        $this->display('index');
    }

    /**
     * [getAudition 海选地点]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-18T18:05:27+0800
     * @return   [type]                   [description]
     */
    public function getAudition()
    {
        // 海选地点
        $auditions = D('FrontC/Agency', 'Logic')->getAudition(['agency_id'=>I('request.agency_id')]);

        $this->success('', '', true, $auditions);
    }
}
