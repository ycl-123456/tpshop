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
            //接值
            $data["prand_name"] = request()->post("prand_name", "");
            $data["prand_url"] = request()->post("prand_url", "");
            $data["prand_des"]=request()->post("prand_des","");
            $data["prand_order"] = request()->post("prand_order", "");
                //处理上传图片
            $file = Request::file('prand_logo');
            $info=$file->validate(['size'=>2048000,'ext'=>'gif,png,jpg'])->move("uploads/brand");
            if($info) {
                $data['prand_logo'] = request()->domain() . "/uploads/brand/" . str_replace('\\', "/", $info->getSaveName());
            }
            //入库
            $prand=Db::table("shop_prand")->insert($data);
            if($prand){
                $this->success("添加成功","Prand/add_prand");
            }else{
                $this->error("添加失败");
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