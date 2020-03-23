<?php
namespace Wap\Controller;

/**
 * Class IndexController
 * @package Wap\Controller
 * 首页控制器
 */
class IndexController extends WapBaseController
{

    /**
     * [index description]
     * @Author   黑暗中的武者
     * @DateTime 2019-10-25T13:59:34+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        // banner
        $banners = D('FrontC/Advert', 'Logic')->getAdvert(['position'=>1]);
        //dump($banners);
        $this->assign('banners', $banners);

        // 花絮
        $reviews = D('FrontC/Review', 'Logic')->getList();
        $this->assign('reviews', $reviews);
        // // 明星选手
        // $traineess = D('FrontC/Trainees', 'Logic')->getList();
        // dump($traineess);

        // // 赛区
        // $agencys = D('FrontC/Agency', 'Logic')->getList();
        // dump($agencys);

        // // 海选地点
        // $auditions = D('FrontC/Agency', 'Logic')->getAudition(['agency_id'=>2]);
        // dump($auditions);

        // // 素材
        // $materials = D('FrontC/Material', 'Logic')->getList(['cate'=>1]);
        // dump($materials);

        $this->display('index');
    }


    public function ceshi()
    {
        $request['id_num'] = '123';
        $request['password'] = '123456';

        $result = D('FrontC/Passport', 'Logic')->doLogin($request);

        dump(D('FrontC/Passport', 'Logic')->getLogicInfo());
        dump($result);

        dump($_SESSION);

    }
}
