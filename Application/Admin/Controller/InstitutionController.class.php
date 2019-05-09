<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class InstitutionController extends CommonController {
    public function jg()
    {
        $qy_id = $_GET['qy'];//区域4，type 7
        $qyArr = M('qy')->select();
        $qyArr = get_all_child($qyArr,$qy_id);
        $qy = implode(',',$qyArr);
        if(empty($qy)){
            $qy = $qy_id;
        }else{
            $qy .= ','.$qy_id;
        }
        $type = $_GET['type'];
        $cate = $_GET['cate'];
        switch ($type) {
            case '1':
                $table = M('gzc');
                $view = 'right';
                break;
            case '2':
                $table = M('lvshisws');
                $view = 'ls';
                break;
            case '3':
                $table = M('falvyz');
                $view = 'yz';
                break;
            case '4':
                $table = M('twh');
                $view = 'tj';
                break;
            case '5':
                $table = M('falvfw');
                $view = 'fw';
                break;
            case '6':
                $table = M('sifajd');
                $view = 'jd';
                break;
            case '7':
                $table = M('sfs');
                $view = 'sfs';
                break;
            case '8':
                $table = M('xzjg');
                $view = 'xzjg';
                break;
        }
        $where['qy']=array('in',$qy);
        //分页开始
        $count = $table->where($where)->count();
        $Page       = new \Think\Page($count,8);
        $show       = $Page->show();// 分页显示输出

        $data = $table->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        //分页结束
        // $data = $table->where($where)->select();
        $this->assign('cate',$cate);
        // $this->assign('list',$data);
        $this->assign('list',$data);
        $this->display($view);
    }
	public function index()
	{
		$count=M('gzc')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('gzc')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}

	//公证处编辑
	public function gzc_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('gzc')->where("id = {$id}")->find();
            if($_FILES['jglg']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['jglg'] = '/Uploads'.$upload['jglg']['savepath'].$upload['jglg']['savename'];
            }else{
                if($id){
                    $_POST['jglg'] = $list['jglg'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传形象标识')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('gzc')->where("id = {$id}")->save($_POST);
            }else{
                if(empty($_POST['jgbm'])||M('gzc')->where("jgbm = {$_POST['jgbm']}")->find()){
                    exit(json_encode(array('status'=>'-1','info'=>'机构编码不能为空或已存在')));
                }
            	$_POST['createtime'] = time();
                $res = M('gzc')->add($_POST);
            }
            if($res){
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>1]))));
            }else{

                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$_POST['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            if($_GET['pid']){
                $this->assign('pid',$_GET['pid']);
            }
            $id = $_GET['id'];
            if($id){
                $list = M('gzc')->where("id = {$id}")->find();
                $this->assign('list',$list);
                $this->assign('pid',$list['pid']);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function gzc_del()
    {

        $list = M('gzc')->where("id = {$id}")->find();
        $res = M('gzc')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>1]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function xzjg()
    {
        $count=M('xzjg')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('xzjg')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $qy = M('qy')->where("id = {$value['qy']}")->find();
            $re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //公证处编辑
    public function xzjg_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('xzjg')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('xzjg')->where("id = {$id}")->save($_POST);
            }else{
                if(empty($_POST['jgbm'])||M('xzjg')->where("jgbm = {$_POST['jgbm']}")->find()){
                    exit(json_encode(array('status'=>'-1','info'=>'机构编码不能为空或已存在')));
                }
                $_POST['createtime'] = time();
                $res = M('xzjg')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>8]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('xzjg')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function xzjg_del()
    {
        $list = M('xzjg')->find($_GET['id']);
        $res = M('xzjg')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>8]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function ls()
	{
		$count=M('lvshisws')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('lvshisws')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//律师事务所编辑
	public function ls_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('lvshisws')->where("id = {$id}")->find();
            if($_FILES['logo']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['logo'] = '/Uploads'.$upload['logo']['savepath'].$upload['logo']['savename'];
            }else{
                if($id){
                    $_POST['logo'] = $list['logo'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传形象标识')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('lvshisws')->where("id = {$id}")->save($_POST);
            }else{
            	$_POST['createtime'] = time();
                $res = M('lvshisws')->add($_POST);
            }
            if($res){
                if($_FILES['logo']['name']){
                    $file = __EMAIL__.$list['logo'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>2]))));
            }else{
                if($_FILES['logo']['name']){
                    $file = __EMAIL__.$_POST['logo'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('lvshisws')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function ls_del()
    {
        $list = M('lvshisws')->where("id = {$id}")->find();
        $res = M('lvshisws')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['logo'];
                    @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>2]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function yz()
	{
		$count=M('falvyz')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('falvyz')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//法律援助机构编辑
	public function yz_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('falvyz')->where("id = {$id}")->find();
            if($_FILES['jglg']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['jglg'] = '/Uploads'.$upload['jglg']['savepath'].$upload['jglg']['savename'];
            }else{
                if($id){
                    $_POST['jglg'] = $list['jglg'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传形象标识')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('falvyz')->where("id = {$id}")->save($_POST);
            }else{
            	$_POST['createtime'] = time();
                $res = M('falvyz')->add($_POST);
            }
            if($res){
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>3]))));
            }else{
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$_POST['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('falvyz')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function yz_del()
    {
        $list = M('falvyz')->where("id = {$id}")->find();
        $res = M('falvyz')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>3]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function tj()
	{
		$count=M('twh')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('twh')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//调委会机构编辑、添加
	public function tj_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('twh')->where("id = {$id}")->find();
          if($_FILES['jglg']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['jglg'] = '/Uploads'.$upload['jglg']['savepath'].$upload['jglg']['savename'];
            }else{
                if($id){
                    $_POST['jglg'] = $list['jglg'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传调委会形象标识')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('twh')->where("id = {$id}")->save($_POST);
            }else{
            	$_POST['createtime'] = time();
                $res = M('twh')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>4]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('twh')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function tj_del()
    {
        $list = M('twh')->where("id = {$id}")->find();
        $res = M('twh')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>4]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function fw()
	{
		$count=M('falvfw')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('falvfw')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//法律服务所编辑
	public function fw_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('falvfw')->where("id = {$id}")->find();
            if($_FILES['jglg']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['jglg'] = '/Uploads'.$upload['jglg']['savepath'].$upload['jglg']['savename'];
            }else{
                if($id){
                    $_POST['jglg'] = $list['jglg'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传形象标识')));
                }
            }

            if($_POST['id']){
                unset($_POST['id']);
                $res = M('falvfw')->where("id = {$id}")->save($_POST);
            }else{
            	$_POST['createtime'] = time();
                $res = M('falvfw')->add($_POST);
            }
            if($res){
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>5]))));
            }else{
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$_POST['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('falvfw')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function fw_del()
    {
        $list = M('falvfw')->where("id = {$id}")->find();
        $res = M('falvfw')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>5]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function jd()
	{
		$count=M('sifajd')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('sifajd')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$qy = M('qy')->where("id = {$value['qy']}")->find();
        	$re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//法律鉴定编辑
	public function jd_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('sifajd')->where("id = {$id}")->find();
            if($_FILES['jglg']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['jglg'] = '/Uploads'.$upload['jglg']['savepath'].$upload['jglg']['savename'];
            }else{
                if($id){
                    $_POST['jglg'] = $list['jglg'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传形象标识')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                if($_POST['jd_type']){
                    $_POST['jd_type'] = implode(',',$_POST['jd_type']);
                }
                $res = M('sifajd')->where("id = {$id}")->save($_POST);
            }else{
            	$_POST['createtime'] = time();
                if(!$_POST['jd_type']){
                    exit(json_encode(array('status'=>'-1','info'=>'未选择鉴定类别')));
                }
                $_POST['jd_type'] = implode(',',$_POST['jd_type']);
                $res = M('sifajd')->add($_POST);
            }
            if($res){
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>6]))));
            }else{
                if($_FILES['jglg']['name']){
                    $file = __EMAIL__.$_POST['jglg'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('sifajd')->where("id = {$id}")->find();
                $this->assign('list',$list);
              	//机构鉴定类别
              	$jd_type_checked = explode(',',$list['jd_type']);
              	$this->assign('jd_type_checked',$jd_type_checked);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            $this->assign('jd_type',M('jd_type')->where("status=1")->select());
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function jd_del()
    {
        $list = M('sifajd')->where("id = {$id}")->find();
        $res = M('sifajd')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['jglg'];
                    @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>6]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

	public function qy()
	{
		$count=M('qy')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('qy')->limit($page->firstRow.','.$page->listRows)->select();
        $re=getTree($re);
        foreach ($re as $key => $value) {
            $re[$key]['name'] = str_repeat('——',$value['level']).$value['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}

	public function qy_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('qy')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('qy')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('qy')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/qy'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('qy')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = getTree(M('qy')->select());

            foreach ($qy as $key => $value) {
                $qy[$key]['name'] = str_repeat('——',$value['level']).$value['name'];
            }
            $this->assign("qy",$qy);
            $this->display();
        }
    }
    public function qy_del()
    {
        $res = M('qy')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/qy'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function sfs()
    {
        $count=M('sfs')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('sfs')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $qy = M('qy')->where("id = {$value['qy']}")->find();
            $re[$key]['qy'] = $qy['name'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function sfs_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('sfs')->where("id = {$id}")->find();
            if($_FILES['sfszp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['sfszp'] = '/Uploads'.$upload['sfszp']['savepath'].$upload['sfszp']['savename'];
            }else{
                if($id){
                    $_POST['sfszp'] = $list['sfszp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            if($id){
                unset($_POST['id']);
                $res = M('sfs')->where("id = {$id}")->save($_POST);
            }else{
                if(empty($_POST['sfsbm'])||M('sfs')->where("sfsbm = {$_POST['sfsbm']}")->find()){
                    exit(json_encode(array('status'=>'-1','info'=>'机构编码不能为空或已存在')));
                }
                $_POST['createtime'] = time();
                $res = M('sfs')->add($_POST);
            }
            if($res){
                if($_FILES['sfszp']['name']){
                    $file = __EMAIL__.$list['sfszp'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jg',['qy'=>$_POST['qy'],'type'=>7]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'请认真填写信息')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('sfs')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $qy = M('qy')->select();
            $this->assign('qy',$qy);
            if($_GET['qy']){
                $this->assign('qy_id',$_GET['qy']);
            }
            $this->display();
        }
    }
    public function sfs_del()
    {
        $list = M('sfs')->where("id = {$_GET['id']}")->find();
        $res = M('sfs')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['sfszp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jg',['qy'=>$list['qy'],'type'=>7]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function gz_member()
    {
        $id = $_GET['id'];
        $gzc = M('gzc')->where("id = {$id}")->find();
        $count=M('gz_member')->where("ssjgbm = '{$gzc['jgbm']}'")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('gz_member')->where("ssjgbm = '{$gzc['jgbm']}'")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //  服务时间
    public function gz_member_statistics()
    {
        if(IS_POST){
            $tl = $_POST['tl'] ? $_POST['tl'] : '';
            $tr = $_POST['tr'] ? $_POST['tr'] : '';
            $id = $_POST['id'];
            if($tl == '' && $tr == ''){
                $tl = strtotime(date('Y-m-d'))-7*24*3600;
                $tr = $tl + 24*3600*15;
            }else if($tl == ''){
                $tr = strtotime($tr)+24*3600;
                $tl = $tr - 24*3600*15;
            }else if($tr == ''){
                $tl = strtotime($tl);
                $tr = $tl + 24*3600*15;
            }else{
                $tl = strtotime($tl);
                $tr = strtotime($tr)+24*3600;
            }
        }else{
            $id = $_GET['id'];
            $tl = strtotime(date('Y-m-d'))-7*24*3600;
            $tr = $tl + 24*3600*15;
        }
        if($id){
            $personal = M('gz_member g')->join('pa_personal p on p.zjh = g.sfzh and p.lxdh = g.sjhm')->where('g.id = '.$id)->find();
            $data = M('chat')->where('(uid = '.$personal['id'].' or uid2 = '.$personal['id'].') and ct_time >= '.$tl.' and ct_time <= '.$tr)->order('ct_time asc')->select();
            //print_r($data);
            $t = 180;
            $list['categories'] = [];
            $list['count'] = [];
            $list['hnum'] = [];
            $list['ctime'] = [];
            for($at = $tl;$at < $tr;$at+=24*3600){
                $hid = [];
                $ttime = 0;
                $stime = 0;
                $count = 0;
                $list['categories'][] = date('Y-m-d',$at);
                foreach ($data as $k => $v) {
                    if($v['ct_time'] >= $at && $v['ct_time'] < $at+24*3600){
                        if(!$hid[$v['uid']] && $v['uid'] != $personal['id']) $hid[$v['uid']] = 1;
                        if($v['uid'] == $personal['id']){
                            if($stime != 0){
                                if($stime+$t < $v['ct_time']) $ttime += $t;
                                else $ttime += $v['ct_time']-$stime;
                            }
                            $stime = $v['ct_time'];
                        }
                        ++$count;
                    }
                }
                $list['hnum'][] = count($hid);
                $list['ctime'][] = round($ttime/60,2);
                $list['count'][] = $count;
            }
            $hid = [];
            $ttime = 0;
            $stime = 0;
            foreach ($data as $k => $v) {
                if(!$hid[$v['uid']] && $v['uid'] != $personal['id']) $hid[$v['uid']] = 1;
                if($v['uid'] == $personal['id']){
                    if($stime != 0){
                        if($stime+$t < $v['ct_time']) $ttime += $t;
                        else $ttime += $v['ct_time']-$stime;
                    }
                    $stime = $v['ct_time'];
                }
            }
            if($ttime != 0) $ttime += $t;
            $this->assign('tl',date('Y-m-d',$tl)); //  回复总数
            $this->assign('tr',date('Y-m-d',$tr)); //  回复总数
            $this->assign('count',count($data)); //  回复总数
            $this->assign('hnum',count($hid));  //  服务人数
            $this->assign('ctime',floor($ttime/60).'分钟'.($ttime%60).'秒');   //  服务总时间
            $this->assign('list',json_encode($list));
            $this->assign('id',$id);
            $this->display();
        }
    }
    //公证处人员编辑
    public function gz_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $gzc = M('gzc')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('gz_member')->where("id = {$id} and ssjgbm = '{$gzc['jgbm']}'")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($user) {

                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        //exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('gz_member')->where("id = {$id}")->save($_POST);
            }else{
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                $_POST['createtime'] = time();
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $gzc['jgmc'];
                $data['yx'] = $_POST['dzyx'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 1;
              	echo '<pre>';var_dump($res,$ress,$user);exit;
              	if(!$user){
                  	
                	$ress = M('personal')->add($data);
                  	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
              
               $res = M('gz_member')->add($_POST);
            }
          	
            if($res !== false && $ress){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/gz_member',['id'=>$gid]))));
            }else{
                    M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('gz_member')->where("id = {$id}")->find();
                $this->assign('list',$list);
                // echo '<pre>';
                // var_dump($list);
                // exit;
            }
            $gzc = M('gzc')->where("id = {$gid}")->find();
            $this->assign('jgbm',$gzc['jgbm']);
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function gz_member_del()
    {
        $gid = $_GET['gid'];
        
        $gzc = M('gzc')->where("id = {$gid}")->find();
        $list = M('gz_member')->where("id = {$_GET['id']} and ssjgbm = '{$gzc['jgbm']}'")->find();
        $res = M('gz_member')->delete($_GET['id']);
        $res_user = M('personal')->where("id = {$list['uid']}")->delete();
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/gz_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function ls_member()
    {
        $id = $_GET['id'];
        $lvshisws = M('lvshisws')->where("id = {$id}")->find();
        $count=M('ls')->where("zyjg = {$lvshisws['id']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('ls')->where("zyjg = {$lvshisws['id']}")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $re[$key]['zyjg'] = $lvshisws['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //律师人员 编辑/添加
    public function ls_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $lvshisws = M('lvshisws')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('ls')->where("id = {$id} and zyjg = {$lvshisws['id']}")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($user){
                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('ls')->where("id = {$id}")->save($_POST);
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['yx'] = $_POST['dzyx'];
                $data['gzdw'] = $lvshisws['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 5;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('ls')->add($_POST);
            }
            if($res && $ress){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/ls_member',['id'=>$gid]))));
            }else{
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('ls')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function ls_member_del()
    {
        $gid = $_GET['gid'];
        
        $lvshisws = M('lvshisws')->where("id = {$gid}")->find();
        $list = M('ls')->where("id = {$_GET['id']} and zyjg = {$lvshisws['id']}")->find();
        $res = M('ls')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/ls_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function yz_member()
    {
        $id = $_GET['id'];
        $falvyz = M('falvyz')->where("id = {$id}")->find();

        $count=M('yz')->where("ssjg = {$falvyz['id']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('yz')->where("ssjg = {$falvyz['id']}")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $re[$key]['ssjg'] = $falvyz['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //法律援助人员编辑
    public function yz_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $falvyz = M('falvyz')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('yz')->where("id = {$id} and ssjg = {$falvyz['id']}")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($user) {
                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        //exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('yz')->where("id = {$id}")->save($_POST);
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user && $user['yhlx'] == 1) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $falvyz['jgmc'];
                $data['yx'] = $_POST['dzyj'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                //$data['splx'] = 5;
              	$data['splx'] = 2;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('yz')->add($_POST);
            }
            if($res && $ress){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/yz_member',['id'=>$gid]))));
            }else{
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('yz')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function yz_member_del()
    {
        $gid = $_GET['gid'];
        
        $falvyz = M('falvyz')->where("id = {$gid}")->find();
        $list = M('yz')->where("id = {$_GET['id']} and ssjg = {$falvyz['id']}")->find();
        $res = M('yz')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/yz_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function tj_member()
    {
        $id = $_GET['id'];
        $twh = M('twh')->where("id = {$id}")->find();

        $count=M('tjy')->where("sstwh = {$twh['id']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('tjy')->where("sstwh = {$twh['id']}")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $re[$key]['sstwh'] = $twh['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //调解员编辑
    public function tj_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $twh = M('twh')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('tjy')->where("id = {$id} and sstwh = {$twh['id']}")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }

            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($user) {
                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('tjy')->where("id = {$id}")->save($_POST);
                if($res !== false){$res = true;}
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user && $user['splx'] == 1) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $twh['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 3;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {

                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('tjy')->add($_POST);
            }
            if($res && $ress){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/tj_member',['id'=>$gid]))));
            }else{
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('tjy')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function tj_member_del()
    {
        $gid = $_GET['gid'];
        
        $twh = M('twh')->where("id = {$gid}")->find();
        $list = M('tjy')->where("id = {$_GET['id']} and sstwh = {$twh['id']}")->find();
        $res = M('tjy')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/tj_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function fw_member()
    {
        $id = $_GET['id'];
        $falvfw = M('falvfw')->where("id = {$id}")->find();

        $count=M('fw')->where("gzdw = {$falvfw['id']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('fw')->where("gzdw = {$falvfw['id']}")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $re[$key]['gzdw'] = $falvfw['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //基层法律工作者编辑
    public function fw_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $falvfw = M('falvfw')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('fw')->where("id = {$id} and gzdw = {$falvfw['id']}")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($user) {
                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('fw')->where("id = {$id}")->save($_POST);
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();	
                if ($user && $user['splx'] == 1) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $falvfw['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 7;
              	if($user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {

                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('fw')->add($_POST);
            }
            if($res && $ress){
                M()->commit();
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/fw_member',['id'=>$gid]))));
            }else{
                M()->rollback();
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$_POST['zp'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('fw')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function fw_member_del()
    {
        $gid = $_GET['gid'];
        $falvfw = M('falvfw')->where("id = {$gid}")->find();
        $list = M('fw')->where("id = {$_GET['id']} and gzdw = {$falvfw['id']}")->find();
        $res = M('fw')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/fw_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function jd_member()
    {
        $id = $_GET['id'];
        $sifajd = M('sifajd')->where("id = {$id}")->find();
        $count=M('jd')->where("szjgbh = '{$sifajd['jgbh']}'")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('jd')->where("szjgbh = '{$sifajd['jgbh']}'")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($re as $key => $value) {
            $re[$key]['szjgbh'] = $sifajd['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //基层法律工作者编辑
    public function jd_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $sifajd = M('sifajd')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('jd')->where("id = {$id} and szjgbh = '{$sifajd['jgbh']}'")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                  	$myinfo = M('jd')->where("id = {$id}}'")->find();
                    if ($user) {
                      	if($user['id'] != $myinfo['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('jd')->where("id = {$id}")->save($_POST);
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user && $user['splx'] == 1) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $sifajd['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 4;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('jd')->add($_POST);
            }
            if($res){
                M()->commit();
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/jd_member',['id'=>$gid]))));
            }else{
                M()->rollback();
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$_POST['zp'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('jd')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $sifajd = M('sifajd')->where("id = {$gid}")->find();
            $this->assign('jgbh',$sifajd['jgbh']);
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function jd_member_del()
    {
        $gid = $_GET['gid'];
        $sifajd = M('sifajd')->where("id = {$gid}")->find();
        $list = M('jd')->where("id = {$_GET['id']} and szjgbh = '{$sifajd['sfsbm']}'")->find();
        $res = M('jd')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/jd_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function sfs_member()
    {
        $id = $_GET['id'];
        $sfs = M('sfs')->where("id = {$id}")->find();
        $count=M('sfs_member')->where("sfsbm = {$sfs['sfsbm']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('sfs_member')->where("sfsbm = '{$sfs['sfsbm']}'")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($re as $key => $value) {
            $re[$key]['zyjg'] = $sfs['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //司法所人员编辑
    public function sfs_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $sfs = M('sfs')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('sfs_member')->where("id = {$id} and sfsbm = '{$sfs['sfsbm']}'")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }

            M()->startTrans();
            if($_POST['id']){

                $my_info = M('sfs_member')->where('id = "'.$_POST['id'].'"')->find();
                $ress = $res2 = $res3 = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }

                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);

                    $exists = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                    if ($exists) {
                        //如果查询到的用户id与当前用户不同，说明有别人注册了此电话号码
                        if($my_info['uid'] != $exists['id']){
                            exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用1！')));
                        }
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }

                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }

                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }

                $res = M('sfs_member')->where("id = {$id}")->save($_POST);

            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user && $user['splx'] == 1) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzhm'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzhm'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $sfs['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 8;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('sfs_member')->add($_POST);
                $datas['nickname'] = $_POST['xm'];
                $datas['email'] = $_POST['dzyx'];
                $datas['pwd'] = encrypt1($_POST['pwd']);
                $datas['status']=1;
                $datas['loc']=$_POST['zp'];
                $datas['time']=time();
                $datas['mid']=$res;
                $res2 = M('admin')->add($datas);
                $res3 = M('role_user')->add(['role_id'=>11,'user_id'=>$res2]);
            }
            if($res && $ress && $res2 && $res3){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/sfs_member',['id'=>$gid]))));
            }else{
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$_POST['zp'];
                    @unlink($file);
                }
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('sfs_member')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $sfs = M('sfs')->where("id = {$gid}")->find();
            $this->assign('sfsbm',$sfs['sfsbm']);
            $this->assign('gid',$gid);
            $this->display();
        }
    }
    public function sfs_member_del()
    {
        $gid = $_GET['gid'];
        $sfs = M('sfs')->where("id = {$gid}")->find();
        $list = M('sfs_member')->where("id = {$_GET['id']} and sfsbm = '{$sfs['sfsbm']}'")->find();
        $res = M('sfs_member')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/sfs_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function xzjg_member()
    {
        $id = $_GET['id'];
        $xzjg = M('xzjg')->where("id = {$id}")->find();
        $count=M('xzjg_member')->where("ssjgbm = {$xzjg['jgbm']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('xzjg_member')->where("ssjgbm = '{$xzjg['jgbm']}'")->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($re as $key => $value) {
            $re[$key]['zyjg'] = $xzjg['jgmc'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('gid',$id);
        $this->display();
    }
    //司法机关编辑 行政机构人员添加
    public function xzjg_member_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $gid = $_POST['gid'];
            $xzjg = M('xzjg')->where("id = {$gid}")->find();
            unset($_POST['gid']);
            $list = M('xzjg_member')->where("id = {$id} and ssjgbm = '{$xzjg['jgbm']}'")->find();
            if($_FILES['zp']['name']){
                $upload = $this->upload_file('Institution');
                $_POST['zp'] = '/Uploads'.$upload['zp']['savepath'].$upload['zp']['savename'];
            }else{
                if($id){
                    $_POST['zp'] = $list['zp'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            M()->startTrans();
            if($_POST['id']){
                $ress = true;
                unset($_POST['id']);
                if(!empty($_POST['pwd'])){
                    $save['mm'] = md5($_POST['pwd']);
                }
                if(!empty($_POST['sjhm'])){
                    checkmobile($_POST['sjhm']);
                  	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                  
                    if ($user) {
                      	if($user['id'] != $list['uid']){
                        	exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                        }
                        
                    }
                    $save['lxdh'] = $_POST['sjhm'];
                }
                if(!empty($_POST['sfzh'])){
                    if(validation_filter_id_card($_POST['sfzh'])!=true){
                        exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                    }
                    // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                    //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                    // }
                    $save['zjh'] = $_POST['sfzh'];
                }
                if(!empty($save)){
                    M('personal')->where("id={$list['uid']}")->save($save);
                }
                $res = M('xzjg_member')->where("id = {$id}")->save($_POST);
            }else{
                $_POST['createtime'] = time();
                if(empty($_POST['pwd'])){
                    exit(json_encode(array('status'=>'-1','info'=>'密码不能为空')));
                }
                checkmobile($_POST['sjhm']);
              	$user = M('personal')->where('lxdh = "'.$_POST['sjhm'].'"')->find();
                if ($user) {
                    exit(json_encode(array('status'=>'-1','info'=>'该手机号已经被使用！')));
                }
                if(validation_filter_id_card($_POST['sfzh'])!=true){
                    exit(json_encode(array('status'=>'-1','info'=>'身份证号格式有误')));
                }
                // if (M('personal')->where('zjh = "'.$_POST['sfzh'].'"')->find()) {
                //     exit(json_encode(array('status'=>'-1','info'=>'该身份证号已经被使用！')));
                // }
                $data['xm'] = $_POST['xm'];
                $data['zjh'] = $_POST['sfzh'];
                $data['lxdh'] = $_POST['sjhm'];
                $data['mm'] = md5($_POST['pwd']);
                $data['xb'] = $_POST['xb'];
                $data['tx'] = $_POST['zp'];
                $data['gzdw'] = $xzjg['jgmc'];
                $data['zcsj'] = date("Y-m-d H:i:s",time());
                $data['zhxgsj'] = date("Y-m-d H:i:s",time());
                $data['zcip'] = get_client_ip();
                $data['yhlx'] = 2;
                $data['splx'] = 9;
              	if(!$user){
                	$ress = M('personal')->add($data);
                	$_POST['uid'] = $ress;
                } else {
                	$_POST['uid'] = $user['id'];
                }
                
                $res = M('xzjg_member')->add($_POST);
            }
            if($res && $ress){
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$list['zp'];
                    @unlink($file);
                }
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Institution/xzjg_member',['id'=>$gid]))));
            }else{
                if($_FILES['zp']['name']){
                    $file = __EMAIL__.$_POST['zp'];
                    @unlink($file);
                }
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $gid = $_GET['gid'];
            if($id){
                $list = M('xzjg_member')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('gid',$gid);
            $this->display();
            $xzjg = M('xzjg')->where("id = {$gid}")->find();
            $this->assign('jgbm',$xzjg['jgbm']);
            $this->assign('gid',$gid);
        }
    }
    public function xzjg_member_del()
    {
        $gid = $_GET['gid'];
        $xzjg = M('xzjg')->where("id = {$gid}")->find();
        $list = M('xzjg_member')->where("id = {$_GET['id']} and ssjgbm = '{$xzjg['jgbm']}'")->find();
        $res = M('xzjg_member')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['zp'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Institution/xzjg_member',['id'=>$gid]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
}