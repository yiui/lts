<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/6
 * Time: 21:55
 */
namespace common\components\Qiniu;

// 引入图片处理类
use function Qiniu\base64_urlSafeEncode;
use Qiniu\Processing\ImageUrlBuilder;

class DoImg {
    public $imageUrlBuilder;

    public function __construct()
    {
        $this->imageUrlBuilder = new ImageUrlBuilder();
    }

    /**
     *
     * 接口	简介
        imageslim	图片瘦身（imageslim）将存储在七牛的JPEG、PNG格式的图片实时压缩而尽可能不影响画质。注意：该功能暂时只支持华东 bucket。
        关于 图片瘦身（imageslim）接口的详细信息请参阅图片瘦身 (imageslim)。
     *
        imageView2	图片基本处理接口可对图片进行缩略操作，生成各种缩略图。imageView2 接口可支持处理的原图片格式有 psd、jpeg、png、gif、webp、tiff、bmp。
        关于 imageView2 接口的详细信息请参阅图片基本处理 (imageView2)。
     *
        imageMogr2	图片高级处理接口为开发者提供了一系列高级图片处理功能，包括缩放、裁剪、旋转等。imageMogr2 接口可支持处理的原图片格式有 psd、jpeg、png、gif、webp、tiff、bmp。
        关于 imageMogr2 接口的详细信息请参阅图片高级处理 (imageMogr2)。
     *
        imageInfo	图片基本信息接口可以获取图片格式、大小、色彩模型信息。在图片下载 URL 后附加 imageInfo 指示符（区分大小写），即可获取 JSON 格式的图片基本信息。
        关于 imageInfo 接口的详细信息请参阅图片基本信息 (imageInfo)。
     *
        exif	图片 EXIF 信息接口通过在图片下载 URL 后附加 exif 指示符（区分大小写）获取数码相机照片的可交换图像文件格式。
        关于 exif 接口的详细信息请参阅图片EXIF信息 (exif)。
     *
        watermark	七牛云存储提供三种水印接口：图片水印接口、文字水印接口，以及一次请求中同时打多个图文水印接口。
        关于 watermark 接口的详细信息请参阅图片水印处理 (watermark)。
     *
        imageAve	图片平均色调接口用于计算一幅图片的平均色调。
        关于 imageAve 接口的详细信息请参阅图片主色调 (imageAve)。
     */


    /**
     * 处理图片，图片格式转换、缩略、剪裁功能
     * imageView2 提供简单快捷的图片格式转换、缩略、剪裁功能。只需要填写几个参数，即可对图片进行缩略操作，生成各种缩略图。
     * @param $mode 0
     * @param null $w
     * @param null $h
     * @param null $format
     * @param int $quality
     * @return string
     */
    public static function imageView2($mode=2,$w=null,$h=null,$format=null,$quality=100){
        //mode 模式取值介绍，所有模式都可以只指定w参数或只指定h参数，并获得合理结果。
        //0   限定缩略图的长边最多为<LongEdge>，短边最多为<ShortEdge>，进行等比缩放，不裁剪。如果只指定 w 参数则表示限定长边（短边自适应），只指定 h 参数则表示限定短边（长边自适应）。
        //1   限定缩略图的宽最少为<Width>，高最少为<Height>，进行等比缩放，居中裁剪。转后的缩略图通常恰好是 <Width>x<Height> 的大小（有一个边缩放的时候会因为超出矩形框而被裁剪掉多余部分）。如果只指定 w 参数或只指定 h 参数，代表限定为长宽相等的正方图。
        //2   限定缩略图的宽最多为<Width>，高最多为<Height>，进行等比缩放，不裁剪。如果只指定 w 参数则表示限定宽（长自适应），只指定 h 参数则表示限定长（宽自适应）。它和模式0类似，区别只是限定宽和高，不是限定长边和短边。从应用场景来说，模式0适合移动设备上做缩略图，模式2适合PC上做缩略图。
        //3   限定缩略图的宽最少为<Width>，高最少为<Height>，进行等比缩放，不裁剪。如果只指定 w 参数或只指定 h 参数，代表长宽限定为同样的值。你可以理解为模式1是模式3的结果再做居中裁剪得到的。
        //4   限定缩略图的长边最少为<LongEdge>，短边最少为<ShortEdge>，进行等比缩放，不裁剪。如果只指定 w 参数或只指定 h 参数，表示长边短边限定为同样的值。这个模式很适合在手持设备做图片的全屏查看（把这里的长边短边分别设为手机屏幕的分辨率即可），生成的图片尺寸刚好充满整个屏幕（某一个边可能会超出屏幕）。
        //5   限定缩略图的长边最少为<LongEdge>，短边最少为<ShortEdge>，进行等比缩放，居中裁剪。如果只指定 w 参数或只指定 h 参数，表示长边短边限定为同样的值。同上模式4，但超出限定的矩形部分会被裁剪。
        //在w、h为限定最大值时，未指定某参数等价于将该参数设置为无穷大（自适应）；在w、h为限定最小值时，未指定参数等于给定的参数，也就限定的矩形是正方形。

        $str='imageView2/'.$mode.'/w/'.$w.'/h/'.$h;
        if ($format){
            $str.='/format/'.$format;
        }
        return $str.='/interlace/0/q/'.$quality.'/ignore-error/1';

//        /format/<Format>		新图的输出格式
//        取值范围：jpg，gif，png，webp等，默认为原图格式。

//        /interlace/<Interlace>		是否支持渐进显示
//        取值范围：1 支持渐进显示，0不支持渐进显示(默认为0)。
//        适用目标格式：jpg
//        效果：网速慢时，图片显示由模糊到清晰。

//        /q/<Quality>		新图的图片质量
//        取值范围是[1, 100]，默认75。
//        七牛会根据原图质量算出一个修正值，取修正值和指定值中的小值。
//        注意：
//        ● 如果图片的质量值本身大于90，会根据指定值进行处理，此时修正值会失效。
//        ● 支持图片类型：jpg。

//       /ignore-error/<ignoreError>		可选
//        取值：1
//        ● 未设置此参数时，正常返回处理结果。
//        ● 设置了此参数时，若图像处理的结果失败，则返回原图。
//        ● 设置了此参数时，若图像处理的结果成功，则正常返回处理结果。
    }


