<?php

namespace common\actions;

use common\components\Ueditor\Config;
use yii;
use yii\helpers\Url;
use yii\base\Action;

use common\components\Ueditor\Channel;

class UeditorAction extends Action
{
    /**
     * @param $date {"t":"目标表名","q":{"需要插入的字段1":"值1","需要插入的字段n":"值n"},"d":是否删除目标，默认不删除，会自动将图片设为空} 在uploadExtra中可以设置
     * @return array
     */
    public function run()
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //header('Access-Control-Allow-Origin:*');//注意！跨域要加这个头 上面那个没有

        //新版 UEditor 的所有请求都是向 controller.php 发起，再通过它分发到其他 php 脚本执行，所有返回值都是有固定格式。

        //假如请求成功，返回的 json 内容里的 state 属性值为 “SUCCESS”。 假如请求失败，state 属性值为错误提示信息。

        // 获取方法
        //$action = !empty($_GET['action']) ? trim($_GET['action']) : '';
        $action = Yii::$app->request->get('action');

        // 实例化处理方法
        $handle = new Channel(Config::QuConfig(true,['th_w'=>870,'th_h'=>870,'th_t'=>9]));

        // 运行
        $response = $handle->dispatcher($action);

        $result = json_encode($response);

        $callback=Yii::$app->request->get('callback');

        /* 输出结果 */
        //if (isset($_GET["callback"])) {
        if ($callback!=null) {
            //if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                /*
                 * JSONP(JSON with Padding)是JSON的一种“使用模式”，可用于解决主流浏览器的跨域数据访问的问题。
                 * 由于同源策略，一般来说位于 server1.example.com 的网页无法与不是 server1.example.com的服务器沟通，而 HTML 的<script> 元素是一个例外。
                 * 利用 <script> 元素的这个开放策略，网页可以得到从其他来源动态产生的 JSON 资料，而这种使用模式就是所谓的 JSONP。
                 * 用 JSONP 抓到的资料并不是 JSON，而是任意的JavaScript，用 JavaScript 直译器执行而不是用 JSON 解析器解析。
                 */
                //在服务器端实现对JSONP支持
                /*
                 *  在客户端调用提供JSONP支持的URL Service，获取JSONP格式数据。
                 * 比如客户想访问http://www.xxx.com/myService.php?jsonp=callbackFunction
                 * 假设客户期望返回JSON数据：["customername1","customername2"]
                 * 那么真正返回到客户端的: callbackFunction([“customername1","customername2"])
                 */
                //echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';//其实就是调用页面js函数等代码，只不过参数（这里参数为json格式）跟着返回了，正好执行源代码中的语句
                echo htmlspecialchars($callback) . '(' . $result . ')';//其实就是调用页面js函数等代码，只不过参数（这里参数为json格式）跟着返回了，正好执行源代码中的语句
                /*
                 * 客户端代码：
                 * <div id="divCustomers">
                   </div>
                    <script type="text/javascript">
                        function onCustomerLoaded(result, methodName) {
                            var html = '<ul>';
                            for (var i = 0; i < result.length; i++) {
                                html += '<li>' + result[i] + '</li>';
                            }
                            html += '</ul>';
                            document.getElementById('divCustomers').innerHTML = html;
                        }
                    </script>
                    <script type="text/javascript" src="http://www.yiwuku.com/myService.php?jsonp=onCustomerLoaded"></script>

                    加载完成后，自动将返回的["customername1","customername2"]作为onCustomerLoaded函数的参数运行此源代码中的js语句。
                 */
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}