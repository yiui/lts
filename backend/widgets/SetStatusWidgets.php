<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/12
 * Time: 14:54
 */
namespace backend\widgets;

use yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Url;

class SetStatusWidgets extends Widget {
    //定义属性
    public $view;
    public $del=true;

    //重写初始化
    public function init(){
        parent::init();
        //如果不需要初始化，就不必重写了
    }

    //吃些运行方法，此方法返回小部件html代码或可直接输出代码
    public function run(){
        if (empty($this->view)){
            return;
        }

        $status_url=Url::to(['set-all']);//设置状态地址
        $js=<<<MYJS
//批量设置状态为某个值（不是状态ID），k=状态值是第几个列字段？（成功会将值修改为设置值）
function setAll(statusId,statusValue,kn){
    //注意这里的$("#grid")，要跟我们GridView::widget设定的 id一致
    var keys = $("#grid").yiiGridView("getSelectedRows");
    if(keys!=""){
        $.post("$status_url",{ids:keys,status:statusId},function (data, status) {
            if (status == "success"){
                if ((data instanceof Object) && !(data instanceof Array)){
                   if(('status' in data) && ('msg' in data)){
                       if(data.status==0){
                           $("tr").each(function (key, domEle) {
                              $.each(keys, function(index, value, array) {
                                  //（yii将每个data-key设为id值）如果data-key属性是我们删除的key
                                  if($(domEle).attr("data-key")==value){
                                      //子元素的第k-1个
                                      $(domEle).children('td').eq(kn-1).html(statusValue);
                                  }
                              });
                            });
                           alert('操作成功: ' + data.msg);
                       }else{
                            var code='';
                            if(data.status > 1){
                                code=' ; 错误代码：'+data.status + '。请重试！';
                            }
                           alert('操作失败: ' + data.msg + code);
                       }
                   }
                }else if (data instanceof Array){
                    // 此 domEle == this 如 $(domEle).css("backgroundColor", "yellow");
                    var isok=true;
                    var errorMsg='';
                    $("tr").each(function (key, domEle) {
                      $(domEle).attr("title",null);
                      $(domEle).attr("data-toggle",null);
                      $(domEle).css("background-color",null);
                      $.each(keys, function(index, value, array) {
                          if($(domEle).attr("data-key")==value){
                             for(var v in data){
                                 if(('id' in data[v]) && ('status' in data[v]) && ('msg' in data[v])){
                                    if(data[v].id==value){
                                        isok=false;//含有处理失败的
                                        var code='';
                                        if(data[v].status > 1){
                                            code=' ; 错误代码：'+data[v].status + '。请重试！';
                                        }
                                        $(domEle).attr("title",data[v].msg+code);
                                        errorMsg=data[v].msg+code;
                                        $(domEle).attr("data-toggle","tooltip");
                                        $(domEle).css("background-color","#FFCC99");
                                        keys.splice(index,1);
                                    }
                                 }
                             }
                          }
                      });
                    });
                    for(var k in keys){
                       $("tr").each(function (key, domEle) {
                          if($(domEle).attr("data-key")==keys[k]){
                               $(domEle).children('td').eq(kn-1).html(statusValue);
                          }
                       });
                    }
                    if(isok){
                        alert('全部处理完成！');
                    }else{
                        alert(errorMsg + ' 部分处理完成，有色框标注为处理失败！请重试！');
                    }
                }else{
                    alert('处理失败！请重试！');
                }
            }else{
                alert('发生未知错误！请重试！');
            }
        });
    }else{
        alert('请先选择！');
    }
}
MYJS;

        if ($this->del) {
            $del_url = Url::to(['del-all']);//删除所选地址
            $js .= <<<MYJS

//批量删除
function delAll(){ 
    //注意这里的$("#grid")，要跟我们GridView::widget设定的 id一致
    var keys = $("#grid").yiiGridView("getSelectedRows");
    if(keys!=""){
        $.post("$del_url",{ids:keys},function (data, status) {
            if (status == "success"){
                if ((data instanceof Object) && !(data instanceof Array)){
                   if(('status' in data) && ('msg' in data)){
                       if(data.status==0){
                           $("tr").each(function (key, domEle) {
                              $.each(keys, function(index, value, array) {
                                  //（yii将每个data-key设为id值）如果data-key属性是我们删除的key
                                  if($(domEle).attr("data-key")==value){
                                        $(domEle).remove();
                                  }
                              });
                            });
                           alert('操作成功: ' + data.msg);
                       }else{
                            var code='';
                            if(data.status > 1){
                                code=' ; 错误代码：'+data.status + '。请重试！';
                            }
                           alert('操作失败: ' + data.msg + code);
                       }
                   }
                }else if (data instanceof Array){
                    // 此 domEle == this 如 $(domEle).css("backgroundColor", "yellow");
                    var isok=true;
                    var errorMsg='';
                    $("tr").each(function (key, domEle) {
                      $(domEle).attr("title",null);
                      $(domEle).attr("data-toggle",null);
                      $(domEle).css("background-color",null);
                      $.each(keys, function(index, value, array) {
                          if($(domEle).attr("data-key")==value){
                             for(var v in data){
                                 if(('id' in data[v]) && ('status' in data[v]) && ('msg' in data[v])){
                                    if(data[v].id==value){
                                        isok=false;//含有处理失败的
                                        var code='';
                                        if(data[v].status > 1){
                                            code=' ; 错误代码：'+data[v].status + '。请重试！';
                                        }
                                        $(domEle).attr("title",data[v].msg+code);
                                        errorMsg=data[v].msg+code;
                                        $(domEle).attr("data-toggle","tooltip");
                                        $(domEle).css("background-color","#FFCC99");
                                        keys.splice(index,1);
                                    }
                                 }
                             }
                          }
                      });
                    });
                    for(var k in keys){
                       $("tr").each(function (key, domEle) {
                          if($(domEle).attr("data-key")==keys[k]){
                                $(domEle).remove();//删除之
                          }
                       });
                    }
                    if(isok){
                        alert('全部处理完成！');
                    }else{
                        alert(errorMsg + ' 部分处理完成，有色框标注为处理失败！请重试！');
                    }
                }else{
                    alert('处理失败！请重试！');
                }
            }else{
                alert('发生未知错误！请重试！');
            }
        });
    }else{
        alert('请先选择！');
    }
}
MYJS;
        }
        $this->view->registerJs($js,View::POS_END);//
    }
}