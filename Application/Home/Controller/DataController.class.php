<?php
namespace Home\Controller;
use Think\Controller;
class DataController extends Controller{
	//用户注册协议
	public function agreement()
	{
		$data = M('config')->field('user_agreement')->find();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//司法要闻类型
	public function judicial_type()
	{
		$data = M('judicial_type')->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//案例分类
	public function case_type()
	{
		$data = M('case_type')->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//普法宣传文章类型
	public function text_type()
	{
		$data = M('text_type')->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//普法宣传视频类型
	public function video_type()
	{
		$data = M('video_type')->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//普法宣传考试类型
	public function exam_type()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
		if($_POST['title']){
			$where['name'] = array('like',"%{$_POST['title']}%");
			$where['status'] = 1;
		}else{
			$where['status'] = 1;
		}
		$data = M('exam_type')->where($where)->select();
		foreach ($data as $key => $value) {
			$data[$key]['img_url'] = $this->savepath($value['img_url']);
			if(M('test_score')->where("uid = {$re['id']} and exam_type_id = {$value['id']}")->find()){
				continue;
			}
			$arr[]=$value;
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$arr]));
	}
	//政务公开类型
	public function affair_type()
	{
		$data = M('affair_type')->select();
		if($data){
        	foreach ($data as $key => $value) {
				$data[$key]['img_url'] = $this->savepath($value['img_url']);
			}
			exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
        } else {
        	exit(json_encode(['state'=>'0','msg'=>'暂无数据或访问出错','data' => '']));
        }
      

	}
	//区域
	public function area()
	{
		$where['pid'] = ['neq',0];
		$data = M('qy')->where($where)->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//Banner
	public function banner()
	{
		$where['status'] = 1;
		$data = M('ad')->where($where)->select();
		foreach ($data as $key => $value) {
			$data[$key]['ad_img'] = $this->savepath($value['ad_img']);
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//业务类型
	public function business()
	{
		$data = M('bussiness')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//用途
	public function usedata()
	{
		$data = M('use')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//翻译语言
	public function language()
	{
		$data = M('language')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//翻译语言
	public function country()
	{
		$data = M('country')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//投诉类型
	public function complain_type()
	{
		$data = M('complain_type')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//预约类别
	public function order_type()
	{
		$data = M('order_type')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//申请人类型
	public function proposer_type()
	{
		$data = M('proposer_type')->where("status=1")->select();
		foreach ($data as $key => $value) {
			$data[$key]['required_type'] = explode(',',$value['required_type']);
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//调解类型
	public function tj_type()
	{
		$data = M('tj_type')->where("status=1")->select();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//鉴定类型
	public function jd_type()
	{
		if($_POST['id']){
			$data = M('jd_type')->where("status=1 and pid = {$_POST['id']}")->select();
		}else{
			$data = M('jd_type')->where("status=1")->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//公证处
	public function gzc()
	{
		if($_POST['id']){
			$organ = M('gzc')->where("id = {$_POST['id']}")->field('jgbm')->find();
			$data = M('gz_member')->where("ssjgbm = '{$organ['jgbm']}'")->field('id,xm,sjhm lxdh,zp')->select();
			foreach ($data as $key => $value) {
				$data[$key]['zp'] = $this->savepath($value['zp']);
			}
		}else{
			$qyArr = M('qy')->select();
	        $qyArr = get_all_child($qyArr,$_POST['qy']);
	        $qy = implode(',',$qyArr);
	        if(empty($qy)){
	            $qy = $_POST['qy'];
	        }else{
	            $qy .= ','.$_POST['qy'];
	        }
	        $where['qy']=['in',$qy];
	        $where['zt']=1;
			$data = M('gzc')->where($where)->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//法律援助
	public function falvyz()
	{
		if($_POST['id']){
			$data = M('yz')->where("ssjg = {$_POST['id']}")->field('id,xm,sjhm lxdh,zp')->select();
			foreach ($data as $key => $value) {
				$data[$key]['zp'] = $this->savepath($value['zp']);
			}
		}else{
			$qyArr = M('qy')->select();
	        $qyArr = get_all_child($qyArr,$_POST['qy']);
	        $qy = implode(',',$qyArr);
	        if(empty($qy)){
	            $qy = $_POST['qy'];
	        }else{
	            $qy .= ','.$_POST['qy'];
	        }
	        // $where['qy']=['in',$qy];
	        $where['zt']=1;
			$data = M('falvyz')->where($where)->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//调委会
	public function twh()
	{
		if($_POST['id']){
			$data = M('tjy')->where("sstwh = {$_POST['id']}")->field('id,xm,sjhm lxdh,zp')->select();
			foreach ($data as $key => $value) {
				$data[$key]['zp'] = $this->savepath($value['zp']);
			}
		}else{
			$qyArr = M('qy')->select();
	        $qyArr = get_all_child($qyArr,$_POST['qy']);
	        $qy = implode(',',$qyArr);
	        if(empty($qy)){
	            $qy = $_POST['qy'];
	        }else{
	            $qy .= ','.$_POST['qy'];
	        }
	        $where['qy']=['in',$qy];
	        $where['zt']=1;
			$data = M('twh')->where($where)->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//司法鉴定
	public function sifajd()
	{
		if($_POST['id']){
			$organ = M('sifajd')->where("id = {$_POST['id']}")->field('id,jgbh')->find();
			$data = M('jd')->where("szjgbh = '{$organ['jgbh']}'")->field('id,xm,sjhm lxdh,zp')->select();
			foreach ($data as $key => $value) {
				$data[$key]['zp'] = $this->savepath($value['zp']);
			}
		}else{
			$qyArr = M('qy')->select();
	        $qyArr = get_all_child($qyArr,$_POST['qy']);
	        $qy = implode(',',$qyArr);
	        if(empty($qy)){
	            $qy = $_POST['qy'];
	        }else{
	            $qy .= ','.$_POST['qy'];
	        }
	        $where['qy']=['in',$qy];
	        $where['zt']=1;
	        $where['jd_type'] = ['like',"%{$_POST['jd_type']}%"];
			$data = M('sifajd')->where($where)->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//律师事务所
	public function lvshisws()
	{
		if($_POST['id']){
			$data = M('ls')->where("zyjg = {$_POST['id']}")->field('id,xm,sjhm lxdh,zp')->select();
			foreach ($data as $key => $value) {
				$data[$key]['zp'] = $this->savepath($value['zp']);
			}
		}else{
			$qyArr = M('qy')->select();
	        $qyArr = get_all_child($qyArr,$_POST['qy']);
	        $qy = implode(',',$qyArr);
	        if(empty($qy)){
	            $qy = $_POST['qy'];
	        }else{
	            $qy .= ','.$_POST['qy'];
	        }
	        $where['qy']=['in',$qy];
	        $where['zt']=1;
			$data = M('lvshisws')->where($where)->select();
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//表格分类
	public function form()
	{
		if(!$_POST['type']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('form')->where("type = {$_POST['type']} and status=1")->select();
		foreach ($data as $key => $value) {
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//表格
	public function form_info(){
		if(!$_POST['id']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('form_info')->where("form_id = {$_POST['id']} and status=1")->select();
		foreach ($data as $key => $value) {
			$data[$key]['path'] = $this->savepath($value['path']);
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//公告
	public function notice()
	{
		if(!$_POST['type']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('notice')->where("status=1 and type={$_POST['type']}")->order('ct_time desc')->find();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//咨询区域
	public function serve_area()
	{
		$data = M('serve_area')->where("status=1")->select();
		foreach ($data as $key => $value) {
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//咨询类别
	public function serve_type()
	{
		if(!$_POST['area_id']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('serve_type')->where("area_id = {$_POST['area_id']} and status=1")->select();
		foreach ($data as $key => $value) {
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//视频区域
	public function serve_video_area()
	{
		$data = M('serve_video_area')->where("status=1")->select();
		foreach ($data as $key => $value) {
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//视频类别
	public function serve_video_type()
	{
		if(!$_POST['area_id']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('serve_video_type')->where("area_id = {$_POST['area_id']} and status=1")->select();
		foreach ($data as $key => $value) {
            $data[$key]['ct_time'] = date("Y-m-d H",$value['ct_time']);
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//发送站内信给工作人员
	public function serve_video_message()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录','data'=>null]));
        }
		if(!$_POST['type']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$member = M('serve_video_member')->where("type = {$_POST['type']}")->find();
		if(empty($member)){
			exit(json_encode(['state'=>'-1','msg'=>'此类别下无人员维护']));
		}
		$title = '视频服务';
		$text = '现有用户正在请求视频服务';
		message($member['uid'],$title,$text);
		$data['ip'] = $member['ip'];
		$data['sort'] = $member['sort'];
		$data['room_id'] = $member['room_id'];
		if($re['id']==$member['uid']){
			$data['account'] = $member['service_account'];
			$data['pwd'] = $member['service_pwd'];
		}else{
			$data['account'] = $member['account'];
			$data['pwd'] = $member['pwd'];
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//新闻封面
	public function image_set()
	{
		$data = M('image_set')->find();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//费用
	public function price()
	{
		if(!$_POST['bussiness_id']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('price')->where("status=1 and bussiness_id = {$_POST['bussiness_id']}")->select();
		foreach ($data as $key => $value) {
            $data[$key]['save_time'] = date("Y-m-d H",$value['save_time']);
          	if($data[$key]['file_name']){
        		$data[$key]['file_name'] = explode('，',$data[$key]['file_name']);
        	} else {
          		for ($i = 0;$i < $data[$key]['file_num'];$i++) {
            		$data[$key]['file_name'][$i] = '';
        		}	
        	}
        }
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//援助机构信息
	public function jg_info()
	{
		if(!$_GET['id']){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		$data = M('falvyz')->field('id,jgmc,dh,dz')->find($_GET['id']);
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	public function tree()
    {
        $data = M('qy')->select();
        $data = getteam($data);
        exit(json_encode(['state'=>1,'msg'=>'成功','data'=>$data]));
    }
	public function upload_video()
	{
        $video = $this->upload_file('Video');
      	
        if($video){
          	//$arr_ext = array('mp4','mp3');
          	//if(!in_array($video['video']['ext'], $arr_ext)){exit(json_encode(['state'=>'-1','msg'=>'上传文件格式不正确']));}
        	 $path = '/Uploads'.$video['video']['savepath'].$video['video']['savename'];
        	 exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$this->savepath($path)]));
        }else{
        	exit(json_encode(['state'=>'-1','msg'=>'视频上传失败']));
        }


  //       header("Content-type: text/html; charset=utf-8");

		// $file = isset($_FILES['file_data']) ? $_FILES['file_data']:null; //分段的文件

		// $name = isset($_POST['file_name']) ? '/Uploads/'.$_POST['file_name']:null; //要保存的文件名

		// $total = isset($_POST['file_total']) ? $_POST['file_total']:0; //总片数

		// $index = isset($_POST['file_index']) ? $_POST['file_index']:0; //当前片数

		// $md5   = isset($_POST['file_md5']) ? $_POST['file_md5'] : 0; //文件的md5值

		// $size  = isset($_POST['file_size']) ?  $_POST['file_size'] : null; //文件大小

		// echo '当前片数：'.$index.PHP_EOL;

		// if(!$file || !$name){
		// 	echo 'failed';
		// 	die();
		// }

		// if ($file['error'] == 0) {
		// 	//检测文件是否存在
		//     if (!file_exists($name)) {
		//         if (!move_uploaded_file($file['tmp_name'], $name)) {
		//             echo '/Uploads/'.$_POST['file_name'];
		//         }
		//     } else {
		//         $content = file_get_contents($file['tmp_name']);
		//         if (!file_put_contents($name, $content, FILE_APPEND)) {
		//             echo 'failed';
		//         }
		// 		echo '/Uploads/'.$_POST['file_name'];
		//     }
		// } else {
		//     echo 'failed';
		// }
	}
	public function test_video()
	{
		$upload = $this->upload_file('Popularizelaw');
		pre($upload);
	}
}