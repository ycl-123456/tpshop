<?php
namespace app\admin\controller;
use app\admin\service\AdminService;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class Common extends Controller{
    public function __construct()
    {
        parent::__construct();
        $cookie=Cookie::get("admin");
        $session=Session::get("admin");
        if($cookie&&!$session){
            $session=$cookie;
            Session::set("admin",$cookie);
        }
        if(!$cookie&&!$session){
            $this->success("请先登录","Login/login");
        }
        $this->checkPower();
        $adminService=new AdminService();
        $left=$adminService->getleft();
        $left=$adminService->getMenuTree($left);
        View::share("left",$left);
    }
    public function checkPower(){
        $superAdmin=Session::get("admin");
        //检测当前登录用户是否是超级管理员
        if(in_array($superAdmin["admin_name"],config("config.super_admin"))){
            return true;
        }
        //如果不是超级管理员，检测
        //获取要访问的控制器和方法
        $access=ucfirst(strtolower(request()->controller())."/".strtolower(request()->action()));
        if(in_array($access,config("config.no_check_action"))){
            return true;
        }
        $mypower=(new AdminService())->getPowerByAdminID(Session::get("admin")["admin_id"]);
        if(in_array($access,$mypower)){
            return true;
        }else{
            $this->success("您没有权限","index/index");
        }

    }
}