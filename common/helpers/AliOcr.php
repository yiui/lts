<?php
/**
 * 阿里云文字识别
 * User: www.zhaoweming.cn
 * Date: 2017/8/26
 * Time: 11:51
 */
namespace common\helpers;

/**
 * 阿里云文字识别
 * Class AliOcr
 * @package common\helpers
 */
class AliOcr {
    /**
     * 印刷文字识别-身份证识别（限时5折）
     * AppKey：24600280     AppSecret：e137c465a3525f098254101cc810bb51 复制
    AppCode：195c58139bed44a2b1f971ec623e3d59
     *
     * 印刷文字识别-营业执照识别（限时5折）
     * AppKey：24600280     AppSecret：e137c465a3525f098254101cc810bb51 复制
    AppCode：195c58139bed44a2b1f971ec623e3d59
     */

    /**
     * 身份证识别
     * @param $idcard_base64_arr array ['face'=>'有头像那面的base64编码','back'=>'图片二进制数据的base64编码']
     * @return array
     */
    public static function IdCard($idcard_base64,$side){
        $host = "https://dm-51.data.aliyun.com";
        $path = "/rest/160601/ocr/ocr_idcard.json";
        $method = "POST";
        $appcode = "195c58139bed44a2b1f971ec623e3d59";
        $headers=[];
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
        $querys = "";
        //  "side":"back" #身份证正反面类型:face/back
        $bodys = '{
            "inputs": [
                {
                    "image": {
                        "dataType": 50,
                        "dataValue": "'.$idcard_base64.'"
                    },
                    "configure": {
                        "dataType": 50,
                        "dataValue": "{
                            \"side\":\"'.$side.'\"
                        }"
                    }
                }
            ]
        }';
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);

        /**
         * 每一个请求返回的结果都是一个json字符串，由dataValue关键词可以索引到，主要有7个字段：
         * 正面返回结果格式:=======================================================================
            address： 识别的地址信息， 类型为字符串
            config_str： 表示发送请求时候的配置字符串，为json字符串格式，表示输入时候的配置参数， 类型为字符串
            face_rect : 人脸位置，center表示人脸矩形中心坐标，size表示人脸矩形长宽，angle表示矩形顺时针旋转的度数。 注： 当所有数字为0时候，标识人脸区域没有检测到。
            name： 识别的身份证姓名， 类型为字符串
            num： 识别的身份证号码， 类型为字符串
            sex： 识别的性别， 类型为字符串
            birth： 识别的出生日期， 类型为字符串
            nationality : 识别的民族， 类型为字符串
            success： 识别流程是否出现异常， false表示识别失败，true表示识别成功， 类型为布尔型
         *
         * 反面：===========================
         * 每个返回结果同样存储在以dataValue为关键词所对应的键值对中，其中有5个字段：
            config_str: 和正面字段含义一样， 类型为字符串
            issue: 身份证签发机关，类型为字符串
            start_date: 身份证有效期起始时间，类型为字符串
            end_date: 身份证有效期结束时间，类型为字符串
            success: 识别流程是否成功，类型为布尔型
         */
        $info=curl_exec($curl);
        curl_close($curl);

        //成功是json，失败是字符串 是否找得到输出值，以此可以确定是否出现异常
        if (strpos($info,'{"outputs":')!==false) {
            $infoObj = json_decode($info);
            $result=[];
            $idcard = json_decode($infoObj->outputs[0]->outputValue->dataValue);
            //识别成功，识别成功不代表正确，可以通过验证一些来判断
            if ($idcard->success) {
                if ($side=='face'){
                    $result = [
                        'address' => $idcard->address,//地址
                        'birth' => $idcard->birth,//验证生日 == 19910101
                        'name' => $idcard->name,//姓名
                        'nationality' => $idcard->nationality,//名族
                        'num' => $idcard->num,//身份证号码
                        'sex' => $idcard->sex,//男 女
                    ];
                }else{
                    $result = [
                        'issue' => $idcard->issue,//身份证签发机关
                        'start_date' => $idcard->start_date,//身份证有效期起始时间
                        'end_date' => $idcard->end_date,//身份证有效期结束时间
                    ];
                }
            }
            return $result;
        }

        return false;
    }

    public static function Gs($gs_base64){
        $host = "https://dm-58.data.aliyun.com";
        $path = "/rest/160601/ocr/ocr_business_license.json";
        $method = "POST";
        $appcode = "195c58139bed44a2b1f971ec623e3d59";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
        $querys = "";
        $bodys = '{
            "inputs":[
                {
                    "image":{
                        "dataType":50,
                        "dataValue":"'.$gs_base64.'"
                    }
                }
            ]
        }';
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $info=curl_exec($curl);
        curl_close($curl);

        //成功是json，失败是字符串 是否找得到输出值，以此可以确定是否出现异常
        if (strpos($info,'{"outputs":')!==false) {
            $infoObj = json_decode($info);
            $result=[];
            $gs = json_decode($infoObj->outputs[0]->outputValue->dataValue);
            //识别成功，识别成功不代表正确，可以通过验证一些来判断
            if ($gs->success) {
                $result = [
                    'num' => $gs->reg_num,//注册号/统一社会信用代码
                    'name' => $gs->name,//公司名称
                    'person' => $gs->person,//法定代表人
                    'over_time' => $gs->valid_period,//营业期限结束日期，输出格式为\"年月日(YYYYMMDD)\"证件中的\"长期\"输出为\"长期\"，若证件没有营业期限，则默认其为\"长期\"
                    'address' => $gs->address,//公司地址
                ];
            }
            return $result;
        }

        return false;
    }

}

