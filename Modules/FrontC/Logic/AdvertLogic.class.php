<?php
namespace FrontC\Logic;

/**
 * Class AdvertLogic
 * @package FrontC\Logic
 * 广告逻辑层
 */
class AdvertLogic extends FrontBaseLogic
{

    /**
     * [getAdvert description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:52:03+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getAdvert($request = [])
    {
        // 判断参数
        if (empty($request['position'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        // 获取广告列表
        $list = D('FrontC/Advert', 'Service')->getAdvert($request);
        // 返回数据
        return $list;
    }
}
