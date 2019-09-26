<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>后台管理中心</h1>
        <p><span id="timer"></span></p>
        <p class="lead">请在左侧选择相应栏目进行管理</p>

        <p><a class="btn btn-lg btn-success" href="#">联系技术员</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>步骤1</h2>

                <p>选择并点击打开左侧相应栏目</p>
            </div>
            <div class="col-lg-4">
                <h2>步骤2</h2>

                <p>打开2级分栏目，点击目标链接</p>
            </div>
            <div class="col-lg-4">
                <h2>步骤3</h2>

                <p>查看并进行增删改查等操作</p>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function startTime()
    {
        var today=new Date();
        var Y=today.getFullYear();
        var M=today.getMonth()+1;
        var D=today.getDate();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
// add a zero in front of numbers<10
        m=checkTime(m);
        s=checkTime(s);
        document.getElementById('timer').innerHTML=Y+"年 "+M+"月 "+D+"日 "+h+":"+m+":"+s;
        t=setTimeout('startTime()',500);
    }

    function checkTime(i)
    {
        if (i<10)
        {i="0" + i};
        return i;
    }

    window.loaded=startTime();
</script>
