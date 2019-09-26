/**
 * Created by wen on 2018/1/9.
 * 倒计时提醒让按钮暂时不可用
 * t 等待的时间，秒
 * obj 按钮对象，用于修改按钮状态
 * name 可以空，操作的名字，默认为 操作
 * no 第几个此类操作按钮？
 */
function jishi(t,obj,name,no){
    var name = arguments[2] ? arguments[2] : '操作';
    var no =  arguments[3] ? arguments[3] : 1;
    if(t == 0){
        $("#jishi_info"+no).remove();
        $(obj).css('display','inline');
    }else{
        $(obj).css('display','none');
        if ($("#jishi_info"+no).length==0){
            $(obj).after('<button id="jishi_info'+no+'" class="'+$(obj).attr('class')+'">请 ' + t + '秒后再' + name + '</button>');
        }else {
            $("#jishi_info"+no).html('请 ' + t + '秒后再' + name);
        }
        t--;
        setTimeout(function(){jishi(t,obj,name,no);},1000);
    }
}