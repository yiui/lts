<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/25
 * Time: 17:24
 */
namespace common\components\Aliyun;

use common\components\Aliyun\Core\Config;
use common\components\Aliyun\Core\Profile\DefaultProfile;
use common\components\Aliyun\Core\DefaultAcsClient;
use common\components\Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use common\components\Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

class DaYu {
    const AK='LTAIET44NJl7hG4l';//LTAIUjq5Fzy1YJrH
    const SK='C00qUjixD8TZaAjvPGGKgvHJszN4vU';

    const SMS_TEST_CODE='SMS_99335076';//测试模版CODE

    //阿里云赠送的常用模板代码
    const SMS_ID_VALID_CODE='SMS_99335077';//身份验证验证码
    const SMS_LOGIN_CODE='SMS_99335075';//登录确认验证码
    const SMS_BAD_LOGIN_CODE='SMS_99335074';//登录异常验证码
    const SMS_REG_CODE='SMS_99335073';//用户注册验证码
    const SMS_PWD_CODE='SMS_99335072';//修改密码验证码
    const SMS_UPDATE_CODE='SMS_99335071';//信息变更验证码

    //短信签名
    const SMS_TEST_NAME='阿里云短信测试专用';
    const SMS_COMMON_NAME='汽车商务网';

    /**
     * 构造器
     */
    public function __construct()
    {
        Config::load();

        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, self::AK, self::SK);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
    }

    /**
     * 发送短信范例
     * https://help.aliyun.com/document_detail/55451.html?spm=5176.sms-account.109.2.6f26be80d1a5ld
     *
     * @param string $signName <p>
     * 必填, 短信签名，应严格"签名名称"填写，参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/sign">短信签名页</a>
     * </p>
     * @param string $templateCode <p>
     * 必填, 短信模板Code，应严格按"模板CODE"填写, 参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/template">短信模板页</a>
     * (e.g. SMS_0001)
     * </p>
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param array|null $templateParam <p>
     * 选填, 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * </p>
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @return stdClass
     */
    public function sendSms($signName, $templateCode, $phoneNumbers, $templateParam = null, $outId = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        // 可选，设置流水号
        if($outId) {
            $request->setOutId($outId);
        }

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);
        //$info=json_decode($acsResponse);
        if ($acsResponse->Code=='OK'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询短信发送情况范例
     * ！！！注意，有很大延时！！！
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return stdClass
     */
    public function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);

        // 选填，短信发送流水号
        $request->setBizId($bizId);

        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);

        // 必填，分页大小
        $request->setPageSize($pageSize);

        // 必填，当前页码
        $request->setCurrentPage($currentPage);

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        if ($acsResponse->Code=='OK'){
            return $acsResponse->TotalCount;//向此号码发送了多少此短信
        }else{
            return false;
        }
    }


}