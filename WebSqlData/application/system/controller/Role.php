<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\Role as RoleModel;
class Role extends Base
{
    //角色列表
    public function index(){
        return view();
    }
    //分页数据
    public function get_list()
    {
        $pagesize = input('limit');
        $res = RoleModel::paginate($pagesize);
        $this->json_return('0000','获取成功',$res);
    }
    //角色添加
    function add(){
        if(request()->isajax()){
            $data = input('post.');
            $res = RoleModel::insert($data);
            if($res){
                $this->json_return('0000','添加成功');
            }else{
                $this->json_return('0001','添加失败');
            }
        }
        return view();
    }
}