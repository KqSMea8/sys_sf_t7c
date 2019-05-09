<?php
namespace Admin\Controller;
use Think\Controller;
class CaseController extends CommonController {


	public function index()
	{
		$count=M('case')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('case')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$type = M('case_type')->where("id = {$value['type_id']}")->find();
        	$re[$key]['name'] = $type['name'];
            $qy = M('qy')->where("id = {$value['qy']}")->find();
            $re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}

    public function case_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('case')->where("id = {$id}")->find();
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Case');
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
                if($id){
                    $_POST['img_url'] = $list['img_url'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传封面图片')));
                }
            }
            if($id){
                unset($_POST['id']);
                $res = M('case')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['type_id'] || $_POST['type_id']==0 || !$_POST['title'] || !$_POST['content'] || !$_POST['intro']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['createtime'] = time();
                $res = M('case')->add($_POST);
            }
            if($res){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Case/index'))));
            }else{
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$_POST['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('case')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $type = M('case_type')->select();
            $this->assign('type',$type);
            $this->assign('qy',M('qy')->select());
            $this->display();
        }
    }
    public function case_del()
    {
        $list = M('case')->where("id = {$_GET['id']}")->find();
        $res = M('case')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['img_url'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Case/index'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function case_type()
    {
        $count=M('case_type')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('case_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function case_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('case_type')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('case_type')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('case_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Case/case_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('case_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function case_type_del()
    {
        $res = M('case_type')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Case/case_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function changeAttr(){
        $map['id']=$_GET['id'];
        $state=M('case')->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M('case')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }
   public function changeStatus(){
        $map['id']=$_GET['id'];
        $state=M('case')->where($map)->getField('is_recommend');
        $data['is_recommend']=abs($state-1);
        if(M('case')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['is_recommend'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }
}