<?php
namespace app\system\controller;
use think\Controller;
use think\Request;
use app\system\model\Articles;
use app\system\model\ArticleCat;
class Article extends Controller
{
    /*排行榜*/
    function ranklist(){}
    /*文章分类 展示*/
    function show(){
        $list=ArticleCat::paginate(2);
        // 把分页数据赋值给模板变量list
        $this->assign('list', $list);
        // 渲染模板输出
        return $this->fetch();
    }
    /*文章分类添加 页面*/
    function cat_add(){
        return view();
    }
    /*文章分类添加 保存*/
    function cat_save(){
        $data=input('post.');
        foreach ($data as $key => $value) {
            if (!$value) {
                $this->error('not have');
            }
            if ($value==' ') {
                $this->error("$key".' not have');
            }
        }
        $add_res=ArticleCat::insert($data);
        if($add_res){
            $this->success('add ok');
        }else{
            $this->error('add no');
        }
    }
    /*文章分类删除*/
    function cat_del(){
        $id=input('id');
        $del_res=ArticleCat::delete($id);
        if($del_res){
            $this->success('del ok');
        }else{
            $this->error('del no');
        }
    }
    /*文章列表 展示*/
    function index(){
        $list=Articles::paginate(2);
        // 把分页数据赋值给模板变量list
        $this->assign('list', $list);
        // 渲染模板输出
        return $this->fetch();
    }
    /*文章添加 页面*/
    function article_add(){
        $type=ArticleCat::all();
        $this->assign('type', $type);
        return view();
    }
    /*文章添加 保存*/
    function article_save(){
        $data=input('post.');
        foreach ($data as $key => $value) {
            if (!$value) {
                $this->error('not have');
            }
            if ($value==' ') {
                $this->error("$key".' not have');
            }
        }
        $add_res=Articles::insert($data);
        if($add_res){
            $this->success('add ok');
        }else{
            $this->error('add no');
        }
    }
    /*文章删除*/
    function article_del(){
        $id=input('id');
        $del_res=Articles::delete($id);
        if($del_res){
            $this->success('del ok');
        }else{
            $this->error('del no');
        }
    }
}