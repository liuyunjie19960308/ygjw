<?php
namespace FrontC\Model;

/**
 * Class HomeMessageModel
 * @package FrontC\Model
 * 站内消息 数据层
 */
class HomeMessageModel extends FrontBaseModel {

    /**
     * @param array $param
     * @return array
     * 基本列表
     */
    public function getList($param = []) {
        //数据总数
        $total  = $this->alias('home_msg')->where($param['where'])->count();
        //创建分页对象
        $Page   = $this->getPage($total, $param['page_size'], $_REQUEST);
        //生成ID查询条件
        $param  = $this->specialSearch($param, $Page, 'home_msg');
        //获取数据
        $list   = $this->alias('home_msg')
            ->field('home_msg.id home_msg_id,home_msg.subject,home_msg.content,home_msg.create_time,home_msg.status')
            ->where($param['special_where'])
            /*->join(array(
                'LEFT JOIN ' . C('DB_PREFIX') . 'member m ON m.id = f_ship.friend_id',
            ))*/
            ->select();
        //返回记录 根据ID顺序排序
        return ['list' => sort_by_array($param['ids_for_sort'], $list, 'home_msg_id'), 'page' => $Page->show()];
    }
}