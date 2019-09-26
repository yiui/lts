<?php

namespace backend\controllers;

use Yii;

use yii\web\Controller;
use common\components\Fadada;
use backend\models\Article;
use yii\web\UploadedFile;
use linslin\yii2\curl;

/**
 *
 * 法大大接口测试
 *
 * TaskController implements the CRUD actions for Task model.
 */
class FddController extends Controller
{


    /**
     * 接口测试
     *
     * 客户唯一识别  1111
     * 客户编号44DE0126161A1293FECB09EF1E4424AF
     *
     * @return mixed
     */
    public function actionIndex()
    {

        //客户注册
        $fdd = new Fadada();
        /**
         *通用
         *
         *
         **/


        /**
         *个人
         *注册账号后去客户编号--->使用客户编号进行实名存证----->获取个人实名存证地址---->查询个人实名信息
         *
         * 获取客户编号-->
         **/
       // $res = $fdd->accountRegister('6677');
        //返回  客户编号  FA400623EA4C94F53314D3CF11DAE98E

        //个人实名信息存证
        $mobile_essential_factor = json_encode([
            'transactionId' => '12345678666',//交易号
        ]);
        // $res = $fdd->personDeposit('FA400623EA4C94F53314D3CF11DAE98E', '田贺', '320382199301300717', '19965412404', '田贺存证', '浙江皓石教育有限公司', $mobile_essential_factor);
        //返回数据 {"code":1,"data":"20190626110538826239057","msg":"success"}
//实名信息哈希存证
     //   $hah = hash_file('sha256', 'aabb.pdf');
//        $noper_time = filectime('aabb.pdf');//var_dump($noper_time);exit;//文件最晚修改时间
//        $file_size = filesize('aabb.pdf');
        //  $res = $fdd->hashDeposit('E7FF94530FEA1F1165665057D5AA6DA8', '123456', '张三实名认证', 'aabb.pdf', $noper_time, $file_size, $hah);
//3三要素身份认证
        //  $res=$fdd->threeElementVerifyMobile('田贺','320382199301300717','19965412404');
        //  var_dump($res);exit;
        //编号证书申请
        //evidence_no 实名存证时返回
        //  $res = $fdd->applyClientNumcert('44DE0126161A1293FECB09EF1E4424AF', '20190626110538826239057');
        //返回   {"code":1,"msg":"success"}

        //自定义印章
        // $res = $fdd->customSignature('FA400623EA4C94F53314D3CF11DAE98E', '田贺');
        //   $img=$res->data->signature_img_base64;
        //   file_put_contents('jack.png',base64_decode($img));//保存签章
//$img='data:image/jpeg;base64,'.$img;

//          echo '<img src="'.$img.'">';

        //{"code":1,"data":{"signature_img_base64":"iVBORw0KGgoAAAANSUhEUgAAAIYAAAA8CAYAAACjKMKCAAAOUUlEQVR42u1deVBV1xlnxnFsmqZpJk2TpmmaLmnamU6aNpM0k8k4TtqMTdM0zThpjYobj014gD4VF0REFEURlVARFYkb+yL7vm+yCAoIKhKDgvDeY5NFhJrb+7vxvDnvcu+79\/GEZ+H+8c273HPuWe73O992vsux6R9lVLUX6yvKKyoGWOovLSvrLysvV2gWEXgO3gMDwAIwYYOLysrKu223u\/w69P0b7\/TcXa8dGFWz5KrQrCA1eA7eAwPAAjBhU15ePtbepfcCSihy7B154KrQzCfwmuY9sABM2LCiZEDXN+JBCvQ9vRVanW6ApX6FZgUNgOeE\/8ACMAFg9Hf3DWkIKLq7u+\/qB4b9eofub1Ro5hN4zfH8ITiABWCCAwb0TO\/wAyedTjemH7zHVysKzXACz8F7YABYoIHh0jfyQA2x0jc05q68rNlF4DnHe2CAxQINDM4Qgc6BeFFe1uwi8JzjPTDAYkEBhkIKMBSaQmDc0g6obGxsGD7RdY5\/eY55+WevMPPmzWPefOttpqz6kqH8WnuX6u+ffMqVfeeJJ5h\/LvqMab2tNTwr1PacOXMMz9+806v660cfc89+98knmUX\/Wmz0vFT\/NEWcjZkw9ju9wyo7B2eubTyP8aFPueOT6t\/S+UmNT2p+YvyzGBhRCSncYMTK80urmLfefodpuHZTxbanCjt5hnntN7811J+\/4H0m\/HQUgzIQrhe8\/xfR9iLjzjMbNnsZyrd4+zKbvHwMz4eGnzIql+qfUFNru+q9+QsmvDgnV3dm\/6EQQ\/se6z1Nzpc\/Prn9T3Z+cscnNj8x\/lkMDC8fP2ZP4CHRiX62eAmz03+faPncuXMZOfe4wbIT\/93rv2c69IOGewBRZV2Tob5+aFz1wcIPGbn9E\/rzBwuZmoarE17cD555hnvpdPtYmXLHJ7f\/yc5P7vjE5ifGP4uB8Y9PF3Er\/PtPP81876mnGCCWLn\/ppy8zFbUNoi+GSAxa3L373nzB+geCjxitFhD6pF8MuSe3f\/JyAoKCGSLaTdXV3r2veu65H8ken5z+LZmfnPGZmp8Y\/ywGxvPPv8BAvBHEHww5ynhu9Wbo1Z+QmsW8+uvXDDYErQOrLjUzQD3RbbjGPaG+sJogks2ROFL9Q9TTqksKGJgrPT854zPVv6Xzkxqf1PzE+PfIvRI0js7ogcA4gpGDMqwKiFdSDsNq195Ag46E2AWK+e0CLDDc+Pf5hh7\/xZnqHyL7jT++ycAAlgOMtk69CsYfxLXc8UnN39L5mRqfufOj+Tcl7io9GYgniDe6Y1oH4poWlbjGyuK3CT3IV1NiYpW+Z6r\/JbYrmdNR8QyfkYIhYvZl\/\/vzZRM8HjnjMzV\/S+dnanzmzI\/PP0FglJSUfIONk76RcY1Wq\/1Gf3d4l1gjzz77QyNjCS8BYpP8TRtKQozngwDlcL2EbBlY0EJGFVw28ndX\/z3O+pbTv5CbJuRuYyVi1fPFvJzxSc3f0vmZGp+c+YnxDzwH74EBYAGYMAsYrh4aBi4VUQU+fns494k2JkGkHCuDFpmOa9RMcOhxTvyh3H9fEGPv5DLhBb3y818wLTc7J\/SPvr19dxvaR1tw7+T2LyT66b8zC0o5Y5gWx0IkNj65\/U92fnLHJzY\/Mf6JAgM7atwGClvIihVPsY6A4JV2DlxwBYYjJiEkJmEpow5sCrhN9PMAB8pAAAXuCelVvnUOwsuEZ0OeX\/jhRxOMO1P9S724F3\/ykqREMTU+uf1Pdn5yxyc2PzH+geffAoPbRFMbgIHUrp6h0fUPJcZuJUw8y7bdWZ6D98AAsGC2KlFoxgLDMhtDodkFjHHKxhg3ZWMoNGN3V2FjjFM2xrhZxqdCMxoYxsanko+hkMUBLoWmjrr7xxwutfR4XL7Ss\/Z6+6CL1W0MRWJYl77qHnaOS27x2+GXf26VXWy17cqYS+s3pSefjb28R0ntm6VU06TTeG7NTFq6PKrxc9vIZpqWLY9qSM68vkMBxgynrII2r9ik5l2ttwZdyb2j4TWHCBDc16dm7D9Udiw4tDLEwSmhnLunSclsah1wM6efnmHGPuJM\/X6v7bmxIaFVIZBI0w4MObt3k6WpbHs6Cate45mWamcff2HFqpi6jVsyzze29HkQiREUXH70TFRDAA2ApPSrOxydE0qX2EY1hR6rPmwOKEJP1BxesTr2Iid1VkQ3bPPJiZYClwKMaaTM\/LZtruuSs1eujqnlq4mzsY2S9kNYeM1B1FWvTcmtv6JbK1cqrbaPrzLqb1nkFQCzrLpj44xQJf+PwOgZ\/q99fmn7FjDTdkXUJT4giO2QknN9u1RbV671ubu4J+cuWR7ZeCSsOljSXmno1kBVbd2eEydkszi7JhZCEkGqKMCYZoIkACOFAAGyc4yvlMNkQsciaoOw4l09kvPgyorVK7lwy9NpTWKxuyY1E8w\/EFx2dPnqmIv8\/lWO8RVCoJwWVVLffIMh30Zge\/lP77zLFJbXTKh3NiaRef2NP3BbwMhSwvcmF5uuC+Yrok18q2EqQ9360oKx999ffFIIEFitu\/cVRbR1DJsVn4AtwkqfHLQBw1Q3+MBBSLKs3ZCaRvpyUScXAKDhp+oOECOWJtggVgEGvqM4cjzCkG+AxBU6y4sksaIeEk\/ob0yQwMJvG6B64ccvGmWXP26AIL9gBi0xXN3P5wUeLgtrae1zm2z7IWEXgiFpwk7WHkQQjC67eWd4jZdPdqyQZEJ9xEcIsEBr1OcLiypvbbK68YkPX5CFxE9y\/eWvXhXNDqfbzsgr5lLdken1OMYg9uwvDtd4ZqTiGvc6e8ecvjha9cV2n7xoMBRRTEv7gdSA3SIUKfXzLzwFVSMkpeABBQSVnUjP\/8p7w5aMZBil8SktO63mlYDhyDyCikAGMjKZ+fXk2A+oA0mB7yQgXcQypqabbutGnSDWHZwTy8TEM39lywmHg4H7DpYel2OYgg6GVIbCHRWzZ0AwQuEen466FFBYfnuzVd1VqAN8v0kYiV9+PdSRIzFInb99\/AmD\/ERrg4Lo86W2UdwqhdpAnKC5bUA9mfbau0ed03NveG\/xzk7ACkebCI1LAQtqgsQqpAjgiU5o2mX1OAYMyZILdQb7ANKDXy8kLJwzPPGBDEmHF7MxQM1tt7kvrvBrTVsC6oHEBjy9MhMv1N3ZIGQQShGMUHgPm7dlx9OuJZgttUdyLqHJf0KsQoQAXKgTuNHTCgyhD2RiktI40Y8y2BL46AZp67A16HoAAr7GgucCMC1eulzUKwFBYkByWAsYCGW7uJ3Pfxh0yjHlPooRjEXEGgAI2jawd44v899XHCEVoYR0QURUDijQvhzp88iAkVtSyTEH32jSK3ymE9QIrH28dO8duVHmAuJc9OW9nl5ZiUQNES8BKxptS7VRUtPh6aJOKpAFCpYg0eh9mSkHBlxPGJb4hbs5W4ABoxM7oXjpazempcuJSWADCxta8FxoQxEBq0MhFUfkei3wTNCnXFC4rUvJMkeiKburFhJWPV687aroelMGHSQEYhrYHUV+BQ0I7IGY48ZCvWzalpUgFxSQQpAuyrb7NBJWIQkYQS0AAPw6Fxt16yAh6L0SBLogOUypDEiFito7Ru++s3fMcfvOvHNisQo+IdIJO0TJx7ACIWYBRmFLHJ6FmFRBHRir2FI3laoHcB0+cuE\/8DQQJaW9IERMEZzCxpsUKJD9BY9FSdSxotRwW5eaDWYgeIRoJ997gf0At5NfxjdmkUjj7JpUTAxS7LWQcmyT7woo+hLtIKAmtH1PCOoKG25KBpeVCWFvEn9AxNKcZxH7QJYW7XYCGJAucGXpZB30gV3TyMSm3VBFDmsSSoSim0j2EdpOV4AxzYRIJ3IliIci5xlsXiGMzY9DIKUP6okvXQAEAj6oGUiExPRrvjBo6VjFzt0FZ2CLKDmfj4utcaz6MHFBTUkN5EogVuG4JtFotSOrCnsYQvYHglLYoKONTqiLPYEl3KYYFyCDAcy6z4\/iUwMFGI+QEMcgkVCsYqE6cEvhkdCAWLcxPY2fFCwU\/4BnMyHEzRq8sGtOnL4YCDsGWVuUB+OEvRcFGI8BIcsbHgP2JIQ8FMQyyKpHdhVUgZzAGFSV4H4I2xbC51AzRgnArPSCGz3ZbX4FGFOxf6JOLiBSg28AAgRIlkEehCk7AM8hskqu03JubCdxEIAO8QnETWCL5Jd\/vZlWH3SW14mIuqBHDQzlWIpJEphBEmNikq\/4mfs8MsDhpYCISoCRuso+rgoMR44G7ApTm2Gcl8R6NkhCJp8mmEMszz3EjqUwHGTTO3x\/q8Jw86QGwtDEoJTrNsI+ABiIxIGKgKEKYEAFARBIrsHfUm0CDJyXxKoaGLRmf7v67UE299lrR6ODbJSjryyjuKTGMLVbYutaTXJjWubVL0zVvdzS7Xv8ZFXkNp+scjxDntu1Nz83r+hG0GTHcCKi+hzaCggszOjUD2025+irLpGjrwa6eobow\/IqlcPyzKNurXbg5s2O+21tHePt7Z33hOvo+js6uoZJPUK3bnWOdHV1D1o6BrRN2sR4zDwsr9LwD9xYLHCH5T08XtNIfUCtKEdPyif94Jj6dFR9sJ19zFU7x7imhJSmQH6dnKI2PztVbAvqqJziG\/z3FcRl5N\/Y3akb8bC0\/66eUfdtvtlpaNveMa7x8lX9JtnPs7w22rvp1OJ4zftCB\/JqlAN5zaf65q5Nmg2pBavtoq96+2Ynf9054EGXX27Revr65yX47cmPyypo9evUDrlb2meHdsQtKe1KwPrNaXmrVDHN6PvAwdLTHfoRNzMP5NWQA3kryIG8QEltXX0ljm9mbypHWltARUUlQzk5ReM5OYXjJSWlg1PZF9rPyyseJf3ht6CgeGSy7YH33BHeLBaAif8BT5+mMt5kR6YAAAAASUVORK5CYII="},"msg":"success"}

        // $res = $fdd->addSignature('44DE0126161A1293FECB09EF1E4424AF', $img);
        //{"code":1,"data":{"signature_id":"4422503","signature_sub_info":null,"status":null},"msg":"success"}
        //获取签章id
        //$signature_id=$res->data->signature_id;
        //var_dump($signature_id);exit;

        //获取个人实名认证地址
//        $notify_url='http://www.baidu.com';
//          $res=$fdd->getPersonVerifyUrl('FA400623EA4C94F53314D3CF11DAE98E',$notify_url);
        // var_dump($res);exit;
//          $url2=base64_decode($res->data->url);
//          header("location:$url2");
//{"code":1,"data":{"transactionNo":"6d34622564a84466a0dbb0e2522286d8","url":"aHR0cHM6Ly9yZWFsbmFtZXZlcmlmeS10ZXN0LmZhZGFkYS5jb20vZmRkQXV0aGVudGljYXRpb25TZXJ2aWNlL3YxL2FwaS9zeW5zQXV0aGVudGljYXRpb24uYWN0aW9uP3RyYW5zYWN0aW9uX25vPTZFRkU5REVEMkE0MUFBQjIwRjUyNDBDQTk2MUEyQzdBQjEzNTlBRjU4QTc1NzI1NDRERDlDNjBDMzEzQzM4NjM3RkYyMTQxMjQ2NDU2NzQ4JnNpZ249T1RJd1EwWXpRVVl3UmpGRE9ESTRSVFU0UmpSQ09ESXpPVEV5TkRNM05VRTNSRUZHT0RVd1F3PT0mYXBwX2lkPTQwMjE4NiZ0aW1lc3RhbXA9MTU2MTUxOTU5NTcyMw=="},"msg":"success"}
//$url='https://realnameverify-test.fadada.com/fddAuthenticationService/v1/api/synsAuthentication.action?transaction_no=6EFE9DED2A41AAB20F5240CA961A2C7AB1359AF58A7572544DD9C60C313C38637FF2141246456748&sign=OTIwQ0YzQUYwRjFDODI4RTU4RjRCODIzOTEyNDM3NUE3REFGODUwQw==&app_id=402186×tamp=1561519595723==';
//      $url2=base64_decode($url);
//      var_dump($url2);exit;
        //查询个人实名认证信息
        //   $res=$fdd->findPersonCertInfo('6d34622564a84466a0dbb0e2522286d8');
        //{"code":1,"data":{"authenticationSubmitTime":"","passTime":"","person":{"auditFailReason":"","auditorTime":"","backgroundIdCardPath":"","bankCardNo":"","certType":"0","headPhotoPath":"","idCard":"","mobile":"","personName":"","photoUuid":"","status":"0","type":"0","verifyType":""},"transactionNo":"6d34622564a84466a0dbb0e2522286d8","type":"1"},"msg":"success"}
        //type: "1"


        /**
         *
         * 企业负责人
         *
         ****/
        //企业负责人注册
        //   $res = $fdd->accountRegister('1122', '1');
//code: 1
//data: "DAF63C292D73749091E11D24DD7DCDE5"
//msg: "success"
        //个人实名信息存证
        $mobile_essential_factor = json_encode([
            'transactionId' => '12345677',//交易号
        ]);
        //   $res = $fdd->personDeposit('DAF63C292D73749091E11D24DD7DCDE5', '宋洋', '52030219891209794X', '18812345678', '用户存证', '皓石教育', $mobile_essential_factor);
//code: 1
//data: "20190627144618953278043"
//msg: "success"

        //获取法人人实名认证地址
//        $notify_url='http://www.baidu.com';
//         $res=$fdd->getPersonVerifyUrl('DAF63C292D73749091E11D24DD7DCDE5',$notify_url);
//object(stdClass)#129 (3) { ["code"]=> int(1) ["data"]=> object(stdClass)#124 (2) { ["transactionNo"]=> string(32) "0d1cfa4e8347483186de4a5667400de1" ["url"]=> string(392) "aHR0cHM6Ly9yZWFsbmFtZXZlcmlmeS10ZXN0LmZhZGFkYS5jb20vZmRkQXV0aGVudGljYXRpb25TZXJ2aWNlL3YxL2FwaS9zeW5zQXV0aGVudGljYXRpb24uYWN0aW9uP3RyYW5zYWN0aW9uX25vPUY4RDRDOTI1QkYwQkI5QzUxOTZBMkZENDY2QTc4QjRDQjAxMjk3MzcwQkZBRTZCOEFFMjY1OTdFRTNBOEMxMDI3RkYyMTQxMjQ2NDU2NzQ4JnNpZ249TXpVNU5qZERNemRFUmpReFJETTBRVGMzUlRKQlF6a3dRVEEyTkVZMk56RXlORVUyTlRRNE1BPT0mYXBwX2lkPTQwMjE4NiZ0aW1lc3RhbXA9MTU2MTk2NDkwNTE2Nw==" } ["msg"]=> string(7) "success" }
        //var_dump($res);exit;
//         $url2=base64_decode($res->data->url);
//         header("location:$url2");var_dump($res);exit;
        //自定义印章
//          $res = $fdd->customSignature('DAF63C292D73749091E11D24DD7DCDE5', '宋洋');
//
//          $img=$res->data->signature_img_base64;
//          file_put_contents('songyang.png',base64_decode($img));//保存签章
//         $img='data:image/jpeg;base64,'.$img;
//
//          echo '<img src="'.$img.'">';exit;
        // ["code"]=>
        //  int(1)
        //  ["data"]=>
        //  object(stdClass)#124 (1) {
        //    ["signature_img_base64"]=>
        //    string(5364) "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"
        //  }
        //  ["msg"]=>
        //  string(7) "success"
        //  var_dump($res);exit;


        /**
         *企业
         *企业注册获取客户编号-------->企业信息实名存证------->获取企业实名认证地址-------->证书编号申请--->企业实名认证查询
         *
         **/

        //企业注册
        //  $res = $fdd->accountRegister('1100', '2');
        //返回  客户编码 {"code":1,"data":"E7FF94530FEA1F1165665057D5AA6DA8","msg":"success"}

        //企业负责人信息
        $company_principal_verified_msg = json_encode([
            'customer_id' => 'DAF63C292D73749091E11D24DD7DCDE5',//企业负责人客户编号
            'preservation_name' => '宋洋实名',//存证名称
            'preservation_data_provider' => '浙江皓石',//存证数据提供方
            'name' => '宋洋',//企业负责人姓名
            'idcard' => '52030219891209794X',//企业负责人idcard
            'verified_type' => '1',//企业负责人实名存证类型 1:公安部二要素(姓名+身份证);2:手机三要素(姓名+身份证+手机号);3:银行卡三要素(姓名+身份证+银行卡);4:四要素(姓名+身份证+手机号+ 银行卡)Z：其他
            "public_security_essential_factor" => [  //verifiedType=1 公安部三要素
                'applyNum' => '456152456',//申请编号
            ],
        ]);

        //企业信息实名存证
        //二进制流
        $file = "jack.png";
        // $res=$fdd->companyDeposit('123456789999','E7FF94530FEA1F1165665057D5AA6DA8','浙江皓石教育有限公司','浙江皓石教育有限公司','浙江皓石教育有限公司','91330110MA28WKUE2W',$file,$company_principal_verified_msg,$file);
//object(stdClass)#130 (3) { ["code"]=> int(1) ["data"]=> string(23) "20190701094444266534056" ["msg"]=> string(7) "success" }

        //自定义印章
       //  $res = $fdd->customSignature('45448C4B17E69C0B744F51D79A7BC10E', '浙江皓石教育有限公司');
         //object(stdClass)#124 (3) { ["code"]=> int(1) ["data"]=> object(stdClass)#130 (1) { ["signature_img_base64"]=> string(8040) "iVBORw0KGgoAAAANSUhEUgAAAKYAAACmCAYAAABQiPR3AAAXVElEQVR42u1dD2gVRxrPpQ8vSM7GGmlCUhIk5+U8CR61NJ56TblcyUno5cTSiBbtVcTTnITiob1Lz1eCTf9dU2xrr0gvtCo5TGmsES1ICSUUEcVKFU8qIUiQIAETjJhiCu9m2t/YL+P+md23+/Le7jcw5GVnd3bmm99+/2bmm7w8Tpw45XZK5eUVi7xU5DqR14u8UeQWkZM2eRPuacIz1SIXMCU5+QFfpcjNIu8SuUvkUyIPizwlciqgPIJ6u/CeBvlepj4nBcICcDLJ3fpFnjQEVB9AJXOHDbdsF3k/7pH3D4h8ReQJh/on0Y4kwFrOoxQPICZErnUBogTfSQBui8grpAgPQSWohZjvFPmEyGMWbZkiQF3BakD0wLhG5G6LwVccqg33lM9wW0vQjk58HJMWH43kwvWyXzy6uQnIJSK/J/KoNrhjAGljtnMgomp0Qs+l/RjF9VoGaW7ojFI8fqUN4hWIw5weRNH2KpFbRf6fZoydgdpRyCjIPku6TeMqw9ATqyPa5yp8bEOaqJdGVxWjYmYHR3LAHk0X64eelgj53Q/5fG6ZyGUBt6UJOinlopIutYySzAOylwzCBPTJ6oDfM1fkZ23KvpHcykedNWjzopC4aKdm5ElXVR2jJnyR3UM4wxjEWXGI75TicYENMFc7PDfLoWyVznGD5PCiriKoNmMaB2URHzA4CqEvThAOKQlfkoF3n1bAhDVfjd93XJ57S+SbIl8TeVDkSyKfF/mWyJeRrwL447I+kb8T+ZEA216MmaUx4hd9XQKXUZU+cZuJUTMFcJRn8P2tANQ5GBovilwh8pd+9FL04wmb8tkh9UH6Rw8QXVx+DOsZXf6IWQ6FnupKVQHVLQfpvMs9ZRDXn0mRDTB+gbKTAFkCnFG2r8ulviW4rxD/zxH5W5EfS0eS+FCFqG4+wHP03gi4iYgfyS2bAqq3HmCQzumFhs8sgNhNgWs+gzZdxLW9TgABgEcIGL6BenBU5NekaE/DMJP1Pejj2UYihSSdWxh17jpRHxnEQMS2FJFS9KLOzS73PojlafugH34i8gciP43yZwC2OShf7FJfqcg75V/t+hz8Xevy/EksHKmmBhLcYt0BiHdF6xOZ0NlzEZSNmKFROlBjQPXuIZy3zOB+CbrPiZEzB5zyMESf1DnzUTakAOZSpxT5N1DvZwCVfPa2iV4p7pkvjSLouhfBcU8FRJ8Gwj0Dk05RAWUbcQH1BsQl54DQ58AFB7yKTSyYkJbyY7Cqa3B9GH+vmbp51DMWhlC+xza9BWC2QsWQFv8rUgdO073UQ7hnR9wBWUAIMgXXRiKkd0lRftlGzOfbPNOvLHDomqWEA0qON+7B3aV0VAmkYwDmVQ/tT+D5D7Xr+RDzl/COw2l6ICYJgyiMIyhLsPhAie4VIb9PGh2t2rXnCZdo18qkXvkwfi+Dz7GGlK/Gcw8bvHuh1E2tjCrDtj8HP2cKXHwRdNdZFh+ZNKj2pkGnpcRYuxArqx2dH85k5+HsriD/S13vEDGKSknZPrh3CgHQzymIAAD5zDz8ldxzud0yOhhQ/eBqg1gJtNjAZfUEPAjteNchImnU1ObaDDCN2jiAspnM4PSazkKI+7ZjhqTMxzvloH5H6jlOrGKl683G/8vx931cfxz/j6hygJlayF/jXgn+Lygngwi+bdEmad2ftVFvXsI7lmm+1+1QLRK4dl0CPkQ1q5eoWeujDMpNxMjp8KpPivvfkVapj/cqR/0dKyc9rhdYXM8nOuIN9dtOB7S5vlxxOvw/C3/PK4e9obtrCH8fg64pgZMy7Ps5Jf590C5JxqwliqCklveWNOq5DJ1LKuoDWlmZzTMXIZ5v23DTO3acB+VSnH7ts71V1KUkdVKyhnKJYR2SWz5OjTSI+S8MuN4t/K6w0qUN37+RjF0ySqBMkj02TT7rkGLsrOIUOreAn3CrzbPjxMreQqzZ/RCH9Rmmxx6lWhjcuxJ6aQW47FXQYNBtihYuskXo6008s99nmxuJxZ6MCqdUekqDh+cW0pU2mLseJ6L3KikbpfqYVo80YvYoMYpn28G1arKcdmracZ6F+8ltdZMMxPAifg/SOvCRX/Lq+4QzfsIv581GnXLSCyjxrATOUQuu2A+AvQMD4qbT7IuFY/tZuR4yR+j3iNVCZXFtt9PMDwy683ApVegin+jLC6B/3jCdcwc4J3NW54T1rfSSNT6ef0LnglTPg7U7rvkFl2j3S0NhXYRUohYYQHecFqFgqjOBBSZ3KFOQDnpwy9UE/Ckvq/Eh1pX/d30uEXApYfktPuuogK+uVK19lP5AAsqb5N4mvOtbXZeLmAGpDJgbDvd8RkA3m6oCWDnfQoyyMpQ/77Ti3sEgUirailwgXglxnnekUc986JlDdIYFete3mpX7DgCcyotwQt8HHaZQP9DXhcpniIU+jMmCBVhLkEpzPWiSOOHLs5lwBViipZzniTSBeYmI6dtYjHFZ08H+Qf7/GLpVfl7MEpzy1x3K5ZTsEfzeABFfHcB7e8gMXmG2Eoc2sijNuo5IYuJ3KYyWo6RczowMkv+VS+ShvBgmNTNlU3YI3onz5Jo0IFcGxIzOBMGMwiIMZeuVHp47bqffwK3zBtExpV9ug0bwPXCCX8zjZEdDpVc+DVE+CI45EqD6NpJ1S+ZgpU0h13kk2pDLPXILwlP4rRYu0Hnq9/3s744JKPfRZXJwtu+lno2ADd5Jv16YMDpfjFg6skG7PD5720n5Jhu3RtScM2ZCdDfSDobhPbQ7jTG5DF/lVqhE4yG+s5WshC+eaQL0+tEv4GMct5svhgi/SfTHqww3T+MyDD2dLvWbY7qGNI33dqs9RDPZ+S1+3QUg3Fk8/4pWpkQ2ByoNfsxumX7kPndgFhF9s2UmOlhOttg2GtxP52ql7/FN/H4UdezUiFfPMApl3KqwV6kVDvYCCyt7ARa4pLSVUWcN39EAe2Ms42FpyBrHbpf76sm2gFKbexJw9cg54DelQcMQCn38FmIxSCus9FEwhFsA7iX4pGdTKeeh/gMkqEIiU51qJkpuuRunxFRaqUG9V+lUI6esArKc8Dju0YU0lLH5dHxlasqxKeC6h0wXz3LKODBf8SqWyWKPkdADeWFLxPexhHw8W6HiQurTV+jEJwyBrOCMxyzCJI77rE95bV4Ps9GVWDU05ePreQMNvA23xRzKcRFYIJ+hkTEAFsJddx365GXoluOI70TdTGVe9EsLzEz6wYyXl/SYGDw2z+brwFMzNpiWfJbhknFwVgCU37nc96HfPU+aIdQbRidqgfqJoJY4YSXQKobIjAO0gYar0f2X8Jh8mUb9JcS1WBd045Wu0OaDU55z+BLZCs8egJ7G4mK6n6pUuX3SrHuXX9vEjVuqWIolLveOU8MGEScGXFj8mwyLrAHnF3SFPHzMKbeoIQb1FhOuWRtUY3tMt23CNTBfczPUONw/N6pn8eQoML/TZ3jgMRkNoO62wHRNWFVKtyw2uL+GbpRCsIEhu7jjnLIKlBugT1pFJXmOMhyf9RcRrlkVFMrf8/DMIdUJAHMlwDkapV2LnHzhqTPtfelkA1PKSdzSw5TgHzuF6cXTNLQ0cd7eDCNKGaecAGYVJPCI7z1CZItmv8M9xyxWB7UTi+4oQDpXA+9+bLRfxMOVc+DaQ20EatUbPn8irRhW5BTbNQacdTtE9VbMJswlEXirMJuw2Eon5aHOKVDu1nyecgnjbvx+Um6lNqijkWxaTHhtwBKygsjryvQUFOn9WtloDAbulxHum9xf9Tl+d5Prd5RTHhLS5BCGIV+uI7JQtMPlvgq6yR6LTA8jbF4K+51VTMhlflZF59DA/UTkf0cYlJ9ojGsdfj+u+T5HDepTu2oPeGlEAmLZzeiZBYf6ca3ByiI/jE1k17CweF7EueWvcU5RTUT7167plO+rzYSYxduB38sNFpBXk0mbAtMGrMFDV9LsyGJllZP52A0RBuZ2AHNbhPs4qOmKlFMWkN/SZfiaS10XPC0kJjvdkh4bXaoah2hti8j5i2uxaGNzRAfsPsyQSWAejDAwqxEK8hp0y1Gb+1a6xZIi8+e9Ji9OEO/8Ug8NXkbnVCHmXyLlkV5rifUEfSTXRLiv+6zcReTaDhXz3kCiqmO+C0wIrGS/F2u8UD9+Dl/NkZi4Uf6mAXNbXsyS6PMLiotCOu42eGbUaDkcsZa6A2rsIbD9eREekASWBVJgHoxgPzdYXCsg53X6CZ/daaQ2Qn8w2itu+OIKRISoijAw6zRQRlKcq7CQ+F2F0DMpBIud77POerfZRYX+SWSOgmFO3BdtgLktQn38GAC6iH1bKTCcuWnW6445fPmpsE7biigo5YrvT22AeTBC/dyHNQ+XMas3K8C6TznqmUS/bGPIGRP19zagjLx1HiANdznqmUS/XMPkMiZquwswtzGVXGnY4CipSeDNciaXEUHvdxDjfVF3tgdIx2J1HpRVYaUK58GkMiZoowsoWZyb01KtNqrUC1SgrJNMJmNivmoITBbn7rTsA/6a7RTQDiaTsfjpM8wH5ZI4ppojPZOW4dLlmsp0j2yOGSH/5AGYLM7d6am28XTpBcqXtILJZETITo/A/CtTzZGeao3GKb1A7YYsZjK5ErHMIyj7sJSQxbmzanSv8a1OymUSGRFxrQ9g9nFQWle6Tk07FMIWrdHpcCWWpQWVP/QJzHcDbMNfRP5ZpF1GONUq5XQ4ewQ6LRelfuQTUNmW34iiyoWDBH5coE4Wb/TlRThhpqY9x0H556jqqsSXWa8urLc01aPt6vk0xwB5yMtWlxwdF+Wy3OjsQ4o2EX4BQuSK6J4XgzHZrwOzJY6zPtJ4EPmfLLqzZjzapx31R6aDkjF1UzRloWg/GHXRbTEO03EYd2AS0f6fLAHly6Ef2MTAzCnCFCJQ7UyCcm2M6c/AdCHQH2dAtB+M+8wQAzP7RHssRbcJMNt4LeaMiXYO+X2vVZ6MrR/TA7HKQgTlR0xhSz/mJgamO7GeCpljLmYq36X1PTM/TXGYK/dJrM6QgbmZqXyX1mquvEldWBH11UU+CVWcAcOHxfmP9B6YFpGDbN0dYvJMI1RThqxyFuc/0PvKtPDqCKP3/bF8TJ5phHo1Q8Bkcf4DvSemrWDHxSHe8zONSA9k0Ln+EdPbfs/PAO+SnEaPP/icvdnJ4twXvW13SfK+8un0eNkjsKRz+H5iTHazOPdEb9t95SoSx9sMSqNgWSr33nVv3CuaXmVx7sktZxmJo4FjF02jhQmY5AFMC1zqamZxbkRzdfBpg16gXEYTDEyjDWutpuHAPSwI2Rxjmo9ZRntDYezjY7qErpb5vyL/1me9O1icW9KmBLibtLuh39MRatEk0u/C3M+NA0EPszifRpM1bhGFk3E3gBxOoFgf1KYwcIh/sTi/x/BJOin9sT21QvT7pxYHSX0UBhfDEdLrWZx/T4uTloYPuaFcxcKO4zk/UnfUQNIWdnwgUf+vLPa318SI5gXEtql0urHf6Gy/aBLp7wDGJ3LmJ4PvLdRmjLbEiOZ1RivbiJ75XsxAeR/E+Ltez0MMsA31OFr6YIzobnyWpIr8NuLl9N0IEOg3MupvkCd++WxHGQarJiZ0HzZao4ElcOrmJTEC5gNZxr1/HgOaVxmfV44H3ract+TEKVhgtnraa0a2Wlxh8nEKEZgXpsXDNHxodNoyd06cghfjU8BZwsuDHRwEgVOIwFTen06vDy7Bg8Nxss45ZQyYaitPrZ+Hz/Ax0ZxCAGUjcHXGF9Mjy937mZycAgTmibS28WAec5iNIE4hGD1yAqcwnYra4jhFySk0YKopyPZ0K6pUwRB4zzmnNLFURLZQVAVRYQ8HduUUAI6U9O0NqkK1GV2ivYRJzMkHhooJt6wNsuJeVNrGZObkAz+7Qgl1Ca6pdM1yJjUnD9gpIdyyLowXKF2zm8nNyQNuDgSqW9pY6BPgnFVMck6GmJkMHTNkcQeHxebkxTZ5PewXFZLZoCYmPScHrDSSbTpFmXhhM1l5xIYQJzuDZyjjkV3IJnU2hDg5GTwDGV02ieAIygXQyEPBiWCjAcbO2IwYyXLZEtEheEaIk5oPVzZIy0w2pC9UHxWnXBXhJ2Z05wPmQK/wdl9OZDvucFZIUOIWmOJTL2ILyqXknJ7mbGpYkuib5R6frUHcnnq7PcYIeDpfuzZH5Cf1ZxBmpV7LC7XnVlu9S1zLx4fWpIeLsWun2/uc+mBXZtiHJ236YEJP6Y9+2IKeqz3t88676xoaztpdtWQu/YKXZfPi3nMif6iyRflykc9q1+aJ/LXIr0GvOUnKHqP1iXxR5N0oWyjyVZE/QGS741q98toekd+R7TJpp9P7nPrg0j+nPtj23YSeuEfvgyNdHMZObr85peyMrNxRi0ae8dJIxEr62qF8lsiXRF6kXV8nQ/dpgzHb5vnzkhvgfxmLcisplwO+Er8flnHSSdmACm7l1k679zn1wa3MoQ+2fTdpJ6TBNQ2YtnTxwIyK8rI1ga2PmLJ1cc8T+OK6wKnKtPJ2OPOfpmWSaCJ/DtFbha8936J+GZD/efL/UflODXyPWzwnRell9XG5tdPufU59cCtz6INt3w3oOR+ge0YDphFdHNS3ylxRhCdNtmhKXxeIKAm6FdNYisgPiXxL5BdQ9g2NQAfi3UHeYMONL9FIYhisw+BCinPka8/tRJ3rTNrp8j7bPrj1z65Op767tVP8Pgbdc5UGTFe6aO/fCGN3KqeC/GI+fcrrYg98uQ2EyHtJ2d2vHK6JwzimZAEGdbFW1wZ50oTFQL+EAbqlczdyn6xzUILHrZ0u73Pqg22ZS52ufbeh52aR38JvHZhGdCFemKmcPeFENHqTOr/FLvA7xNIsjZCr8PslTYQ9JfIh/JZnNT5JyqTS/rRWtxR3j9i8dzEGNF8zABaQ/z8mbbFtp9P7XPpgW+ZSp23fXej5DcS+5KLXAcAuN7po5Q1EGrbmsn+rjfg4GyzK5dk5rxA3zYgirPi7DHpeKdwbcpDWomw3RNYsAGqQBmWA62PEoV3H6OAS6/gcROwStGWeWzud3ufSB9sylzpt++7WTlLHKhsPyD100UA5Eci+8CzzcU7qYh2W/F58xUcsxPGzIn8p3SmaJSqfewuK/GkpBrXnHqX3W4DliE3ZOgyO5DTLPbTT6X2WfTAos6zTqe9u7ST3SQ/Edg90aSScMhmlmQHFOfno6dwbu41Ep2yPYgc3kQ5y3M3cYShTM75aKEPW+iRxwhfy8GflOBUQ57kE5sY4dHopccJfyAnnbLxAWUJm8EZitTDHovO1DImsYRrDsWYaEBe9RFzs4LDaMzoercQd1JvVc98ZIkiSKNi9HO4w4/QvgtNeeU06mEH8SJxGst1zhDe4ZYzuDUR0D3O8fWsiFZM9RCmsNeR96+Hp+AeIpDrBGwrdibaFbA0e5ogfoUqnMSwmYdFtSLxycnZ6Cpy0kimTFk0ribGpghHwoQ8+idlMdKApKOks3v2J7Umiw69nLpk+YQthKU6QY4TbWCcy0tl3EbVIftivx94NFJIo6iGiaAyuJib0dDoV4cMdI7Tq5Zim4RO+VrPeJ3DGenXM6VKFc3RGNd28nlGTeYD2EJdHCgZTU8zo0AgATmkckqd5s0DEt5GFIcrN1BFVLgrumCRuH2XUdLDIzr7BKsCi1q/IYKUQWymJBQqJHAdjK/pHueMZ+H55CWEODKLcs7Nf07eUwfQ2xF9BDnxoddAbh7V+jOJ6Lbt9chOgCfhCuzUrVblPvgI3bZpp9xP8jWsAuJPE70iNvC7MbzMYIwbSWgCx32LglZ52ErqaFI8rgl7tBP9iLdSOTsxTj1m0JYWoGkm0o4BHMT46aZ0LUHXQnoIF3IXcgef13A5Vogv3D0DXnXCofxLtSIIrVvIocaIWfjNmTLoAxGEXwHrNIzBYuvAeBiGntNSAclj2dZhr3oiVOUmbvAn3NOGZahbHZun/p9FTJQoe2agAAAAASUVORK5CYII=" } ["msg"]=> string(7) "success" }
     //   var_dump($res);exit;
      //     $img=$res->data->signature_img_base64;
//         file_put_contents('haoshi.png',base64_decode($img));//保存签章
//$img='data:image/jpeg;base64,'.$img;
//
//            echo '<img src="'.$img.'">';

        //{"code":1,"data":{"signature_img_base64":"iVBORw0KGgoAAAANSUhEUgAAAIYAAAA8CAYAAACjKMKCAAAOUUlEQVR42u1deVBV1xlnxnFsmqZpJk2TpmmaLmnamU6aNpM0k8k4TtqMTdM0zThpjYobj014gD4VF0REFEURlVARFYkb+yL7vm+yCAoIKhKDgvDeY5NFhJrb+7vxvDnvcu+79\/GEZ+H+8c273HPuWe73O992vsux6R9lVLUX6yvKKyoGWOovLSvrLysvV2gWEXgO3gMDwAIwYYOLysrKu223u\/w69P0b7\/TcXa8dGFWz5KrQrCA1eA7eAwPAAjBhU15ePtbepfcCSihy7B154KrQzCfwmuY9sABM2LCiZEDXN+JBCvQ9vRVanW6ApX6FZgUNgOeE\/8ACMAFg9Hf3DWkIKLq7u+\/qB4b9eofub1Ro5hN4zfH8ITiABWCCAwb0TO\/wAyedTjemH7zHVysKzXACz8F7YABYoIHh0jfyQA2x0jc05q68rNlF4DnHe2CAxQINDM4Qgc6BeFFe1uwi8JzjPTDAYkEBhkIKMBSaQmDc0g6obGxsGD7RdY5\/eY55+WevMPPmzWPefOttpqz6kqH8WnuX6u+ffMqVfeeJJ5h\/LvqMab2tNTwr1PacOXMMz9+806v660cfc89+98knmUX\/Wmz0vFT\/NEWcjZkw9ju9wyo7B2eubTyP8aFPueOT6t\/S+UmNT2p+YvyzGBhRCSncYMTK80urmLfefodpuHZTxbanCjt5hnntN7811J+\/4H0m\/HQUgzIQrhe8\/xfR9iLjzjMbNnsZyrd4+zKbvHwMz4eGnzIql+qfUFNru+q9+QsmvDgnV3dm\/6EQQ\/se6z1Nzpc\/Prn9T3Z+cscnNj8x\/lkMDC8fP2ZP4CHRiX62eAmz03+faPncuXMZOfe4wbIT\/93rv2c69IOGewBRZV2Tob5+aFz1wcIPGbn9E\/rzBwuZmoarE17cD555hnvpdPtYmXLHJ7f\/yc5P7vjE5ifGP4uB8Y9PF3Er\/PtPP81876mnGCCWLn\/ppy8zFbUNoi+GSAxa3L373nzB+geCjxitFhD6pF8MuSe3f\/JyAoKCGSLaTdXV3r2veu65H8ken5z+LZmfnPGZmp8Y\/ywGxvPPv8BAvBHEHww5ynhu9Wbo1Z+QmsW8+uvXDDYErQOrLjUzQD3RbbjGPaG+sJogks2ROFL9Q9TTqksKGJgrPT854zPVv6Xzkxqf1PzE+PfIvRI0js7ogcA4gpGDMqwKiFdSDsNq195Ag46E2AWK+e0CLDDc+Pf5hh7\/xZnqHyL7jT++ycAAlgOMtk69CsYfxLXc8UnN39L5mRqfufOj+Tcl7io9GYgniDe6Y1oH4poWlbjGyuK3CT3IV1NiYpW+Z6r\/JbYrmdNR8QyfkYIhYvZl\/\/vzZRM8HjnjMzV\/S+dnanzmzI\/PP0FglJSUfIONk76RcY1Wq\/1Gf3d4l1gjzz77QyNjCS8BYpP8TRtKQozngwDlcL2EbBlY0EJGFVw28ndX\/z3O+pbTv5CbJuRuYyVi1fPFvJzxSc3f0vmZGp+c+YnxDzwH74EBYAGYMAsYrh4aBi4VUQU+fns494k2JkGkHCuDFpmOa9RMcOhxTvyh3H9fEGPv5DLhBb3y818wLTc7J\/SPvr19dxvaR1tw7+T2LyT66b8zC0o5Y5gWx0IkNj65\/U92fnLHJzY\/Mf6JAgM7atwGClvIihVPsY6A4JV2DlxwBYYjJiEkJmEpow5sCrhN9PMAB8pAAAXuCelVvnUOwsuEZ0OeX\/jhRxOMO1P9S724F3\/ykqREMTU+uf1Pdn5yxyc2PzH+geffAoPbRFMbgIHUrp6h0fUPJcZuJUw8y7bdWZ6D98AAsGC2KlFoxgLDMhtDodkFjHHKxhg3ZWMoNGN3V2FjjFM2xrhZxqdCMxoYxsanko+hkMUBLoWmjrr7xxwutfR4XL7Ss\/Z6+6CL1W0MRWJYl77qHnaOS27x2+GXf26VXWy17cqYS+s3pSefjb28R0ntm6VU06TTeG7NTFq6PKrxc9vIZpqWLY9qSM68vkMBxgynrII2r9ik5l2ttwZdyb2j4TWHCBDc16dm7D9Udiw4tDLEwSmhnLunSclsah1wM6efnmHGPuJM\/X6v7bmxIaFVIZBI0w4MObt3k6WpbHs6Cate45mWamcff2HFqpi6jVsyzze29HkQiREUXH70TFRDAA2ApPSrOxydE0qX2EY1hR6rPmwOKEJP1BxesTr2Iid1VkQ3bPPJiZYClwKMaaTM\/LZtruuSs1eujqnlq4mzsY2S9kNYeM1B1FWvTcmtv6JbK1cqrbaPrzLqb1nkFQCzrLpj44xQJf+PwOgZ\/q99fmn7FjDTdkXUJT4giO2QknN9u1RbV671ubu4J+cuWR7ZeCSsOljSXmno1kBVbd2eEydkszi7JhZCEkGqKMCYZoIkACOFAAGyc4yvlMNkQsciaoOw4l09kvPgyorVK7lwy9NpTWKxuyY1E8w\/EFx2dPnqmIv8\/lWO8RVCoJwWVVLffIMh30Zge\/lP77zLFJbXTKh3NiaRef2NP3BbwMhSwvcmF5uuC+Yrok18q2EqQ9360oKx999ffFIIEFitu\/cVRbR1DJsVn4AtwkqfHLQBw1Q3+MBBSLKs3ZCaRvpyUScXAKDhp+oOECOWJtggVgEGvqM4cjzCkG+AxBU6y4sksaIeEk\/ob0yQwMJvG6B64ccvGmWXP26AIL9gBi0xXN3P5wUeLgtrae1zm2z7IWEXgiFpwk7WHkQQjC67eWd4jZdPdqyQZEJ9xEcIsEBr1OcLiypvbbK68YkPX5CFxE9y\/eWvXhXNDqfbzsgr5lLdken1OMYg9uwvDtd4ZqTiGvc6e8ecvjha9cV2n7xoMBRRTEv7gdSA3SIUKfXzLzwFVSMkpeABBQSVnUjP\/8p7w5aMZBil8SktO63mlYDhyDyCikAGMjKZ+fXk2A+oA0mB7yQgXcQypqabbutGnSDWHZwTy8TEM39lywmHg4H7DpYel2OYgg6GVIbCHRWzZ0AwQuEen466FFBYfnuzVd1VqAN8v0kYiV9+PdSRIzFInb99\/AmD\/ERrg4Lo86W2UdwqhdpAnKC5bUA9mfbau0ed03NveG\/xzk7ACkebCI1LAQtqgsQqpAjgiU5o2mX1OAYMyZILdQb7ANKDXy8kLJwzPPGBDEmHF7MxQM1tt7kvrvBrTVsC6oHEBjy9MhMv1N3ZIGQQShGMUHgPm7dlx9OuJZgttUdyLqHJf0KsQoQAXKgTuNHTCgyhD2RiktI40Y8y2BL46AZp67A16HoAAr7GgucCMC1eulzUKwFBYkByWAsYCGW7uJ3Pfxh0yjHlPooRjEXEGgAI2jawd44v899XHCEVoYR0QURUDijQvhzp88iAkVtSyTEH32jSK3ymE9QIrH28dO8duVHmAuJc9OW9nl5ZiUQNES8BKxptS7VRUtPh6aJOKpAFCpYg0eh9mSkHBlxPGJb4hbs5W4ABoxM7oXjpazempcuJSWADCxta8FxoQxEBq0MhFUfkei3wTNCnXFC4rUvJMkeiKburFhJWPV687aroelMGHSQEYhrYHUV+BQ0I7IGY48ZCvWzalpUgFxSQQpAuyrb7NBJWIQkYQS0AAPw6Fxt16yAh6L0SBLogOUypDEiFito7Ru++s3fMcfvOvHNisQo+IdIJO0TJx7ACIWYBRmFLHJ6FmFRBHRir2FI3laoHcB0+cuE\/8DQQJaW9IERMEZzCxpsUKJD9BY9FSdSxotRwW5eaDWYgeIRoJ997gf0At5NfxjdmkUjj7JpUTAxS7LWQcmyT7woo+hLtIKAmtH1PCOoKG25KBpeVCWFvEn9AxNKcZxH7QJYW7XYCGJAucGXpZB30gV3TyMSm3VBFDmsSSoSim0j2EdpOV4AxzYRIJ3IliIci5xlsXiGMzY9DIKUP6okvXQAEAj6oGUiExPRrvjBo6VjFzt0FZ2CLKDmfj4utcaz6MHFBTUkN5EogVuG4JtFotSOrCnsYQvYHglLYoKONTqiLPYEl3KYYFyCDAcy6z4\/iUwMFGI+QEMcgkVCsYqE6cEvhkdCAWLcxPY2fFCwU\/4BnMyHEzRq8sGtOnL4YCDsGWVuUB+OEvRcFGI8BIcsbHgP2JIQ8FMQyyKpHdhVUgZzAGFSV4H4I2xbC51AzRgnArPSCGz3ZbX4FGFOxf6JOLiBSg28AAgRIlkEehCk7AM8hskqu03JubCdxEIAO8QnETWCL5Jd\/vZlWH3SW14mIuqBHDQzlWIpJEphBEmNikq\/4mfs8MsDhpYCISoCRuso+rgoMR44G7ApTm2Gcl8R6NkhCJp8mmEMszz3EjqUwHGTTO3x\/q8Jw86QGwtDEoJTrNsI+ABiIxIGKgKEKYEAFARBIrsHfUm0CDJyXxKoaGLRmf7v67UE299lrR6ODbJSjryyjuKTGMLVbYutaTXJjWubVL0zVvdzS7Xv8ZFXkNp+scjxDntu1Nz83r+hG0GTHcCKi+hzaCggszOjUD2025+irLpGjrwa6eobow\/IqlcPyzKNurXbg5s2O+21tHePt7Z33hOvo+js6uoZJPUK3bnWOdHV1D1o6BrRN2sR4zDwsr9LwD9xYLHCH5T08XtNIfUCtKEdPyif94Jj6dFR9sJ19zFU7x7imhJSmQH6dnKI2PztVbAvqqJziG\/z3FcRl5N\/Y3akb8bC0\/66eUfdtvtlpaNveMa7x8lX9JtnPs7w22rvp1OJ4zftCB\/JqlAN5zaf65q5Nmg2pBavtoq96+2Ynf9054EGXX27Revr65yX47cmPyypo9evUDrlb2meHdsQtKe1KwPrNaXmrVDHN6PvAwdLTHfoRNzMP5NWQA3kryIG8QEltXX0ljm9mbypHWltARUUlQzk5ReM5OYXjJSWlg1PZF9rPyyseJf3ht6CgeGSy7YH33BHeLBaAif8BT5+mMt5kR6YAAAAASUVORK5CYII="},"msg":"success"}

       // $res = $fdd->addSignature('45448C4B17E69C0B744F51D79A7BC10E', $img);
        //{"code":1,"data":{"signature_id":"4587629","signature_sub_info":null,"status":null},"msg":"success"}
        /// //获取签章id
        //$signature_id=$res->data->signature_id;
        //var_dump($signature_id);exit;


        $legal_info = json_encode([
            'legal_name' => '宋洋',
        ]);

        //获取企业实名认证地址
         //  $res=$fdd->getCompanyVerifyUrl('45448C4B17E69C0B744F51D79A7BC10E','www.baidu.com',$legal_info);
//{"code":1,"data":{"transactionNo":"0f3b50e434bd4b7886b3e1b8044aa7a1","url":"aHR0cHM6Ly9yZWFsbmFtZXZlcmlmeS10ZXN0LmZhZGFkYS5jb20vZmRkQXV0aGVudGljYXRpb25TZXJ2aWNlL3YxL2FwaS9zeW5zQXV0aGVudGljYXRpb24uYWN0aW9uP3RyYW5zYWN0aW9uX25vPUI1QjMyREZENDk4MDFGNkI0Q0VBNzdGM0IxMjk3RDZEN0RERUEyMTRBQjNCOUMzNzhBODFFNzM0QTgwMjU1ODE3RkYyMTQxMjQ2NDU2NzQ4JnNpZ249UWpCR05VRTRRVGRGUkRjME4wTXlPRE5GUkRBME5EUTVPVUUxUlRjeFFqQTNOVUl6T0VSRk5BPT0mYXBwX2lkPTQwMjE4NiZ0aW1lc3RhbXA9MTU2MTk3Mzc1MTg2OA=="},"msg":"success"}
//     //  //跳转认证页面
        // $url2=base64_decode($res->data->url);
        //header("location:$url2");

        // $res=$fdd->applyCert('44DE0126161A1293FECB09EF1E4424AF','12345678944');
        //编号证书申请
        //verified_serialno String 是 实名认证序列号 获 取 实 名 认证 地 址 时 返回
        // $res=$fdd->applyNumcert('45448C4B17E69C0B744F51D79A7BC10E','0f3b50e434bd4b7886b3e1b8044aa7a1');

        //企业查询认证
          //  $res=$fdd->findCompanyCertInfo('0f3b50e434bd4b7886b3e1b8044aa7a1');


        /**
         *
         * 业务逻辑
         *
         * 各种信息实名认证完成后实施
         * 合同模板上传--->印章上传/自定义印章上传------->模板填充--->企业方自动签署----->客户手动签署---->合同查看---->合同归档---->合同下载
         **/
        //合同上传
        $doc_url = "https://docs.qq.com/doc/BqI21X2yZIht1Wa5TS2EGjbB05QQLU3t4SFA0D4QyU1D8UAQ0IQmKC2Cjyb92Epx2w1QB96n17FEoB312GIY0?opendocxfrom=admin";
        //    $res=$fdd->uploaddocs('123456','劳动合同','',$doc_url);
        //
        //模板上传
        //把文件转成文件流
          $jrhb_temp='http://jrhb_admin.test.jiarixiaodui.com/jrhb_temp.pdf';
          $file1='http://img.jiarixiaodui.com/test/moban.pdf';
     //     $res=$fdd->uploadtemplate('1234567899',$file1);
//code: "1", msg: "上传成功！", result: "success"}//code: "1"//msg: "上传成功！"//result: "success"


//模板填充
        $parameter_map = json_encode([
            "parent_name" => "1133",
        ]);

     //   $contract_id = '12345678955566';
        $contract_id = '12345678955567';
    //   $res = $fdd->generateContract('家长协议', '1234567899', $contract_id, $parameter_map);
       //{"code":"1000","download_url":"https:\/\/testapi.fadada.com:8443\/api\/\/downLoadContract.action?app_id=402186&v=2.0×tamp=20190701173849&contract_id=12345678955567&msg_digest=MTZEN0RBODU3OEEzQTYwODBCMTQ3QjQ4NzNBQUFCRDNEOTA5MkRFRA==","msg":"\u64cd\u4f5c\u6210\u529f","result":"success","viewpdf_url":"https:\/\/testapi.fadada.com:8443\/api\/\/viewContract.action?app_id=402186&v=2.0×tamp=20190701173849&contract_id=12345678955567&msg_digest=MTZEN0RBODU3OEEzQTYwODBCMTQ3QjQ4NzNBQUFCRDNEOTA5MkRFRA=="}
        //自动签署
   //     $res=$fdd->extsignAuto('6543210',$contract_id,'45448C4B17E69C0B744F51D79A7BC10E','家长协议','签章','1',2,'2');
       //{"code":"1000","download_url":"https:\/\/testapi.fadada.com:8443\/api\/\/getdocs.action?app_id=402186×tamp=20190702093138&v=2.0&msg_digest=NUUwMjMxRDczQTUwOEVERDA1RUI5NUE0MUIyMkMyMTgxQTJEMUZFNQ==&send_app_id=null&transaction_id=6543210","msg":"\u6587\u6863\u7b7e\u7f72\u6210\u529f","result":"success","viewpdf_url":"https:\/\/testapi.fadada.com:8443\/api\/\/viewdocs.action?app_id=402186×tamp=20190702093138&v=2.0&msg_digest=NUUwMjMxRDczQTUwOEVERDA1RUI5NUE0MUIyMkMyMTgxQTJEMUZFNQ==&send_app_id=null&transaction_id=6543210"}
        //同一个合同双方签署交易号不同
        //手动签署
         $res=$fdd->extsign('jy222344',$contract_id,'FA400623EA4C94F53314D3CF11DAE98E','19965412404','家长协议','http://www.baidu.com','签字','2');
    //   file_put_contents('tt.txt',$res);
  //     echo $res;
//exit;
        //合同查看  直接打开页面
       // $res=$fdd->viewContract($contract_id);
//         echo $res;exit;
        //合同下载  直接下载
        //  $res=$fdd->downLoadContract($contract_id,false);
//         file_put_contents('ht.pdf',$res);
//        echo $res;
//        exit;
        //合同归档,表示合同签署结束
       //  $res=$fdd->contractFiling($contract_id);


        /**
        *公司签署流程，
         * 客户在平台注册(姓名，身份证，手机号)---->三要素验证----->获取客户编号------->进行存证------>合同签署(短信验证)----->签署成功结束
         *
         *
         **/
        return json_encode($res);
    }


    public function actionHello()
    {
        var_dump(mb_strlen('3a9a7ef351c7676beb058084f9a158c2'));
        exit;

    }

    //签署合同后跳转
//页面跳转规范（return_url）

    public function afterContract()
    {

    }

//签署结果异步通知
    public function notify()
    {

    }

//实名认证异步回调
    public function verifyBack()
    {
        $appId = Yii::$app->request->post('appId');
        $serialNo = Yii::$app->request->post('serialNo');
        $customerId = Yii::$app->request->post('customerId');
        $status = Yii::$app->request->post('status');
        $statusDesc = Yii::$app->request->post('statusDesc');
    }

//实名认证同步回调
    public function synBack()
    {
        $personName = Yii::$app->request->get('personName');
        $transactionNo = Yii::$app->request->get('transactionNo');
        $authenticationType = Yii::$app->request->get('authenticationType');
        $status = Yii::$app->request->get('status');
    }


}
