<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    //注册
     public function register(){
        if(!IS_POST){
            $value = array(
                "state" => '-1',
                "msg" => '请使用post方式',
                "data" => '',
            );
            exit(json_encode($value));
        }

        $cha = M('personal')->where('lxdh = "'.$_POST['tel'].'"')->find();
        if ($cha) {
            $value = array(
                "state" => '0',
                "msg" => '该手机号已经被使用！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        //查找用户账号是否已存在
        $user_exists = M('personal')->where('dlzh = "'.$_POST['dlzh'].'"')->find();
        if ($user_exists) {
            $value = array(
                "state" => '0',
                "msg" => '该用户账号已被注册！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        $this->check_mobileverify($_POST['tel'],$_POST['verify_code']);
        if($_POST['password'] == ''){
            $value = array(
                "state" => '-1',
                "msg" => '密码不能为空！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        if($_POST['password'] != $_POST['password2']){
            $value = array(
                "state" => '-1',
                "msg" => '密码不一致！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        if(!$_POST['name']){
            exit(json_encode(['state'=>'-1','msg'=>'请上传用户名']));
        }
        if(!$_POST['id_card']){
            exit(json_encode(['state'=>'-1','msg'=>'请上传身份证号']));
        }
        if(validation_filter_id_card($_POST['id_card'])!=true){
            exit(json_encode(['state'=>'-1','msg'=>'身份证号格式有误']));
        }
        // if(!$_POST['work_unit']){
        //     exit(json_encode(['state'=>'-1','msg'=>'请上传工作单位']));
        // }
        // if(!$_POST['post_code']){
        //     exit(json_encode(['state'=>'-1','msg'=>'请上传邮政编码']));
        // }
        // if(!$_POST['address']){
        //     exit(json_encode(['state'=>'-1','msg'=>'请上传联系地址']));
        // }
        // $gz_member = M('gz_member')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($gz_member){
        //     $data['splx'] = 1;
        //     $data['yhlx'] = 2;
        // }
        // $yz = M('yz')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($yz){
        //     $data['splx'] = 2;
        //     $data['yhlx'] = 2;
        // }
        // $ls = M('ls')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($ls){
        //     $data['splx'] = 5;
        //     $data['yhlx'] = 2;
        // }
        // $tjy = M('tjy')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($tjy){
        //     $data['splx'] = 3;
        //     $data['yhlx'] = 2;
        // }
        // $jd = M('jd')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($jd){
        //     $data['splx'] = 4;
        //     $data['yhlx'] = 2;
        // }
        // $xjk = M('xjk_member')->where("sfzh = {$_POST['id_card']} and sjhm = {$_POST['tel']}")->find();
        // if($xjk){
        //     $data['splx'] = 6;
        //     $data['yhlx'] = 2;
        // }
        // if($data['yhlx']!=2){
            $special_personnel = M('special_personnel')->where("id_card = {$_POST['id_card']} and mobile = {$_POST['tel']}")->find();
            if($special_personnel){
                $data['yhlx']=3;
            }
        // }
        // if(!$_POST['yhlx'] || $_POST['yhlx']>3 || $_POST['yhlx']<1){
        //     exit(json_encode(['state'=>'-1','msg'=>'请上传用户类型或类型错误']));
        // }
        
        // if($_FILES['avator']['name']){
        //     $upload = $this->upload_file('User');
        //     $data['tx'] = '/Uploads'.$upload['avator']['savepath'].$upload['avator']['savename'];
        // }else{
        //     exit(json_encode(['state'=>'-1','msg'=>'未上传头像']));
        // }
        $data['dlzh'] = $_POST['dlzh'];
        $data['xm'] = $_POST['name'];
        $data['zjh'] = $_POST['id_card'];//身份证号
        $data['lxdh'] = $_POST['tel'];
        $data['mm'] = md5($_POST['password']);
        $data['gzdw'] = $_POST['work_unit'];
        $data['yzbm'] = $_POST['post_code'];
        $data['lxdz'] = $_POST['address'];
        $data['zcsj'] = date("Y-m-d H:i:s",time());
        $data['zhdlsj'] = date("Y-m-d H:i:s",time());
        $data['zhxgsj'] = date("Y-m-d H:i:s",time());
        $data['zcip'] = get_client_ip();
        $data['zcfs'] = 1;
        //$data['yhlx'] = $_POST['yhlx'];
        $data['access_token'] = md5($_POST['password'].time());

        $re = M('personal')->add($data);
        if($re){
            $arr['id'] = $re;
            $arr['xm'] = $data['xm'];
            $arr['access_token'] = $data['access_token'];
            if($data['yhlx']){
                $arr['yhlx']=$data['yhlx'];
                // if($data['yhlx']==2){
                //     $arr['splx']=$data['splx'];
                // }
                if($data['yhlx']==3){
                    M('special_personnel')->where("id_card = {$_POST['id_card']} and mobile = {$_POST['tel']}")->update(['uid'=>$re]);
                }
            }
            $value = array(
                "state" => '1',
                "msg" => '注册成功',
                "data" => $arr,
            );
        }else{
            $value = array(
                "state" => '0',
                "msg" => '注册失败',
            );
        }
        exit(json_encode($value));
    }
    //注册测试接口（没有验证码）
     public function test_register(){
        if(!IS_POST){
            $value = array(
                "state" => '-1',
                "msg" => '请使用post方式',
                "data" => '',
            );
            exit(json_encode($value));
        }

        $cha = M('personal')->where('lxdh = "'.$_POST['tel'].'"')->find();
        if ($cha) {
            $value = array(
                "state" => '0',
                "msg" => '该手机号已经被使用！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        //查找用户账号是否已存在
        $user_exists = M('personal')->where('dlzh = "'.$_POST['dlzh'].'"')->find();
        if ($user_exists) {
            $value = array(
                "state" => '0',
                "msg" => '该用户账号已被注册！',
                "data" => '',
            );
            exit(json_encode($value));
        }

        if($_POST['password'] == ''){
            $value = array(
                "state" => '-1',
                "msg" => '密码不能为空！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        if($_POST['password'] != $_POST['password2']){
            $value = array(
                "state" => '-1',
                "msg" => '密码不一致！',
                "data" => '',
            );
            exit(json_encode($value));
        }
        if(!$_POST['name']){
            exit(json_encode(['state'=>'-1','msg'=>'请上传用户名']));
        }
        if(!$_POST['id_card']){
            exit(json_encode(['state'=>'-1','msg'=>'请上传身份证号']));
        }
        if(validation_filter_id_card($_POST['id_card'])!=true){
            exit(json_encode(['state'=>'-1','msg'=>'身份证号格式有误']));
        }

            $special_personnel = M('special_personnel')->where("id_card = {$_POST['id_card']} and mobile = {$_POST['tel']}")->find();
            if($special_personnel){
                $data['yhlx']=3;
            }

        $data['dlzh'] = $_POST['dlzh'];
        $data['xm'] = $_POST['name'];
        $data['zjh'] = $_POST['id_card'];//身份证号
        $data['lxdh'] = $_POST['tel'];
        $data['mm'] = md5($_POST['password']);
        $data['gzdw'] = $_POST['work_unit'];
        $data['yzbm'] = $_POST['post_code'];
        $data['lxdz'] = $_POST['address'];
        $data['zcsj'] = date("Y-m-d H:i:s",time());
        $data['zhdlsj'] = date("Y-m-d H:i:s",time());
        $data['zhxgsj'] = date("Y-m-d H:i:s",time());
        $data['zcip'] = get_client_ip();
        $data['zcfs'] = 1;
        //$data['yhlx'] = $_POST['yhlx'];
        $data['access_token'] = md5($_POST['password'].time());

        $re = M('personal')->add($data);
        if($re){
            $arr['id'] = $re;
            $arr['xm'] = $data['xm'];
            $arr['access_token'] = $data['access_token'];
            if($data['yhlx']){
                $arr['yhlx']=$data['yhlx'];

                if($data['yhlx']==3){
                    M('special_personnel')->where("id_card = {$_POST['id_card']} and mobile = {$_POST['tel']}")->update(['uid'=>$re]);
                }
            }
            $value = array(
                "state" => '1',
                "msg" => '注册成功',
                "data" => $arr,
            );
        }else{
            $value = array(
                "state" => '0',
                "msg" => '注册失败',
            );
        }
        exit(json_encode($value));
    }
   //登录
    public function login(){
    	if(!IS_POST){
    		$value = array(
    			'state' => 0,
    			'msg' => '请使用post方式',
    		);
    		exit(json_encode($value));
    	}
      	if(empty(trim($_POST['tel'])) || empty(trim($_POST['password']))){
        	exit(json_encode(['status' => '0','msg' => '用户名和密码不能为空']));
        }
    	$tel = $_POST['tel'];//手机号码或账号
      	
    	$password =md5($_POST['password']);
		
    	$personal=M('personal');
      	//前台用户 可以使用 账号名 手机号 身份证号登录
    	$re=$personal->where("(dlzh = '{$_POST['tel']}' or lxdh = '{$_POST['tel']}' or zjh = '{$_POST['tel']}') and mm = '{$password}'")->find();
		
        if(!$re){
            $value = array(
                'state' =>0,
                'msg' =>'账号或密码有误',
            );
            exit(json_encode($value));
        }

        //工作人员、审批人员不以手机号登录账号 开始
        // if($re['yhlx'] == 2){
        //     if($re['dlzh'] != $tel){
        //         $value = array(
        //         'state' => 0,
        //         'msg' => '工作人员不能以手机号码登录',
        //     );
        //         exit(json_encode($value));
        //     }
        // }
        //结束
        
    	$data['zhdlsj'] = date("Y-m-d h:i:s",time());
        $data['zhxgsj'] = date("Y-m-d h:i:s",time());
        $data['access_token'] = md5($re['id'].$_POST['password'].time());
		$re2 = $personal->where('id = "'.$re['id'].'"')->save($data);
        $data['id'] = $re['id'];
        $data['yhlx'] = $re['yhlx'];
        $data['xm'] = $re['xm'];
        if($data['yhlx']==2){
            $data['splx'] = $re['splx'];
        }
        unset($data['zhdlsj']);
        unset($data['zhxgsj']);
    	if($re2){
    		$value = array(
    			'state' =>1,
    			'msg' => '登录成功',
    			'data' =>$data
    		);
    	}
    	exit(json_encode($value));
    }
    //微信注册/登录
    public function wechat_login()
    {
        $openid = $_POST['openid'];
        if(!$openid){
            exit(json_encode(['state'=>'-1','msg'=>'未上传openID']));
        }
        $personal = M('personal')->where("openid = {$openid}")->find();
        if($personal){
            $data['zhdlsj'] = date("Y-m-d h:i:s",time());
            $data['zhxgsj'] = date("Y-m-d h:i:s",time());
            $data['zcfs'] = 2;
            $data['access_token'] = md5($personal['id'].$_POST['openid'].time());
            $re2 = $personal->where('id = "'.$personal['id'].'"')->save($data);
            $data['id'] = $personal['id'];
            // $data['yhlx'] = $personal['yhlx'];
            // if($data['yhlx']==2){
            //     $data['splx'] = $personal['splx'];
            // }
            unset($data['zhdlsj']);
            unset($data['zhxgsj']);
            unset($data['zcfs']);
            if($re2){
                exit(json_encode(['state'=>'1','msg'=>'登录成功','data'=>$data]));
            }
        }else{
            $data['openid'] = $openid;
            $data['zcsj'] = date("Y-m-d H:i:s",time());
            $data['zhdlsj'] = date("Y-m-d H:i:s",time());
            $data['zhxgsj'] = date("Y-m-d H:i:s",time());
            $data['zcip'] = get_client_ip();
            $data['access_token'] = md5($openid.time());
            $re = M('personal')->add($data);
            if($re){
                // if($data['yhlx']){
                //     $arr['yhlx'] = $data['yhlx'];
                // }
                // if($arr['yhlx']==2){
                //     $arr['splx']=$data['splx'];
                // }
                $arr['uid'] = $re;
                $arr['access_token'] = $data['access_token'];
                exit(json_encode(['state'=>1,'msg'=>'注册成功','data'=>$arr]));
            }
        }
    }
    public function yzm () {
        if(!IS_POST){
            $value = array(
                "state" => '-1',
                "msg" => '请使用post方式',
                "data" => '',
            );
            exit(json_encode($value));
        }
        $this->checkmobile($_POST['mobile']);

        $data['yzm'] = mt_rand(1000,9999);
        $data['tel'] = $_POST['mobile'];
        //调用短信
        $result = A('Qcloudsms')->sms('224819',$data['tel'],[$data['yzm']]);
        $data['ct_time']=time();
        M('yzm')->add($data);
        $value = array(
                "state" => '1',
                "msg" => '获取验证码成功',
                "data" => $data,
            );
        exit(json_encode($value));
    }

    //生成验证码
	public function photo_yzm()
    {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 17;
        $Verify->length   = 4;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
    //修改密码
    public function editpwd()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['newpassword']==''){
            $value=array(
                'state'=>'-1',
                'msg'=>'新密码不能为空',
            );
            exit(json_encode($value));
        }
        if(strlen($_POST['newpassword']) < 6 || strlen($_POST['newpassword']) > 11)
        {
             $value=array(
                'state'=>'-1',
                'msg'=>'长度不对',
                );
            exit(json_encode($value));
        }
        // if($_POST['repassword']==''){
        //     $value=array(
        //         'state'=>'-1',
        //         'msg'=>'确认密码不能为空',
        //     );
        //     exit(json_encode($value));
        // }
        // if($_POST['newpassword']==$_POST['repassword']){
            $data['mm']=md5($_POST['newpassword']);
            $re1=M('personal')->where('id="'.$re['id'].'"')->save($data);
            if($re1){
                 $value=array(
                'state'=>'1',
                'msg'=>'密码修改成功',
                );
                exit(json_encode($value));
            }else{
                 $value=array(
                    'state'=>'-1',
                    'msg'=>'密码修改失败',
                    );
                exit(json_encode($value));
            }
        // }else{
        //     $value=array(
        //     'state'=>'-1',
        //     'msg'=>'两次密码不一致，请重新输入',
        //     );
        //     exit(json_encode($value));
        // }
    }
    //忘记密码
    public function reset(){

        if($_POST['user_name']==''){
            $value=array(
                'state'=>'-1',
                'msg'=>'用户名不能为空',
            );
            exit(json_encode($value));
        }

        if($_POST['password']==''){
            $value=array(
                'state'=>'-1',
                'msg'=>'原始密码不能为空',
            );
            exit(json_encode($value));
        }
         if($_POST['newpassword']==''){
            $value=array(
                'state'=>'-1',
                'msg'=>'新密码不能为空',
            );
            exit(json_encode($value));
        }
        if($_POST['repassword']==''){
            $value=array(
                'state'=>'-1',
                'msg'=>'确认密码不能为空',
            );
            exit(json_encode($value));
        }
        $re=M('personal')->where('user_name= "'.$_POST['user_name'].'"and user_password="'.md5($_POST['password']).'"')->find();
        if($re){
              if($_POST['newpassword']==$_POST['repassword']){
                    $data['user_password']=md5($_POST['newpassword']);
                    $re1=M('personal')->where('user_name="'.$_POST['user_name'].'"')->save($data);
                    if($re1){
                         $value=array(
                        'state'=>'1',
                        'msg'=>'密码修改成功',
                        );
                        exit(json_encode($value));
                    }else{
                         $value=array(
                        'state'=>'-1',
                        'msg'=>'密码修改失败',
                        );
                        exit(json_encode($value));
                    }
              }else{
                    $value=array(
                    'state'=>'-1',
                    'msg'=>'两次密码不一致，请重新输入',
                    );
                    exit(json_encode($value));
                    }
        }else{
            $value=array(
                'state'=>'-1',
                'msg'=>'用户名或密码不正确，请重新输入',
            );
            exit(json_encode($value));
        }
    }

    public function qrcode()
    {
        $url="https://www.baidu.com";
        $level=2;
        $size=4;
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        $data = $object->png($url,false, $errorCorrectionLevel, $matrixPointSize,2);
        //pre($data);
    }
    public function qrcode_login()
    {
        $access_token = $_POST['access_token'];
        exit(json_encode(['state'=>'1','msg'=>'success','data'=>$access_token]));
    }
  	   //身份证号,人脸,指纹登录（终端登录方式type 1身份证号 2人脸 3指纹）
    public function terminal_login(){
    	if(!IS_POST){
    		$value = array(
    			'state' => 0,
    			'msg' => '请使用post方式',
    		);
    		exit(json_encode($value));
    	}
      	$type = $_REQUEST['type'];
      	if($type == 1){
        	$login_val = $_REQUEST['account'];//身份证号
        } else if($type == 2){
        	$login_val = $_REQUEST['account'];//人脸
        } else if($type == 3){
        	$login_val = $_REQUEST['account'];//指纹
        } else {
        	exit(json_encode(array('state'=>0,'msg'=>'type类型错误')));
        }
      
    	$password =md5($_REQUEST['password']);

    	$personal=M('personal');
      	if($type == 1){
        	$re=$personal->where("(zjh = '{$login_val}')")->find();
        } else {
        	$re=$personal->where("(dlzh = '{$login_val}' or lxdh = '{$login_val}')")->find();
        }
    	

        if(!$re){
            $value = array(
                'state' =>0,
                'msg' =>'账号或密码有误',
            );
            exit(json_encode($value));
        }
        
    	$data['zhdlsj'] = date("Y-m-d h:i:s",time());
        $data['zhxgsj'] = date("Y-m-d h:i:s",time());
        $data['access_token'] = md5($re['id'].$_POST['password'].time());
		$re2 = $personal->where('id = "'.$re['id'].'"')->save($data);
        $data['id'] = $re['id'];
        $data['yhlx'] = $re['yhlx'];
        $data['xm'] = $re['xm'];
        if($data['yhlx']==2){
            $data['splx'] = $re['splx'];
        }
        unset($data['zhdlsj']);
        unset($data['zhxgsj']);
    	if($re2){
    		$value = array(
    			'state' =>1,
    			'msg' => '登录成功',
    			'data' =>$data
    		);
    	}
    	exit(json_encode($value));
    }
  
  	//通过用户标识token获取用户信息
  	public function access_token_info(){
      
      $token = $_REQUEST['token'];
      if(empty($token)){
      	exit(json_encode(array('state'=>0,'msg'=>'token不能为空')));
      }
      
      $personal=M('personal');
      $re=$personal->where("(access_token = '{$token}')")->find();
      if($re){
      	exit(json_encode(array('state'=>1,'msg'=>'查询成功','data'=>$re)));
      } else {
      	exit(json_encode(array('state'=>0,'msg'=>'查询出错或没有数据','data'=> '')));
      }
    }
  
  	public function correct_info(){
    	
    }
      
}