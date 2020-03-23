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
                        <i class="icon-plus"></i>
                    </span>
                    <h5></h5>
                </div>-->
                <ul class="nav nav-tabs">
                    <li <?php if(empty($_REQUEST['flag'])): ?>class="active"<?php endif; ?>><a data-toggle="tab" href="#tab1">文章信息</a></li>
                    <?php if(!empty($row['id'])): ?><!--<li <?php if($_REQUEST['flag'] == 'gl'): ?>class="active"<?php endif; ?>><a data-toggle="tab" href="#tab2">关联商品</a></li>--><?php endif; ?>
                </ul>
                <!--<div class="widget-title"></div>-->
                <br>
                <div class="widget-content tab-content no-padding">
                    <div id="tab1" <?php if(empty($_REQUEST['flag'])): ?>class="tab-pane active"<?php else: ?>class="tab-pane"<?php endif; ?>>
                    <form class="form-horizontal text-height-27-form" method="post" action="<?php echo U('Article/update');?>" id="form">
                        <input type="hidden" name="model" value="Article">
                        <input type="hidden" name="id" value="<?php echo ($row['id']); ?>">
                        <div class="control-group">
                            <label class="control-label">文章标识</label>
                            <div class="controls">
                                <input type="text" name="unique_code" value="<?php echo ($row['unique_code']); ?>" class="text-width-40" maxlength="30">
                                <span class="help-block">可选 30个字符以内，针对一些规定位置的文章</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">文章标题</label>
                            <div class="controls">
                                <input type="text" name="title" value="<?php echo ($row['title']); ?>" class="text-width-40" maxlength="60">
                                <span class="help-block">* 60个字符以内</span>
                            </div>
                        </div>
                        <!--<div class="control-group">
                            <label class="control-label">文章简述</label>
                            <div class="controls">
                                <input type="text" name="short_desc" value="<?php echo ($row['short_desc']); ?>" class="text-width-40" maxlength="30">
                                <span class="help-block">30个字符以内</span>
                            </div>
                        </div>-->
                        <div class="control-group">
                            <label class="control-label">文章分类</label>
                            <div class="controls">
                                <?php echo ($select); ?>
                                <span class="help-block">* 文章所属分类</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">封面图</label>
                            <div class="controls">
                                <?php echo hook('upload',array('is_first'=>1,'unique_sign'=>'cover','field_name'=>'cover','field_value'=>$row['cover'],'save_path'=>'Article'));?>
                                <span class="help-block">文章封面图</span>
                            </div>
                        </div>
                        <!--<div class="control-group">
                            <label class="control-label">轮播图</label>
                            <div class="controls">
                                
                                <span class="help-block">文章轮播图</span>
                            </div>
                        </div>-->
                        <div class="control-group">
                            <label class="control-label">文章内容</label>
                            <div class="controls">
                                <textarea name="content"><?php echo ($row['content']); ?></textarea>
                                <?php echo hook('adminArticleEdit', array('is_first'=>1,'name'=>'content','value'=>$row['content'],'width'=>910,'height'=>500));?>
                                <span class="help-block">* 文章详细内容</span>
                            </div>
                        </div>
                        <!--<div class="control-group">
                            <label class="control-label">外链接</label>
                            <div class="controls">
                                <input type="text" name="link" value="<?php echo ($row['link']); ?>" class="text-width-50">
                                <span class="help-block">带有http://的全地址</span>
                            </div>
                        </div>-->
                        <!--<div class="control-group">
                            <label class="control-label">是否推荐</label>
                            <div class="controls">
                                <label style="margin-right:20px;display:inline">
                                    <div class="radio"><span><input name="is_best" style="opacity: 0;" type="radio" value="1" <?php if($row['is_best'] == 1): ?>checked<?php endif; ?>></span></div> 是
                                </label>
                                <label style="margin-right:20px;display:inline">
                                    <div class="radio"><span><input name="is_best" style="opacity: 0;" type="radio" value="0" <?php if(empty($row['is_best'])): ?>checked<?php endif; ?>></span></div> 否
                                </label>
                                <span class="help-block">设置为是 则出现在首页</span>
                            </div>
                        </div>-->
                        <!-- <div class="control-group">
                            <label class="control-label">排序</label>
                            <div class="controls">
                                <input type="text" name="sort" value="<?php echo ($row['sort']); ?>" class="number-only text-width-10">
                                <span class="help-block">排序</span>
                            </div>
                        </div> -->
                        <!--<div class="control-group">
                            <label class="control-label">浏览量</label>
                            <div class="controls">
                                <input type="text" name="view" value="<?php echo ($row['view']); ?>" class="number-only text-width-10">
                                <span class="help-block">可修改文章浏览量</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">收藏次数</label>
                            <div class="controls">
                                <input type="text" name="collections" value="<?php echo ($row['collections']); ?>" class="number-only text-width-10">
                                <span class="help-block">可修改文章收藏次数</span>
                            </div>
                        </div>-->
                        <div class="form-actions">
                            <button class="btn" onclick="javascript:history.back(-1);return false;">返 回</button>　
                            <button class="btn btn-info ajax-post no-refresh" type="submit" id="submit" target-form="form-horizontal">保 存</button>
                        </div>
                    </form>
                    </div>

                    <div id="tab2" <?php if($_REQUEST['flag'] == 'gl'): ?>class="tab-pane active"<?php else: ?>class="tab-pane"<?php endif; ?>>
                        <form action="<?php echo U('Article/saveRG', array('art_id'=>$row['id']));?>" method="post" autocomplete="off">
                            <button href="#modal-goods" class="btn btn-success" data-toggle="modal" title="选择商品" class="" onclick="setSrc(this);" data-id="<?php echo ($row['id']); ?>" data-type="1">选择商品</button>
                            <input type="hidden" name="relation_goods" class="values" value="<?php echo ($row['relation_goods']); ?>">
                            　<button type="submit" class="btn btn-info">保存</button>
                        </form>
                        <br><br>

                        <div class="element-box">
                            <?php if(is_array($row['goods_list'])): $i = 0; $__LIST__ = $row['goods_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?><div class="element-item" data-val="<?php echo ($goods['id']); ?>">
                                    <i class="fa fa-remove" onclick="removeElement(this,1)"></i>
                                    <img src="<?php echo ($goods['abs_url']); ?>" class="" style="width:200px;height: 200px;"/>
                                    <p><?php echo (msubstr($goods['goods_name'],0,30)); ?></p>
                                    <p>￥ <?php echo ($goods['price']); ?> 元</p>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <script>
                            //重置ids
                            function setFileIds(obj,parent) {
                                var ids = [];
                                $(".element-box").find('.element-item').each(function(){
                                    ids.push($(this).attr('data-val'));
                                });
                                if(ids.length > 0)
                                    $(".values").val(ids.join(','));
                                else
                                    $(".values").val('');
                            }
                            function removeElement(obj, parent) {
                                $(obj).parent().remove();
                                setFileIds(obj, parent);
                            }
                        </script>
                    </div>
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

</body>
</html>