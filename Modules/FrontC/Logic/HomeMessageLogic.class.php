<?php
namespace FrontC\Logic;

/**
 * Class HomeMessageLogic
 * @package FrontC\Logic
 * 消息逻辑层
 */
class HomeMessageLogic extends FrontBaseLogic {

    /**
     * @param array $request
     * @return array
     * 消息列表
     */
    public function messages($request = []) {
        //用户信息
        $param['where']['home_msg.user_id']      = $request['m_id'];
        $param['where']['home_msg.user_type']    = 1;
        //每页数据量
        $param['page_size'] = 15;
        //排序
        $param['order'] = 'home_msg.id DESC';

        $result = D('FrontC/HomeMessage')->getList($param);

        foreach($result['list'] as &$value) {
            //时间处理
            $value['create_time'] = fuzzy_date($value['create_time']);
        }

        return $result;
    }

    /**
     * @param array $request
     * @return array
     * 消息详情
     */
    public function detail($request = []) {
        if(empty($request['home_msg_id'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        //获取数据
        $detail = M('HomeMessage')->where(array('id'=>$request['home_msg_id']))->field('id home_msg_id,subject,content,create_time')->find();
        //查询错误
        if(!$detail)
            return $this->setLogicInfo('数据不存在！', false);
        //时间处理
        $detail['create_time'] = fuzzy_date($detail['create_time']);
        //已读设置
        M('HomeMessage')->where(['id'=>$request['home_msg_id']])->setField('status', 1);

        return $detail;
    }

    /**
     * 未读消息
     * 详细描述：
     * 特别注意：
     * POST参数：*m_id(用户ID)
     */
    public function notRead($m_id = 0) {
        if(empty($m_id)) {
            return '0';
        }
        //未读消息
        $not_read_msg  = M('HomeMessage')->where(['user_id'=>$m_id,'status'=>0])->count();
        //未读资讯
        $not_read_news = M('News')->where(['id'=>['exp', 'NOT IN (SELECT news_id FROM '.C('DB_PREFIX').'news_read_records WHERE m_id='.$m_id.')'],'status'=>1])->count();

        if($not_read_msg + $not_read_news > 0) {
            return '1';
        }
        return '0';
    }
}