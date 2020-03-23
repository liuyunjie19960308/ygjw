<?php
namespace Bms\Logic;

/**
 * 
 */
class ConsultationLogic extends BmsBaseLogic
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-11T16:18:17+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getList($request = [])
    {
        //排序条件
        $param['order']     = 'create_time DESC';
        //页码
        $param['page_size'] = C('LIST_ROWS');
        //查询的字段
        $param['field']     = 'id,iwant,mobile,ip,create_time,finish_time,status';
        //返回数据
        return D('Consultation')->getList($param);
    }

    /**
     * [afterSetField description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-11T16:18:19+0800
     * @param    integer                  $result  [description]
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    protected function afterSetField($result = 0, $request = array())
    {
        M('Consultation')->where(array('id'=>$request['ids']))->data(array('finish_time'=>NOW_TIME))->save();
        return true;
    }
}