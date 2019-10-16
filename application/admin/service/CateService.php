<?php
namespace app\admin\service;

use think\Db;

class CateService{
    public function getCateByRecursion($cate,$pid=0,$level=0){
        $orderCate=[];
        foreach($cate as $key => $value) {
            if($value["cate_pid"]==$pid){
                $value["level"]=$level;
                $orderCate[]=$value;
                $orderCate=array_merge($orderCate,$this->getCateByRecursion($cate,$value["cate_id"],$level+1));
            }
        }
        return $orderCate;
    }
}