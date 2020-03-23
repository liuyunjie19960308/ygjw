<?php
namespace Bms\Logic;

/**
 * 
 */
class UserProfileLogic extends BmsBaseLogic
{

    /**
     * [getList description]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-05T14:11:12+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getList($request = [])
    {
        //查询条件
        $param['where'] = $this->createWhere($request, 'user_profile.');
        //排序
        $param['order'] = 'user_profile.status ASC,user_profile.id DESC';
        //自定义排序
        if(!empty($request['sort'])) {
            $param['order'] = str_replace(':',' ',$request['sort']);
        }

        //每页数量
        $param['page_size'] = !isset($request['page_size']) ? C('LIST_ROWS') : $request['page_size'];

        //返回数据
        $result = D('UserProfile')->getList($param);

        foreach($result['list'] as $key => $value) {

            $value['avatar_path'] = D('FrontC/Member', 'Service')->getAvatar($value['avatar']);

            $result['list'][$key] = $value;
        }

        return $result;
    }

    /**
     * @param array $request
     * @return array
     * 创建查询条件
     */
    public function createWhere($request = [], $alias = '') {
        $where = [];
        //ID 查找
        if(!empty($request['name'])) {
            $where[$alias.'name'] = $request['name'];
        }
        //等级 查找
        if(!empty($request['type'])) {
            $where[$alias.'type'] = $request['type'];
        }
        //城市名称查找
        if(!empty($request['city_name'])) {
            $where[$alias.'city_name'] = ['LIKE', '%'.$request['city_name'].'%'];
        }
        //时间查找
        if (!empty($request['start_date'])&&!empty($request['end_date'])) {
            //$where[$alias.'create_time'] = ['between', strtotime($request['start_date']) . "," . strtotime($request['end_date'])];
            $where[$alias.'create_time'] = ['between', strtotime(str_replace('+', ' ', $request['start_date'])) . "," . strtotime(str_replace('+', ' ', $request['end_date']))];
        }
        return $where;
    }

    /**
     * @param array $request
     * @return mixed
     * 会员详情
     */
    public function findRow($request = []) {
        //参数判断
        if(!empty($request['id'])) {
            $param['where']['user_profile.id'] = $request['id'];
        } else {
            $this->setLogicInfo('参数错误！'); return false;
        }

        //获取数据
        $row = D('UserProfile')->findRow($param);
        if(!$row) {
            $this->setLogicInfo('未查到此记录！'); return false;
        }

        //头像
        $row['avatar_path'] = D('FrontC/Member', 'Service')->getAvatar($row['avatar']);

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

    /**
     * @param $result
     * @param array $request
     * @return boolean
     * 更新后执行
     */
    protected function afterUpdate($result = 0, $request = []) {
        if(empty($request['id'])) {
            //用户编号添加
            call_procedure('_generate_code_', [$result, 'm']);
        }
        return true;
    }

    /**
     * @param array $request
     * @return boolean
     * 彻底删除前执行 将数据存入回收站 
     */
    protected function beforeRemove($request = []) {
        //return true;
        //if(api('Recycle/recovery',[$request, 'Member', 'nickname', [['model'=>'Shop','relation_field'=>['this'=>'m_id','parent'=>'id']]]])) {
        if(api('Recycle/recovery', [$request, 'Member', 'nickname'])) {
            return true;
        }
        $this->setLogicInfo('删除失败！');  return false;
    }

    /**
     * @param array $request
     * @return bool
     * 执行通过操作
     */
    public function doPass($request = []) {
        if (empty($request['ids'])) {
            return $this->setLogicInfo('参数错误！', false);
        }

        $where['id'] = is_numeric($request['ids']) ? $request['ids'] : ['IN', $request['ids']];
        
        //$where['status'] = 0;

        //判断状态是否可以执行此审核操作
        if (!M('UserProfile')->where($where)->count()) {
            return $this->setLogicInfo('操作禁止！存在非审核中状态的用户！', false);
        }

        $data = [
            'status' => 1,
        ];

        if (!M('UserProfile')->where($where)->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }

        //TODO-SEND 发送推送/邮件

        return $this->setLogicInfo('操作成功！', true);
    }

    /**
     * @param array $request
     * @return bool
     * 执行拒绝通过操作
     */
    public function doRefusePass($request = [])
    {
        if (empty($request['user_id'])) {
            return $this->setLogicInfo('参数错误！', false);
        }

        $where['id']        = $request['user_id'];
        $where['status']    = 0;
        // 判断状态是否可以执行此审核操作
        if (!M('UserProfile')->where($where)->count()) {
            return $this->setLogicInfo('操作禁止！存在非审核中状态的用户！', false);
        }
        // 判断是否填写了原因
        if(empty($request['refuse_remark'])) {
            return $this->setLogicInfo('请填写拒绝通过原因！', false);
        }

        $data = [
            'status'        => 2,
            'refuse_remark' => $request['refuse_remark']
        ];

        // 执行修改
        if(!M('UserProfile')->where($where)->data($data)->save()) {
            return $this->setLogicInfo('系统繁忙，稍后重试！', false);
        }

        //TODO-SEND 发送推送/邮件

        return $this->setLogicInfo('操作成功！', true);
    }
}
