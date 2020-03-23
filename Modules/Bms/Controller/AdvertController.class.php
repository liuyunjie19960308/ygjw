<?php
namespace Bms\Controller;

/**
 * Class AdvertController
 * @package Bms\Controller
 * 广告管理控制器
 */
class AdvertController extends BmsBaseController
{

     protected function getUpdateRelation()
     {
         $this->assign('position', C('ADVERT_POSITION'));
         $this->assign('target_rules', C('TARGET_RULES'));
         $row = $this->get('row');
     }

     protected function getAddRelation()
     {
         $this->assign('position', C('ADVERT_POSITION'));
         $this->assign('target_rules', C('TARGET_RULES'));
     }

   
}
