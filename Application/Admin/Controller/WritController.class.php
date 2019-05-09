<?php
namespace Admin\Controller;
use Think\Controller;
class WritController extends CommonController{
	//文书查询维护
	public function index()
	{
        if($_GET['keyword']){
            $where['keyword'] = ['like',"%,{$_GET['keyword']},%"];
        }
        if($_GET['type']){
            $where['type'] = $_GET['type'];
        }else{
            $where['type'] = $_GET['type'] = 1;
        }
		$count=M('writ')->where($where)->count();
        $page= new \Think\Page($count,15);
        $show= $page->show();
        $re=M('writ')->where($where)->order('ct_time desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($re as $key => $value) {
            switch ($_GET['type']) {
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
                    $re[$key]['type'] = '司法鉴定书';
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
                $_POST['keyword'] = ','.$_POST['keyword'].',';
                $res = M('writ')->add($_POST);
            }
            if($res){
                if($_FILES['path']['name']){
                    $file = __EMAIL__.$list['path'];
                    @unlink($file);
                }
                exit(json_encode(array('status'=>'1','info'=>'编辑成功','url'=>U('Writ/index',['type'=>$_POST['type']]))));
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
            exit(json_encode(array('status'=>'1','info'=>'删除成功','url'=>U('Writ/index',['type'=>$list['type']]))));
        }else{
            exit(json_encode(array('status'=>'-1','info'=>'删除失败')));
        }
    }

    public function changeAttr(){
        $map['id']=$_GET['id'];
        $table = M('writ');
        $state=$table->where($map)->getField('status');
        $data['status']=abs($state-1);
        if($table->where($map)->save($data)){
            echo json_encode(array("status" => 1, "info" => '<img src="'.__ROOT__.'/Public/Img/action_'.$data['status'].'.png" border="0">'));
            //echo '<img src="../Public/Img/action_'.$data['is_recommend'].'.png" border="0">';
            exit;
        }
        sql();
        return false;
   }
}