    /**
     * 图片高级处理
     * imageMogr2 提供一系列高级图片处理功能，包括格式转换、缩放、裁剪、旋转等。
     * @param bool $thumbnail 参看缩放操作参数表，默认为不缩放。
     * @param bool $gravity 参看图片处理重心参数表，目前在imageMogr2中只影响其后的裁剪偏移参数，默认为左上角(NorthWest)。
     * @param bool $crop 参看裁剪操作参数表，默认为不裁剪。
     * @param bool $rotate 旋转角度，取值范围为1-360，默认为不旋转。
     * @param bool $format 图片格式。支持jpg、gif、png、webp等，默认为原图格式，参看支持转换的图片格式。
     * @param bool $blur 高斯模糊参数。radius是模糊半径，取值范围为1-50。sigma是正态分布的标准差，必须大于0。图片格式为gif时，不支持该参数。
     * @param int $interlace 是否支持渐进显示。取值1支持渐进显示，取值0不支持渐进显示（默认为0）。适用jpg目标格式，网速慢时，图片显示由模糊到清晰。
     * @param int $quality 新图的图片质量。取值范围为1-100，默认75。表示强制使用指定值，如：100!。
     * @param int $sharpen 图片是否锐化，当设置值为1时打开锐化效果。
     * @param null $size_limit 限制图片转换后的大小，支持以兆字节和千字节为单位的图片。
     * @param bool $auto_orient 建议放在首位，根据原图EXIF信息自动旋正，便于后续处理。
     * @return string
     */
    public static function imageMogr2($thumbnail=false,$gravity=false,$crop=false,$rotate=false,$format=false,$blur=false,$interlace=0,$quality=100,$sharpen=0,$size_limit=false,$auto_orient=false){
        $str='imageMogr2';
        if ($auto_orient){
            $str.='/auto-orient';
        }

        /**
         * $thumbnail[
         *     type=>处理类型，必须，以下值必须跟type对应
         *     value=>参数值，可选
         *      w=>宽值，可选
         *      h=>高值，可选
         * ]
         */
        if ($thumbnail and is_array($thumbnail)){
            $str.='/thumbnail/';
            switch ($thumbnail['type']){
                case 1:
                    $str.='!'.$thumbnail['value'].'p';//基于原图大小，按指定百分比缩放。取值范围1-999。
                    break;
                case 2:
                    $str.='!'.$thumbnail['value'].'px';//以百分比形式指定目标图片宽度，高度不变。Scale取值范围1-999。
                    break;
                case 3:
                    $str.='!x'.$thumbnail['value'].'p';//以百分比形式指定目标图片高度，宽度不变。Scale取值范围1-999。
                    break;
                case 4:
                    $str.=$thumbnail['w'].'x';//指定目标图片宽度，高度等比缩放，Width取值范围1-9999。
                    break;
                case 5:
                    $str.='x'.$thumbnail['h'];//指定目标图片高度，宽度等比缩放，Height取值范围1-9999。
                    break;
                case 6:
                    $str.=$thumbnail['w'].'x'.$thumbnail['h'];//等比缩放，比例值为宽缩放比和高缩放比的较小值，Width 和 Height 取值范围1-9999。宽缩放比：目标宽/原图宽   高缩放比：目标高/原图高
                    break;
                case 7:
                    $str.='!'.$thumbnail['w'].'x'.$thumbnail['h'].'r';//等比缩放，比例值为宽缩放比和高缩放比的较大值，Width 和 Height 取值范围1-9999。宽缩放比：目标宽/原图宽   高缩放比：目标高/原图高
                    break;
                case 8:
                    $str.=$thumbnail['w'].'x'.$thumbnail['h'].'!';//按指定宽高值强行缩略，可能导致目标图片变形，width和height取值范围1-9999。
                    break;
                case 9:
                    $str.=$thumbnail['w'].'x'.$thumbnail['h'].'>';//等比缩小，比例值为宽缩放比和高缩放比的较小值。如果目标宽和高都大于原图宽和高，则不变，Width 和 Height 取值范围1-9999。宽缩放比：目标宽/原图宽   高缩放比：目标高/原图高;
                    break;
                case 10:
                    $str.=$thumbnail['w'].'x'.$thumbnail['h'].'<';//等比放大，比例值为宽缩放比和高缩放比的较小值。如果目标宽(高)小于原图宽(高)，则不变，Width 和 Height 取值范围1-9999。宽缩放比：目标宽/原图宽   高缩放比：目标高/原图高;
                    break;
                case 11:
                    $str.=$thumbnail['value'].'@';//按原图高宽比例等比缩放，缩放后的像素数量不超过指定值，Area取值范围1-24999999。
                    break;
                default:
                    $str.=$thumbnail['w'].'x'.$thumbnail['h'].'>';// 9
            }
        }

        $str.='/strip';//去除图片中的元信息。去除的信息有：bKGD、cHRM、EXIF、gAMA、iCCP、iTXt、sRGB、tEXt、zCCP、zTXt、date

        //裁剪操作基准点
        if ($gravity){
            $str.='/gravity/';//在图片高级处理现有的功能中只影响其后的裁剪操作参数表，即裁剪操作以 gravity 为原点开始偏移后，进行裁剪操作。
        }elseif ($crop){
            $str.='/gravity/Center';
        }

        /**
         * $crop[
         *     type=>处理类型，必须，以下值必须跟type对应
         *      w=>宽值，可选
         *      h=>高值，可选
         *      x=>宽偏移，可选
         *      y=>高偏移，可选
         * ]
         */
        if ($crop and is_array($crop)){
            $str.='/crop/';
            $crop_str='';
            //裁剪操作参数
            switch ($crop['crop_type']){
                case 1:
                    $crop_str.=$crop['w'].'x';//指定目标图片宽度，高度不变。取值范围为0-10000。
                    break;
                case 2:
                    $crop_str.='x'.$crop['h'];//指定目标图片高度，宽度不变。取值范围为0-10000。
                    break;
                default:
                    $crop_str.=$crop['w'].'x'.$crop['h'];//同时指定目标图片宽高。取值范围为0-10000。
            }
            //裁剪偏移参数
            if (isset($crop['move_type'])) {
                $move_str = '!'.$crop_str;
                switch ($crop['move_type']) {
                    case 1:
                        $move_str .= 'a'.$crop['x'] . 'a'.$crop['y'];//指定目标图片宽度，高度不变。取值范围为0-10000。
                        break;
                    case 2:
                        $move_str .= '-'.$crop['x'] . 'a'.$crop['y'];//指定目标图片高度，宽度不变。取值范围为0-10000。
                        break;
                    case 3:
                        $move_str .= 'a'.$crop['x'] . '-'.$crop['y'];//指定目标图片高度，宽度不变。取值范围为0-10000。
                        break;
                    default:
                        $move_str .= '-'.$crop['x'] . '-'.$crop['y'];//同时指定目标图片宽高。取值范围为0-10000。
                }
                $str.=$move_str;
            }else{
                $str.=$crop_str;
            }

        }

        if ($rotate){
            $str.='/rotate/'.$rotate;//旋转角度，取值范围为1-360，默认为不旋转。
        }
        if ($format){
            $str.='/format/'.$format;//图片格式。支持jpg、gif、png、webp等，默认为原图格式，参看支持转换的图片格式。
        }
        if ($blur){
            $str.='/blur/'.$blur;//高斯模糊参数。radius是模糊半径，取值范围为1-50。sigma是正态分布的标准差，必须大于0。图片格式为gif时，不支持该参数。
        }

        $str.='/interlace/'.$interlace;//是否支持渐进显示。取值1支持渐进显示，取值0不支持渐进显示（默认为0）。适用jpg目标格式，网速慢时，图片显示由模糊到清晰。
        $str.='/quality/'.$quality;//新图的图片质量。取值范围为1-100，默认75。
        $str.='/sharpen/'.$sharpen;//图片是否锐化，当设置值为1时打开锐化效果。

        if ($size_limit){
            $str.='/size-limit/'.$size_limit;//限制图片转换后的大小，支持以兆字节和千字节为单位的图片。
        }else{
            $str.='/size-limit/2000k';
        }

        return $str;
    }

