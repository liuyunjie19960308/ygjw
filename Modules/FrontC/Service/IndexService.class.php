<?php
namespace FrontC\Service;

/**
 * Class IndexService
 * @package FrontC\Service
 * 首页数据服务层
 */
class IndexService extends FrontBaseService {


    /**
     * @param $mall_type
     * @return mixed
     * 导航列表
     * 加入数据缓存
     */
    public function getNavigation($mall_type) {
        //获取缓存中数据
        $list = [];//S('Navigation_Cache');
        //不存在缓存 查找数据库
        if(!$list) {
            //商城类型
            $where = D('FrontC/Where', 'Service')->mall_type($mall_type, 'nav.');
            $where['nav.status'] = 1;

            $list = M('Navigation')->alias('nav')
                ->field('nav.name,nav.target_rule,nav.param,nav.icon,file.abs_url icon_path')
                ->where($where)
                ->join([
                    'LEFT JOIN '.C('DB_PREFIX').'file file ON file.id = nav.icon',
                ])
                ->order('nav.sort ASC')
                ->select();
            //计入缓存
            //S('Navigation_Cache', $list);
        }
        return $list;
    }

    /**
     * @param $mall_type
     * @return mixed
     * 版块列表
     * 加入数据缓存
     */
    public function getSection($mall_type) {
        //获取缓存中数据
        $list = [];//S('Section_Cache');
        //不存在缓存 查找数据库
        if(!$list) {
            //商城类型
            $where = D('FrontC/Where', 'Service')->mall_type($mall_type);
            $where['status'] = 1;

            $list = M('Section')->field('name,layout,configure')->where($where)->order('sort ASC')->select();
            foreach($list as &$value) {
                //解析版块配置json
                $configure = json_decode($value['configure'], true);
                //处理每小版块的图片
                foreach($configure as &$config) {
                    $config['cover_path'] = api('File/getFiles', array($config['cover']))[0]['abs_url'];
                }
                $value['configure'] = $configure;
            }
            //计入缓存
            //S('Section_Cache', $list);
        }
        return $list;
    }

    /**
     * @param int $mall_type
     * @return array
     * 专题列表
     */
    public function specialList($mall_type = 0) {
        //商城类型
        $where = D('FrontC/Where', 'Service')->mall_type($mall_type);
        $where['status']     = 1;
        $where['index_show'] = 1;

        $list = M('Special')->where($where)->field('id spe_id,name,cover')->order('sort ASC,id DESC')->select();
        if(!$list)
            return [];

        foreach($list as $key => $value) {
            //版块封面
            $cover_path = api('File/getFiles', array($value['cover'], array('id', 'abs_url')))[0]['abs_url'];
            $value['cover_path'] = empty($cover_path) ? '' : $cover_path;

            $list[$key] = $value;
        }

        return $list;
    }
}