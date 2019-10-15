<?php
namespace app\admin\controller;
use think\Db;
class Role extends Common
{
    public function add_role(){
        if(request()->isGet()){
            $power=Db::table("shop_power")->select();
            return view("",["power"=>$power]);
        }
        if(request()->isPost()){
            $role_name=request()->post("role_name","");
            $power_id=request()->post("power_pid","");
            $name=Db::table("shop_role")->where("role_name",$role_name)->find();
            if($role_name==$name["role_name"]){
                $this->error("该角色已被添加");
            }
            $role=Db::table("shop_role")->insert(["role_name"=>$role_name]);
            $role_id=Db::table("shop_role")->where("role_name",$role_name)->find();
            foreach($power_id as $key=>$val){
                $power_id=Db::table("shop_power")->where("power_id",$val)->find();
                $shop_role_power=Db::table("shop_role_power")
                    ->insert(["power_id"=>$power_id["power_id"],"role_id"=>$role_id["role_id"]]);
            }
            if($role&&$shop_role_power){
                $this->success("添加成功","Role/show_role");
            }else{
                $this->error("失败");
            }
        }
    }
    public function show_role(){
        $role=Db::table("shop_role")->select();
        return view("",["role"=>$role]);
    }
}