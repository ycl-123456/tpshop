<?php
namespace app\admin\model;
use think\Db;
use think\Model;
class Goods extends Model
{
    protected $pk = 'goods_id';
    public function Attr(){
        return $this->belongsToMany("Attr");
    }
    public function comments()
    {
        return $this->hasMany('Image','goods_id');
    }
}