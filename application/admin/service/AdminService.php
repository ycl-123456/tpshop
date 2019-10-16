<?php
namespace app\admin\service;
use app\admin\model\Admin;
use app\admin\model\AdminRole;
use app\admin\model\Power;
use app\admin\model\Role;
use think\facade\Session;

class AdminService{
    public function getPowerByAdminID($admin_id){
        $AdminRoleModel=new AdminRole();
        $role_id=$AdminRoleModel->where("admin_id",$admin_id)->column("role_id");
        $RoleModel=new Role();
        $role=$RoleModel->all($role_id);
        $mypower=[];
        foreach ($role as $key=>$val){
            $mypower=array_merge($mypower,$val->power->toArray());
        }
        $myaccess=[];
        foreach($mypower as $key=>$val){
            array_push($myaccess,ucfirst(strtolower($val["power_controller"]))."/".strtolower($val["power_action"]));
        }
        $myaccess=array_unique($myaccess);
        return $myaccess;
    }
    public function getleft(){
        $admin_name=Session::get("admin")["admin_name"];
        if(in_array($admin_name,config("config.super_admin"))){
            $left=(new Power())->where("power_ismu",1)->all()->toArray();
        }else{
            $admin_id=Session::get("admin")["admin_id"];
            $AdminModel=new Admin();
            $admin=$AdminModel->get($admin_id);
            $left=[];
            foreach($admin->role as $key=>$val){
                $left=array_merge($left,$val->power->where("power_ismu",1)->toArray());
            }
        }
        return $left;
    }
    public function getMenuTree($left,$pid=0){
        $leftTree=[];
        foreach($left as $key=>$val){
            if($val["power_pid"]==$pid){
                if($son=$this->getMenuTree($left,$val['power_id'])){
                    $val["son"]=$son;
                }
                $leftTree[]=$val;
            }
        }
        return $leftTree;
    }

}