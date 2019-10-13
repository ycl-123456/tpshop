<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;

class Common extends Controller{
    public function __construct()
    {
        parent::__construct();
        $cookie=Cookie::get("admin");
        $session=Session::get("admin");
        if($cookie&&!$session){
            $session=$cookie;
            Session::add("admin",$cookie);
        }
        if(!$cookie&&!$session){
            $this->success("请先登录","Login/login");
        }

    }
}