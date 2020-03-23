<?php
namespace Bms\Logic;

/**
 * Class MemberLogic
 * @package Bms\Logic
 * 会员管理逻辑层
 */
class MemberLogic extends BmsBaseLogic {

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = []) {
        //查询条件
        $param['where'] = $this->createWhere($request, 'm.');
        //排序
        $param['order'] = 'm.id DESC';
        //自定义排序
        if(!empty($request['sort'])) {
            $param['order'] = str_replace(':',' ',$request['sort']);
        }

        //每页数量
        $param['page_size'] = !isset($request['page_size']) ? C('LIST_ROWS') : $request['page_size'];

        //返回数据
        $result = D('Member')->getList($param);

        foreach($result['list'] as $key => $value) {
            //互联登录账号绑定情况 等级
            $value['extra'] = D('FrontC/Member', 'Service')->getInfo('', $value['id'], '15,18');
            //头像
            $value['avatar_path'] = D('FrontC/Member', 'Service')->getAvatar($value['avatar']);

            $value['amounts_1'] = M('FinanceWithdrawRecords')->where(['user_id'=>$value['id'],'user_type'=>1,'status'=>1])->sum('amounts');
            $value['amounts_2'] = M('FinanceWithdrawRecords')->where(['user_id'=>$value['id'],'user_type'=>1,'status'=>0])->sum('amounts');
            $value['amounts_3'] = M('FinanceBalanceRecords')->where(['user_id'=>$value['id'],'user_type'=>1,'status'=>1,'trend'=>5])->sum('amounts');




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
        if(!empty($request['id'])) {
            $where[$alias.'id'] = $request['id'];
        }
        //等级 查找
        if(!empty($request['level'])) {
            $where[$alias.'level'] = $request['level'];
        }
        //账号 查找
        if(!empty($request['account'])) {
            $where[$alias.'account'] = $request['account'];
        }
        //城市名称查找
        if(!empty($request['city_name'])) {
            $where[$alias.'city_name'] = ['LIKE', '%'.$request['city_name'].'%'];
        }
        //时间查找
        if(!empty($request['start_time'])) {
            $where[$alias.'create_time']  = ['between', strtotime($request['start_time']) . "," . strtotime($request['start_time'] . '23:59')];
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
            $param['where']['m.id'] = $request['id'];
        } else {
            $this->setLogicInfo('参数错误！'); return false;
        }

        //获取数据
        $row = D('Member')->findRow($param);
        if(!$row) {
            $this->setLogicInfo('未查到此记录！'); return false;
        }

        //头像
        $row['avatar_path'] = D('FrontC/Member', 'Service')->getAvatar($row['avatar']);
        //二维码
        // if(!is_file('./Uploads/Member/Code/' . MD5($row['member_sn']) .'.png')) {
        //     vendor('phpQrcode.phpqrcode');
        //     \QRcode::png($row['member_sn'],'./Uploads/Member/Code/'.MD5($row['member_sn']).'.png',QR_ECLEVEL_L,10,1,true);
        // }
        // $row['code_path'] = C('FILE_HOST') . '/Uploads/Member/Code/' . MD5($row['member_sn']) .'.png';
        //互联登录绑定情况
        //$row['interconnect'] = D('FrontC/Member', 'Service')->getInfo('', $row['id'], '15');
        //互联登录账号绑定情况 等级
        //$row['extra'] = D('FrontC/Member', 'Service')->getInfo('', $row['id'], '15,18');

        return $row;
    }

    /**
     * @param array $data
     * @param array $request
     * @return array
     * 处理提交数据 进行加工或者添加其他默认数据
     */
    protected function processData($data = [], $request = []) {
        if(empty($request['id'])) { //添加
            if(empty($request['password'])) {
                return $this->setLogicInfo('请输入登陆密码！', false);
            }
            $data['password']       = MD5($request['password']);
            $data['password_visible'] = $request['password'];
            $data['mobile']         = $request['account'];
            $data['nickname']       = D('FrontC/Member', 'Service')->accountFormat($request['account']);
            $data['register_ip']    = get_client_ip(1);
        } else { //修改
            unset($data['password']);
            //如果编辑密码则修改
            if(!empty($request['password'])) {
                $data['password'] = MD5($request['password']);
                $data['password_visible'] = $request['password'];
            }
        }

        //地区信息处理
        $data['province_id']    = substr($request['province_info'], 0, strpos($request['province_info'], '|'));
        $data['province_name']  = substr($request['province_info'], strpos($request['province_info'], '|')+1);
        $data['city_id']        = substr($request['city_info'], 0, strpos($request['city_info'], '|'));
        $data['city_name']      = substr($request['city_info'], strpos($request['city_info'], '|')+1);
        $data['district_id']    = substr($request['district_info'], 0, strpos($request['district_info'], '|'));
        $data['district_name']  = substr($request['district_info'], strpos($request['district_info'], '|')+1);

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
     * @param int $result
     * @param array $request
     * @return boolean
     * 彻底删除成功后执行
     */
    protected function afterRemove($result = 0, $request = []) {
        //将商家商品置为下架
        if($request['shop_id']) {
            M('Goods')->where(['goods_type'=>2,'shop_id'=>$request['shop_id']])->data(['status'=>0])->save();
        }
        return true;
    }

    /********************发信时信息获取*********************/
    /**
     * @param array $where_arr
     * @return array
     * 生成查询条件
     */
    public function getToUsersWhere($where_arr = []) {
        $where = [];
        //ID查询
        if(!empty($where_arr['id'])) {
            $where['id'] = $where_arr['id'];
        }
        //账号查询
        if(!empty($where_arr['account'])) {
            $where['account'] = $where_arr['account'];
        }
        return $where;
    }

    /**
     * @param $where
     * @return mixed
     * 获取记录总数
     */
    public function getToUsersCount($where = []) {
        return D('Member')->where($where)->count();
    }

    /**
     * @param $where
     * @param $order
     * @param $first_row
     * @param $page_size
     * @return mixed
     * 获取数据列表
     */
    public function getToUsersList($where = [], $order = '', $first_row = 0, $page_size = 200) {
        return D('Member')->field('id,account,mobile,email,nickname')->where($where)->order($order)->limit($first_row,$page_size)->select();
    }
    /********************发信时信息获取*********************/


    /**
     * @param array $request
     * @return bool
     * 导入数据
     */
    function import($request = []) {
        //判断是否上传了导入文件
        if(empty($request['import_file'])) {
            $this->setLogicInfo('您未上传导入文件！'); return false;
        }
        //获取导入文件中数据
        $data = api('Excel/readExcelToData',[$request['import_file']]);
        //文件错误
        if($data === false) {
            $this->setLogicInfo('导入文件格式有误！'); return false;
        }
        //数据为空
        if(empty($data)) {
            $this->setLogicInfo('导入数据为空！'); return false;
        }
        //生成一些其他数据
        foreach($data as $key => $value){
            $data[$key]['create_time']  = NOW_TIME;                   //创建时间
            $data[$key]['password']     = MD5($value['password']);  //密码加密
        }
        //存入数据库  //TODO 是否要验证重复
        $result = D('Member')->addAll($data);
        if($result) {
            //删除文件记录
            return true;
        } else {
            $this->setLogicInfo('数据导入失败！'); return false;
        }
    }
}