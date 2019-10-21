<?php
namespace app\admin\service;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\facade\Request;
use think\Image;

class GoodsService{
   public function img($goods_img,$data){
//      $image=Image::open($goods_img);
//      $img=$image->thumb(50, 50)->save('./thumb.png');
      // 要上传图片的本地路径
      $filePath = $goods_img['tmp_name'];
      $ext=pathinfo($goods_img['name'],PATHINFO_EXTENSION);//后缀
      // 上传到七牛后保存的文件名
      $key = uniqid().".".$ext;
      // 需要填写你的 Access Key 和 Secret Key
      $accessKey = 'sgVBQxbasL4zKznWuPp_sfQISM0bJSyQe-bdFvVD';//你的accessKey
      $secretKey = 'zYhP7plpB5t8LOnsxp_rePxJN5yWdnunpVC786uR';//你的secretKey
      // 构建鉴权对象
      $auth = new Auth($accessKey,$secretKey);
      // 要上传的空间
      $bucket = 'tp5shop';
      $token = $auth->uploadToken($bucket);
      // 初始化 UploadManager 对象并进行文件的上传
      $uploadMgr = new UploadManager();
      // 调用 UploadManager 的 putFile 方法进行文件的上传
      list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
      if($err==null){
         $path="http://pzcbrfqmd.bkt.clouddn.com/".$ret['key'];
         $data["goods_img"]=$path;
         return $data;
      }else{
         return false;
      }
   }
   public function simg($goods_simg){
      // 要上传图片的本地路径
      $filePath = $goods_simg['tmp_name'];
      $ext=pathinfo($goods_simg['name'],PATHINFO_EXTENSION);//后缀
      // 上传到七牛后保存的文件名
      $key = uniqid().".".$ext;
      // 需要填写你的 Access Key 和 Secret Key
      $accessKey = 'sgVBQxbasL4zKznWuPp_sfQISM0bJSyQe-bdFvVD';//你的accessKey
      $secretKey = 'zYhP7plpB5t8LOnsxp_rePxJN5yWdnunpVC786uR';//你的secretKey
      // 构建鉴权对象
      $auth = new Auth($accessKey,$secretKey);
      // 要上传的空间
      $bucket = 'tp5shop';
      $token = $auth->uploadToken($bucket);
      // 初始化 UploadManager 对象并进行文件的上传
      $uploadMgr = new UploadManager();
      // 调用 UploadManager 的 putFile 方法进行文件的上传
      list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
      if($err==null){
         $path="http://pzcbrfqmd.bkt.clouddn.com/".$ret['key'];
         return $path;
      }else{
         return false;
      }
   }
   public function upload($files){
      foreach($files as $file){
         // 移动到框架应用根目录/uploads/ 目录下
         $info = $file->move( 'uploads/goods');
         if($info){
            $goodspath[]=request()->domain()."/uploads/goods/".str_replace('\\',"/",$info->getSaveName());
         }else{
            // 上传失败获取错误信息
            echo $file->getError();
         }
      }
      return $goodspath;
   }
}