<?php
/**
 * Created by PhpStorm.
 * Description: 阿里oss上传图片
 * Author: Weini
 * Date: 2016/11/17 0017
 * Time: 上午 11:34
 */

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

class UploadForm extends Model
{
    public $files;  //用来保存文件

    public function scenarios()
    {
        return [
            'upload' => ['files'], // 添加上传场景
        ];
    }

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, png, gif', 'mimeTypes' => 'image/jpeg, image/png, image/gif', 'maxSize' => 1024 * 1024 * 10, 'maxFiles' => 1, 'on' => ['upload']],
            //设置图片的验证规则
        ];
    }

    /**
     * 上传单个文件到阿里云
     * @return boolean  上传是否成功
     */
    public function uploadfile()
    {
        $res['error'] = 1;

        if ($this->validate()) {
            $uploadPath = dirname(dirname(__FILE__)) . '/web/uploads/';  // 取得上传路径
            if (!file_exists($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }

            $ext = $this->files->getExtension();                // 获取文件的扩展名
            $randnums = $this->getrandnums();                   // 生成一个随机数，为了重命名文件
            $imageName = date("YmdHis") . $randnums . '.' . $ext;     // 重命名文件
            $ossfile = 'file/' . date("Ymd") . '/' . $imageName;      // 这里是保存到阿里云oss的文件名和路径。如果只有文件名，就会放到空间的根目录下。
            $filePath = $uploadPath . $imageName;                 // 生成文件的绝对路径

            if ($this->files->saveAs($filePath)) {               // 上传文件到服务器
                $filedata['filename'] = $imageName;             // 准备图片信息，保存到数据库
                $filedata['filePath'] = $filePath;              // 准备图片信息，保存到数据库
                $filedata['ossfile'] = $ossfile;                // 准备图片信息，保存到数据库
                $filedata['userid'] = Yii::$app->user->id;      // 准备图片信息，保存到数据库，这个字段必须要，以免其他用户恶意删除别人的图片
                $filedata['uploadtime'] = time();               // 准备图片信息，保存到数据库

                // 上边这些代码不能照搬，要根据你项目的需求进行相应的修改。反正目的就是记录上传文件的信息
                // 老板，这些代码是我搬来的，没仔细看，如果出问题了，你就扣我的奖金吧^_^

                $trans = Yii::$app->db->beginTransaction();     // 开启事务
                try {
                    $savefile = Yii::$app->db->createCommand()->insert('file', $filedata)->execute(); //把文件的上传信息写入数据库
                    $newid = Yii::$app->db->getLastInsertID();  //获取新增文件的id，用于返回。

                    if ($savefile) {                            // 如果插入数据库成功
                        $ossupload = Yii::$app->Aliyunoss->upload($ossfile, $filePath);  //调用Aliyunoss组件里边的upload方法把文件上传到阿里云oss

                        if ($ossupload) {                       // 如果上传成功，
                            $res['error'] = 0;                  // 准备返回信息
                            $res['fileid'] = $newid;            // 准备返回信息
                            $res['ossfile'] = $ossfile;         // 准备返回信息
                            $trans->commit();                   // 提交事务
                        } else {                                // 如果上传失败
                            unlink($filePath);                  // 删除服务器上的文件
                            $trans->rollBack();                 // 事务回滚
                        }
                    }
                    unlink($filePath);                          // 插入数据库失败，删除服务器上的文件
                    $trans->rollBack();                         // 事务回滚
                } catch (Exception $e) {                         // 出了异常
                    unlink($filePath);                          // 删除服务器上的文件
                    $trans->rollBack();                         // 事务回滚
                }

            }
        }

        return $res;                                            // 返回上传信息
    }

    /**
     * 生成随机数
     * @return string 随机数
     */
    protected function getrandnums()
    {
        $arr = array();
        while (count($arr) < 10) {
            $arr[] = rand(1, 10);
            $arr = array_unique($arr);
        }
        return implode("", $arr);
    }


    /**
     * 删除阿里云oss里存储的文件和数据库里边保存到文件上传信息
     * @param $fileid    文件表里边的主键id
     * @return boolean   删除是否成功
     */
    public function deletefile($fileid)
    {
        $res['error'] = 1;    // 1表示默认有错误。
        $fileinfo = Yii::$app->db->createCommand('select ossfile,filePath,userid from file where id=:id')->bindParam(':id', $fileid)->queryOne();
        // 根据主键从数据库里边查询文件的信息,至少要一个服务器文件的完整路径（用来删除服务器文件）和一个ossfile的名称（删除阿里云oss里边的文件）

        if (count($fileinfo) > 0) {                     // 如果找到了文件的记录

            // 这里边的验证可以更丰富一些，比如验证用户是否有权限删除该文件，文件是否属于该用户等等

            $ossfile = $fileinfo['ossfile'];            // 获取ossfile
            $realfile = $fileinfo['filePath'];          // 获取服务器上的文件
            $owner = $fileinfo['userid'];               // 获取上传图片用户的id
            $operator = Yii::$app->user->id;            // 获取删除图片的用户

            if ($owner != $operator) {                  // 如果删除图片的用户不是上传的用户，报错并返回
                $res['errmsg'] = '您删除的图片不存在';
                return $res;
            }

            $trans = Yii::$app->db->beginTransaction(); // 开启事务
            try {
                $delstatus = Yii::$app->db->createCommand()->delete('file', 'id = ' . $fileid)->execute();
                //删除数据库里边的记录

                if ($delstatus) {                       // 如果删除成功
                    if (Yii::$app->Aliyunoss->delete($ossfile)) { //删除阿里云oss上的文件
                        @unlink($realfile);             // 删除服务器上的文件
                        $res['error'] = 0;              // 准备返回信息
                        $trans->commit();               // 提交事务
                    }
                }
                $trans->rollBack();                     // 删除失败，事务回滚
            } catch (Exception $e) {                    // 发生异常
                $res['errmsg'] = '删除失败';              // 准备返回信息
                $trans->rollBack();                     // 事务回滚
            }

        } else {
            $res['errmsg'] = '图片不存在，请重试';           // 图片不存在
        }

        return $res;                                     // 返回删除结果
    }
}