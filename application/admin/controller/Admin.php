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
            $data["admin_name"]=request()->post("admin_name","");
            $admin_pwd=request()->post("admin_pwd","");
            $admin_repwd=request()->post("admin_repwd","");
            if($admin_pwd!==$admin_repwd){
                $this->error("确认密码错误");
            }
            $admin_sult=substr(uniqid(),-4);
            $data["admin_pwd"]=md5(md5($admin_pwd).$admin_sult);
            $data["admin_sult"]=$admin_sult;
            $name=(new \app\admin\model\Admin())
                ->where("admin_name",$data["admin_name"])->find();
            if($data["admin_name"]==$name["admin_name"]){
                $this->error("该管理员以被添加");
            }
            $admins=new \app\admin\model\Admin();
            $admin=$admins->save($data);
            $shop_admin_role=$admins->role()->saveAll($admin_role);
            if($admin&&$shop_admin_role){
                $this->success("管理员添加成功","Admin/show_admin");
            }else{
                $this->error("添加失败");
            }

        }

    }
    public function show_admin(){
        $admins=new \app\admin\model\Admin();
        $admin=$admins->all();
        return view("",["admin"=>$admin]);
    }
}