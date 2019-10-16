<?php
namespace app\admin\service;
use app\admin\model\Admin;
use app\admin\model\Role;

class RoleService{
    public function getRole(){
        $role=new Role();
        return $role->all();
    }
}