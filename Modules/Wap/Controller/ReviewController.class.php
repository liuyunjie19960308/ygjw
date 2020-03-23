<?php
namespace Home\Controller;

/**
 * 花絮
 */
class ReviewController extends HomeBaseController
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
        $adverts = D('FrontC/Advert', 'Logic')->getAdvert(['position'=>1]);
        dump($adverts);

        // 花絮
        $reviews = D('FrontC/Review', 'Logic')->getList();
        dump($reviews);

        // 明星选手
        $traineess = D('FrontC/Trainees', 'Logic')->getList();
        dump($traineess);

        // 赛区
        $agencys = D('FrontC/Agency', 'Logic')->getList();
        dump($agencys);

        // 海选地点
        $auditions = D('FrontC/Agency', 'Logic')->getAudition(['agency_id'=>2]);
        dump($auditions);

        // 素材
        $materials = D('FrontC/Material', 'Logic')->getList(['cate'=>1]);
        dump($materials);

        $this->display('index');
    }


    /**
     * [detail 花絮详情]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:29:44+0800
     * @return   [type]                   [description]
     */
    public function detail()
    {
        $review = D('FrontC/Review', 'Logic')->getDetail(I('request.'));
        $this->assign('review', $review);
        $this->display('detail');
    }
}
