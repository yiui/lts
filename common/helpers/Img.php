<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/6
 * Time: 9:54
 */
class Img {

    public function getNewName($newname,$config){
        $oldname=$newname;
        //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
        if ($config['th_name']===true){
            $newname='th_'.$config['th_width'].'_'.$config['th_height'].'_'.$newname;
        }else if (is_string($config['th_name'])){
            //虽然起了名字，但仍然防止同名
            $newname='th_'.$config['th_width'].'_'.$config['th_height'].'_'.$config['th_name'].date('YmdHis').rand(40000,88888);
        }

        //缩放
        $img = Image::thumbnail($config['path']. $newname . '.' . $file->extension, $config['th_width'], $config['th_height'],ImageInterface::THUMBNAIL_INSET);
        if ($img->save($config['path']. $newname . '.' . $file->extension)) {
            //缩放成功
            if ($oldname!=$newname){
                //删除上传后、缩放前的文件
                $this->delete($oldname,['savedb'=>false]);//还没有保存到数据库
                unset($this->upfile[$oldname]);
            }
            $oldname=$newname;//现在的名字
            //添加缩放消息
            $this->upfile[$newname]['path']=$config['path'] . $newname. '.'. $file->extension;
            $this->upfile[$newname]['url']=$config['url'] . $newname. '.'. $file->extension;
        } else {
             return false;
        }
    }

    public function thumb(){

    }
}