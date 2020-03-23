<?php if (!defined('THINK_PATH')) exit();?>
<!--避免重复加载-->
<?php if($plugins_param['is_first'] == 1): ?><script type="text/javascript" src="/Public/Static/plupload-3.1.2/js/plupload.full.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="/Public/Static/uploadify/uploadify.css" media="all"> -->
    <script type="text/javascript" src="/Public/Static/jquery.dragsort-0.5.1.min.js"></script><?php endif; ?>
<div id="up-container<?php echo ($plugins_param['unique_sign']); ?>">
    <input type="file" name="upload_file_<?php echo ($plugins_param['unique_sign']); ?>" id="upload_file_<?php echo ($plugins_param['unique_sign']); ?>">
    <input type="hidden" name="<?php echo ($plugins_param['field_name']); ?>" id="upload_file_input_<?php echo ($plugins_param['unique_sign']); ?>" value=""/>

    <div class="upload-file-box upload-file-box-<?php echo ($plugins_param['unique_sign']); ?> img-enlarge-box">
        <?php $files = $plugins_param['field_value']; ?>
        <!--判断是传文件还是图片-->
        <?php if(!empty($files)): if(empty($plugins_param['type']) or ($plugins_param['type'] == 'picture')): if(is_array($files)): foreach($files as $key=>$file): ?><div class="upload-pre-item" val="<?php echo ($file['id']); ?>">
                        <img src="<?php echo ($file['path']); ?>" class="img-enlarge"/>
                        <i class="fa fa-remove" onclick="removeFile<?php echo ($plugins_param['unique_sign']); ?>(this)"></i>
                    </div><?php endforeach; endif; ?>
            <?php else: ?>
                <?php if(is_array($files)): foreach($files as $key=>$file): ?><div class="upload-pre-item" val="<?php echo ($file['id']); ?>">
                        <a href="<?php echo U('UpDownLoad/download',array('id'=>$file['id']));?>"><?php echo ($file['name']); ?></a>
                        <i class="fa fa-remove" onclick="removeFile<?php echo ($plugins_param['unique_sign']); ?>(this)"></i>
                    </div><?php endforeach; endif; endif; endif; ?>
        <img src="/Public/Static/img/loading.gif" style="width: 100px;height: 100px;display: none" class="loading">
    </div>
    <div class="clear"></div>
</div>
<div id="filelist"></div>



<script type="text/javascript">
// Custom example logic
 var queue_limit_<?php echo ($plugins_param['unique_sign']); ?> = '<?php echo ($plugins_param["queue_limit"]); ?>',
            type_<?php echo ($plugins_param['unique_sign']); ?> = '<?php echo ($plugins_param["type"]); ?>',
            file_type_exts<?php echo ($plugins_param['unique_sign']); ?> = '<?php echo ($plugins_param["exts"]); ?>';
    queue_limit_<?php echo ($plugins_param['unique_sign']); ?> == '' ? queue_limit_<?php echo ($plugins_param['unique_sign']); ?> = 1 : '';
    file_type_exts<?php echo ($plugins_param['unique_sign']); ?> != '' ? file_type_exts<?php echo ($plugins_param['unique_sign']); ?>='*.'+file_type_exts<?php echo ($plugins_param['unique_sign']); ?> : '';
    file_type_exts<?php echo ($plugins_param['unique_sign']); ?> == '' && '<?php echo ($plugins_param["type"]); ?>'=='attachment' ? file_type_exts<?php echo ($plugins_param['unique_sign']); ?> = '*.zip;*.rar;*.tar;*.gz;*.7z;*.doc;*.docx;*.txt;*.xml;*.xls' : '';
    file_type_exts<?php echo ($plugins_param['unique_sign']); ?> == '' && ('<?php echo ($plugins_param["type"]); ?>'=='picture'||'<?php echo ($plugins_param["type"]); ?>'=='') ? file_type_exts<?php echo ($plugins_param['unique_sign']); ?> = "*.jpg;*.gif;*.png;*.jpeg" : '';
    