    /**
     * 图片水印
     * @param null $waterimg 水印图片地址
     * @param string $gravity 水印位置
     * @param int $dx 距离x轴距离
     * @param int $dy 距离y轴距离
     * @param int $dissolve 透明度
     * @return string
     */
    public static function waterMarkImg($waterimg=null,$gravity='NorthEast',$dx=10,$dy=10,$dissolve=70){
        if (empty($waterimg)){
            $waterimg=Common::WATERIMG;
        }
        $waterimg=base64_urlSafeEncode($waterimg);

        return 'watermark/1/image/'.$waterimg.'/dissolve/'.$dissolve.'/gravity/'.$gravity.'/dx/'.$dx.'/dy/'.$dy;
        ///ws/'.$ws;//水印图片自适应原图的短边比例，ws的取值范围为0-1。具体是指水印图片保持原比例，并短边缩放到原图短边＊ws。例如：原图大小为250x250，水印图片大小为91x61，如果ws=1，那么最终水印图片的大小为：372x250
    }

    /**
     * 文字水印
     * @param null $text 水印文字
     * @param string $font 字体
     * @param int $fontsize 文字大小
     * @param string $fill 文字RGB颜色
     * @param string $gravity 文印位置
     * @param int $dx 横轴边距，单位:像素(px)，默认值为10。
     * @param int $dy 纵轴边距，单位:像素(px)，默认值为10。
     * @param int $dissolve 透明度，取值范围1-100，默认值100（完全不透明）。
     * @return string
     */
    public static function waterMarkText($text=null,$font='黑体',$fontsize=320,$fill='#FFFFFF',$gravity='Center',$dx=10,$dy=10,$dissolve=50){
        if (empty($text)){
            $text=\Yii::$app->name;
        }
        $text=base64_urlSafeEncode($text);

        return 'watermark/2/text/'.$text.'/font/'.base64_urlSafeEncode($font).'/fontsize/'.$fontsize.'/fill/'.base64_urlSafeEncode($fill).'/dissolve/'.$dissolve.'/gravity/'.$gravity.'/dx/'.$dx.'/dy/'.$dy;
        ///ws/'.$ws;//水印图片自适应原图的短边比例，ws的取值范围为0-1。具体是指水印图片保持原比例，并短边缩放到原图短边＊ws。例如：原图大小为250x250，水印图片大小为91x61，如果ws=1，那么最终水印图片的大小为：372x250
    }

