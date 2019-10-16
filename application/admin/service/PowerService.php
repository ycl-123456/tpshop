<?php
namespace app\admin\service;
use think\Db;

class PowerService{
    public function getOrderPower($powers,$pid=0,$level=0){
        $orderPower=[];
        foreach($powers as $key=>$val){
            //dump($val);
            if($val["power_pid"]==$pid){
                $val["level"]=$level;
                $orderPower[]=$val;
                $orderPower=array_merge($orderPower,$this->getOrderPower($powers,$val["power_id"],$level+1));
            }
        }
        return $orderPower;
    }
    public function addPower($data){
        $power=Db::table("shop_power")->insert($data);
        return $power;
    }

}