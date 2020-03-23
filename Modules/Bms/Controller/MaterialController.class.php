<?php
namespace Bms\Controller;

/**
 * 素材
 */
class MaterialController extends BmsBaseController
{
    protected function getUpdateRelation()
    {
        $this->assign('lanmu', C('LANMU'));
    }

    protected function getAddRelation()
    {
        $this->assign('lanmu', C('LANMU'));
    }
}