    /**
     * 同时打印多个水印
     * @param $mkarr[0=>['type'=>'img','mkstr'=>waterMark1(xxx)],1=>['type'=>'text','mkstr'=>waterMark2(xxx)]] 水印参数数组
     * @return string
     */
    public static function waterMarkAll($mkarr){
        $str='watermark/3';
        foreach ($mkarr as $mk){
            if ($mk['type']=='text'){
                $str.='/text/'.$mk['mkstr'];
            }else{
                $str.='/image/'.$mk['mkstr'];
            }
        }
        return $str;
    }

    /**
     * roundPic 将图片生成圆角图片，并且可以指定图片的圆角大小。这个接口支持的原图片格式有png、jpg，处理后的图片格式为png。
     * @param $radius 圆角大小的参数，水平和垂直的值相同，可以使用像素数（如200）或百分比（如!25p）。不能与radiusx和radiusy同时使用。
     * @param $x 圆角水平大小的参数，可以使用像素数（如200）或百分比（如!25p）。需要与radiusy同时使用。
     * @param $y 圆角垂直大小的参数，可以使用像素数（如200）或百分比（如!25p）。需要与radiusx同时使用。
     * @return string
     */
    public static function roundPic($radius,$x,$y){
        return 'roundPic/radius/'.$radius.'/radiusx/'.$x.'/radiusy/'.$y;
    }

