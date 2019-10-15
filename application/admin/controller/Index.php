<?php
namespace app\admin\controller;
use app\admin\model\Powermodel;
use think\Controller;
use think\Db;
use think\facade\Cookie;
use think\facade\Session;

class Index extends Common
{
    public function index()
    {
        $admin=Db::table("shop_admin")->select();
        return view("",["admin"=>$admin]);
    }
}
