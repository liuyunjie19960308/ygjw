<?php
namespace Bms\Controller;

/**
 * Class MemberController
 * @package Bms\Controller
 * 会员控制器
 */
class MemberController extends BmsBaseController {

    /***
     * @var array
     * 日期参数
     */
    protected $dateParam = [];

    /**
     * 初始化执行
     */
    public function _initialize() {
        //执行 父类_initialize()的方法体
        parent::_initialize();
        //日期参数
        $this->dateParam = api('Statistics/setDateParam', [I('request.')]);
    }

    protected function getIndexRelation() {
        $this->assign('level_rules', D('FrontC/Member', 'Service')->levelRule());
    }

    protected function getUpdateRelation() {
        $this->_commonAssign();
    }

    protected function getAddRelation() {
        $this->_commonAssign();
    }

    private function _commonAssign() {
        // //获取省列表
        // $this->assign('provinces', D('Region','Service')->select(['where'=>['parent_id'=>100000]]));
        // $row = $this->__get('row');
        // //var_dump($row);
        // //获取城市列表
        // if(!empty($row['province_id']))
        //     $this->assign('cities', D('Region','Service')->select(['where'=>['parent_id'=>$row['province_id']]]));
        // //获取区县列表
        // if(!empty($row['city_id']))
        //     $this->assign('districts', D('Region','Service')->select(['where'=>['parent_id'=>$row['city_id']]]));
        // //等级数据
        // $level_rules = D('FrontC/Member', 'Service')->levelRule();
        // $this->assign('level_rules', $level_rules);
        //var_dump($level_rules);
    }

    /**
     * 详情页面数据
     */
    protected function getDetailRelation() {
        //获取地址列表
        //$this->assign('adr_list', D('FrontC/Address', 'Logic')->getList(['m_id'=>I('request.id')]));
        //获取账号列表
        //$this->assign('uwa_list', D('FrontC/UserWithdrawAccount', 'Logic')->getList(['m_id'=>I('request.id')]));
    }

    /**
     * 数据统计
     */
    public function stat() {
        $this->assign('breadcrumb_nav', '数据统计');
        $this->display('Member/stat/stat');
    }

    public function statTotal() {
        //总数统计
        $this->assign('total', M('Member')->count());
        $this->assign('status_1', M('Member')->where(['status'=>1])->count());
        $this->assign('status_0', M('Member')->where(['status'=>0])->count());
        $this->display('Member/stat/statTotal');
    }

    public function statQuantityLine() {
        //每天用户数量线状统计
        $this->assign('day_users_quantity_line', D('Statistics','Logic')->usersQuantityLine(I('request.'), $this->dateParam));
        $this->display('Member/stat/statQuantityLine');
    }

    public function statGenderPie() {
        //男女比例饼状图
        $this->assign('gender_pie', D('Statistics', 'Logic')->genderPie(I('request.')));
        $this->display('Member/stat/statGenderPie');
    }

    /**
     * 调整方法
     */
    public function adjust() {
        $this->checkRule(self::$rule);
        if(!IS_POST) {
            $this->assign('breadcrumb_nav', L('_ADJUST_BREADCRUMB_NAV_'));
            $this->assign('info', M('Member')->where(['id'=>I('request.ids')])->field('balance,integral,trial')->find());
            $this->display('adjust');
        } else {
            $result = D('MsC/Adjust','Logic')->doAdjust(I('request.'));
            if(!$result)
                $this->error(D('MsC/Adjust','Logic')->getLogicInfo());
            else
                $this->success('操作成功！', cookie('__forward__'));
        }
    }

    /**
     * 导出
     */
    public function export() {
        //权限验证
        $this->checkRule(self::$rule);
        $Obj = D('MsC/Export', 'Service');
        $Obj->setFileName('用户数据');
        $Obj->setSavePath('./Data/export/member');
        $result = $Obj->memberExport(I('request.'));
//        if(false === $result) {
//            $this->error(self::$logicObject->getLogicInfo());
//        } else {
//            $this->success(self::$logicObject->getLogicInfo());
//        }
    }

    /**
     * 导入数据
     */
    function import() {
        $this->checkRule(self::$rule);
        if(!IS_POST) {
            $this->assign('breadcrumb_nav', L('_IMP_BREADCRUMB_NAV_'));
            $this->display('import');
        } else {
            $result = D('Member','Logic')->import(I('post.'));
            if($result) {
                $this->success('数据导入成功！', cookie('__forward__'));
            } else {
                $this->error(D('Member','Logic')->getLogicInfo());
            }
        }
    }


    public function delRelation()
    {
        if (empty($_REQUEST['recommended_account'])) {
            $this->error('请输入账号！');
        }
        $recommended_id = M('Member')->where(['account'=>I('request.recommended_account')])->field('')->find();

        if ($recommended_id) {
            $result = M('DistributeRelationship')->where(['recommender_id'=>I('request.recommender_id'),'recommended_id'=>$recommended_id])->delete();
            if (!$result) {
                $this->error('删除失败！');
            }
        } else {
            $this->error('账号不存在！');
        }
        $this->success('操作成功！');
    }
}