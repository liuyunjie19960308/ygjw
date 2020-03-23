<?php
namespace Bms\Logic;

/**
 * 
 */
class MaterialLogic extends BmsBaseLogic
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-23T15:06:05+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getList($request = [])
    {
        // 排序条件
        $param['order']     = 'sort ASC,id DESC';
        // 页码
        $param['page_size'] = C('LIST_ROWS');
        // 返回数据
        $result = D('Material')->getList($param);

        foreach ($result['list'] as &$value) {
            $value['pc_show'] = api('File/getFiles', array($value['pc_show']));
        }

        return $result;
    }

    /**
     * [findRow description]
     * @Author   黑暗中的武者
     * @DateTime 2019-06-15T16:02:20+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function findRow($request = [])
    {
        if (!empty($request['id'])) {
            $param['where']['id'] = $request['id'];
        } else {
            return $this->setLogicInfo('参数错误！', false);
        }
        // 获取数据
        $row = D('Material')->findRow($param);
        if (!$row) {
            return $this->setLogicInfo('未查到此记录！', false);
        }
        // 获取展示封面
        $row['pc_show'] = api('File/getFiles', [$row['pc_show']]);
        
        //返回数据
        return $row;
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     * 处理提交数据 进行加工或者添加其他默认数据
     */
    protected function processData($data = [], $request = [])
    {
        return $data;
    }
}
