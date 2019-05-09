<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {

    //用户列表
   public function index(){
    $where['yhlx']=['NEQ','2'];
    // if($name=I('get.name'))$map['name']=array('like',$name);
    // if($id=I('get.id'))$map['id']=array('like',$id);
    // if($tel=I('get.tel'))$map['tel']=array('like',$tel);
    $count=M('personal')->where($where)->count();
    $page= new \Think\Page($count,15);
    $show=$page->show();
    $where['zt'] = ['neq', 2];//2表示已经被删除
    $list=M('personal')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    foreach ($list as $key => $value) {
        switch ($value['yhlx']) {
            case '1':
                $list[$key]['yhlx'] = '普通用户';
                break;
            case '2':
                $list[$key]['yhlx'] = '工作人员';
                break;
            case '3':
                $list[$key]['yhlx'] = '特殊人员';
                break;
        }
    }
    $this->assign('list',$list);
    $this->assign('page',$show);
    $this->assign('user_list',1);//普通用户列表
    $this->display();
   }

   //用户状态修改/删除（0停用，1可用，2删除）
   public function change_user_status(){

    $uid = $_POST['id'];
    $status = $_POST['status'];
    $where['id'] = $uid;//用户id
    //查找用户是否存在
    $user = M('personal')->where($where)->find();
    $message = '';
    if($status == 1){
        $message = '启用用户';
    } else if($status == 0){
        $message = '停用用户';
    } else {
        $message = '删除用户';
    }
    if($user){
            // var_dump($user);
      		if(in_array($status,array(0,1))){
            	$data['id'] = $uid;
            	$data['zt'] = $status;//修改状态 停用/正常
            	$res = M('personal')->save($data);
            }
            if($status == 2){
            	$res = M('personal')->where('id='.$uid)->delete();
            }
      		
            if($res){
                exit(json_encode(array('status'=>'1','msg'=>$message . '成功')));
            } else {
                exit(json_encode(array('status'=>'0','msg'=>$message . '失败')));
            }

    } else {
        exit(json_encode(array('status'=>'-1','msg'=>'用户不存在')));
    }
   }
   //工作人员
   public function worker()
   {
    $where1['yhlx']=2;
    $where1['zt'] = ['neq', 2];//2表示已经被删除
    $count=M('personal')->where($where1)->count();
    $page= new \Think\Page($count,15);
    $show=$page->show();
    $list=M('personal')->where($where1)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    foreach ($list as $key => $value) {
        switch ($value['yhlx']) {
            case '1':
                $list[$key]['yhlx'] = '普通用户';
                break;
            case '2':
                $list[$key]['yhlx'] = '工作人员';
                break;
            case '3':
                $list[$key]['yhlx'] = '特殊人员';
                break;
        }
    }
    $this->assign('list',$list);
    $this->assign('user_list',2);//工作人员用户列表
    $this->assign('page',$show);
    $this->display('index');
   }
   //用户信息
   public function detail()
   {
       if(IS_POST){
            $data=$_POST;
            $id = $data['id'];
         	
            //unset($data['id']);
            $user = M('personal')->find($id);

            //if($user['xm']==$data['xm'] && $user['lxdh']==$data['lxdh'] && $user['yx']==$data['yx'] && $user['qq']==$data['qq']){
                //if($user['yhlx']==2){
                    //exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('User/worker'))));
                //}else{
                    //exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('User/index'))));
                //}
            //}
           // if($data['lxdh']){
              //  if(M('personal')->where("lxdh = {$data['lxdh']}")->find()){
                   // exit(json_encode(array('status'=>'-1','info'=>'此手机号码已被使用')));
                //}
          //  }
            $res = M('personal')->where("id = {$data['id']}")->save($data);
            if($res !== false){
                if($user['yhlx']==2){
                    exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('User/worker'))));
                }else{
                    exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('User/index'))));
                }
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
       }
       $personal = M('personal')->find($_GET['id']);
       $typeArr = ['普通用户','工作人员','特殊人员'];
       $personal['yhlx'] = $typeArr[$personal['yhlx']+1];
       $this->assign('list',$personal);
       $this->display();
   }
   //特殊人员
    public function special_personnel()
    {
        $admin = M('role_user')->where("user_id={$_SESSION['my_info']['aid']}")->find();
        if($admin['role_id']==11){
            $where['mid']=$_SESSION['my_info']['mid'];
        }
        $count=M('special_personnel')->where($where)->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('special_personnel')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $typeArr = ['宽管','严管'];
        foreach ($re as $key => $value) {
            $re[$key]['type'] = $typeArr[$value['supervise_type']-1];
            if(empty($value['uid'])){
                $re[$key]['clock']='未打';
                continue;
            }
            if($value['supervise_type']==1){
                $start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
                $end_time = strtotime(date("Y-m-d",time()));
                $where['ct_time'] = array(array('EGT', $start_time, array('ELT', $end_time)));
                $where['uid'] = $value['uid'];
                if(M('clock')->where($where)->find()){
                    $re[$key]['clock']='已打';
                }else{
                    $re[$key]['clock']='未打';
                }
            }
            if($value['supervise_type']==2){
                $where['uid'] = $value['uid'];
                $start_time = strtotime(date("Y-m-d",time()));
                $end_time = strtotime(date("Y-m-d",time()))+3600*12;
                $where['ct_time'] = array(array('EGT', $start_time, array('ELT', $end_time)));
                $clock1 = M('clock')->where($where)->find();
                $start_time = strtotime(date("Y-m-d",time()))+3600*12;
                $end_time = strtotime(date("Y-m-d",time()));
                $where['ct_time'] = array(array('EGT', $start_time, array('ELT', $end_time)));
                $clock2 = M('clock')->where($where)->find();
                if($clock1 && $clock2){
                    $re[$key]['clock']='已打';
                }else{
                    $re[$key]['clock']='未打';
                }
            }
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //编辑特殊人员
    public function special_personnel_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('special_personnel')->where("id = {$id}")->find();
            if($id){
                unset($_POST['id']);
                if($_FILES){
                    $upload = $this->upload_file('User');
                    if($_FILES['head_img']){
                        $data['head_img'] = $upload['head_img']['savepath'];
                    }
                    if($_FILES['idcard_img']){
                        $data['idcard_img'] = $upload['idcard_img']['savepath'];
                    }
                }
                $res = M('special_personnel')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['name'] || !$_POST['mobile'] || !$_POST['id_card']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                if(!preg_match('/^1[34578]\d{9}$/', $_POST['mobile'])){
                    exit(json_encode(['state'=>'-1','info'=>'手机号码格式有误']));
                }
                if(empty($_POST['kinsfolk_name']) &&empty($_POST['bondsman_name']) && empty($_POST['friend_name'])){
                    exit(json_encode(array('status'=>'-1','info'=>'请至少填写一个联系人姓名')));
                }
                if(empty($_POST['kinsfolk_mobile']) && empty($_POST['bondsman_mobile']) && empty($_POST['friend_mobile'])){
                    exit(json_encode(array('status'=>'-1','info'=>'请完至少填写一个联系人联系方式')));
                }
                if(empty($_FILES['head_img']) || empty($_FILES['idcard_img'])){
                    exit(json_encode(array('status'=>'-1','info'=>'请完整上传图片')));
                }
                if(empty($_POST['mid'])){
                    exit(json_encode(array('status'=>'-1','info'=>'请选择监管人员')));
                }
                $upload = $this->upload_file('User');
                $data['head_img'] = $upload['head_img']['savepath'];
                $data['idcard_img'] = $upload['idcard_img']['savepath'];
                $_POST['ct_time'] = time();
                $res = M('special_personnel')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('User/special_personnel'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $admin = M('role_user')->where("user_id={$_SESSION['my_info']['aid']}")->find();
            $id = $_GET['id'];
            if($admin['role_id']==11){
                $this->assign('mid',$_SESSION['my_info']['mid']);
                $this->assign('nickname',$_SESSION['my_info']['nickname']);
                $this->assign('type',1);
                if($id){
                    $list = M('special_personnel')->where("id = {$id}")->find();
                    $this->assign('list',$list);
                    $member = M('sfs_member')->field('sfsbm')->find($_SESSION['my_info']['mid']);
                    $this->assign("member",M('sfs_member')->where("sfsbm = '{$member['sfsbm']}' and id != {$_SESSION['my_info']['mid']}")->field('id,xm')->select());
                }
            }else{
                if($id){
                    $list = M('special_personnel')->where("id = {$id}")->find();
                    $this->assign('list',$list);
                    $member = M('sfs_member')->field('sfsbm')->find($list['mid']);
                    $this->assign("member",M('sfs_member')->where("sfsbm = '{$member['sfsbm']}' and id != {$list['mid']}")->field('id,xm')->select());
                }
                $this->assign('qy',M('qy')->select());
            }
            $this->display();
        }
    }

    //修改用状态 status(字段)
    public function special_personnel_change_valid(){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $status_name = $status == 1 ? '激活' : '停用' ;
        $res = M('special_personnel')->where('id =' . $id)->save(['status' => $status]);
        if($res){
            exit(json_encode(array('state'=>'1','info' => $status_name . '成功','url'=>U('User/special_personnel'))));
        }else{
            exit(json_encode(array('state'=>'-1','info'=> $status_name . '失败')));
        }
    }

    //删除特殊人员
    public function special_personnel_del()
    {
        $id = $_GET['id'];
        $res = M('special_personnel')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('User/special_personnel'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //打卡记录
    public function clock(){
        $id = $_GET['id'];
        $special_personnel = M('special_personnel')->find($id);
        if(empty($special_personnel['uid'])){
            exit(json_encode(array('status'=>'-1','info'=>'此人员还未绑定用户')));
        }
        $count=M('clock')->where("id = {$special_personnel['uid']}")->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $list=M('clock')->where("uid = {$special_personnel['uid']}")->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($list as $key => $value) {
            $list[$key]['personal'] = M('personal')->where("id = {$value['uid']}")->field('id,xm')->find();
            $list[$key]['special_personnel'] = M('special_personnel')->where("uid = {$value['uid']}")->find();
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    //打卡记录详情
    public function clock_info()
    {
        $id = $_GET['id'];
        $clock = M('clock')->find($id);
        if(empty($clock)){
            exit(json_encode(array('status'=>'-1','info'=>'记录不存在')));
        }
        $clock['personal'] = M('personal')->where("id = {$clock['uid']}")->field('id,xm')->find();
        $clock['special_personnel'] = M('special_personnel')->where("uid = {$clock['uid']}")->find();
        $this->assign('list',$clock);
        $this->display();
    }
}