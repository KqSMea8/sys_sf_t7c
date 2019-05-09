<?php
namespace Admin\Controller;
use Think\Controller;
class TypeController extends CommonController {

		public function index(){
        $count=M('type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('type')->where('state=0')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($re as $k=>$v){
        	if($v['pid']==0){
        		$re[$k]['pid']='顶级';
        	}else{
        		$re[$k]['pid']=M('type')->where('id="'.$v['pid'].'"')->getField('name');
        	}
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
      }
	
		// public function type_add(){

		// }


}