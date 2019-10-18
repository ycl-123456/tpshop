<?php
namespace app\admin\controller;
use App\Services\QiNiu\QiNiu;
use think\Db;
use think\facade\Request;
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
class Prand extends Common
{
    public function add_prand()
    {
        if (request()->isGet()) {
            return view();
        }
        if(request()->isPost()){
            config("debug",false);
            $data=input("post.");
            $file=$_FILES["prand_logo"];
            //七牛云
            // 要上传图片的本地路径
            $filePath = $file['tmp_name'];
            $ext=pathinfo($file['name'],PATHINFO_EXTENSION);//后缀
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
                $data['prand_logo']=$path;
                $brand=new \app\admin\model\Prand();
                $prand=$brand->save($data);
                echo json_encode(["status"=>1,"msg"=>"ok"]);
            }else{
                echo json_encode(["status"=>0,"msg"=>"添加失败"]);
            }


        }
    }
    public function show_prand(){
       $prand=Db::table("shop_prand")->select();
        return view("",["prand"=>$prand]);
    }
    public function brandDel(){
        $prand_id=request::get("prand_id","");
        $brandDelete=Db::table("shop_prand")->delete($prand_id);
        if($brandDelete){
            echo json_encode(["status"=>1,"msg"=>"删除分类成功"]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"删除分类失败"]);
        }

    }
}