    //========================================对已有图片==进行的处理==========================================================================================


    /**
     * 缩略图链接拼接
     *
     * @param  string $url 图片链接
     * @param  int $mode 缩略模式
     * @param  int $width 宽度
     * @param  int $height 长度
     * @param  string $format 输出类型
     * @param  int $quality 图片质量
     * @param  int $interlace 是否支持渐进显示
     * @param  int $ignoreError 忽略结果
     * @return string
     * @link http://developer.qiniu.com/code/v6/api/kodo-api/image/imageview2.html
     * @author Sherlock Ren <sherlock_ren@icloud.com>
     */
    public function thumb(
        $url,
        $mode,
        $width,
        $height,
        $format = null,
        $interlace = null,
        $quality = null,
        $ignoreError = 1
    ){
        // 要处理图片
//        $url = 'http://78re52.com1.z0.glb.clouddn.com/resource/gogopher.jpg';//只缩放
//        $url2 = 'http://78re52.com1.z0.glb.clouddn.com/resource/gogopher.jpg?watermark/1/gravity/SouthEast/dx/0/dy/0/image/'
//            . 'aHR0cDovL2Fkcy1jZG4uY2h1Y2h1amllLmNvbS9Ga1R6bnpIY2RLdmRBUFc5cHZZZ3pTc21UY0tB';//缩放加水印


        return $this->imageUrlBuilder->thumbnail($url, $mode,$width, $height, $format, $interlace, $quality, $ignoreError);

        //可拼接多个操作参数 如$url2 图片+水印
        //return $this->imageUrlBuilder->thumbnail($url2, $mode,$width, $height, $format, $interlace, $quality, $ignoreError);
    }

    /**
     * 图片水印
     *
     * @param  string $url 图片链接
     * @param  string $image 水印图片链接
     * @param  numeric $dissolve 透明度 [可选]
     * @param  string $gravity 水印位置 [可选]
     * @param  numeric $dx 横轴边距 [可选]
     * @param  numeric $dy 纵轴边距 [可选]
     * @param  numeric $watermarkScale 自适应原图的短边比例 [可选]
     * @link   http://developer.qiniu.com/code/v6/api/kodo-api/image/watermark.html
     * @return string
     * @author Sherlock Ren <sherlock_ren@icloud.com>
     */
    function water($url,
                   $image=null,
                   $dissolve = 100,
                   $gravity = 'SouthEast',
                   $dx = null,
                   $dy = null,
                   $watermarkScale = null){
//        $url = 'http://78re52.com1.z0.glb.clouddn.com/resource/gogopher.jpg';
//        $url2 = 'http://78re52.com1.z0.glb.clouddn.com/resource/gogopher.jpg?watermark/1/gravity/SouthEast/dx/0/dy/0/image/'
//            . 'aHR0cDovL2Fkcy1jZG4uY2h1Y2h1amllLmNvbS9Ga1R6bnpIY2RLdmRBUFc5cHZZZ3pTc21UY0tB';
//        $waterImage = 'http://developer.qiniu.com/resource/logo-2.jpg';

        if (empty($image)){
            $image=Common::WATERIMG;
        }

        return $this->imageUrlBuilder->waterImg($url, $image, $dissolve, $gravity, $dx, $dy, $watermarkScale);
    }

    /**
     * 文字水印
     *
     * @param  string $url 图片链接
     * @param  string $text 文字
     * @param  string $font 文字字体
     * @param  string $fontSize 文字字号
     * @param  string $fontColor 文字颜色 [可选]
     * @param  numeric $dissolve 透明度 [可选]
     * @param  string $gravity 水印位置 [可选]
     * @param  numeric $dx 横轴边距 [可选]
     * @param  numeric $dy 纵轴边距 [可选]
     * @link   http://developer.qiniu.com/code/v6/api/kodo-api/image/watermark.html#text-watermark
     * @return string
     * @author Sherlock Ren <sherlock_ren@icloud.com>
     */
    function text($url,
                  $text,
                  $font = '黑体',
                  $fontSize = 0,
                  $fontColor = null,
                  $dissolve = 100,
                  $gravity = 'SouthEast',
                  $dx = null,
                  $dy = null){

        return $this->imageUrlBuilder->waterText($url,$text,$font,$fontSize,$fontColor,$dissolve,$gravity,$dx,$dy);
    }
}