<?php
namespace app\admin\controller;
use app\admin\model\Powermodel;
use think\Db;
class Power extends Common
{
    public function add_power(){
        if(request()->isGet()){
            $power=new Powermodel();
            $powers=$power->getPowers();
            return view("",["powers"=>$powers]);
        }
        if(request()->isPost()){
            $data["power_name"]=request()->post("power_name","");
            $data["power_pid"]=request()->post("power_pid","");
            $data["power_ismu"]=request()->post("power_ismu","");
            $data["power_controller"]=request()->post("power_controller","");
            $data["power_action"]=request()->post("power_action","");
            $power=new Powermodel();
            $powers=$power->addPower($data);
            if($powers){
                $this->success("添加成功","Power/show_power");
            }else{
                $this->error("添加失败");
            }

        }

    }
    public function show_power(){
        $power=Db::table("shop_power")->select();
        return view("",["powers"=>$power]);
    }
}