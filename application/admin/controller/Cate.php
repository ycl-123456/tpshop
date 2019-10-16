<?php
namespace app\admin\controller;
use app\admin\service\CateService;
use think\Controller;
use think\Db;
use app\admin\model;
class Cate extends Common
{
    public function add_cate(){
        if (request()->isGet()) {
            $cateService=new CateService();
            $cate=new model\Cate();
            $cates=$cateService->getCateByRecursion($cate->all());
           return view("",["cate"=>$cates]);
        }
        if(request()->isPost()){
            //接值
            $data["cate_name"] = request()->post("cate_name", "");
            $data["cate_pid"] = request()->post("cate_pid", "");
            $data["cate_key"] = request()->post("cate_key", "");
            $data["cate_dec"] = request()->post("cate_des", "");
            $data["cate_order"] = request()->post("cate_order", "");
            $cate=Db::table("shop_cate")->insert($data);
            if($cate){
                $this->success("添加分类成功","Cate/add_cate");
            }else{
                $this->error("添加失败");
            }
        }
    }
    public function show_cate(){
        if (request()->isGet()) {
            $cate=Db::table("shop_cate")->select();
            return view("",["cate"=>$cate]);
        }
    }
}