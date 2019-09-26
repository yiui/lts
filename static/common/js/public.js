/**
 * Created by admin on 2017/9/25.
 */
//获取当前时间
function dateTime(){
    var d = new Date(),str = '';
    str += d.getFullYear()+'年';
    str += d.getMonth() + 1+'月';
    str += d.getDate()+'日';
    str += d.getHours()+'时';
    str += d.getMinutes()+'分';
    str += d.getSeconds()+'秒';
    return str;
}