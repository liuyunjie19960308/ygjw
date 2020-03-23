<?php
namespace FrontC\Model;

/**
 * 
 */
class MaterialModel extends FrontBaseModel
{

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = [])
    {
        $Page = null;
        // 是否分页
        if (!empty($param['page_size'])) {
            // 数据总数
            $total = $this->alias('material')->where($param['where'])->count();
            // 创建分页对象
            $Page  = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        }
        //获取数据
        $list = $this->alias('material')
                     ->field('material.id material_id,material.pc_show,material.wap_show')
                     ->where($param['where'])
                     ->order($param['order'])
                     ->join([

                     ])
                     ->limit($Page->firstRow, $Page->listRows)
                     ->select();
        // 返回数据
        return ['list' => $list,'page' => $Page == null ? '' : $Page->show()];
    }
}
