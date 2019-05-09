<?php
namespace Admin\Controller;
use Think\Controller;
class PopularizelawController extends CommonController {

	public function index()
	{
        $where = [];
        $cate = '宣传';
        if($_GET['cate']){
            $where['cate'] = $_GET['cate'];
            $cate = $_GET['cate'] == 1 ? '新闻' : '视频';
        }
        // echo '<pre>';var_dump($where);exit;
		$count=M('popularizelaw')->where($where)->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('popularizelaw')->where($where)->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($re as $key => $value) {
        	switch ($value['cate']) {
                case '1':
                    $re[$key]['cate_name'] = '文章';
                    $type = M('text_type')->where("id = {$value['type_id']}")->find();
                    $re[$key]['name'] = $type['name'];
                    break;
                
                case '2':
                    $re[$key]['cate_name'] = '视频';
                    $type = M('video_type')->where("id = {$value['type_id']}")->find();
                    $re[$key]['name'] = $type['name'];
                    break;
            }
        }
        $this->assign('cate', $cate);
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
	}

	public function popularizelaw_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('popularizelaw')->where("id = {$id}")->find();
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Popularizelaw');
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
                $res = M('popularizelaw')->where("id = {$id}")->save($_POST);
            }else{
            	if(!$_POST['title'] || !$_POST['content']){
            		exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
            	}
            	$_POST['createtime'] = time();
                $_POST['cate']=1;
                $res = M('popularizelaw')->add($_POST);
            }
            if($res !== false){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/index'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('popularizelaw')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $type = M('text_type')->select();
            $this->assign('type',$type);
            $this->display();
        }
    }
    public function popularizelaw_del()
    {
        $list = M('popularizelaw')->where("id = {$_GET['id']}")->find();
        $res = M('popularizelaw')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['img_url'];
            @unlink($file);
            $files = __EMAIL__.$list['cover'];
            @unlink($files);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Popularizelaw/index'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }
    public function video_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('popularizelaw')->where("id = {$id}")->find();
          	if(!empty($id) && empty($_FILES)){
            	
            } else {
            	$upload = $this->upload_file('Popularizelaw');
            }
            //$upload = $this->upload_file('Popularizelaw');
            if($_FILES['img_url']['name']){
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
              	
                if($id){
                    $_POST['img_url'] = $list['img_url'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传视频')));
                }
            }
            if($_FILES['cover']['name']){
                $_POST['cover'] = '/Uploads'.$upload['cover']['savepath'].$upload['cover']['savename'];
            }else{
                if($id){
                    $_POST['cover'] = $list['cover'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'请上传封面图片')));
                }
            }
            if($id){
                unset($_POST['id']);
                $res = M('popularizelaw')->where("id = {$id}")->save($_POST);
            }else{
                if(!$_POST['title']){
                    exit(json_encode(array('status'=>'-1','info'=>'请完善信息')));
                }
                $_POST['createtime'] = time();
                $_POST['cate']=2;
                $res = M('popularizelaw')->add($_POST);
            }
            if($res !== false){
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                if($_FILES['cover']['name']){
                    $file = __EMAIL__.$list['cover'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/index'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('popularizelaw')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $type = M('video_type')->select();
            $this->assign('type',$type);
            $this->display();
        }
    }
    public function exam_type()
    {
        $count=M('exam_type')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('exam_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function exam_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            if($_POST['total_points']<0 || $_POST['subject_num']<0){
                exit(json_encode(array('status'=>'-1','info'=>'请填正整数')));
            }
            $list = M('exam_type')->where("id = {$id}")->find();
            $file = __EMAIL__.$list['img_url'];
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Popularizelaw');
                $_POST['img_url'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
            }else{
                if($id){
                    $_POST['img_url'] = $list['img_url'];
                }else{
                    exit(json_encode(array('status'=>'-1','info'=>'未上传图片')));
                }
            }
            $_POST['save_time'] = time();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('exam_type')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('exam_type')->add($_POST);
            }
            if($res){
                 if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$list['img_url'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/exam_type'))));
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
                $list = M('exam_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function exam_type_del()
    {
        $list = M('exam_type')->where("id = {$_GET['id']}")->find();
        $res = M('exam_type')->delete($_GET['id']);
        if($res){
            $file = __EMAIL__.$list['img_url'];
            @unlink($file);
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Popularizelaw/exam_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function exam_list()
    {
        $count=M('exam_list')->where("type_id = {$_GET['id']}")->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('exam_list')->where("type_id = {$_GET['id']}")->limit($page->firstRow.','.$page->listRows)->order('order_number')->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->assign('type_id',$_GET['id']);
        $this->display();
    }
    //题目添加
    public function exam_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $type_id = $_POST['type_id'];
            $list = M('exam_list')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('exam_list')->where("id = {$id} and type_id = {$type_id}")->save($_POST);
            }else{
                if(!$_POST['title'] || !$_POST['A_answer'] || !$_POST['B_answer'] || !$_POST['C_answer'] || !$_POST['D_answer'] || !$_POST['true_answer'])
                {
                    exit(json_encode(array('status'=>'-1','info'=>'请完善题目信息')));
                }
                $exam_type = M('exam_type')->where("id = {$type_id}")->find();
                $count = M('exam_list')->where("type_id = {$type_id}")->count();
                if($exam_type['status']==1){
                    exit(json_encode(array('status'=>'-1','info'=>'此类型下试题数量已满')));
                }
                $res = M('exam_list')->add($_POST);
            }
            if($res){
                if($count+1==$exam_type['subject_num']){
                    M('exam_type')->where("id = {$type_id}")->save(['status'=>1]);
                    if($exam_type['state']==1){
                        M('exam_list')->where("type_id = {$type_id}")->save(['points'=>sprintf('%.2f',$exam_type['total_points']/$exam_type['subject_num'])]);
                    }
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/exam_list',['id'=>$type_id]))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('exam_list')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->assign('type_id',$_GET['type_id']);
            $this->display();
        }
    }
    public function exam_del()
    {
        $type_id = $_GET['type_id'];
        $res = M('exam_list')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Popularizelaw/exam_list',['id'=>$type_id]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function text_type()
    {
        $count=M('text_type')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('text_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function text_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('text_type')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('text_type')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('text_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/text_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('text_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function text_type_del()
    {
        $res = M('text_type')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Popularizelaw/text_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function video_type()
    {
        $count=M('video_type')->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $re=M('video_type')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$re);
        $this->assign('page',$show);
        $this->display();
    }

    public function video_type_add()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $list = M('video_type')->where("id = {$id}")->find();
            if($_POST['id']){
                unset($_POST['id']);
                $res = M('video_type')->where("id = {$id}")->save($_POST);
            }else{
                $res = M('video_type')->add($_POST);
            }
            if($res){
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/video_type'))));
            }else{
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $list = M('video_type')->where("id = {$id}")->find();
                $this->assign('list',$list);
            }
            $this->display();
        }
    }
    public function video_type_del()
    {
        $res = M('video_type')->delete($_GET['id']);
        if($res){
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Popularizelaw/video_type'))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function points()
    {
        if(IS_POST){
            $type_id = $_POST['type_id'];
            $exam_type = M('exam_type')->where("id = {$type_id}")->find();
            if(!$exam_type){
                exit(json_encode(array('status'=>'-1','info'=>'无此试题类型')));
            }
            $data = $_POST['data'];
            
            $count=0;
            foreach ($data as $key => $value) {
                if($value['points']==0 || $value['points']=='' || $value['points']==null){
                    exit(json_encode(array('status'=>'-1','info'=>'分数不能为空')));
                }
                $count +=$value['points'];
            }
            
            if($count!=$exam_type['total_points']){
                exit(json_encode(array('status'=>'-1','info'=>'分数总数与试题类型总数不等')));
            }
            $res = true;
            M()->startTrans();
            foreach ($data as $key => $value) {
                $res = M('exam_list')->where("id = {$value['id']}")->save(['points'=>$value['points']]);
                if(!$res){
                    $res = false;
                    break;
                }
            }
            if($res){
                M()->commit();
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/exam_type'))));
            }else{
                M()->rollback();
                exit(json_encode(array('status'=>'-1','info'=>'编辑失败')));
            }
        }
        if(IS_GET){
            $id = $_GET['id'];
            if($id){
                $exam_type = M('exam_type')->where("id = {$id}")->find();
                $list = M('exam_list')->where("type_id = {$_GET['id']}")->order('order_number')->select();
                if($exam_type['state']==1){
                    $this->error('此类型试题不需要设置分数',U('Popularizelaw/exam_type'));
                }
                if(!$list){
                    $this->error('请先设置题目',U('Popularizelaw/exam_type'));
                }
                $this->assign('list',$list);
            }
            $this->assign('type_id',$id);
            $this->display();
        }
    }

    public function image()
    {
        if(IS_POST){
            $id = $_POST['id'];
            $image_set = M('image_set')->where("id = {$id}")->find();
            if($_FILES['img_url']['name']){
                $upload = $this->upload_file('Popularizelaw');
                $_POST['popularizelaw'] = '/Uploads'.$upload['img_url']['savepath'].$upload['img_url']['savename'];
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
                    $file = __EMAIL__.$image_set['popularizelaw'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Popularizelaw/image'))));
            }else{
                if($_FILES['img_url']['name']){
                    $file = __EMAIL__.$_POST['popularizelaw'];
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
        $state=M('exam_type')->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M('exam_type')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }

   public function changeStatus(){
        $map['id']=$_GET['id'];
        $state=M('popularizelaw')->where($map)->getField('status');
        $data['status']=abs($state-1);
        if(M('popularizelaw')->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        return false;
   }
}