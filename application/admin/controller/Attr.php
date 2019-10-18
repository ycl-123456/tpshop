<?php
namespace app\admin\controller;
use app\admin\model\Type;
use think\Db;
class Attr extends Common
{
    public function show_attr()
    {
        if(request()->isGet()){
            $attr=Db::table("shop_type")
                ->alias("t")->join("shop_attr a","t.type_id=a.type_id")
                ->select();
            $typeModel=new \app\admin\model\Type();
            $type=$typeModel->all();
            return view("",["type"=>$type,"attr"=>$attr]);
        }
    }
    public function ajax(){
        $type_id=request()->get("type_id","");
        $typeModel=new \app\admin\model\Type();
        $type_group=$typeModel->field("type_group")->get($type_id);
        $type_groups=explode(",", $type_group["type_group"]);
        if($type_groups){
            echo json_encode(["status"=>1,"msg"=>"ok","type_groups"=>$type_groups]);
        }else{
            echo json_encode(["status"=>0,"msg"=>"not ok"]);
        }
    }
    public function add_attr()
    {
        if(request()->isGet()){
            $typeModel=new \app\admin\model\Type();
            $type=$typeModel->all();
            return view("",["type"=>$type]);
        }
        if(request()->isPost()){
            $data=input("post.");
            $typeModel=new Type();
            $attr_num=$typeModel->where("type_id",$data["type_id"])
                ->field("attr_num")->find();
            $attr_num=$attr_num["attr_num"]+1;
            $attrModel=new \app\admin\model\Attr();
            $attr=$attrModel->save($data);
            $type=$typeModel->where("type_id",$data["type_id"])->update(["attr_num"=>$attr_num]);
            if($attr&&$type){
                $this->success("添加成功","show_attr");
            }else{
                $this->error("添加失败");
            }
        }
    }
}
