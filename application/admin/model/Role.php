<?php
namespace app\admin\model;
use think\Db;
use think\Model;
class Role extends Model
{
    protected $pk = 'role_id';
    public function power(){
        return $this->belongsToMany("Power",'role_power',"power_id","role_id");
    }
}