//alert(queue_limit_<?php echo ($plugins_param['unique_sign']); ?>);
var uploader<?php echo ($plugins_param['unique_sign']); ?> = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : "upload_file_<?php echo ($plugins_param['unique_sign']); ?>",
    container: document.getElementById('up-container<?php echo ($plugins_param['unique_sign']); ?>'),
    url : "<?php echo U('UpDownLoad/upload',array('session_id'=>session_id(),'type'=>$plugins_param['type'],'save_path'=>$plugins_param['save_path'],'exts'=>$plugins_param['exts'],'max_size'=>$plugins_param['max_size'],'is_water'=>$plugins_param['is_water'],'is_thumb'=>$plugins_param['is_thumb'],'thumb_width'=>$plugins_param['thumb_width'],'thumb_height'=>$plugins_param['thumb_height'],'thumb_prefix'=>$plugins_param['thumb_prefix'],'thumb_suffix'=>$plugins_param['thumb_suffix'],'is_oss'=>$plugins_param['is_oss']));?>",
    flash_swf_url : '../js/Moxie.swf',
    silverlight_xap_url : '../js/Moxie.xap',
    file_data_name : 'fileData',
    multi_selection: queue_limit_<?php echo ($plugins_param['unique_sign']); ?> > 1 ? true : false, //是否可以多选
    filters : {
        max_file_size : '5mb',
        mime_types: [
            {title : "Image files", extensions : "jpeg,jpg,gif,png,xls,xlsx"},
            {title : "Zip files", extensions : "zip"}
        ]
    },

    init: {
        PostInit: function() {
            document.getElementById('filelist').innerHTML = '';

            // document.getElementById('uploadfiles').onclick = function() {
            //     uploader.start();
            //     return false;
            // };
        },

        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
            });
            uploader<?php echo ($plugins_param['unique_sign']); ?>.start();
            $('.loading').show();
            return false;
        },

        UploadProgress: function(up, file) {
            //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },

        Error: function(up, err) {
            //document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            updateAlertWithTime("\nError #" + err.code + ": " + err.message,"alert-error",1500);
        },

        FileUploaded: function(uploader,file,responseObject) {
            //alert(responseObject.response);
            var data = JSON.parse(responseObject.response);
            var src = '';
            if(data.status == 1){
                $('.loading').hide();
                //判断是图片还是文件 显示方式不同
                var html = '<div class="upload-pre-item" val="' + data.id + '">';
                if (type_<?php echo ($plugins_param['unique_sign']); ?> == '' || type_<?php echo ($plugins_param['unique_sign']); ?> == 'picture') {
                    src = data.url || '' + data.path;
                    html += '<img src="' + src + '" class="img-enlarge"/>';
                } else {
                    html += '<a href="'+data.download_url+'">'+data.name+'</a>';
                }
                html += '<i class="fa fa-remove" onclick="removeFile<?php echo ($plugins_param['unique_sign']); ?>(this)"></i></div>';

                if(queue_limit_<?php echo ($plugins_param['unique_sign']); ?> == 1) {
                    //单个文件
                    $("#upload_file_<?php echo ($plugins_param['unique_sign']); ?>").parent().parent().find('.upload-file-box').html(html);
                } else {
                    //多个文件
                    $("#upload_file_<?php echo ($plugins_param['unique_sign']); ?>").parent().parent().find('.upload-file-box').append(html);
                }
                setFileIds<?php echo ($plugins_param['unique_sign']); ?>();

                document.getElementById('filelist').innerHTML = '';

            } else {
                updateAlert(data.info,'alert-error');
                setTimeout(function(){
                    $('.alert').hide();
                },2500);
            }
        }
    }
});

uploader<?php echo ($plugins_param['unique_sign']); ?>.init();

    //删除文件
    function removeFile<?php echo ($plugins_param['unique_sign']); ?>(o){
        var p = $(o).parent().parent();
        $(o).parent().remove();
        setFileIds<?php echo ($plugins_param['unique_sign']); ?>();
    }
    //重置ids
    function setFileIds<?php echo ($plugins_param['unique_sign']); ?>(){
        var ids = [];
        $("#upload_file_<?php echo ($plugins_param['unique_sign']); ?>").parent().parent().find('.upload-file-box').find('.upload-pre-item').each(function(){
            ids.push($(this).attr('val'));
        });
        if(ids.length > 0)
            $("#upload_file_input_<?php echo ($plugins_param['unique_sign']); ?>").val(ids.join(','));
        else
            $("#upload_file_input_<?php echo ($plugins_param['unique_sign']); ?>").val('');
    }
    setFileIds<?php echo ($plugins_param['unique_sign']); ?>();

    // $(".upload-file-box-<?php echo ($plugins_param['unique_sign']); ?>").dragsort({
    //     dragSelector:'div',
    //     placeHolderTemplate: '<div class="upload-pre-item">&nbsp;</div>',
    //     dragEnd:function(){setFileIds<?php echo ($plugins_param['unique_sign']); ?>();}
    // });

</script>