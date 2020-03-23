<?php
namespace FrontC\Logic;

/**
 * 
 */
class MaterialLogic extends FrontBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = [])
    {
        // 栏目
        if (!empty($request['cate'])) {
            $param['where']['material.cate'] = $request['cate'];
        }
        // 获取数据
        $result = D('FrontC/Material', 'Service')->getList($param, $request);

        return $result;
    }
}
