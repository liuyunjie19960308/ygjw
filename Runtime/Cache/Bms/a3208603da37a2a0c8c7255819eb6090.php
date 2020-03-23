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
                <!--<div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h5>更新广告信息</h5>
                </div>-->
                <br>
                <div class="widget-content no-padding">
                    <form class="form-horizontal text-height-27-form" method="post" action="<?php echo U('Advert/update');?>">
                        <input type="hidden" name="model" value="Advert">
                        <input type="hidden" name="id" value="<?php echo ($row['id']); ?>">
                        <div class="control-group">
                            <label class="control-label">广告位置</label>
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn checked" data-default="--广告位置--"></button>
                                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if(is_array($position)): $i = 0; $__LIST__ = $position;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$po): $mod = ($i % 2 );++$i;?><li data-value="<?php echo ($key); ?>" data-title="<?php echo ($po); ?>" <?php if(($key) == $row['position']): ?>class="selected"<?php endif; ?>>
                                            <a href="javascript:void(0)"><?php echo ($po); ?></a>
                                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </ul>
                                </div>
                                <input type="hidden" name="position" value="<?php echo ($row['position']); ?>">
                                <span class="help-block">* 广告展示位置</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">广告投放时间</label>
                            <div class="controls">
                                <div class="input-daterange" id="datepicker">
                                    <?php if(!empty($row['id'])): ?><input type="text" name="start_time" value="<?php echo (date('Y-m-d',$row['start_time'])); ?>" class="text-width-10" readonly>
                                        --
                                        <input type="text" name="end_time" value="<?php echo (date('Y-m-d',$row['end_time'])); ?>" class="text-width-10" readonly>
                                        <?php else: ?>
                                        <input type="text" name="start_time" value="" class="text-width-10" readonly>
                                        --
                                        <input type="text" name="end_time" value="" class="text-width-10" readonly><?php endif; ?>
                                </div>
                                <span class="help-block">* 广告开始-结束展示时间</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">广告图片</label>
                            <div class="controls">
                                <?php echo hook('upload',array('is_first'=>1,'unique_sign'=>'picture','field_name'=>'picture','field_value'=>$row['picture'],'save_path'=>'Advert'));?>
                                <span class="help-block">* 广告图片，banner尺寸：954px X 497px</span>
                            </div>
                        </div>
                                            <div class="control-group">
                            <label class="control-label">文章内容</label>
                            <div class="controls">
                                <textarea name="content"><?php echo ($row['content']); ?></textarea>
                                <?php echo hook('adminArticleEdit', array('is_first'=>1,'name'=>'content','value'=>$row['content'],'width'=>910,'height'=>500));?>
                                <span class="help-block">* 文章详细内容</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">跳转规则</label>
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn checked" data-default="--跳转规则--"></button>
                                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if(is_array($target_rules)): $i = 0; $__LIST__ = $target_rules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rule): $mod = ($i % 2 );++$i;?><li data-value="<?php echo ($key); ?>" data-title="<?php echo ($rule); ?>" <?php if(($key) == $row['target_rule']): ?>class="selected"<?php endif; ?>>
                                            <a href="javascript:void(0)"><?php echo ($rule); ?></a>
                                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </ul>
                                </div>　
                                <input type="hidden" name="target_rule" value="<?php echo ($row['target_rule']); ?>">
                                <input type="text" name="param" value="<?php echo ($row['param']); ?>" class="text-width-30" placeholder="跳转规则对应的参数">
                                <span class="help-block">
                                    跳转规则，前端根据此规则跳转不同的页面。不设置则仅展示！<br>
后接跳转规则对应的参数<br>
对应关系：<br>
　　1：网址--带有　http://　或者　https://　的网址链接<br>
　　2：文章详情--文章的ID<br>
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">描述</label>
                            <div class="controls">
                                <input type="text" name="description" value="<?php echo ($row['description']); ?>" class="text-width-40">
                                <span class="help-block">该广告的简单描述</span>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn" onclick="javascript:history.back(-1);return false;">返 回</button>　
                            <button class="btn btn-info ajax-post" target-form="form-horizontal" type="submit">保 存</button>
                        </div>
                    </form>
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

    <!--日历 start-->
    <link href="/Public/Static/plugins/datepicker/datepicker3.css" rel="stylesheet">
    <script src="/Public/Static/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!--日历 end-->


        <script>
//一般直接写在一个js文件中
layui.use(['layer', 'form'], function(){
  var layer = layui.layer
  ,form = layui.form;
  
  layer.msg('Hello World');
});
</script> 

</body>
</html>