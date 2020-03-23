<?php
namespace Bms\Controller;

/**
 * 
 */
class UserProfileController extends BmsBaseController
{

    protected function getIndexRelation() {
        
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
     * 导出
     */
    public function export() {
        //权限验证
        $this->checkRule(self::$rule);
        $Obj = D('MsC/Export', 'Service');
        $Obj->setSavePath('./Data/export/user');
        if ($_REQUEST['type'] == 1) {
            $Obj->setFileName('选手数据');
            $result = $Obj->memberExport1(I('request.'));
        } else {
            $Obj->setFileName('评委数据');
            $result = $Obj->memberExport2(I('request.'));
        }
        
//        if(false === $result) {
//            $this->error(self::$logicObject->getLogicInfo());
//        } else {
//            $this->success(self::$logicObject->getLogicInfo());
//        }
    }


    /**
     * 执行审核通过
     */
    public function pass() {
        $this->checkRule(self::$rule);
        $result = self::$logicObject->doPass(I('request.'));
        if(!$result) {
            $this->error(self::$logicObject->getLogicInfo());
        }
        $this->success(self::$logicObject->getLogicInfo(), cookie('__forward__'));
    }

    /**
     * 拒绝通过
     */
    public function refusePass() {
        $this->checkRule(self::$rule);
        $result = self::$logicObject->doRefusePass(I('request.'));
        if(!$result) {
            $this->error(self::$logicObject->getLogicInfo());
        }
        $this->success(self::$logicObject->getLogicInfo(), cookie('__forward__'));
    }
}
