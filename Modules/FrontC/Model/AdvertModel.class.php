<?php
namespace FrontC\Model;

/**
 * Class AdvertModel
 * @package FrontC\Model
 * 广告数据模型
 */
class AdvertModel extends FrontBaseModel
{

    /**
     * @param array $param
     * @return array
     * 广告列表
     */
    public function getList($param = [])
    {
        return $this->alias('ad')
                    ->field('ad.id ad_id,ad.position,ad.picture,ad.target_rule,ad.param,ad.start_time,ad.end_time,file.abs_url')
                    ->where(array('ad.status'=>1))
                    ->order('ad.sort ASC,ad.id DESC')
                    ->join(array(
                        'LEFT JOIN '.C('DB_PREFIX').'file file ON file.id = ad.picture',
                    ))
                    ->select();
    }
}
