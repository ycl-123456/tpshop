<?php
namespace app\admin\controller;

use think\captcha\Captcha;
use think\Db;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\Validate;
use think\Controller;
class Login extends Controller
{
    public function login()
    {
        if (request()->isGet()) {
            return view();
        }
        if (request()->isPost()) {
            //验证验证码
            $code = request()->post("code", "");
//            $captcha = new Captcha();
//            if (!$captcha->check($code)) {
//                $this->error("验证码错误");
//            }
            //接值
            $admin_name = request()->post("admin_name", "");
            $admin_pwd = request()->post("admin_pwd", "");
            $save = request()->post("save")?1:0;
            $time=time();
            $admin_ip=$_SERVER["SERVER_ADDR"];
            //验证值
            $data = [
                'admin_name'  => $admin_name,
                'admin_pwd'  => $admin_pwd
            ];
            $validate = Validate::make([
                'admin_name'  => 'require|length:5,15',
                'admin_pwd'  => 'require'
            ]);
            if (!$validate->check($data)) {
                $this->error('用户名或密码不符合规定');
            }

            //连接数据库
            $data=Db::table("shop_admin")->where("admin_name",$admin_name)->find();
            $admin_pwd=md5(md5($admin_pwd).$data["admin_sult"]);
            //var_dump($admin_pwd);die;
            $admin = Db::table("shop_admin")
                ->field(["admin_id","admin_name","admin_time"])
                ->where("admin_name", $admin_name)
                ->where("admin_pwd", $admin_pwd)
                ->find();
            if ($admin) {
                Db::table("shop_admin")->where("admin_id",$admin["admin_id"])
                    ->update(["admin_time"=>$time,"admin_ip"=>$admin_ip]);
                if($save==1){
                    Cookie::set("admin",$admin,3600);
                }
                   Session::set("admin",$admin);
                $this->success("登录成功", "Index/index");
            } else {
                $this->error("登录失败");
            }
        }
    }
    public function loginout(){
        Cookie::delete("admin");
        Session::delete("admin");
        $this->success("退出成功","login/login");
    }
}
