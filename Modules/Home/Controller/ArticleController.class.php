<?php
namespace Home\Controller;

/**
 * Class CenterController
 * @package Home\Controller
 * 文档
 */
class ArticleController extends HomeBaseController {

    /**
     * 初始化执行
     * 每个控制器方法执行前 先执行该方法
     */
    protected function _initialize() {
        //执行 父类_initialize()的方法体
        parent::_initialize();
    }

    /**
     * [detail description]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-21T11:30:25+0800
     * @return   [type]                   [description]
     */
    public function detail()
    {
        $this->assign('art', D('FrontC/Article','Logic')->artInfo(I('request.')));
        $this->display('detail');
    }

}