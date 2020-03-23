<?php
namespace Bms\Logic;

/**
 * 
 */
class VisitRecordsLogic extends BmsBaseLogic
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-11T15:09:28+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getList($request = [])
    {
        // 排序条件
        $param['order'] = 'visit_rec.id DESC';
        // 返回数据
        $result = D('VisitRecords')->getList($param);

        foreach ($result['list'] as $key => &$value) {
            // 没有城市信息的通过IP获取城市信息
            if (empty($value['city'])) {
                $value['city'] = get_city_by_ip($value['ip_plaintext']);
                M('VisitRecords')->where(['id'=>$value['id']])->data(['city'=>$value['city']])->save();
            }
        }
        return $result;
    }
}
