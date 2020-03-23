<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo ($content_header); ?>----后台管理系统</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--基本-->
        <!--<link rel="stylesheet" href="/Public/Bms/css/bootstrap.1.0.min.css" />-->
        <link rel="stylesheet" href="/Public/Bms/css/bootstrap.2.3.2.css" />
        <link rel="stylesheet" href="/Public/Bms/css/font-awesome-4.7.0/font-awesome.min.css" />
        <link rel="stylesheet" href="/Public/Bms/css/unicorn.main.min.css" />
        <link rel="stylesheet" href="/Public/Bms/css/unicorn.<?php echo C('BACK_STYLE');?>.min.css" class="skin-color" />
        <link rel="stylesheet" href="/Public/Bms/layui/css/layui.css">
        <!--基本-->
        <!--表单表格-->
        <link rel="stylesheet" href="/Public/Bms/css/uniform.min.css" />
        <!--表单表格-->
        <!--扩展样式-->
        <link rel="stylesheet" href="/Public/Bms/css/custom.css" />
        <!--扩展样式-->
        <!--弹出提示框-->
        <link rel="stylesheet" href="/Public/Bms/css/jquery.gritter.min.css" />
        <!--弹出提示框-->
        <!--jquery-->
        <script src="/Public/Bms/layui/layui.js"></script>
        <script src="/Public/Static/jquery.min.js"></script>
        <!--jquery-->
        
        
    </head>
    <body>

        <!--logo-->
        <div id="header">
            <!-- <h1><a href="./dashboard.html"></a></h1> -->
            <h2 style="color:#fff"></h2>
        </div>

        <!--右侧 管理员 退出-->
        <div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav btn-group">
                <li class="btn btn-inverse">
                    <a title="" href="javascript:void(0)">
                        <i class="fa fa-user font-size-13"></i> <span class="text"><?php echo session('admin.account');?></span>
                    </a>
                </li>
                <li class="btn btn-inverse">
                    <a title="" href="<?php echo U('Administrator/rePass');?>">
                        <i class="fa fa-lock font-size-13"></i> <span class="text">修改密码</span>
                    </a>
                </li>
                <li class="btn btn-inverse">
                    <a title="" href="<?php echo U('System/clearCache');?>" class="ajax-get no-refresh">
                        <i class="fa fa-trash font-size-13"></i> <span class="text">清除缓存</span>
                    </a>
                </li>
                <li class="btn btn-inverse">
                    <a title="" href="#help" data-toggle="modal">
                        <i class="fa fa-question font-size-13"></i> <span class="text">帮助</span>
                    </a>
                </li>
                <li class="btn btn-inverse">
                    <a title="" href="<?php echo U('Login/logOut');?>">
                        <i class="fa fa-power-off font-size-13"></i> <span class="text">退出</span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="help" class="modal hide" style="z-index: 99999999">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3>帮助信息</h3>
    </div>
    <div class="modal-body">
        <p>一、双击文本框可清空内容！</p>
        <p>二、列表中头部标题存在“<i class="fa fa-pencil"></i>”的列可双击修改！</p>
        <p>三、带有“*”的表单为必填信息</p>
        <p>四、文章管理中“系统文章”分类中的文章切勿删除</p>
        <p>五、图标展示地址：<a href="http://9iphp.com/fa-icons" target="_blank">http://9iphp.com/fa-icons</a></p>
        <p>六、图片拖动可以排序，排序后不要忘记保存哦！</p>
        <p>七、“系统配置”中的内容不可更改，否则会影响整个网站的访问！</p>
    </div>
