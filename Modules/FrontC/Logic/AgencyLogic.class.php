<?php
namespace FrontC\Logic;

/**
 * 赛区
 */
class AgencyLogic extends FrontBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = [])
    {
        // 获取数据
        $result = D('FrontC/Agency', 'Service')->getList($param, $request);

        return $result;
    }

    /**
     * [getAudition 海选地点]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T14:40:26+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getAudition($request = [])
    {
        $param['where']['audition.agency_id'] = $request['agency_id'];
        // 获取数据
        $result = D('FrontC/AuditionAddress', 'Service')->getList($param, $request);

        return $result;
    }
}
