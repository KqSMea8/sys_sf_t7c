<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends CommonController{

   //问答列表
    public function index(){
        $count=M('ad')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('ad')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function ad()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('ad')->where("id = {$id}")->find();
            $post = $_POST;
            if($_FILES['ad_img']['name']){
                $upload = $this->upload('News');
                $post['ad_img'] = '/Uploads'.$upload['ad_img']['savepath'].$upload['ad_img']['savename'];
            }else{
                if($list['ad_img']){
                    $post['ad_img'] = $list['ad_img'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传图片')));
                }
            }
            $post['save_time']=time();
            if($id){
               $res = M('ad')->where("id = {$id}")->save($post);
            }else{
               $res = M('ad')->add($post);
            }
            if($res){
                if($_FILES['ad_img']['name']){
                    $file = __EMAIL__.$list['ad_img'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/index'))));
            }else{
                if($_FILES['ad_img']['name']){
                    $file = __EMAIL__.$post['ad_img'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('ad')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function ad_del()
    {
        $id = $_GET['id'];
        $list = M('ad')->where("id = {$id}")->find();
        $res = M('ad')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['ad_img'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/news'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function change(){
        $map['id']=$_GET['id'];
        $state=M('ad')->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M('ad')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }

    public function about()
    {
        if(IS_POST){
            $re = M('config')->find();
            $post = $_POST;
            if($_FILES['about_img']['name']){
                $upload = $this->upload('News');
                $post['about_img'] = '/Uploads'.$upload['about_img']['savepath'].$upload['about_img']['savename'];
            }else{
                if($re['about_img']){
                    $post['about_img'] = $re['about_img'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传图片')));
                }
            }
            $post['save_time']=time();
            if($post['id']){
               $res = M('config')->save($post);
            }else{
               $res = M('config')->add($post);
            }
            if($res){
                if($_FILES['about_img']['name']){
                    $file = __EMAIL__.$re['about_img'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/about'))));
            }else{
                if($_FILES['about_img']['name']){
                    $file = __EMAIL__.$post['about_img'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $re = M('config')->find();
            $this->assign('list',$re);
            $this->display();
        }
    }

    public function agreement()
    {
        if(IS_POST){
            $re = M('config')->find();
            $post = $_POST;
            $post['save_time']=time();
            if($post['id']){
               $res = M('config')->save($post);
            }else{
               $res = M('config')->add($post);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/agreement'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $re = M('config')->find();
            $this->assign('list',$re);
            $this->display();
        }
    }
    //表格分类
    public function form()
    {
        $count=M('form')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('form')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            switch ($value['type']) {
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
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/form'))));
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
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/form'))));
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
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/form_info',['id'=>$_POST['form_id']]))));
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
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/form',['id'=>$form_id]))));
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
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/qy'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
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
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/qy'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //公告
    public function notice()
    {
        $count=M('notice')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('notice')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            switch ($value['type']) {
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
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/notice'))));
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
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/notice'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //服务区域
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
    //服务区域编辑
    public function jd_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('jd_type')->where("id = {$id}")->find();
            $_POST['ct_time'] = time();
            if($id){
                unset($_POST['id']);
                $res = M('jd_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                
                $res = M('jd_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/jd_type'))));
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
    //删除服务区域
    public function jd_type_del()
    {
        $id = $_GET['id'];
        $res = M('jd_type')->delete($id);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/jd_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //调委会类别
    public function twh_type()
    {
        $count=M('twh_type')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('twh_type')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $type = M('serve_area')->where("id = {$value['area_id']}")->find();
            $re[$key]['type'] = $type['title'];
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //调委会类别编辑
    public function twh_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('twh_type')->where("id = {$id}")->find();
            
            if($id){
                unset($_POST['id']);
                $res = M('twh_type')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('twh_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/twh_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('twh_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $type = M('serve_area')->select();
            $this->assign('type',$type);
            $this->display();
        }
    }
    //调委会类别删除
    public function twh_type_del()
    {
        $res = M('twh_type')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/twh_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //文书
    public function writ()
    {
        $count=M('writ')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('writ')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            switch ($value['type']) {
                case '1':
                    $re[$key]['type'] = '司法鉴定书';
                    break;
                case '2':
                    $re[$key]['type'] = '调解书';
                    break;
                case '3':
                    $re[$key]['type'] = '法律援助判决书';
                    break;
                default:
                    break;
            }
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //文书编辑
    public function writ_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('writ')->where("id = {$id}")->find();
            if($_FILES['path']['name']){
                $upload = $this->upload_file('News');
                $_POST['path'] = '/Uploads'.$upload['path']['savepath'].$upload['path']['savename'];
            }else{
                if($id){
                    $_POST['path'] = $list['path'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传文书文件')));
                }
            }
            if($id){
                unset($_POST['id']);
                $res = M('writ')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['type'] || $_POST['type']==0 || !$_POST['title'] || !$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile'] || !$_POST['files_id']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['ct_time'] = time();
                $res = M('writ')->add($_POST);
            }
            if($res){
                if($_FILES['path']['name']){
                    $file = __EMAIL__.$list['path'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/writ'))));
            }else{
                if($_FILES['path']['name']){
                    $file = __EMAIL__.$_POST['path'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('writ')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    //文书删除
    public function writ_del()
    {
        $list = M('writ')->where("id = {$_GET['id']}")->find();
        $res = M('writ')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['path'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/writ'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //消息推送
    public function message()
    {
        $count=M('message')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('message')->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
            $re[$key]['personal'] = M('personal')->where("id = {$value['uid']}")->field('xm')->find();
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }
    //消息推送添加
    public function message_add()
    {
        // echo '<pre>';
        // var_dump($_POST);
        // echo $_POST['is_writ'] == 1 ? '111' : '222';
        // exit;
        if(IS_POST){

            if(!$_POST['title'] || !$_POST['text'] || !$_POST['type']){
                exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
            }

            $upload = $this->upload_file('News');
            if($_FILES['img_url']['name']){
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'请上传图片')));
            }

            if($_FILES['adjunct']['name']){
                $_POST['adjunct'] = '/Uploads'.$upload['adjunct']['savepath'].$upload['adjunct']['savename'];
            }else{
              	$_POST['adjunct'] = '';
                //exit(json_encode(array('status'=>'-1','info'=>'请上传附件')));
            }
            //
            $is_writ = 0;//是否是文书类消息
            if($_POST['is_writ'] == 1){
                $is_writ = 1;
            }
            switch ($_POST['send_type']) {
                case '1':
                    if($_POST['type']!=4){
                        $personal = M('personal')->where("yhlx = {$_POST['type']}")->field('id')->select();
                    }else{
                        $personal = M('personal')->field('id')->select();
                    }
                    $time = time();
                    foreach ($personal as $key => $value) {
                        $data['uid'] = $value['id'];
                        $data['title'] = $_POST['title'];
                        $data['text'] = $_POST['text'];
                        $data['img_url'] = $_POST['img_url'];
                        $data['adjunct'] = $_POST['adjunct'];
                        $data['is_writ'] = $is_writ;
                        $data['ct_time'] = $time;
                        $arr[] = $data;
                    }
                    $res = M('message')->addAll($arr);
                    break;
                case '2':
                    $time = time();
                    if($_POST['type']==1){
                        if(empty($_POST['zjh'])){
                            exit(json_encode(array('status'=>'-1','info'=>'未输入身份证号码')));
                        }
                        $personal = M('personal')->where("zjh = {$_POST['zjh']}")->field('id')->find();
                        if(empty($_POST['zjh'])){
                            exit(json_encode(array('status'=>'-1','info'=>'身份证号码不存在')));
                        }
                        $data['uid'] = $personal['id'];
                    }
                    if($_POST['type']==2){
                        if(empty($_POST['id'])){
                            exit(json_encode(array('status'=>'-1','info'=>'未选择人员')));
                        }
                        $data['uid'] = $_POST['id'];
                    }
                    $data['title'] = $_POST['title'];
                    $data['text'] = $_POST['text'];
                    $data['img_url'] = $_POST['img_url'];
                    $data['adjunct'] = $_POST['adjunct'];
                    $data['is_writ'] = $is_writ;
                    $data['ct_time'] = $time;

                    $res = M('message')->add($data);
                    break;
                default:
                    exit(json_encode(array('status'=>'-1','info'=>'推送类型错误')));
                    break;
            }

            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/message'))));
            }else{
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$_POST['img_url'];
                    @unlink($file);
                }
                if($_FILES['adjunct']['name']){
                    $file = __EMAIL__.$_POST['adjunct'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $this->assign('qy',M('qy')->select());
            $this->display();
        }
    }
    //消息推送修改
    public function message_save()
    {
        if(IS_POST){

            $id = $_POST['id'];
            $list = M('message')->where("id = {$id}")->find();
            if($_POST['title']==$list['title'] && $_POST['text']==$list['text'] && !$_FILES['img_url']['name'] && !$_FILES['adjunct']['name']){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/message'))));
            }
            $upload = $this->upload_file('News');
            if($_FILES['img_url']['name']){
                $data['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }
            if($_FILES['adjunct']['name']){
                $data['adjunct'] = '/Uploads'.$upload['adjunct']['savepath'].$upload['adjunct']['savename'];
            }
            if($_POST['title']){
                $data['title'] = $_POST['title'];
            }
            if($_POST['text']){
                $data['text'] = $_POST['text'];
            }
            $res = M('message')->where("id = {$_POST['id']}")->save($data);
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/message'))));
            }else{
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$_POST['img_url'];
                    @unlink($file);
                }
                if($_FILES['adjunct']['name']){
                    $file = __EMAIL__.$_POST['adjunct'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            $list = M('message')->where("id = {$id}")->find();
            $this->assign('list',$list);
            $this->display();
        }
    }
    //消息推送删除
    public function message_del()
    {
        $list = M('message')->where("id = {$_GET['id']}")->find();
        $res = M('message')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/message'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    //调委会类别
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
    //调委会类别编辑
    public function price_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $_POST['save_time'] = time();
            if($id){
                unset($_POST['id']);
                $res = M('price')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title'] || !$_POST['num'] || $_POST['file_num']==0){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $res = M('price')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('News/price'))));
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
    //调委会类别删除
    public function price_del()
    {
        $res = M('price')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('News/price'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    //上传文件
    public function upload($filename){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mp3','mp4','caf');// 设置附件上传类型
        $upload->autoSub  = true;
        $upload->subName  = array('date','Y-m-d');
        $upload->rootPath = "./Uploads/";//文件上传保存的根路径，下面的Upload文件夹放在这里面，./Public/Upload
        $upload->savePath  =  '/'.$filename.'/'; // 设置附件上传目录，文件上传上来以后放在了这个文件件里面。
        $info   =   $upload->upload();
        if(!$info){ // 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            return $info;
            // foreach($info as $file){
            //     return '/Uploads'.$file['savepath'].$file['savename'];
            // }
        }
    }
}