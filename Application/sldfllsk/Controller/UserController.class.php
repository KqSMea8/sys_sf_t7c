<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {

    //用户列表
   public function index(){
    if($id=I('get.id'))$map['id']=array('like',$id);
    if($tel=I('get.tel'))$map['tel']=array('like',$tel);
    if($name=I('get.name'))$map['name']=array('like',$name);
    $count=M('user')->count();
    $page= new \Think\Page($count,15);
    $show=$page->show();
    $list=M('user')->where($map)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    $this->assign('list',$list);
    $this->assign('page',$show);
    $this->display();
   }

   //更改用户冻结状态
   public function state_change(){
    $map['id']=$_GET['id'];
    $state=M('user')->where($map)->getField('dj_state');
    if($state){
        $data['dj_state']=0;
    }else{
        $data['dj_state']=1;
    }
    $re=M('user')->where($map)->save($data);
    if($re){
        exit(json_encode(array('state'=>1,'info'=>'更改成功')));
    }else{
        exit(json_encode(array('state'=>1,'info'=>'更改失败')));
    }

   }

   






}