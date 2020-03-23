<?php
namespace FrontC\Core\Analysis;

/**
 * 访问分析
 */
class Visit
{
    /**
     * [pageInfo 获取页面信息]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T17:45:06+0800
     * @param    string                   $page_url  [description]
     * @param    boolean                  $is_router [description]
     * @return   [type]                              [description]
     */
    public function pageInfo($page_url = '', $is_router = false)
    {
        // 判空
        if (empty($page_url)) {
            ['page_title' => '', 'page_code' => 0];
        }

        // 解析地址  键值 1--控制器  2--方法
        $break = explode('/', $page_url);
        
        list($a, $controller, $action) = $break;
        $controller = strtolower($controller);
        $action     = strtolower($action);

        // 首页
        if (empty($controller) || $controller == '#1mao') {
            $page_title = '首页';
            $page_code  = 10001;
        } else {
            $page_title = ($this->rule[$controller]['title']?:'') . ($this->rule[$controller]['pages'][$action]['title']?:'');
            $page_code  = $this->rule[$controller]['pages'][$action]['code']?:0;
        }

        return ['page_title' => $page_title, 'page_code' => $page_code];
    }

    /**
     * [$rule description]
     * @var [type]
     */
    protected $rule = [
        'index' => [
            'title' => '首页',
            'pages' => [
                'index' => [
                    'code'  => 10001,
                    'title' => '主页',
                ],
            ]
        ],
    ];
}
