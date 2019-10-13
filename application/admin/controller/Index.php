<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Cookie;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        return view();
    }
}
