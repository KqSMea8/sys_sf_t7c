<?php
namespace Admin\Controller;
use Think\Controller;
class HandleController extends CommonController{
	public function index()
	{
		if($_GET['status']){
			$where['status'] = $_GET['status'];
		}
		if($_GET['start_time'] && $_GET['end_time']){
            $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
        }
        if($_GET['qy']){
			$where['qy'] = $_GET['qy'];
		}
		if($_GET['jgry_id']){
			$where['jgry_id'] = $_GET['jgry_id'];
		}
		switch ($_GET['type']) {
			case '1':
				$count=M('gzc_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('gzc_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
				$where['type']=1;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$where['type']=1;
				$count=M('order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($_GET['type']==2){
				if($value['approver_id']==0){
					$data[$key]['flag']=1;
				}
			}
		}
		$this->assign('jgry',M('gz_member')->field('id,xm')->select());
		$this->assign("list",$data);
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',1);
		$this->display();
	}
	public function falvyz()
	{
		if($_GET['status']){
			$where['status'] = $_GET['status'];
		}
		if($_GET['jgry_id']){
			$where['jgry_id'] = $_GET['jgry_id'];
		}
		if($_GET['qy']){
			$where['qy'] = $_GET['qy'];
		}
		if($_GET['start_time'] && $_GET['end_time']){
            $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
        }
		switch ($_GET['type']) {
			case '1':
				$count=M('yz_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('yz_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
				$where['type']=2;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$where['type']=2;
				$count=M('order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($value['approver_id']==0){
				$data[$key]['flag']=1;
			}
		}
		// echo '<pre>';
		// var_dump($data);
		// exit;
		$this->assign("list",$data);
		//法律援助
		$this->assign('jgry',M('yz')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',2);
		$this->display('index');
	}
	public function tj()
	{
		if($_GET['status']){
			$where['status'] = $_GET['status'];
		}
		if($_GET['jgry_id']){
			$where['jgry_id'] = $_GET['jgry_id'];
		}
		if($_GET['qy']){
			$where['qy'] = $_GET['qy'];
		}
		if($_GET['start_time'] && $_GET['end_time']){
            $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
        }
		switch ($_GET['type']) {
			case '1':
				$count=M('tj_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('tj_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
			$where['type']=3;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$count=M('tj_order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('tj_order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($value['approver_id']==0){
				$data[$key]['flag']=1;
			}
		}
		$this->assign("list",$data);
		$this->assign('jgry',M('tjy')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',3);
		$this->display('index');
	}
	public function jd()
	{
		if($_GET['status']){
			$where['status'] = $_GET['status'];
		}
		if($_GET['jgry_id']){
			$where['jgry_id'] = $_GET['jgry_id'];
		}
		if($_GET['qy']){
			$where['qy'] = $_GET['qy'];
		}
		if($_GET['start_time'] && $_GET['end_time']){
            $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
        }
		switch ($_GET['type']) {
			case '1':
				$count=M('jd_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('jd_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
				$where['type']=4;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$count=M('jd_order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('jd_order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($_GET['type']==1 || $_GET['type']==2){
				if($value['approver_id']==0){
					$data[$key]['flag']=1;
				}
			}
		}
		$this->assign("list",$data);
		$this->assign('jgry',M('jd')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',4);
		$this->display('index');
	}
	public function ls()
	{
		if($_GET['status']){
			$where['status'] = $_GET['status'];
		}
		if($_GET['jgry_id']){
			$where['jgry_id'] = $_GET['jgry_id'];
		}
		if($_GET['qy']){
			$where['area_id'] = $_GET['qy'];
		}
		if($_GET['start_time'] && $_GET['end_time']){
            $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
        }
		switch ($_GET['type']) {
			case '3':
				$count=M('ls_order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('ls_order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
				$where['type']=5;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($_GET['type']==2){
				if($value['approver_id']==0){
					$data[$key]['flag']=1;
				}
			}
		}
		$this->assign("list",$data);
		$this->assign('jgry',M('ls')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',5);
		$this->display('index');
	}
	public function save_Data($data)
	{
		foreach ($data as $key => $value) {
			$personal = M('personal')->where("id = '{$value['uid']}'")->field('id,tx,xm,lxdh,yx,zjh,lxdz')->find();
			$data[$key]['xm'] = $personal['xm'];
			switch ($value['status']) {
				case '0':
					$data[$key]['status'] = '待审核';
					break;
				case '1':
					$data[$key]['status'] = '审核中';
					break;
				case '2':
					$data[$key]['status'] = '审核通过';
					break;
				case '3':
					$data[$key]['status'] = '需补充材料';
					break;
				case '4':
					$data[$key]['status'] = '已撤销';
					break;
				case '5':
					$data[$key]['status'] = '已完成待评价';
					break;
				case '6':
					$data[$key]['status'] = '已完成已评价';
					break;
				case '7':
					$data[$key]['status'] = '审核未通过';
					break;
			}
		}
		return $data;
	}
	public function set()
	{
		if(IS_POST){
			switch ($_GET['cate']) {
	            case '1':
	            	$action = 'index';
	                switch ($_GET['type']) {
	                	case '1':
	                		$table = M('gzc_bid');
	                		break;
	                	case '2':
	                		$where['type']=1;
	                		$where['id']=$_GET['hid'];
	                		$where['jgry_id']=$_GET['mid'];
	                		$table = M('complain');
	                		if($table->where($where)->find()){
	                			exit(json_encode(array('status'=>'-1','info'=>'不能指定被投诉人为审批人')));
	                		}
	                		break;
	                	case '3':
	                		$where['type']=1;
	                		$table = M('order');
	                		break;
	                	default:
	                		exit(json_encode(array('status'=>'-1','info'=>'审批类型错误')));
	                		break;
	                }
	                break;
	            case '2':
	            	$action = 'falvyz';
	                switch ($_GET['type']) {
	                	case '1':
	                		$table = M('yz_bid');
	                		break;
	                	case '2':
	                		$where['type']=2;
	                		$where['id']=$_GET['hid'];
	                		$where['jgry_id']=$_GET['mid'];
	                		$table = M('complain');
	                		if($table->where($where)->find()){
	                			exit(json_encode(array('status'=>'-1','info'=>'不能指定被投诉人为审批人')));
	                		}
	                		break;
	                	case '3':
	                		$where['type']=2;
	                		$table = M('order');
	                		break;
	                	default:
	                		exit(json_encode(array('status'=>'-1','info'=>'审批类型错误')));
	                		break;
	                }
	                break;
	            case '3':
	            	$action = 'tj';
	                switch ($_GET['type']) {
	                	case '1':
	                		$table = M('tj_bid');
	                		break;
	                	case '2':
	                		$where['type']=3;
	                		$where['id']=$_GET['hid'];
	                		$where['jgry_id']=$_GET['mid'];
	                		$table = M('complain');
	                		if($table->where($where)->find()){
	                			exit(json_encode(array('status'=>'-1','info'=>'不能指定被投诉人为审批人')));
	                		}
	                		break;
	                	case '3':
	                		$table = M('tj_order');
	                		break;
	                	default:
	                		exit(json_encode(array('status'=>'-1','info'=>'审批类型错误')));
	                		break;
	                }
	                break;
	            case '4':
	            	$action = 'jd';
	                switch ($_GET['type']) {
	                	case '1':
	                		$table = M('jd_bid');
	                		break;
	                	case '2':
	                		$where['type']=4;
	                		$where['id']=$_GET['hid'];
	                		$where['jgry_id']=$_GET['mid'];
	                		$table = M('complain');
	                		if($table->where($where)->find()){
	                			exit(json_encode(array('status'=>'-1','info'=>'不能指定被投诉人为审批人')));
	                		}
	                		break;
	                	case '3':
	                		$table = M('jd_order');
	                		break;
	                	default:
	                		exit(json_encode(array('status'=>'-1','info'=>'审批类型错误')));
	                		break;
	                }
	                break;
	            case '5':
	            	$action = 'ls';
	                switch ($_GET['type']) {
	                	case '2':
	                		$where['type']=5;
	                		$where['id']=$_GET['hid'];
	                		$where['jgry_id']=$_GET['mid'];
	                		$table = M('complain');
	                		if($table->where($where)->find()){
	                			exit(json_encode(array('status'=>'-1','info'=>'不能指定被投诉人为审批人')));
	                		}
	                		break;
	                	case '3':
	                		$table = M('ls_order');
	                		break;
	                	default:
	                		exit(json_encode(array('status'=>'-1','info'=>'审批类型错误')));
	                		break;
	                }
	                break;
	            default:
            		exit(json_encode(array('status'=>'-1','info'=>'机构审批类型错误')));
            		break;
        	}
        	$res = $table->where("id = {$_POST['hid']}")->save(['approver_id'=>$_POST['id']]);

        	if($res){
                exit(json_encode(array('status'=>'1','info'=>'指定成功','url'=>U("Handle/{$action}",['type'=>$_GET['type']]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'指定失败')));
            }
		}
		if(IS_GET){
			$this->assign('qy',M('qy')->select());
			$this->display();
		}
	}
	//表格分类
    public function form()
    {
        $count=M('form')->where("type = {$_GET['type']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('form')->where("type = {$_GET['type']}")->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	switch ($_GET['type']) {
	            case '1':
	                $re[$key]['type'] = '公证办理';
	                break;
	            case '2':
	                $re[$key]['type'] = '法律援助申请';
	                break;
	            case '3':
	                $re[$key]['type'] = '人民调解申请';
	                break;
	            case '4':
	                $re[$key]['type'] = '司法鉴定申请';
	                break;
	            case '5':
	                $re[$key]['type'] = '律师预约';
	                break;
        	}
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //表格分类编辑
    public function form_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('form')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('form')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['type']|| !$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('form')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/form',['type'=>$_POST['type']]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('form')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除表格分类
    public function form_del()
    {
        $id = $_GET['id'];
        if(M('form_info')->where("form_id = {$id}")->find()){
            exit(json_encode(array('status'=>'-1','info'=>'该分类下存在表格，不可删除!')));
        }
        $res = M('form')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/form',['type'=>$_GET['type']]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //表格列表
    public function form_info()
    {
        $form_id = $_GET['id'];
        $where['form_id'] = $form_id;
        $count=M('form_info')->where($where)->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('form_info')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('form_id',$form_id);
        $this->assign('page',$show);
        $this->display();
    }
    //表格编辑
    public function form_info_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('form_info')->where("id = {$id}")->find();
            if($_FILES['path']['name']){
                $upload = $this->upload_file('Form');
                $_POST['path'] = '/Uploads'.$upload['path']['savepath'].$upload['path']['savename'];
            }else{
                if($id){
                    $_POST['path'] = $list['path'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传文件')));
                }
            }
            if($id){
                unset($_POST['id']);
                $res = M('form_info')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['form_id']|| !$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('form_info')->add($_POST);
            }
            if($res){
                if($_FILES['path']['name']){
                    $file = __EMAIL__.$list['path'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/form_info',['id'=>$_POST['form_id']]))));
            }else{
                if($_FILES['path']['name']){
                    $file = __EMAIL__.$_POST['path'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $form_id = $_GET['form_id'];
            $id = $_GET['id'];
            if($id){
                $list = M('form_info')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('form_id',$form_id);
            $this->display();
        }
    }
    //删除表格
    public function form_info_del()
    {
        $id = $_GET['id'];
        $form_id = $_GET['form_id'];
        $list = M('form_info')->where("id = {$id} and form_id = {$form_id}")->find();
        $res = M('form_info')->delete($id);
        if($res){
            $file = __EMAIL__.$list['path'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/form',['id'=>$form_id]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //公告
    public function notice()
    {
        $count=M('notice')->where("type={$_GET['type']}")->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('notice')->where("type={$_GET['type']}")->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            switch ($_GET['type']) {
                case '1':
                    $re[$key]['type'] = '公证办理';
                    break;
                case '2':
                    $re[$key]['type'] = '法律援助申请';
                    break;
                case '3':
                    $re[$key]['type'] = '人民调解申请';
                    break;
                case '4':
                    $re[$key]['type'] = '司法鉴定申请';
                    break;
                case '5':
                    $re[$key]['type'] = '律师预约';
                    break;
            }
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //添加公告
    public function notice_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('notice')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('notice')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['type'] || !$_POST['title'] || !$_POST['text']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('notice')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/notice',['type'=>$_POST['type']]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('notice')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除公告
    public function notice_del()
    {
        $id = $_GET['id'];
        $res = M('notice')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/notice'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
	public function changeAttr(){
        $map['id']=$_GET['id'];
        $table = $_GET['table'];
        $state=M($table)->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M($table)->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        sql();
        return false;
   }
	//申办事项
	public function business()
	{
		$count=M('bussiness')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('bussiness')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//编辑申办事项
	public function bussiness_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('bussiness')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('bussiness')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('bussiness')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/business'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('bussiness')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除申办事项
    public function bussiness_del()
    {
        $id = $_GET['id'];
        if(M('price')->where("bussiness_id = {$id}")->find()){
            exit(json_encode(array('status'=>'-1','info'=>'该事项下存在对应要件及费用，不可删除!')));
        }
        $res = M('bussiness')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/business'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //申办事项要件及费用
    public function price()
    {
        $count=M('price')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('price')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //编辑申办事项要件及费用
    public function price_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $_POST['save_time'] = time();
            if($id){
                unset($_POST['id']);
                $res = M('price')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title'] || !$_POST['num'] || $_POST['file_num']==0 || !$_POST['file_name']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $res = M('price')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/price'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('price')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign("bussiness",M('bussiness')->where("status=1")->select());

            $this->display();
        }
    }
    //删除申办事项要件及费用
    public function price_del()
    {
        $res = M('price')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/price'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //使用地维护
    public function country()
    {
        $count=M('country')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('country')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //编辑使用地
    public function country_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            if($id){
                unset($_POST['id']);
                $res = M('country')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('country')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/country'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('country')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign("bussiness",M('bussiness')->where("status=1")->select());

            $this->display();
        }
    }
    //删除使用地
    public function country_del()
    {
        $res = M('country')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/country'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //用途维护
    public function usee()
    {
        $count=M('use')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('use')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display('use');
    }
    //编辑用途
    public function use_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            if($id){
                unset($_POST['id']);
                $res = M('use')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('use')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/usee'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('use')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign("bussiness",M('bussiness')->where("status=1")->select());

            $this->display();
        }
    }
    //删除用途
    public function use_del()
    {
        $res = M('use')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/usee'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //翻译语言维护
    public function language()
    {
        $count=M('language')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('language')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //编辑翻译语言
    public function language_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            if($id){
                unset($_POST['id']);
                $res = M('language')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('language')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/language'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('language')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign("bussiness",M('bussiness')->where("status=1")->select());

            $this->display();
        }
    }
    //删除翻译语言
    public function language_del()
    {
        $res = M('language')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/language'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //申请人类型
	public function proposer_type()
	{
		$count=M('proposer_type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('proposer_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//编辑申请人类型
	public function proposer_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('proposer_type')->where("id = {$id}")->find();
            $_POST['save_time'] = time();
            if($id){
                unset($_POST['id']);
                $res = M('proposer_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $res = M('proposer_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/proposer_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('proposer_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除申请人类型
    public function proposer_type_del()
    {
        $id = $_GET['id'];
        $res = M('proposer_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/proposer_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //调解类型
	public function tj_type()
	{
		$count=M('tj_type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('tj_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//编辑调解类型
	public function tj_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('tj_type')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('tj_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('tj_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/tj_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('tj_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除调解类型
    public function tj_type_del()
    {
        $id = $_GET['id'];
        $res = M('tj_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/tj_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //鉴定类型
	public function jd_type()
	{
		$count=M('jd_type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('jd_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}
	//编辑鉴定类型
	public function jd_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('jd_type')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('jd_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('jd_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/jd_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('jd_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //删除鉴定类型
    public function jd_type_del()
    {
        $id = $_GET['id'];
        $res = M('jd_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/jd_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //律师流转
    public function ls_flow()
    {
    	$count=M('ls_flow')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('ls_flow')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$jg = M('lvshisws')->field('jgmc')->find($value['jg_id']);
            $re[$key]['jg'] = $jg['jgmc'];
            $member_data = M('ls')->field('id,xm')->find($value['mid']);
            $re[$key]['xm'] = $member_data['xm'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    public function ls_flow_add()
    {
    	if(IS_POST){
    		$data = $_POST;
            $data['ct_time']=time();
            if(empty($data['mid']) || empty($data['uid'])|| empty($data['jg_id']) || empty($data['time'])){
            	exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
            }
            if(M('ls_flow')->where("mid={$data['mid']} or uid={$data['uid']}")->find()){
                M('ls_flow')->where("mid={$data['mid']} or uid={$data['uid']}")->delete();
            }
            $res = M('ls_flow')->add($data);
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'提交成功','url'=>U('Handle/ls_flow'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'提交失败')));
            }
    	}
    	if(IS_GET){
    		$this->assign('qy',M('qy')->select());
    		$this->display();
    	}
    }
    public function ls_flow_del()
    {
        $res = M('ls_flow')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/ls_flow'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //工作人员审批
    public function shen_pi(){
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }

        

        switch ($_POST['type']) {
			case '1':
                $jgry = M('gz_member')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
				$table = M('gzc_bid');
				break;
			case '2':
				$table = M('yz_bid');
				break;
			case '3':
				$table = M('tj_bid');
				break;
			case '4':
				$table = M('jd_bid');
				break;
        }

        $data = $table->where($where)->order('ct_time desc')->field('id,uid,proposer_name,ct_time')->select();
        if($data){
            foreach ($data as $key => $value) {
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
        }else{
            exit(json_encode(['state'=>'-1','msg'=>'暂无数据']));
        }
    }

    //工作人员流转
    public function liu_zhuan(){
    	
    }

    //工作人员统计
    public function statistical(){

    }

    //法律援助 审批人员维护
    public function falvyz_weihu(){
		// if($_GET['status']){
		// 	$where['status'] = $_GET['status'];
		// }
		// if($_GET['jgry_id']){
		// 	$where['jgry_id'] = $_GET['jgry_id'];
		// }
		// if($_GET['qy']){
		// 	$where['qy'] = $_GET['qy'];
		// }
		// if($_GET['start_time'] && $_GET['end_time']){
  //           $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
  //       }
		switch ($_GET['type']) {
			case '1':
				$count=M('yz_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('yz_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
				$where['type']=2;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$where['type']=2;
				$count=M('order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($value['approver_id']==0){
				$data[$key]['flag']=1;
			}
		}
		$this->assign("list",$data);
		//法律援助机构人员
		$this->assign('jgry',M('yz')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',2);

    	$this->display('personnel_weihu');
    }

    //人民调解 审批人员维护
    public function tj_weihu(){
		// if($_GET['status']){
		// 	$where['status'] = $_GET['status'];
		// }
		// if($_GET['jgry_id']){
		// 	$where['jgry_id'] = $_GET['jgry_id'];
		// }
		// if($_GET['qy']){
		// 	$where['qy'] = $_GET['qy'];
		// }
		// if($_GET['start_time'] && $_GET['end_time']){
  //           $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
  //       }
		switch ($_GET['type']) {
			case '1':
				$count=M('tj_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('tj_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '2':
			$where['type']=3;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$count=M('tj_order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('tj_order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($value['approver_id']==0){
				$data[$key]['flag']=1;
			}
		}
		$this->assign("list",$data);
		//调解员
		$this->assign('jgry',M('tjy')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',3);
		$this->display('personnel_weihu');
    }

    //司法鉴定 审批人员维护
    public function jd_weihu(){
  //   	if($_GET['status']){
		// 	$where['status'] = $_GET['status'];
		// }
		// if($_GET['jgry_id']){
		// 	$where['jgry_id'] = $_GET['jgry_id'];
		// }
		// if($_GET['qy']){
		// 	$where['qy'] = $_GET['qy'];
		// }
		// if($_GET['start_time'] && $_GET['end_time']){
  //           $where['ct_time'] = array(array('EGT', strtotime($_GET['start_time'])), array('ELT', strtotime($_GET['end_time'])));
  //       }
		switch ($_GET['type']) {
			case '1':
				$count=M('jd_bid')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('jd_bid')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();

				break;
			case '2':
				$where['type']=4;
				$count=M('complain')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('complain')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
			case '3':
				$count=M('jd_order')->where($where)->count();
				$page= new \Think\Page($count,15);
				$show= $page->show();
				$data = M('jd_order')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
				break;
		}
		$data = $this->save_Data($data);
		foreach ($data as $key => $value) {
			if($_GET['type']==1 || $_GET['type']==2){
				if($value['approver_id']==0){
					$data[$key]['flag']=1;
				}
			}
		}
		// echo '<pre>';
		// var_dump($data);
		// exit;
		$this->assign("list",$data);
		//鉴定人员
		$this->assign('jgry',M('jd')->field('id,xm')->select());
		$this->assign('page',$show);
		$this->assign('type',$_GET['type']);
		$this->assign('qy',M('qy')->select());
		$this->assign('cate',4);
		$this->display('personnel_weihu');
    }
  
  	//预约类别列表
  	public function order_type(){
    	$count=M('order_type')->count();
        $page= new \Think\Page($count,10);
        $show= $page->show();
        $re=M('order_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
  
  	//预约类别添加/编辑
  	public function order_type_add(){
    	if(IS_POST){
            $id = $_POST['id'];
            $list = M('order_type')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                $res = M('order_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title'] || !$_POST['file_num'] || !$_POST['required_type']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('order_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/order_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('order_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
  
  	//预约类别删除
  	public function order_type_del(){
    	$id = $_GET['id'];
        $res = M('order_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/order_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
  
  
  	//援助类别列表
  	public function yuanzhu_type(){
    	$count=M('yuanzhu_type')->where(['p_id'=>0])->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('yuanzhu_type')->where(['p_id'=>0])->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
  
  	//援助类别添加/编辑
  	public function yuanzhu_type_add(){
      	
    	if(IS_POST){
            $id = $_POST['id'];
            if($id){
                unset($_POST['id']);
                $res = M('yuanzhu_type')->where("id = {$id}")->save($_POST);
            }else{
              	
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
              	
                $res = M('yuanzhu_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/yuanzhu_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('yuanzhu_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
          	if($_GET['pid']){
            	$this->assign('pid',$_GET['pid']);
            }
            $this->display();
        }
    }
  
  	//援助类别删除
  	public function yuanzhu_type_del(){
    	$id = $_GET['id'];
        $res = M('yuanzhu_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/yuanzhu_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
  
  	//医疗机构列表
  	public function medical_institution(){
      	$count=M('medical_institution')->where(['status'=>1])->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('medical_institution')->where(['status'=>1])->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
  
  	//医疗机构编辑/添加
  	public function medical_institution_add(){
      	if(IS_POST){
            $id = $_POST['id'];
            if($id){
                unset($_POST['id']);
                $res = M('medical_institution')->where("id = {$id}")->save($_POST);
            }else{
              	
                if(!$_POST['name']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
              	
                $res = M('medical_institution')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Handle/medical_institution'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
    	if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('medical_institution')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
          	if($_GET['pid']){
            	$this->assign('pid',$_GET['pid']);
            }
            $this->display();
        }
    }
  
  	//援助类别删除
  	public function medical_institution_del(){
    	$id = $_GET['id'];
        $res = M('medical_institution')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Handle/medical_institution'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
  
  	//前台获取医疗机构
  	public function get_medical_institution(){
    	$data = M('medical_institution')->where(['status'=>1])->field('id,name')->select();
      	//$ids = array_column($data,'id');
      	//$names = array_column($data,'name');
      	if(empty($data)){
        	exit(json_encode(array('status'=>'0','info'=>'没有数据或查询出错','data'=>[])));
        } else {
        	exit(json_encode(array('status'=>'1','info'=>'获取援助类别成功','data'=>$data)));
        }
    }

}