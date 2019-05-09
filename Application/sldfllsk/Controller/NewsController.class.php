<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends CommonController {

   //问答列表
      public function index(){
        $count=M('answer')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('answer')->where('state=0')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
      }

    //问答发布
    public function wd_issue(){
        if(IS_POST){
            $id=$_GET['id'];
            $data['problem']=$_POST['problem'];
            $data['answer']=$_POST['answer'];
            $data['stime']=time();
            $data['state']=$_POST['state'];
            if($id){
                if(M('answer')->where('id="'.$id.'"')->save($data)){
                     exit(json_encode(array('state'=>1,'info'=>'编辑成功','url'=>U('News/index'))));
                }else{
                    exit(json_encode(array('state'=>-1,'info'=>'编辑失败','url'=>U('News/index'))));
                }
                
            }else{
                if(M('answer')->add($data)){
                    exit(json_encode(array('state'=>1,'info'=>'添加成功','url'=>U('News/index'))));
                }else{
                    exit(json_encode(array('state'=>-1,'info'=>'添加失败','url'=>U('News/index'))));
            }
            }
        }else{
            $id=$_GET['id'];
            $re=M('answer')->where('id="'.$id.'"')->find();
            $this->assign('list',$re);
            $this->display();
        }
        
    }

    

    //问答状态变更
    public function change(){
        $data['id']=$_GET['id'];
        $state=M('answer')->where('id= "'.$data['id'].'"')->getField('state');
        if($state == 1){
            $data['state']=0;
        }else{
            $data['state']=1;
        }
        $re=M('answer')->where('id = "'.$data['id'].'"')->save($data);    
        if($re){
            exit(json_encode(array('state'=>1,'msg'=>'更改成功','data'=>$data['state'])));
        }else{
            exit(json_encode(array('state'=>-1,'msg'=>'更改失败')));
        }

    }

//问答删除
    public function wd_del(){
        $data['id']=$_GET['id'];
        $re=M('answer')->where('id = "'.$data['id'].'"')->delete();
        if($re){
            exit(json_encode(array('status'=>1,'info'=>'删除成功','url'=>U('News/index'))));
        }else{
            exit(json_encode(array('status'=>0,'info'=>'删除失败','url'=>U('News/index'))));
        }
    }











    }

   

