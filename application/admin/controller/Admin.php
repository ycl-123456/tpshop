<?php
namespace app\admin\controller;
use think\Db;
class Admin extends Common
{
    public function add_admin(){
        if(request()->isGet()){
            $role=Db::table("shop_role")->select();
            return view("",["role"=>$role]);
        }
        if(request()->isPost()){
            //接值
            $admin_role=request()->post("admin_role","");
            $admin_role_name=implode(",",$admin_role);
            $data["role_name"]=$admin_role_name;
            $data["admin_name"]=request()->post("admin_name","");
            $admin_pwd=request()->post("admin_pwd","");
            $admin_repwd=request()->post("admin_repwd","");
            if($admin_pwd!==$admin_repwd){
                $this->error("确认密码错误");
            }
            $admin_sult=substr(uniqid(),-4);
            $data["admin_pwd"]=md5(md5($admin_pwd).$admin_sult);
            $data["admin_sult"]=$admin_sult;
            $name=\app\admin\model\Admin::get("admin_name",$data["admin_name"]);
            if($data["admin_name"]==$name["admin_name"]){
                $this->error("该管理员以被添加");
            }
            $admin=new \app\admin\model\Admin();
            $admin=$admin->save($data);
            $admin_id=\app\admin\model\Admin::get("admin_name",$data["admin_name"]);
            foreach($admin_role as $key=>$val){
                $role_id=Db::table("shop_role")->where("role_name",$val)->find();
                $shop_admin_role=Db::table("shop_admin_role")
                    ->insert(["admin_id"=>$admin_id["admin_id"],"role_id"=>$role_id["role_id"]]);
            }
            if($admin&&$shop_admin_role){
                $this->success("管理员添加成功","Admin/show_admin");
            }else{
                $this->error("添加失败");
            }

        }

    }
    public function show_admin(){
        $admin=Db::table("shop_admin")->select();
        return view("",["admin"=>$admin]);
    }
}