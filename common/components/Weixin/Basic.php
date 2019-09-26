<?php
/**
 * 请求登录和获取用户信息
 *
 * Created by www.yiui.top.
 * Date: 2018/1/2
 *
 * 1. 第三方发起微信授权登录请求，微信用户允许授权第三方应用后，微信会拉起应用或重定向到第三方网站，并且带上授权临时票据code参数；
 * 2. 通过code参数加上AppID和AppSecret等，通过API换取access_token；
 * 3. 通过access_token进行接口调用，获取用户基本数据资源或帮助用户实现基本操作。
 */
namespace common\components\Weixin;

use common\helpers\CurlHelper;
use yii\helpers\Url;

class Basic {
    const AppID='';
    const AppSecret='';

    public $stateStr;//csrf

    public $redirect_uri=['wechat/index'];//登录后重定向的地址
    public $access_token;//用户的接口调用凭证
    public $scope='snsapi_login';//授权作用域（网页 登录:scope=snsapi_login）

    /**
     * 第一步:获取登录链接
     */
    public function getLoginStr(){
        /**
         * appid	是	应用唯一标识
        redirect_uri	是	请使用urlEncode对链接进行处理
        response_type	是	直接填code
        scope	是	应用授权作用域，拥有多个作用域用逗号（,）分隔，网页应用目前仅填写snsapi_login即可
        state	否	用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验
         */
        return 'https://open.weixin.qq.com/connect/qrconnect?appid='.self::AppID.'&redirect_uri='.urlencode(\Yii::$app->params['user_domain'].Url::to($this->redirect_uri)).'&response_type=code&scope='.$this->scope.'&state='.$this->getStateStr().'#wechat_redirect';
    }

    /**
     * 第二步,用户确认登录,微信重定向至 redirect_uri?code=微信返回的登录码&state=STATE,在重定向页面接收此登录码
     * code的超时时间为10分钟，一个code只能成功换取一次access_token即失效。
     */

    /**
     * 获取状态码,防止csrf攻击（跨站请求伪造攻击）
     * @return string
     */
    public function getStateStr(){
        return $this->stateStr;
    }

    public function setStateStr($str){
        $this->stateStr=$str;
    }

    /**
     * 用登录码去获取 AccessToken
     * access_token是调用授权关系接口的调用凭证
     * access_token有效期（目前为2个小时）较短
     * @param $code
     */
    public function getAccessToken($code){
        /**
         * 通过code换取access_token、refresh_token和已授权scope 接口
         *
         * appid	是	应用唯一标识，在微信开放平台提交应用审核通过后获得
        secret	是	应用密钥AppSecret，在微信开放平台提交应用审核通过后获得
        code	是	填写第一步获取的code参数
        grant_type	是	填authorization_code
         */
//        $tokenstr=file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.self::AppID.'&secret='.self::AppSecret.'&code='.$code.'&grant_type=authorization_code');
        $tokenstr=CurlHelper::get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.self::AppID.'&secret='.self::AppSecret.'&code='.$code.'&grant_type=authorization_code');

        /**
         * 正确的返回：
         *
         * {
                "access_token":"ACCESS_TOKEN",                  //接口调用凭证
                "expires_in":7200,                              //access_token接口调用凭证超时时间，单位（秒）
                "refresh_token":"REFRESH_TOKEN",                //用户刷新access_token
                "openid":"OPENID",                              //授权用户唯一标识
                "scope":"SCOPE",                                //用户授权的作用域，使用逗号（,）分隔
                "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"      //当且仅当该网站应用已获得该用户的userinfo授权时，才会出现该字段。
           }
         *
         * 错误返回样例：{"errcode":40029,"errmsg":"invalid code"}
         */

        $token=json_decode($tokenstr);

