//上传图片
function upload(fileO, upUrl, savePath, flag) {
    fileO.off("click").trigger("click");
    fileO.one('change', function(){
        // 创建FormData对象
        var data = new FormData();
        // 为FormData对象添加数据
        $.each(fileO[0].files, function(i, file) {
            data.append('file', file);
        });
        // 上传路径
        data.append('save_path', savePath);
        loadingShow(); //显示加载图片
        // 发送数据
        $.ajax({
            url: upUrl,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false, //不可缺
            processData: false, //不可缺
            success:function(data) {
                loadingHide();
                if (data.status == 0) {
                    showPop(data.info, 'error', 1500, '');
                } else {
                    afterUpSuccess(data, flag);
                }
            },
            error:function() {
                showPop('上传出错', 'error', 1500, '');
            }
        });
    })
}

function removeFile(o){
    $(o).parent().remove();
    setFileIds();
}

//重置文件ids
function setFileIds(idO, inputO)
{
    var ids = [];
    idO.each(function(){
        ids.push($(this).attr('file-id'));
    });
    if (ids.length > 0) {
        inputO.val(ids.join(','));
    } else {
        inputO.val('');
    }
}