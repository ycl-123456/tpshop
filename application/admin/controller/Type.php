<?php
namespace app\admin\controller;
use app\admin\model\Attr;
use think\Db;

class Type extends Common
{
    public function add_type()
    {
        if(request()->isGet()){
            return view();
        }
        if(request()->isPost()){
            //接值
            $data["type_name"]=request()->post("type_name","");
            $data["type_group"]=request()->post("type_group","");
            $types=new \app\admin\model\Type();
            $type=$types->save($data);
            if($type){
                $this->success("添加成功","Type/show_type");
            }else{
                $this->error("添加失败");
            }
        }

    }
    public function show_type(){
        $types=new \app\admin\model\Type();
        $type=$types->all();
        return view("",["type"=>$type]);
    }
}