<?php
namespace Admin\Controller;
use Think\Controller;
// 本类设置项目一些常用信息
class CompanyController extends CommonController {

    //公司列表
      public function index(){
        $count=M('company')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('company')->where('sh_state=1')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
      }

      //待审核公司列表
      public function company_sh(){
        $count=M('company')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('company')->where('sh_state=0')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
      }

      

      //公司冻结状态
      public function change_dj(){
        $id=$_GET['id'];
        $state=M('company')->where('id="'.$id.'"')->getField('dj_state');
        if($state){
          $data['dj_state']=0;
        }else{
          $data['dj_state']=1;
        }
        $re=M('company')->where('id="'.$id.'"')->save($data);
        if($re){
          echo json_encode(array('state'=>1,'info'=>'更改成功'));
          exit;
        }else{
          echo json_encode(array('state'=>-1,'info'=>'更改失败'));
        }
      }

      //已投递的简历
      public function resume_list(){
        $name=$_POST['name'];
        $re=M('deliver')->where('cid="'.$cid.'"')->field('uid')->select();
        foreach($re as $k=>$v){
            $info[$k]=M('user')->where('id="'.$v.'"')->field('name,age,sex,resume')->find();
        }
        $this->assign('list',$info);
        $this->display();
      } 
}

?>