<?php
namespace Admin\Controller;
use Think\Controller;
class ServiceController extends CommonController{
    //咨询区域维护
    public function index()
    {
        $count=M('serve_area')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('serve_area')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //咨询区域编辑
    public function serve_area_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('serve_area')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('serve_area')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('serve_area')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Service/index'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('serve_area')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('qy',M('qy')->select());
            $this->display();
        }
    }
    public function serve_area_del()
    {
        if(M('serve_type')->where("area_id = {$_GET['id']}")->find()){
            exit(json_encode(array('status'=>'-1','info'=>'此区域下有咨询类别，不可删除')));
        }
        $res = M('serve_area')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Service/index'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
	//咨询类别维护
	public function serve_type()
	{
		$count=M('serve_type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('serve_type')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('serve_area')->find($value['area_id']);
        	$re[$key]['area'] = $qy['title'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//咨询类别编辑
	public function serve_type_add()
	{
		if(IS_POST){
            $id = $_POST['id'];
            $list = M('serve_type')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('serve_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title'] || !$_POST['area_id']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('serve_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Service/serve_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('serve_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('qy',M('serve_area')->select());
            $this->display();
        }
	}
    public function serve_type_del()
    {
        $res = M('serve_type')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Service/serve_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function serve_member()
    {
        $count=M('serve_member')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('serve_member')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $type = M('serve_type')->find($value['type']);
            $re[$key]['type'] = $type['title'];
            switch ($value['cate']) {
                case '1':
                $jg = M('gzc')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('gz_member')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '2':
                $jg = M('lvshisws')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('ls')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '3':
                $jg = M('falvyz')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('yz')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '4':
                $jg = M('twh')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('tjy')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '5':
                $jg = M('sfs')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('sfs_member')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '6':
                $jg = M('sifajd')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('jd')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '7':
                $jg = M('falvfw')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('fw')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            case '8':
                $jg = M('xzjg')->field('jgmc')->find($value['jg_id']);
                $re[$key]['jg'] = $jg['jgmc'];
                $member_data = M('xzjg_member')->field('id,xm')->find($value['mid']);
                $re[$key]['xm'] = $member_data['xm'];
                break;
            }
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    public function serve_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            unset($_POST['id']);
            $data = $_POST;
            if(empty($data['mid']) || empty($data['uid']) || empty($data['type']) || empty($data['jg_id']) || empty($data['cate'])){
                exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
            }
            if(M('serve_member')->where("mid={$data['mid']} or uid={$data['uid']}")->find()){
                M('serve_member')->where("mid={$data['mid']} or uid={$data['uid']}")->delete();
            }
            if(!empty($id) && $id!=''){
                $res = M('serve_member')->where("id = {$id}")->save($data);
            }else{
                $data['ct_time']=time();
                $res = M('serve_member')->add($data);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Service/serve_member'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $this->assign('sqy',M('serve_area')->select());
            $this->assign('type',M('serve_type')->select());
            $this->assign('qy',M('qy')->select());
            $this->display();
        }
    }
    public function serve_member_del()
    {
        $res = M('serve_member')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Service/serve_member'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //咨询类别区域
    public function serve_area_type()
    {
        $data = M('serve_type')->where("area_id={$_GET['area']}")->field('id,title')->select();
        exit(json_encode(array('status'=>'1','info'=>'成功','data'=>$data)));
    }
    public function details()
    {
        if(!$_GET['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择机构ID']));
        }
        switch ($_GET['type']) {
            case '1':
                $table = M('gzc');
                $organ = $table->where("id = {$_GET['id']}")->field('jgbm')->find();
                $member_data = M('gz_member')->where("ssjgbm = '{$organ['jgbm']}'")->field('id,xm,uid')->select();
                break;
            case '2':
                $member_data = M('ls')->where("zyjg = {$_GET['id']}")->field('id,xm,uid')->select();
                break;
            case '3':
                $member_data = M('yz')->where("ssjg = {$_GET['id']}")->field('id,xm,uid')->select();
                break;
            case '4':
                $member_data = M('tjy')->where("sstwh = {$_GET['id']}")->field('id,xm,uid')->select();
                break;
            case '5':
                // $table = M('sfs');
                // $organ = $table->where("id = {$_GET['id']}")->field('sfsbm')->find();
                // $member_data = M('sfs_member')->where("sfsbm = '{$organ['sfsbm']}'")->field('id,xm,uid')->select();
                $member_data = M('fw')->where("gzdw = {$_GET['id']}")->field('id,xm,uid')->select();
                break;
            case '6':
                $table = M('sifajd');
                $organ = $table->where("id = {$_GET['id']}")->field('jgbh')->find();
                $member_data = M('jd')->where("szjgbh = '{$organ['jgbh']}'")->field('id,xm,uid')->select();
                break;
            case '7':
                // $member_data = M('fw')->where("gzdw = {$_GET['id']}")->field('id,xm,uid')->select();
                $table = M('sfs');
                $organ = $table->where("id = {$_GET['id']}")->field('sfsbm')->find();
                $member_data = M('sfs_member')->where("sfsbm = '{$organ['sfsbm']}'")->field('id,xm,uid')->select();
                break;
            case '8':
                $table = M('xzjg');
                $organ = $table->where("id = {$_GET['id']}")->field('jgbm')->find();
                $member_data = M('xzjg_member')->where("ssjgbm = '{$organ['jgbm']}'")->field('id,xm,uid')->select();
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'无此类型机构或人员']));
                break;
        }
        exit(json_encode(array('status'=>'1','info'=>'成功','data'=>$member_data)));
    }
}