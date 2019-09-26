<?php

/**
 * 配置文件
 * 
 * @author   widuu <admin@widuu.com>
 * @document https://github.com/widuu/qiniu_ueditor_1.4.3
 */
namespace common\components\Ueditor;

use common\components\Qiniu\Common;

class Config {
    const USER_DIR_PRE='20171020WEN';

    /**
     * 基本配置
     * @return array
     */
    public static function BeseConfig(){
        if (\Yii::$app->user->isGuest) {
            echo json_encode([
                "state" 	 => "您还没有登录或登录超时！",
            ]);
            exit();
        }

        $uhash=hash('sha256',\Yii::$app->user->id.self::USER_DIR_PRE);
        $pre = 'users/'.$uhash.'/';

        return [
            /* 七牛云存储配置start */
            'userId' => $uhash,
            'uploadType' => 'qiniu', /* qiniu|local 【qiniu】七牛云存储 【local】本地上传*/
            'qiniuUploadType'  => 'url', /* url|php 【url】 通过URL直传，根据token来判断返回地址,【php】 通过php文件方式传输 */
            'uploadQiniuUrl'   => 'http://upload.qiniu.com/', /* 七牛上传地址 */
            'qiniuUploadPath'  => $pre,   /* 七牛上传的前缀 */
            'qiniuDatePath'    => 'yyyy/mmdd',       /* 文件夹后的时间例如 uploads/0712 留空uploads/, 格式 yyyy == 2017 yy == 17 mm 月份 07 dd 日期 12 */
            'uploadSaveType'   => 'date',       /* 保存文件的名称类型 */
            'getTokenActionName' => 'getToken', /* 获取上传token的方法*/
            'removeImageActionName' => 'remove',  /* 删除图片的方法 */
            'VideoBlockFileSize' => 4194304,  /* 视频块大小,是每块4MB，所以这个不用修改 */
            'VideoChunkFileSize' => 2097152,  /* 视频上传分块大小，建议是整数倍防止出错，列如1048576（1MB），524288（512KB）默认是2MB */
            'VideoChunkMaxSize'  => 10485760, /* 视频文件超过多大来进行分片上传，现在默认是10MB */
            'ChunkUploadQiniuUrl'=> 'http://upload.qiniu.com', /* 分片上传创建的host地址 */
            'makeFileActionName' => 'makeFile', /* 合成文件的url方法 */

            /* 七牛云存储配置end */

            /* 上传图片配置项 */
            'imageActionName'=> 'uploadimage', /* 执行上传图片的action名称 */
            'imageFieldName'=> 'file', /* 提交的图片表单名称 */
            'imageMaxSize' => 2048000, /* 上传大小限制，单位B */
            'imageAllowFiles'=> ['.png', '.jpg', '.jpeg', '.gif', '.bmp'], /* 上传图片格式显示 */
            'imageCompressEnable'=> true, /* 是否压缩图片,默认是true */
            'imageCompressBorder'=> 800, /* 图片压缩最长边限制 */
            'imageInsertAlign'=> 'none', /* 插入的图片浮动方式 */
            'imageUrlPrefix'=> '', /* 图片访问路径前缀 */
            'imagePathFormat'=> '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
            /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
            /* {time} 会替换成时间戳 */
            /* {yyyy} 会替换成四位年份 */
            /* {yy} 会替换成两位年份 */
            /* {mm} 会替换成两位月份 */
            /* {dd} 会替换成两位日期 */
            /* {hh} 会替换成两位小时 */
            /* {ii} 会替换成两位分钟 */
            /* {ss} 会替换成两位秒 */
            /* 非法字符 \ : * ? ' < > | */
            /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

            /* 涂鸦图片上传配置项 */
            'scrawlActionName'=> 'uploadscrawl', /* 执行上传涂鸦的action名称 */
            'scrawlFieldName'=> 'file', /* 提交的图片表单名称 */
            'scrawlPathFormat'=> '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            'scrawlMaxSize'=> 2048000, /* 上传大小限制，单位B */
            'scrawlAllowFiles'=> ['.png', '.jpg', '.jpeg', '.gif', '.bmp'], /* 上传图片格式显示 */
            'scrawlUrlPrefix'=> '', /* 图片访问路径前缀 */
            'scrawlInsertAlign'=> 'none',

            /* 截图工具上传 */
            'snapscreenActionName'=> 'uploadimage', /* 执行上传截图的action名称 */
            'snapscreenFieldName'=> 'file', /* 提交的表单名称 */
            'snapscreenPathFormat'=> '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            'snapscreenUrlPrefix'=> '', /* 图片访问路径前缀 */
            'snapscreenInsertAlign'=> 'none', /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            'catcherLocalDomain'=> ['127.0.0.1', 'localhost', 'img.baidu.com'],
            'catcherActionName'=> 'catchimage', /* 执行抓取远程图片的action名称 */
            'catcherFieldName'=> 'source', /* 提交的图片列表表单名称 */
            'catcherPathFormat'=> '/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            'catcherUrlPrefix'=> '', /* 图片访问路径前缀 */
            'catcherMaxSize'=> 2048000, /* 上传大小限制，单位B */
            'catcherAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            'videoActionName'=> 'uploadvideo', /* 执行上传视频的action名称 */
            'videoFieldName'=> 'file', /* 提交的视频表单名称 */
            'videoPathFormat'=> '/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            'videoUrlPrefix'=> '', /* 视频访问路径前缀 */
            'videoMaxSize' => 102400000, /* 上传大小限制，单位B，默认10MB */
            'videoAllowFiles'=> [
                '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
                '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'
            ], /* 上传视频格式显示 */

            /* 上传文件配置 */
            'fileActionName'=> 'uploadfile', /* controller里,执行上传视频的action名称 */
            'fileFieldName'=> 'file', /* 提交的文件表单名称 */
            'filePathFormat'=> '/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}', /* 上传保存路径,可以自定义保存路径和文件名格式 */
            'fileUrlPrefix'=> '', /* 文件访问路径前缀 */
            'fileMaxSize'=> 5120000, /* 上传大小限制，单位B，默认5MB */
            'fileAllowFiles'=> [
                '.png', '.jpg', '.jpeg', '.gif', '.bmp',
                '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
                '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
                '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
                '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml','.md'
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            'imageManagerActionName'=> 'listimage', /* 执行图片管理的action名称 */
            'imageManagerListPath'=> '/upload/image/', /* 指定要列出图片的目录 */
            'imageManagerListSize'=> 20, /* 每次列出文件数量 */
            'imageManagerUrlPrefix'=> '', /* 图片访问路径前缀 */
            'imageManagerInsertAlign'=> 'none', /* 插入的图片浮动方式 */
            'imageManagerAllowFiles'=> ['.png', '.jpg', '.jpeg', '.gif', '.bmp'], /* 列出的文件类型 */

            /* 列出指定目录下的文件 */
            'fileManagerActionName'=> 'listfile', /* 执行文件管理的action名称 */
            'fileManagerListPath'=> '/upload/file/', /* 指定要列出文件的目录 */
            'fileManagerUrlPrefix'=> '', /* 文件访问路径前缀 */
            'fileManagerListSize'=> 20, /* 每次列出文件数量 */
            'fileManagerAllowFiles'=> [
                '.png', '.jpg', '.jpeg', '.gif', '.bmp',
                '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
                '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
                '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
                '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'
            ] /* 列出的文件类型 */

        ];
    }

    /**
     * 七牛上传的一些配置
     * @param bool $water 是否开启水印
     * @param bool|array $th 是否开起缩放，开启则为缩放的配置数组
     * @param string $name_type 文件名格式
     * @return array
     *
     */
    public static function QuConfig($water=true,$th=true,$name_type='date'){

        $config=array(
            'upload_type' => 'qiniu',  // [qiniu|local] 设置上传方式 qiniu 上传到七牛云存储 ,local 上传到本地
            /* 本地上传配置信息 */
            'orderby'     => 'asc',   // [desc|asc] 列出文件排序方式，仅仅在本地上传时候有效
            'root_path'	  => $_SERVER['DOCUMENT_ROOT'], //本地上传 本地的绝对路径

            /* 七牛云存储信息配置 */
            'bucket'      => Common::BUCKET['open'], // 七牛Bucket的名称
            'host'        => Common::DOMAINS['open'],
            'access_key'  => Common::AK,
            'secret_key'  => Common::SK,

            /* 上传配置 */
            'timeout'     => Common::EXPIRES,  // 上传时间

            'save_type'   => $name_type,  // 保存类型

            /* 水印设置 */
            'use_water'   => $water,  // 是否开启水印
            /* 七牛水印图片地址 */
            'water_url'   => Common::WATERIMG,

            /* 水印显示设置 */
            'dissolve'    => 60,  // 水印透明度
            'gravity'	  => 'SouthEast',  // 水印位置具体见文档图片说明和选项
            'dx'		  => 5,  //边距横向位置
            'dy'		  => 5,   //边距纵向位置

            /* 缩放设置 */
            'use_th' => $th===false?false:true,   //启用缩放？
            'th_w'=>400,    //宽度限制
            'th_h'=>400,   //高度限制
            'th_t'=>9,       //缩放模式
        );

        if (is_array($th)){
            $config=array_merge($config,$th);
        }

        return array_merge(self::BeseConfig(),$config);
    }
}