</div>
        <!--菜单 start-->
        <div id="sidebar">
            <ul>
                <?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php if(!empty($menu['_child'])): ?>submenu<?php endif; ?> <?php echo ($menu['group']['class']); ?>">
                    <!--菜单组-->
                        <a <?php if(empty($menu['_child'])): ?>href="<?php echo (U($menu['group']['url'])); ?>"<?php endif; ?>>
                            <i class="fa <?php echo ($menu['group']['icon']); ?>"></i>
                            <span><?php echo ($menu['group']['title']); ?></span>
                            <?php if(count($menu['_child']) > 0): ?><span class="label"><?php echo count($menu['_child']);?></span><?php endif; ?>
                        </a>
                    <!--菜单组-->
                    <!--子菜单-->
                        <?php if(!empty($menu['_child'])): ?><ul <?php if(($menu['group']['class']) == "active"): ?>style="display: block"<?php endif; ?>>
                            <?php if(is_array($menu['_child'])): $i = 0; $__LIST__ = $menu['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li class="<?php echo ($child['class']); ?>"><a href="<?php echo (U($child['url'])); ?>" target="<?php echo ((isset($child['target']) && ($child['target'] !== ""))?($child['target']):''); ?>"><?php echo ($child['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!--子菜单-->
                    </li>
                    </notempty><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <!--菜单 end-->

        <div id="style-switcher">
            <i class="fa fa-arrow-left" style="color:#fff;height:25px;vertical-align:middle"></i>
            <span>Style:</span>
            <a href="<?php echo U('Config/quickEdit',array('model'=>'Config','ids'=>54,'field'=>'value','value'=>'grey'));?>" style="background-color: #555555;" class="ajax-get"></a>
            <a href="<?php echo U('Config/quickEdit',array('model'=>'Config','ids'=>54,'field'=>'value','value'=>'blue'));?>" style="background-color: #2D2F57;" class="ajax-get"></a>
            <a href="<?php echo U('Config/quickEdit',array('model'=>'Config','ids'=>54,'field'=>'value','value'=>'red'));?>" style="background-color: #673232;" class="ajax-get"></a>
        </div>

        <!--主体内容-->
        <div id="content">
            <div id="content-header">
                <h1><?php echo ($content_header); ?></h1>
                <div class="btn-group">
                    <!--<a href="<?php echo U('Withdraw/index',array('status'=>0));?>" class="btn btn-large tip-bottom refresh no-handle-withdraw" title="未处理提现">
                        <i class="fa fa-credit-card"></i>
                    </a>
                    <a class="btn btn-large tip-bottom refresh no-read-message" title="消息">
                        <i class="fa fa-envelope-square"></i>
                    </a>-->
                    <a class="btn btn-large tip-bottom refresh" title="刷新"><i class="fa fa-refresh"></i></a>
                </div>
            </div>

            <!--导航-->
            <div id="breadcrumb">
                <a href="<?php echo U('Index/index');?>" title="" class=""><i class="fa fa-home font-size-14"></i> 首页</a>
                <a href="javascript:void(0)" class=""><?php echo ($content_header); ?></a>
                <a href="javascript:void(0)" class=""><?php echo ($breadcrumb_nav); ?></a>
            </div>

            <div class="alert top-alert" style="display:none;position:fixed;z-index:10000000;width:100%;padding: 11px 35px 11px 14px;top:112px;border-radius: 0px;">
	<button class="close" data-dismiss="alert"></button>
	<strong></strong>
</div>
<!--<div class="alert alert-success" style="display: none">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Success!</strong>
</div>
<div class="alert alert-info" style="display: none">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Info!</strong>
</div>
<div class="alert alert-error" style="display: none">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error!</strong>
</div>-->

            <div class="container-fluid">

                

    <div class="row-fluid main-row-fluid">

        <div class="span12">
            <div class="widget-box">

                <div class="widget-title widget-title-search list-widget-title">
                    <button class="btn btn-success href" url="<?php echo U('Qualifications/Qualifications_add');?>" target-form="ids">新增</button>&nbsp;
                    <!--<button class="btn btn-inverse ajax-post" url="<?php echo U('Qualifications/statusAll',array('value'=>2));?>" target-form="ids">禁用</button>&nbsp;-->
                    <!--<button class="btn btn-info ajax-post" url="<?php echo U('Qualifications/statusAll',array('value'=>1));?>" target-form="ids">启用</button>&nbsp;-->
                    <button class="btn btn-danger confirm ajax-post" url="<?php echo U('Qualifications/deleteAll');?>" target-form="ids">删除</button>&nbsp;
                    <!--<button type="button" class="btn btn-primary move">移动</button>-->
                    <span style="display: none">
                    <?php echo (replace($select,'target-form','ids')); ?><button class="btn btn-primary ajax-post"  target-form="ids">确定</button>
                    </span>
                    <!--<form action="<?php echo U('Article/index');?>" method="post" class="form">-->
                        <!--<input type="text" name="title" value="<?php echo ($_REQUEST['title']); ?>" placeholder="文章标题">-->
                        <!--<?php echo ($select); ?>-->
                        <!--<button class="btn btn-inverse">查询</button>-->
                    <!--</form>-->
                </div>

                <div class="widget-content no-padding no-border">
                    <table class="table table-bordered table-striped with-check reset-checkbox">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="title-checkbox" name="title-checkbox" class="check-all"/><!--<i class="icon-resize-vertical"></i>--></th>
                            <th>ID</th>
                            <th>资质证书介绍</th>
                            <th><i class="fa fa-pencil"></i>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="tbody">
                        <?php if(empty($list)): ?><tr><td colspan="30">Aho！没有查询结果！！</td></tr><?php endif; ?>
                        <?php foreach($list as $v):?>
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $v['id'];?>" class="ids"/></td>
                            <td align="center">
                                <?php echo $v['id'];?>                </td>

                            <td align="left">
                                <?php echo $v['title'];?>                </td>
                                <td align="center">
                                    <?php echo $v['sort'];?>                </td>
                                <td>
                                    <a href="<?php echo U('Qualifications/desc',array('id'=>$v['id']));?>" title="编辑" class="tip-bottom">
                                        <span class="label label-success label-success-hover">编辑</span></a>&nbsp;
                                    <!--<?php if($v['status'] == 2 or $v['status'] == 1): ?>-->
                                        <!--<a href="<?php echo U('Qualifications/statusOne',array('value'=>$v['status'],'id'=>$v['id']));?>" title="<?php echo (show_status_name($v['status'])); ?>" class="tip-bottom ajax-get">-->
                                            <!--<span class="label <?php echo (show_status_label($v['status'])); ?>"><?php echo (show_status_name($v['status'])); ?></span></a>&nbsp;-->
                                    <!--<?php endif; ?>-->

                                    <a href="<?php echo U('Qualifications/deleteOne',array('ids'=>$v['id']));?>" data-ing-html="正在删除 <i class='fa fa-spinner fa-spin'></i>" title="删除" class="tip-bottom confirm ajax-get">
                                        <span class="label label-important label-important-hover">删除</span></a>
                                </td>
                            </tr>
                        <?php endforeach;?>

                        </tbody>
                    </table>

                </div>

                <div class="pagination alternate">
                    <?php echo ($page); ?>
                </div>

            </div>
        </div>
    </div>


                <div class="row-fluid">
                    <div id="footer" class="span12">
                        Copyright  © 2017 <a href="" target="_blank"></a>
                    </div>
                </div>
            </div>
        </div>

        <!--基本-->
        <script src="/Public/Bms/js/bootstrap.min.js"></script>
        <script src="/Public/Bms/js/unicorn.min.js"></script>
        <!--基本-->
        <!--表单加载-->
        <script src="/Public/Bms/js/jquery.uniform.min.js"></script>
        <!--表单加载-->
        <script src="/Public/Bms/js/common.js"></script>
        <script src="/Public/Bms/js/js.js"></script>
        <!--弹出提示框-->
        <script src="/Public/Bms/js/jquery.gritter.min.js"></script>
        <script src="/Public/Bms/js/jquery.peity.min.js"></script>
        <!--弹出提示框-->
        <script>
//            $.gritter.add({
//                image: 'fa fa-info-circle', sticky: false, title: '温馨提醒',time: 5000, speed: 500, position: 'top-right', class_name: 'gritter-success',
//                text: '您有未处理用户提现：'+data.no_handle_withdraw+''
//            });
//            $(document).ready(function(){
//                getTip();
//            });
//            setInterval(function(){
//                getTip();
//            },30000);
            function getTip() {
                $.get('<?php echo U("System/tip");?>').success(function(data){
//                    if(data.no_handle_withdraw != 0) {
//                        $('a.no-handle-withdraw').append('<span class="label label-important no-read-message">'+data.no_handle_withdraw+'</span>');
//                    }
                    if(data.data.not_delivery_order != 0) {
                        $.gritter.add({
                            image: 'fa fa-info-circle', sticky: false, title: '温馨提醒',time: 5000, speed: 500, position: 'top-right', class_name: 'gritter-success',
                            text: '您有未发货的订单：'+data.data.not_delivery_order+'单'
                        });
                    }

                     if(data.data.not_assign_order != 0) {
                        $.gritter.add({
                            image: 'fa fa-info-circle', sticky: false, title: '温馨提醒',time: 5000, speed: 500, position: 'top-right', class_name: 'gritter-success',
                            text: '您有未分配的维修订单：'+data.data.not_assign_order+'单'
                        });
                    }
                });
            }
        </script>
        
    <script>
        $(function(){
            $('button.move').click(function(){
                if($(this).next('span').css('display') == 'none') {
                    $(this).next('span').show();
                } else {
                    $(this).next('span').hide();
                }
            })
        })
    </script>

    </body>
</html>