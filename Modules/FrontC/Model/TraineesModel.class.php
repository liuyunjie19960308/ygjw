<?php
namespace FrontC\Model;

/**
 * 
 */
class TraineesModel extends FrontBaseModel
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
            $total = $this->alias('trainees')->where($param['where'])->count();
            // 创建分页对象
            $Page  = $this->getPage($total, C('LIST_ROWS'), $_REQUEST);
        }
        //获取数据
        $list = $this->alias('trainees')
                     ->field('trainees.id trainees_id,trainees.short_desc,trainees.show_cover,trainees.video_id,trainees.link_url')
                     ->where($param['where'])
                     ->order($param['order'])
                     ->join([

                     ])
                     ->limit($Page->firstRow, $Page->listRows)
                     ->select();
        // 返回数据
        return ['list' => $list,'page' => $Page == null ? '' : $Page->show()];
    }

    /**
     * [findRow description]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:32:45+0800
     * @param    array                    $param [description]
     * @return   [type]                          [description]
     */
    public function findRow($param = [])
    {
        return $this->alias('trainees')
                    ->field('trainees.id trainees_id,trainees.name,trainees.short_desc,trainees.video_id,trainees.create_time,trainees.content')
                    ->where($param['where'])
                    ->find();
    }
}