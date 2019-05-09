<?php
namespace Admin\Controller;
use Think\Controller;
class AppController extends CommonController {
	public function index()
	{
		$count=M('edition')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('edition')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	public function add()
	{
		if(IS_POST){
			if(empty($_POST['title']) || empty($_POST['number']) || empty($_POST['url']) || empty($_POST['size']) || empty($_POST['info']) || empty($_POST['content'])){
				exit(json_encode(array('status'=>'-1','info'=>'信息上传不完整')));
			}
			$id = $_POST['id'];
			unset($_POST['id']);
			$data = $_POST;
			$data['ct_time'] = time();
			if($id){
				$res = M('edition')->where("id = {$id}")->save($data);
			}else{
				$res = M('edition')->add($data);
			}
			if($res){
				exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('App/index'))));
			}else{
				exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
			}
		}
		if($_GET['id']){
			$this->assign("info",M('edition')->find($_GET['id']));
		}
		$this->display();
	}
	public function del()
	{
		if(!$_GET['id']){
			exit(json_encode(array('status'=>'-1','info'=>'参数错误')));
		}
		$res = M('edition')->delete($_GET['id']);
		if($res){
			exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('App/index'))));
		}else{
			exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
		}
	}
}