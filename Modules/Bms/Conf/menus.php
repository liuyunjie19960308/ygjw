<?php
/**
 * 菜单配置列表
 * group  父菜单 title标题名称  icon改组图标 class是否选中 默认为空 url链接地址 check_level验证级别 1控制器+方法
 * child 子菜单 同上
 */
return [
    'MENUS' => [
        [
            'group' => ['title' => '主页', 'icon' => 'fa-home', 'url' => 'Index/index'],
        ],
    [
            'group' => ['title'=>'导航栏管理','icon'=>'fa-user'],
            '_child' => [
                ['title'=>'导航栏列表','url'=>'Menus/lists'],
            ]
        ],
        [
            'group' => ['title'=>'Banner管理','icon'=>'fa-book'],
            '_child' => [
                ['title'=>'Banner列表','url'=>'Banner/lists'],
            ]
        ],
        [
            'group' => ['title'=>'新闻管理','icon'=>'fa-comments'],
            '_child' => [
                ['title'=>'新闻列表','url'=>'Journalism/lists'],
            ]
        ],
        [
            'group' => ['title'=>'声明与政策管理','icon'=>'fa-database'],
            '_child' => [
                ['title'=>'声明管理','url'=>'Policy/statement_info'],
                ['title'=>'政策管理','url'=>'Policy/policy_info'],
            ]
        ],
        [
            'group' => ['title'=>'技术支持','icon'=>'fa-barcode'],
            '_child' => [
                ['title'=>'技术优势','url'=>'Technology/lists'],
                ['title'=>'团队优势','url'=>'Cooperation/lists'],
                ['title'=>'服务保障','url'=>'Cooperation/lists'],
            ],
        ],
         [
             'group' => ['title'=>'合作伙伴','icon'=>'fa-volume-up'],
             '_child' => [
                 ['title'=>'伙伴列表','url'=>'Cooperation/lists'],
                 ],
         ],
        [
            'group' => ['title'=>'关于我们','icon'=>'fa-list-alt'],
            '_child' => [
                ['title'=>'公司介绍','url'=>'Introduce/introduce_index'],
                ['title'=>'我们的使命','url'=>'Mission/mission_index'],
                ['title'=>'我们的精神','url'=>'Spirit/lists'],
                ['title'=>'公司资质','url'=>'Qualifications/lists'],
                ['title'=>'公司历史','url'=>'Hstory/lists'],
                ['title'=>'联系我们','url'=>'EnterpriseContact/lists'],
                ['title'=>'客户提交垂询','url'=>'CustomerInfo/lists'],
            ],
        ],
//        [
//            'group' => ['title'=>'文章管理','icon'=>'fa-book'],
//            '_child' => [
//                ['title'=>'文章列表','url'=>'Article/index'],
//                ['title'=>'文章分类','url'=>'ArticleCategory/index'],
//            ]
//        ],
        // [
        //     'group' => ['title'=>'发信管理','icon'=>'fa-comments'],
        //     '_child' => [
        //         ['title'=>'信息模板','url'=>'SendTemplate/index'],
        //         ['title'=>'发信记录','url'=>'SendLog/index'],
        //     ]
        // ],
        // [
        //     'group' => ['title'=>'数据备份/还原','icon'=>'fa-database'],
        //     '_child' => [
        //         ['title'=>'数据备份','url'=>'Database/export','check_level'=>1],
        //         ['title'=>'数据还原','url'=>'Database/import','check_level'=>1]
        //     ]
        // ],
//        [
//            'group' => ['title'=>'内容管理','icon'=>'fa-list-alt'],
//            '_child' => [
//                ['title'=>'广告管理','url'=>'Advert/index'],
//                //['title'=>'意见反馈','url'=>'Feedback/index'],
//                ['title'=>'明星选手','url'=>'Trainees/index'],
//                ['title'=>'精彩花絮','url'=>'Review/index'],
//                ['title'=>'赛区公示','url'=>'Agency/index'],
//                ['title'=>'海选地点','url'=>'AuditionAddress/index'],
//                ['title'=>'素材介绍','url'=>'Material/index'],
//                //['title'=>'访问记录','url'=>'VisitRecords/index'],
//            ]
//        ],
        [
            'group' => ['title'=>'系统设置','icon'=>'fa-cogs'],
            '_child' => [
                ['title'=>'网站设置','url'=>'ConfigSet/index/config_group/1'],
                ['title'=>'配置管理','url'=>'Config/index'],
            ]
        ],
        [
            'group' => ['title'=>'插件扩展','icon'=>'fa-sitemap'],
            '_child' => [
                ['title'=>'插件管理','url'=>'Plugins/index'],
                ['title'=>'钩子管理','url'=>'Hooks/index']
            ]
        ],
        [
            'group' => ['title'=>'管理员操作','icon'=>'fa-group'],
            '_child' => [
                ['title'=>'管理员信息','url'=>'Administrator/index'],
                //['title'=>'分组权限','url'=>'AuthGroup/index'],
                //['title'=>'行为信息','url'=>'Action/index'],
                //['title'=>'行为日志','url'=>'ActionLog/index'],
            ]
        ],
    ],

    /*菜单映射设置 CURRENT设置当前控制器或者控制器+方法  MAPPING为映射的控制器  键值需一致  及可使访问当前控制器使映射控制器菜单高亮*/
    'CURRENT'   => ['Attribute'],
    'MAPPING'   => ['GoodsType']
];