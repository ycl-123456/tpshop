<?php
namespace app\admin\controller;
use app\admin\model\Attr;
use app\admin\model\Prand;
use app\admin\model\Type;
use app\admin\service\GoodsService;
use application\admin\model\Cate;
use think\Db;
use think\facade\Request;

class Goods extends Common
{
    public function goods_show()
    {
        $goodsModel=new \app\admin\model\Goods();
        $goods=$goodsModel->all();
        $prands=new Prand();
        $cates=new \app\admin\model\Cate();
        foreach ($goods as $k=>$val){
            $prand=$prands->get($val["prand_id"]);
            $cate=$cates->get($val["cate_id"]);
        }
        return view("",["goods"=>$goods,"prand"=>$prand,"cate"=>$cate]);
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
            $data=Request::except('attr_value,attr_price,attr_id');
            $goods_img=$_FILES["goods_img"];
            $goods_simg=$_FILES["goods_simg"];
            $goodsService=new GoodsService();
            $data=$goodsService->img($goods_img,$data);
            $path=$goodsService->simg($goods_simg);
            $data["goods_simg"]=$path;
            $goodsModel=new \app\admin\model\Goods();
            $goods=$goodsModel->save($data);
            $goods_id=$goodsModel->goods_id;
           $attrs=Request::only('attr_value,attr_price,attr_name,attr_id');
           $attr=[];
            if(empty($attrs["attr_price"])){
                foreach($attrs["attr_id"] as $k=>$v){
                    $attr[$k]["attr_id"]=$v;
                }
                foreach($attrs["attr_name"] as $k1=>$v1){
                    $attr[$k1]["attr_name"]=$v1;
                }
                foreach($attrs["attr_value"] as $k2=>$v2){
                    $attr[$k2]["attr_value"]=$v2;
                }
            }else{
                foreach($attrs["attr_id"] as $k=>$v){
                    $attr[$k]["attr_id"]=$v;
                }
                foreach($attrs["attr_name"] as $k1=>$v1){
                    $attr[$k1]["attr_name"]=$v1;
                }
                foreach($attrs["attr_value"] as $k2=>$v2){
                    $attr[$k2]["attr_value"]=$v2;
                }
                foreach($attrs["attr_price"] as $k3=>$v3){
                    $attr[$k3]["attr_name"]=$v3;
                }
            }
            foreach($attr as $key=>$val){
                $goods_attr=$goodsModel->Attr()->attach($val["attr_id"],$val);
            }
            $files=Request::file("image_img");
            $goodspath=$goodsService->upload($files);
            foreach ($goodspath as $key=>$val){
                $img[$key]["image_img"]=$val;
            }
            $im=$goodsModel->comments()->saveAll($img);
            if($goods&&$goods_attr&&$im){
                $this->success("添加成功","Goods/goods_show");
            }else{
               $this->error("添加失败");
            }


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
