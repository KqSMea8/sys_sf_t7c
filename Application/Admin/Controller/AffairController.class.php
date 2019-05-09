<?php
namespace Admin\Controller;
use Think\Controller;
class AffairController extends CommonController {

	public function index()
	{
		$count=M('affair')->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('affair')->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	$type = M('affair_type')->where("id = {$value['type_id']}")->find();
        	$re[$key]['name'] = $type['name'];
            switch ($value['cate']) {
                case '1':
                    $re[$key]['cate'] = '机构';
                    break;
                case '2':
                    $re[$key]['cate'] = '人员';
                    break;
            }
        }
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}

	public function affair_add()
    {
        if(IS_POST){

            $id = $_POST['id'];
            $list = M('affair')->where("id = {$id}")->find();
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Affair');
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
                if($id){
                    $_POST['img_url'] = $list['img_url'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传封面图片')));
                }
            }
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('affair')->where("id = {$id}")->save($_POST);

            }else{
            	if(!$_POST['type_id'] || $_POST['type_id']==0 || !$_POST['title'] || !$_POST['content'] || !$_POST['cate'] || $_POST['cate']==0){
            		exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
            	}
              	//echo '<pre>';
              	//var_dump($_POST);exit;
            	$_POST['createtime'] = time();
                $res = M('affair')->add($_POST);
            }
            if($res !== false){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Affair/index'))));
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
                $list = M('affair')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $type = M('affair_type')->select();
            $this->assign('type',$type);
            $this->assign('qy',M('qy')->select());
            $this->display();
        }
    }
    public function affair_del()
    {
        $list = M('judicial')->where("id = {$_GET['id']}")->find();
        $res = M('affair')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['img_url'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Affair/index'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

	public function affair_type()
    {
        $count=M('affair_type')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('affair_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function affair_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('affair_type')->where("id = {$id}")->find();
            $file = __EMAIL__.$list['img_url'];
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Affair');
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
                if($id){
                    $_POST['img_url'] = $list['img_url'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }

            if($_POST['id']){
                unset($_POST['id']);
                $res = M('affair_type')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('affair_type')->add($_POST);
            }
            if($res){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Affair/affair_type'))));
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
                $list = M('affair_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function affair_type_del()
    {
        $res = M('affair_type')->delete($_GET['id']);
        $list = M('affair_type')->where("id = {$_GET['id']}")->find();
        if($res){
            $file = __EMAIL__.$list['img_url'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Affair/affair_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function image()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $image_set = M('image_set')->where("id = {$id}")->find();
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Affair');
                $_POST['affair'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
               exit(json_encode(array('status'=>'-1','info'=>'请上传封面图片')));
            }
            if($id){
                unset($_POST['id']);
                $res = M('image_set')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('image_set')->add($_POST);
            }
            
            if($res){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$image_set['affair'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Affair/image'))));
            }else{
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$_POST['affair'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $image_set = M('image_set')->find();
            $this->assign('list',$image_set);
            $this->display();
        }
    }

    public function changeAttr(){
        $map['id']=$_GET['id'];
        $state=M('affair')->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M('affair')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }
}