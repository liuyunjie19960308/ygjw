<?php
namespace Bms\Controller;

/**
 * 海选地点
 */
class AuditionAddressController extends BmsBaseController
{

    protected function getUpdateRelation()
    {
        $result = D('FrontC/Agency', 'Logic')->getList();
        $this->assign('agencys', $result);
    }

    protected function getAddRelation()
    {
        $result = D('FrontC/Agency', 'Logic')->getList();
        $this->assign('agencys', $result);
    }
}
