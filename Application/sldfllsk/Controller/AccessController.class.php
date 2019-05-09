<?php
namespace Admin\Controller;
use Think\Controller;
class AccessController extends CommonController {
    /**
      +----------------------------------------------------------
     * 管理员列表
      +----------------------------------------------------------
     */
    public function index() {
       $M = M("admin");
        $count = $M->count();
        $page = new \Think\Page($count, 15);
        $showPage = $page->show();
        $list = $M->order('aid desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign("page", $showPage);
        $this->assign("list", $list);
        $this->display();
    }

    public function roleList() {
        $this->assign("list", D("Access")->roleList());
        $this->display();
    }

    public function userList(){
        $M = M("personal");
        $count = $M->count();
        $page = new \Think\Page($count, 15);
        $showPage = $page->show();
        $list = $M->order('id')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign("page", $showPage);
        $this->assign("list", $list);
        $this->display();
    }

    public function vipList(){
        $M = M("vip");
        $count = $M->count();
        $page = new \Think\Page($count, 15);
        $showPage = $page->show();
        $list = $M->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("page", $showPage);
        $this->assign("list", $list);
        $this->display();
    }

    public function addRole() {
        if (IS_POST) {
            $this->checkToken();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->addRole());
        } else {
            $this->assign("info", $this->getRole());
            $this->display("editRole");
        }
    }

    public function editRole() {
        if (IS_POST) {
            $this->checkToken();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->editRole());
        } else {
            $M = M("Role");
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该角色", U('Access/roleList'));
            }
            $this->assign("info", $this->getRole($info));
            $this->display();
        }
    }

    public function opNodeStatus() {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D("Access")->opStatus("Node"));
    }

    public function opRoleStatus() {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(D("Access")->opStatus("Role"));
    }

    public function opSort() {
        $M = M("Node");
        $datas['id'] = (int) I("post.id");
        $datas['sort'] = (int) I("post.sort");
        header('Content-Type:application/json; charset=utf-8');
        if ($M->save($datas)) {
            echo json_encode(array('status' => 1, 'info' => "处理成功"));
        } else {
            echo json_encode(array('status' => 0, 'info' => "处理失败"));
        }
    }

    public function editNode() {
        if (IS_POST) {
            $this->checkToken();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->editNode());
        } else {
            $M = M("Node");
            $info = $M->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该节点", U('Access/nodeList'));
            }
            $this->assign("info", $this->getPid($info));
            $this->display();
        }
    }

    public function addNode() {
        if (IS_POST) {
            $this->checkToken();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->addNode());
        } else {
            $this->assign("info", $this->getPid(array('level' => 1)));
            $this->display("editNode");
        }
    }

    /**
      +----------------------------------------------------------
     * 添加管理员
      +----------------------------------------------------------
     */
    public function addAdmin() {
        if (IS_POST) {
            $this->checkToken();
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->addAdmin());
        } else {
            $this->assign("info", $this->getRoleListOption(array('role_id' => 0)));
            $this->display();
        }
    }

    public function changeRole() {
        header('Content-Type:application/json; charset=utf-8');
        if (IS_POST) {
            $this->checkToken();
            echo json_encode(D("Access")->changeRole());
        } else {
            $M = M("Node");
            $info = M("Role")->where("id=" . (int) $_GET['id'])->find();
            if (empty($info['id'])) {
                $this->error("不存在该用户组", U('Access/roleList'));
            }
            $access = M("Access")->field("CONCAT(`node_id`,':',`level`,':',`pid`) as val")->where("`role_id`=" . $info['id'])->select();
            $info['access'] = count($access) > 0 ? json_encode($access) : json_encode(array());
            $this->assign("info", $info);
            $datas = $M->where("level=1")->select();
            foreach ($datas as $k => $v) {
                $map['level'] = 2;
                $map['pid'] = $v['id'];
                $datas[$k]['data'] = $M->where($map)->select();
                foreach ($datas[$k]['data'] as $k1 => $v1) {
                    $map['level'] = 3;
                    $map['pid'] = $v1['id'];
                    $datas[$k]['data'][$k1]['data'] = $M->where($map)->select();
                }
            }
            $this->assign("nodeList", $datas);
            $this->display();
        }
    }

    /**
      +----------------------------------------------------------
     * 添加管理员
      +----------------------------------------------------------
     */
    public function editAdmin() {
        if (IS_POST) {
            // $this->checkToken();
        header('Content-Type:application/json; charset=utf-8');
            echo json_encode(D("Access")->editAdmin());
        } else {
            $M = M("Admin");
            $aid = (int) $_GET['aid'];
            $pre = C("DB_PREFIX");
            $info = $M->where("`aid`=" . $aid)->join($pre . "role_user ON " . $pre . "admin.aid = " . $pre . "role_user.user_id")->find();
            if (empty($info['aid'])) {
                $this->error("不存在该管理员ID", U('Access/index'));
            }
            if ($info['email'] == C('ADMIN_AUTH_KEY')) {
                $this->error("超级管理员信息不允许操作", U("Access/index"));
                exit;
            }
            $this->assign("info", $this->getRoleListOption($info));
            $this->display("addAdmin");
        }
    }


