var isLoading = false, // 是否加载中的标识，如果加载中true 则不会重复请求接口
    query     = {},    // 请求的参数
    p         = 1,     // 页码
    append    = true;  // 内容append标识，true就是添加 false就是替换

$(function() {
    getList();
});

$(document).scroll(function() {
    var scroH    = $(document).scrollTop();  //滚动高度
    var viewH    = $(window).height();       //可见高度 
    var contentH = $(document).height();     //内容高度

    if (contentH - (scroH + viewH) <= 100) {  //距离底部高度小于100px
         if (scroll == true) {
            append = true;
            getList();
         }
    }
});
function getList() {
    // 加载中为否的情况 可进行请求
    if (isLoading === false) {
        // 置为加载中 控制重复请求
        isLoading = true;
        // 显示加载中效果
        loadingShow();
        // 获取请求参数
        query = getQuery();
        // ajax请求
        $.post(target, query).success(function(data) {
            // 错误处理
            if(data.status == 0) {
                if(p == 1) {
                    $('.scroll-append-box').append('<div style="padding: 40px;text-align: center">暂无结果！</div>');
                }
            } else {
                // 如果数据长度为0 处理
                if (p > 1 && data.data.length == 0) {
                    // 没有更多数据的处理，每个页面不同 放到每个页面中自己写
                    noMore(); //loadingHide(); return;
                }
                // 页号增加
                p++;
                // 标签
                var html = '';
                // 循环数据赋值
                for(var i in data.data) {
                    html += getHtml(data.data[i]);
                }
                // html插入 / 替换
                if (append == true) {
                    $('.scroll-append-box').append(html);
                } else {
                    $('.scroll-append-box').html(html);
                }
                
                // 加载中置否
                isLoading = false;
            }
            // 隐藏加载中效果
            loadingHide();
        });
    }
}