        if (isset($token['errcode'])){
            //return ['code'=>$token['errcode'],'msg'=>$token['errmsg']];
            return false;
        }else{
            return $token;
        }
    }

    /**
     * 当access_token超时后，可以使用refresh_token进行刷新
     * refresh_token拥有较长的有效期（30天），当refresh_token失效的后，需要用户重新授权。
     * 若access_token已超时，那么进行refresh_token会获取一个新的access_token，新的超时时间；
     * 若access_token未超时，那么进行refresh_token不会改变access_token，但超时时间会刷新，相当于续期access_token。
     */
    public function getRefreshToken($refresh_token){
        //刷新或续期access_token使用接口
//        $refreshstr=file_get_contents('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.self::AppID.'&grant_type=refresh_token&refresh_token='.$refresh_token);
        $refreshstr=CurlHelper::get('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.self::AppID.'&grant_type=refresh_token&refresh_token='.$refresh_token);

        /**
         * 正确的返回：
         * {
                "access_token":"ACCESS_TOKEN",      //接口调用凭证
                "expires_in":7200,                  //access_token接口调用凭证超时时间，单位（秒）
                "refresh_token":"REFRESH_TOKEN",    //用户刷新access_token
                "openid":"OPENID",                  //授权用户唯一标识
                "scope":"SCOPE"                     //用户授权的作用域，使用逗号（,）分隔
           }
         *
         * 错误返回样例：{"errcode":40030,"errmsg":"invalid refresh_token"}
         */
        $refresh=json_decode($refreshstr);

        if (isset($refresh['errcode'])){
            return ['code'=>$refresh['errcode'],'msg'=>$refresh['errmsg']];
        }else{
            return $refresh;
        }
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param $access_token
     */
    public function ckToken($openid,$access_token){
        /**
         * access_token	是	调用接口凭证
         * openid	是	普通用户标识，对该公众帐号唯一
         */
//        $ckstr=file_get_contents('https://api.weixin.qq.com/sns/auth?access_token='.$access_token.'&openid='.$openid);
        $ckstr=CurlHelper::get('https://api.weixin.qq.com/sns/auth?access_token='.$access_token.'&openid='.$openid);

        /**
         * 正确的Json返回结果：
         * {
                "errcode":0,"errmsg":"ok"
           }
         *
         * 错误的Json返回示例:
         * {
                "errcode":40003,"errmsg":"invalid openid"
           }
         */
        $ck=json_decode($ckstr);

        if (isset($ck['errcode']) and $ck['errcode']==0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取用户个人信息（UnionID机制）
     * 此接口用于获取用户个人信息。开发者可通过OpenID来获取用户基本信息。特别需要注意的是，如果开发者拥有多个移动应用、网站应用和公众帐号，可通过获取用户基本信息中的unionid来区分用户的唯一性，因为只要是同一个微信开放平台帐号下的移动应用、网站应用和公众帐号，用户的unionid是唯一的。换句话说，同一用户，对同一个微信开放平台下的不同应用，unionid是相同的。请注意，在用户修改微信头像后，旧的微信头像URL将会失效，因此开发者应该自己在获取用户信息后，将头像图片保存下来，避免微信头像URL失效后的异常情况。
     */
    public function getUserInfo($openid,$access_token){
        /**
         * access_token	是	调用凭证
         * openid	是	普通用户的标识，对当前开发者帐号唯一
         * lang	  否	国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语，默认为zh-CN
         */
//        $ckstr=file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid);

        $ckstr=CurlHelper::get('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid);

        /**
         * 开发者最好保存用户unionID信息，以便以后在不同应用中进行用户信息互通。
         *
         * 正确的Json返回结果：
        {
            "openid":"OPENID",              //普通用户的标识，对当前开发者帐号唯一
            "nickname":"NICKNAME",          //普通用户昵称
            "sex":1,                        //普通用户性别，1为男性，2为女性
            "province":"PROVINCE",          //普通用户个人资料填写的省份
            "city":"CITY",                  //普通用户个人资料填写的城市
            "country":"COUNTRY",            //国家，如中国为CN
         * //用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
            "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
            "privilege":[                   //用户特权信息，json数组，如微信沃卡用户为（chinaunicom）
                "PRIVILEGE1",
                "PRIVILEGE2"
            ],
            "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"     //用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。

        }
         *
         *
         * 错误的Json返回示例:
         * {
        "errcode":40003,"errmsg":"invalid openid"
        }
         */
        $ck=json_decode($ckstr);

        if (isset($ck['errcode'])){
            return false;
        }else{
            return $ck;
        }
    }

}