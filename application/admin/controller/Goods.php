<?php
namespace app\admin\controller;
use app\admin\model\Attr;
use app\admin\model\Prand;
use app\admin\model\Type;
use think\Db;

class Goods extends Common
{
    public function goods()
    {
        return view();
    }
    public function goods_add(){
        if(request()->isGet()){
            $prand=new Prand();
            $prand=$prand->all();
            $cate=new \app\admin\model\Cate();
            $cate=$cate->all();
            $type=new Type();
            $type=$type->all();
            return view("",["prand"=>$prand,"cate"=>$cate,"type"=>$type]);
        }
        if(request()->isPost()){
            $data=input("post.");
            dump($data);
             $goods_img=$_FILES["goods_img"];
            dump($goods_img);
        }
    }
    public function ajax(){
        $type_id=request()->get("type_id","");
        $attrs=new Attr();
        $attr=$attrs->where("type_id",$type_id)->all();
        foreach($attr as $key=>$val){
           $atte_select["attr_select"]=$val["attr_select"];
            $attr[$key]["attr_select"]=explode(",", $atte_select["attr_select"]);
        }
        if($attr){
            echo json_encode(["status"=>1,"msg"=>"ok","attr"=>$attr]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"Not ok"]);
        }

    }
}
