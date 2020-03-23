$(function(){

            //全选函数
            $('.checkbox-all').click(function(){
                if($(this).prop('checked')){
                    $(this).parent().next().find('.checkboxs').prop('checked',true);
                }else{
                    $(this).parent().next().find('.checkboxs').prop('checked',false);
                }
            })

            //单个checkbox与全选的关系函数
            $('.select-content').on('click','.checkboxs',function(e){

                var checkedAll = $(this).parents('.select-content').prev().find('.checkbox-all');
                var checkboxs = $(this).prop('checked');
                if(!checkboxs&&checkedAll.prop('checked')){
                    checkedAll.prop('checked',false);
                }else if(checkboxs&&!checkedAll.prop('checked')){
                    var lis = $(this).parents('ul').children();
                    for(var i=0;i<lis.length;i++){
                        if($(lis[i]).find('.checkboxs').prop('checked')){
                            if(i==lis.length-1){
                                checkedAll.prop('checked',true)
                            }
                        }else{
                            break;
                        }
                    }
                }
                stopFunc(e);
            });
            $('.select-content').on('click','li',function(){
                $(this).children('.checkboxs').click();
            })

            //左右移按钮点击事件
            $('.arrow-btn').click(function(){
                var checkboxs,origin,target,num=0;
                if($(this).hasClass('right')){
                    origin = $('.unselect-ul');
                    target = $('.selected-ul');
                }else{
                    origin = $('.selected-ul');
                    target = $('.unselect-ul');
                }
                checkboxs = origin.find('.checkboxs');
                for(var i=0;i<checkboxs.length;i++){
                    if($(checkboxs[i]).prop('checked')){
                        var that = $(checkboxs[i]).parent().clone();
                        that.children('input').prop('checked',false);
                        target.append(that);
                        $(checkboxs[i]).parent().remove();
                    }else{
                        num++;
                    }
                }
                if(checkboxs.length == num){
                    alert('未选中任何告警规则！');
                }else{
                    origin.parent().prev().find('.checkbox-all').prop('checked',false);
                }
            })

            $('.unselect-ul li').click(function(){
                var checkboxs, origin, target, num = 0;
                if($(this).hasClass('right')){
                    origin = $('.unselect-ul');
                    target = $('.selected-ul');
                }else{
                    origin = $('.selected-ul');
                    target = $('.unselect-ul');
                }
                checkboxs = origin.find('.checkboxs');
                for(var i=0;i<checkboxs.length;i++){
                    if($(checkboxs[i]).prop('checked')){
                        var that = $(checkboxs[i]).parent().clone();
                        that.children('input').prop('checked',false);
                        target.append(that);
                        $(checkboxs[i]).parent().remove();
                    }else{
                        num++;
                    }
                }
                if(checkboxs.length == num){
                    alert('未选中任何告警规则！');
                }else{
                    origin.parent().prev().find('.checkbox-all').prop('checked',false);
                }
            });

        })


        function stopFunc(e){
            e.stopPropagation?e.stopPropagation():e.cancelBubble=true;
        }

function arrow(obj)
{
    var lis, origin, target, num = 0;
    if($(obj).hasClass('right')){
        //origin = $('.unselect-ul');
        
        target = $('.unselect-ul');
    }else{
        //origin = $('.selected-ul');
        target = $('.selected-ul');
    }









    lis = origin.find('li');
    for(var i=0;i<lis.length;i++){
        if($(lis[i]).prop('checked')){
            var that = $(lis[i]).parent().clone();
            that.children('input').prop('checked',false);
            target.append(that);
            $(lis[i]).parent().remove();
        }else{
            num++;
        }
    }
    if(lis.length == num){
        alert('未选中任何告警规则！');
    }else{
        origin.parent().prev().find('.checkbox-all').prop('checked',false);
    }
}