/**
 * ==============身份证====================================
 *
 * 参数均通过http请求的body传输，格式以json字符串封装，json字段因服务而异。 各服务的返回结果同样以json字符串形式保存
 *
 * 上传参数：
 * 服务所需的参数通过http POST请求的body上传，以json字符串格式封装，而每个参数又有两个json字段，dataType和dataValue，形式如下：
 * {
        param :{
            "dataType":  xxx  # int类型
            "dataValue":  xxx  #根据dataType定类型
        }
    }
 *
 *
 * dataType的定义如下，用户可以根据具体的数字，把dataValue转换成对应的格式存储：
 * Bool = 1; Int32 = 10 Int64 = 20; Float = 30; Double = 40; String = 50; DateTime = 60;
 *
 *
 * 上传请求的body示例如下：

    "inputs": [
        {
            "image": {
                "dataType": 50,
                "dataValue": "base64_image_string"      #图片以base64编码的string
            },
            "configure": {
                "dataType": 50,
                "dataValue": "{
                    \"arg1\" : \"arg1_value\",
                    \"arg2\" : \"arg2_value\"
                }"
            }   #[可选参数]
        },
 *      更多请求...
    ]
 *
 * 输入参数是json字符串信息，关键词inputs对应一个json数组，里面可以存放多个json object，每个json object包含单个识别请求所需的参数，
 * 服务支持多机并发访问，因此可以一次上传多组参数进行识别。每一组参数则又有两个字段构成：
 *
 *参数名称      	参数类型	是否可选	    描述	                                                                                            默认值
  image	        string	否	        dataType为50(字符串)， dataValue是base64编码后的图像数据	                                        空字符串
  configure	    string	是	        dataType为50(字符串)， dataValue是json格式字符串，其中的具体参数字段根据不同的识别服务而定，参见API介绍	空字符串
 *
 *
 * 结果解析：
 * 返回结果以json字符串格式封装，存储在以outputs为关键词对应的的数组中。
 * 如果inputs为关键词的数组中包含多个请求参数，那么对应的outputs对应的数组中也包含同样个数的json object。每个json object有3个字段：
 * outputLabel: 所用识别服务的名称
   outputMulti: 暂时未使用，为{}
   outputValue: json字符串，其中dataType 50表示数据类型为字符串，dataValue为对应的输出结果字符串，该字符串为json字符串格式，需要重新使用json解析获取每个字段的结果，其中具体的json字 段由不同的识别服务决定
 *
 * {
        "outputs": [
            {
                "outputLabel": "service_name",
                "outputMulti": {},
                "outputValue": {
                    "dataType": 50,
                    "dataValue": "{
                        \"outputinfo1\" : \"output1\",
                        \"outputinfo2\" : \"output2\"
                    }"
                }
            },
 *          ...更多对应的返回结果
        ]
    }
 */

/**
 * ================营业执照==================================
 *
 * 请求体：
 * {
        "inputs": [
            {
                "image": {
                    "dataType": 50,
                    "dataValue": "对图片内容进行Base64编码的字符"
                }
            }
        ]
    }
 *
 * 正常返回：
 * {
        "outputs": [
            {
                "outputLabel": "ocr_business",
                "outputMulti": {},
                "outputValue": {
                    "dataType": 50,
                    "dataValue": "{
                        \"config_str\": \"\",                     #配置字符串信息
                        \"reg_num\": \"123456765432101\",         #注册号/统一社会信用代码
                        \"name\": \"杭州西溪科技有限公司\",          #公司名称
                        \"person\":\"张三\",                       #法定代表人
                        \"valid_period\" : "20340801",            #营业期限结束日期，输出格式为"年月日(YYYYMMDD)"
                        #证件中的"长期"输出为"长期"，若证件没有营业期限，则默认其为"长期"
                        \"address\" : \"浙江省杭州市文一西路969号\",  #公司地址
                        \"success\": true                         #识别成功与否 true/false
                        \"request_id\"：\"84701974fb983158_20160526100112\",  #请求对应的唯一表示
                    }"
                }
            }
 *      ]
    }
 *
 */