/**
     * 管理员删除
     */
    public function admin_del(){
        $aid=I('get.aid');
        if(!$aid){
            return false;
        }
        $map['aid']=$aid;
        $M=M('admin');
        if($M->where($map)->delete()){
            echo json_encode(array("status" => 1, "info" =>'删除成功'));
        }else{
            echo json_encode(array("status" =>0, "info" =>'删除失败，可能是不存在该ID的记录'));
        }
    }

    private function getRole($info = array()) {
       // import("Category");
        $cat = new \Org\Util\Category('Role', array('id', 'pid', 'name', 'fullname'));
        $list = $cat->getList();               //获取分类结构
        foreach ($list as $k => $v) {
            $disabled = $v['id'] == $info['id'] ? ' disabled="disabled"' : "";
            $selected = $v['id'] == $info['pid'] ? ' selected="selected"' : "";
            $info['pidOption'].='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
        }
        return $info;
    }

    private function getRoleListOption($info = array()) {
       // import("Category");
        $cat = new \Org\Util\Category('Role', array('id', 'pid', 'name', 'fullname'));
        $list = $cat->getList();               //获取分类结构
        $info['roleOption'] = "";
        foreach ($list as $v) {
            $disabled = $v['id'] == 1 ? ' disabled="disabled"' : "";
            $selected = $v['id'] == $info['role_id'] ? ' selected="selected"' : "";
            $info['roleOption'].='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
        }
        return $info;
    }

    private function getPid($info) {
        $arr = array("请选择", "项目", "模块", "操作");
        for ($i = 1; $i < 4; $i++) {
            $selected = $info['level'] == $i ? " selected='selected'" : "";
            $info['levelOption'].='<option value="' . $i . '" ' . $selected . '>' . $arr[$i] . '</option>';
        }
        $level = $info['level'] - 1;
        import("Category");
        $cat = new \Org\Util\Category('Node', array('id', 'pid', 'title', 'fullname'));
        $list = $cat->getList();               //获取分类结构
        $option = $level == 0 ? '<option value="0" level="-1">根节点</option>' : '<option value="0" disabled="disabled">根节点</option>';
        foreach ($list as $k => $v) {
            $disabled = $v['level'] == $level ? "" : '';
            $selected = $v['id'] != $info['pid'] ? "" : ' selected="selected"';
            $option.='<option value="' . $v['id'] . '"' . $disabled . $selected . '  level="' . $v['level'] . '">' . $v['fullname'] . '</option>';
        }
        $info['pidOption'] = $option;
        return $info;
    }


    /**
     * 添加会员
     */
    public function add_user(){
        if(IS_POST){
            $id=$_POST['id'];
            $data['vip']=$_POST['vip'];
            $data['user_name']=$_POST['user_name'];
            $data['user_password']=$_POST['user_password'];
            $data['invite_code'] = $_POST['invite_code'] == '' ? '' : $_POST['invite_code'];
            $data['state']=$_POST['state'];
            $data['trate']=$_POST['trate'];
            $data['tel']=$_POST['tel'];
            $data['user_registertime'] = time();
            $data['person_code'] = substr(md5(time().md5($_POST['user_name']).$_POST['user_password']),rand(0,27),8);
            $cha = M('personal')->where('user_name = "'.$data['user_name'].'"')->find();
            if ($cha) {
                $value = array(
                    "state" => '0',
                    "msg" => '该用户名已经被使用！',
                    "data" => '',
                );
                exit(json_encode($value));
            }

            if($data['user_name']==''){
                echo json_encode(array("status" => 0, "info" => "会员名称不能为空"));
                exit;
            }
             if($data['user_password']==''){
                echo json_encode(array("status" => 0, "info" => "会员密码不能为空"));
                exit;
            }
            
            $personal=M('personal');
            if($id){
                $map['id']=$id;
                if($personal->where($map)->save($data)){
                    echo json_encode(array("status" => 1, "info" => "成员修改成功",'url'=>U('Access/userList')));
                    exit;
                }else{
                    echo json_encode(array("status" => 0, "info" => "成员修改失败"));
                    exit;
                }
            }else{
                $re=$personal->add($data);
                if($re){
                    //添加后台记录
                    $data5['uid']=$re;
                    $data5['dtime']=time();
                    $data5['type']=11;
                    $data5['state']='创建会员';
                    $re5=M('htrecord')->add($data5);
                    
                    //查询是否成为推广员，是则写入返利记录
                    if(!empty($data['invite_code'])){
                        $re1=M('personal')->where('person_code="'.$data['invite_code'].'"')->getField('id');
                        if($re1){
                            $re3['uid']=$re1;
                            $re3['name']=$_POST['user_name'];
                            $re3['iid']=$re;
                            $re3['stime']=date('Y-m-d h:i:s',time());
                            $re2=M('rebate')->add($re3);
                        }
                    }
                    echo json_encode(array("status" => 1, "info" => "成员添加成功",'url'=>U('Access/userList')));
                    exit;
                }else{
                    echo json_encode(array("status" => 0, "info" => "成员添加失败"));
                    exit;
                }
            }
        }else{
            $m_page=M('personal');
            $map['id']=I('get.id');
            $info=$m_page->where($map)->find();
            $this->assign('list',$info);
            $pagelist = new \Org\Util\Category('Page', array('id', 'parent_id', 'page_name', 'fullname'));
            $this->assign("pagelist", $pagelist->getList());
            $this->display();
        }
    }

