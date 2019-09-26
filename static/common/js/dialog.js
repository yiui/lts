var dialog = {
    // 错误弹出层
    error: function(message) {
        layer.open({
            content:message,
            icon:2,
            title : '错误提示',
        });
    },

    //成功弹出层
    success : function(message,url) {
        layer.open({
            content : message,
            icon : 1,
            yes : function(){
                location.href=url;
            },
        });
    },

    // 确认弹出层
    confirm : function(message, url) {G_ALERT;
        layer.open({
            content : message,
            icon:3,
            btn : ['是','否'],
            yes : function(){
                location.href=url;
            },
        });
    },

    //无需跳转到指定页面的确认弹出层
    toconfirm : function(message) {
        layer.open({
            content : message,
            icon:3,
            btn : ['确定'],
        });
    },
    iframe : function (title,url,widthm,height) {
            layer.open({
            type: 2,
            title: title,
            content: url,
            area: [width, height],
            maxmin: true,
            btn: [ '关闭'],
            yes: function(){
                layer.closeAll(); //此处只是为了演示，实际使用可以剔除
        }
        });
    },
    notice:function (id,content) {
        layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '300px;'
            ,shade: 0.4
            ,id: 'LAY_layout'+id//设定一个id，防止重复弹出
            ,resize: false
            ,btn: ['关闭']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">'+content+'</div>'
        });
    },
    tips:function ($id,content,tyle,time,color) {
        if(content == undefined){
            content='';
        }
        if(tyle ==undefined){
            tyle=1;
        }
        if(time == undefined){
            time='5000';
        }
        if(color == undefined){
            color='#3595CC';
        }
        layer.tips(content,$id, {
            tips: [tyle, color],
            time: 4000
        });
    }
    
}
