<?php
namespace app\admin\model;
use think\Model;

class Type extends Model
{
    protected $pk = 'type_id';
    public function attr()
    {
        return $this->hasMany('attr','type_id');
    }
}