/**
     * 会员删除
     */
    public function user_del(){
        $id=I('get.id');
        if(!$id){
            return false;
        }
        $map['id']=$id;
        $M=M('personal');
        if($M->where($map)->delete()){
            echo json_encode(array("status" => 1, "info" =>'删除成功'));
        }else{
            echo json_encode(array("status" =>0, "info" =>'删除失败，可能是不存在该ID的记录'));
        }
    }


//添加管理员
    public function add_admin(){
        if(IS_POST){
            $aid=$_GET['aid'];
            $data['nickname']=$_POST['nickname'];
            $data['email']=$_POST['email'];

            $data['pwd']=$_POST['pwd'];
            $pwd=M('personal')->where('aid="'.$aid.'"')->field('pwd')->find();
            if($pwd!=$data['pwd']){
                $data['pwd']=md5($_POST['pwd']);
            }

            $data['remark']=$_POST['remark'];
            $data['status']=$_POST['status'];
            $data['import']=$_POST['import'];

           if($_FILES['img']['name']){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath  =      './adminpicture/'; // 设置附件上传目录
                $upload->saveName = array('uniqid','');
                $upload->autoSub = false;
                $info   =   $upload->uploadOne($_FILES['img']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $data['loc']='./adminpicture/'.$info['savename'];
                }
            } 

            $vip=M('admin');
            $map['aid']=$aid;
            if($aid){
                if($vip->where($map)->save($data)){
                    echo json_encode(array("status" => 1, "info" => "修改成功",'url'=>U('Access/index')));
                    exit;
                }else{
                    echo json_encode(array("status" => -1, "info" => "修改失败",'url'=>U('Access/index')));
                    exit;
                }
            }else{
                if($vip->add($data)){
                    echo json_encode(array("status" => 1, "info" => "添加成功",'url'=>U('Access/index')));
                    exit;
                }else{
                    echo json_encode(array("status" => -1, "info" => "添加失败",'url'=>U('Access/index')));
                    exit;
                }
            }
        }else{
            $vip=M('admin');
            $map['aid']=I('get.aid');
            $info=$vip->where($map)->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    //添加vip等级
    public function add_vip(){
        if(IS_POST){
           $id=$_GET['id'];
            $data['vip1']=$_POST['vip1'];
            $data['vip2']=$_POST['vip2'];
            $data['vip3']=$_POST['vip3'];
            $data['vip4']=$_POST['vip4'];
            $data['vip5']=$_POST['vip5'];
            $data['vip6']=$_POST['vip6'];
            $data['vip7']=$_POST['vip7'];
            $data['vip8']=$_POST['vip8'];
            $data['vip9']=$_POST['vip9'];
            $data['vip10']=$_POST['vip10'];
            $data['vip11']=$_POST['vip11'];
            $data['name']=$_POST['name'];
            $data['type']=$_POST['type'];
            $data['state']=$_POST['state'];
            $vip=M('vip');
            $map['id']=$id;
            if($id){
                if($vip->where($map)->save($data)){
                    echo json_encode(array("status" => 1, "info" => "会员等级修改成功",'url'=>U('Access/vipList')));
                    exit;
                }else{
                    echo json_encode(array("status" => 0, "info" => "会员等级修改失败"));
                    exit;
                }
            }else{
                if($vip->add($data)){
                    echo json_encode(array("status" => 1, "info" => "会员等级添加成功",'url'=>U('Access/vipList')));
                    exit;
                }else{
                    echo json_encode(array("status" => 0, "info" => "会员等级添加失败"));
                    exit;
                }
            }
            
        }else{  
            $m_page=M('vip');
            $map['id']=I('get.id');
            $info=$m_page->where($map)->find();

            $this->assign('list',$info);
            $pagelist = new \Org\Util\Category('Page', array('id', 'parent_id', 'page_name', 'fullname'));
            $this->assign("pagelist", $pagelist->getList());
            $this->display();
        }
    }

/**
     * 等级方案删除
     */
    public function vip_del(){
        $id=I('get.id');
        if(!$id){
            return false;
        }
        $map['id']=$id;
        $M=M('vip');
        if($M->where($map)->delete()){
            echo json_encode(array("status" => 1, "info" =>'删除成功'));
        }else{
            echo json_encode(array("status" =>0, "info" =>'删除失败，可能是不存在该ID的记录'));
        }
    }

    



    //更改会员状态
    public function user_change(){
        $data['id']=$_GET['id'];
        $state=M('personal')->where('id= "'.$data['id'].'"')->getField('state');
        if($state == 1){
            $data['state']=0;
        }else{
            $data['state']=1;
        }
        $re=M('personal')->where('id = "'.$data['id'].'"')->save($data);    
        if($re){
            exit(json_encode(array('state'=>1,'msg'=>'更改成功','data'=>$data['state'])));
        }else{
            exit(json_encode(array('state'=>-1,'msg'=>'更改失败')));
        }

    }

    //更改管理员状态
     public function admin_change(){
        //if($_SESSION['my_info']['aid']==1){
         $data['aid']=$_GET['aid'];
            $state=M('admin')->where('aid= "'.$data['aid'].'"')->getField('status');
            if($state == 1){
                $data['status']=0;
            }else{
                $data['status']=1;
            }
            $re=M('admin')->where('aid = "'.$data['aid'].'"')->save($data);    
            if($re){
                exit(json_encode(array('state'=>1,'msg'=>'更改成功','data'=>$data['status'])));
            }else{
                exit(json_encode(array('state'=>-1,'msg'=>'更改失败')));
            }
            
        // }else{
        //     exit(json_encode(array('state'=>1,'msg'=>'您不是超级管理员，暂无修改权限')));
        // }
        

    }

    //更改等级方案
    public function vip_change(){
        $data['id']=$_GET['id'];
            $state=M('vip')->where('id= "'.$data['id'].'"')->getField('state');
            if($state == 1){
                $data['state']=0;
            }else{
                $data['state']=1;
                $data1['state']=0;
                $res=M('vip')->field('id')->select();
                foreach($res as $k=>$v){
                    $ress=M('vip')->where('id="'.$v['id'].'"')->save($data1);
                }
            }
            $re=M('vip')->where('id = "'.$data['id'].'"')->save($data);    
            if($re){
                exit(json_encode(array('state'=>1,'msg'=>'更改成功','data'=>$data['state'])));
            }else{
                exit(json_encode(array('state'=>-1,'msg'=>'更改失败')));
            }


    }




//修改密码
    public function pwd(){
        $this->display();
    }


    //修改密码
    public function changePwd(){
        if(IS_POST){
            $email=$_POST['email'];
            $pwd=$_POST['pwd'];
            $newpwd=$_POST['newpwd'];
            $repwd=$_POST['repwd'];
            if($pwd==null){
                exit(json_encode(array('state'=>-1,'msg'=>'密码不能为空')));
            }
            $re=M('admin')->where('email="'.$email.'"and pwd ="'.md5($newpwd).'"')->find();
            if(!$re){
                exit(json_encode(array('state'=>-1,'msg'=>'密码不正确，请重新输入')));
            }
            if($newpwd!=$repwd){
                exit(json_encode(array('state'=>-1,'msg'=>'两次密码不一致，请重新输入')));
            }
            $data['pwd']=md5(C("AUTH_CODE") . md5($newpwd));
            $re1=M('admin')->where('email = "'.$email.'"')->save($data);
            if($re1){
                exit(json_encode(array('state'=>1,'msg'=>'密码修改成功')));
            }else{
                exit(json_encode(array('state'=>-1,'msg'=>'修改失败')));
            }
        }else{
            exit(json_encode(array('state'=>-1,'msg'=>'请填写参数')));
        }
    }

    //修改信息
    public function info(){
        $email=$_SESSION['my_info']['email'];
        $re=M('admin')->where('email="'.trim($email).'"')->find();
        $this->assign('list',$re);
        $this->display();
    }


    //修改其他信息
    public function changeInfo(){
        if(IS_POST){
            $aid=$_POST['aid'];
            $data['nickname']=$_POST['nickname'];
            $data['email']=$_POST['email'];
            $data['remark']=$_POST['remark'];
        if (!is_email($_POST['email'])) {
            return array('status' => -1, 'msg' => "邮件地址错误");
        }
        if($_FILES['img']['name']){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath  =      './adminpicture/'; // 设置附件上传目录
                $upload->saveName = array('uniqid','');
                $upload->autoSub = false;
                $info   =   $upload->uploadOne($_FILES['img']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $data['loc']='./adminpicture/'.$info['savename'];
                }
            } 
        $re=M('admin')->where('aid="'.$aid.'"')->save($data);  
        if($re){
                exit(json_encode(array('state'=>1,'msg'=>'修改成功')));
            }else{
                exit(json_encode(array('state'=>-1,'msg'=>'修改失败')));
            }
        }else{
            exit(json_encode(array('state'=>-1,'msg'=>'请填写参数')));
        }
    }


}