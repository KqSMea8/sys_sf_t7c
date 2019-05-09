<?php
namespace Home\Controller;
use Think\Controller;
class OrganController extends Controller{

	//机构及人员搜索
	public function search()
	{

	}
	//机构及人员选择
	public function choose()
	{
        if(!$_POST['area']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择区域']));
        }
        if(!$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        $qyArr = M('qy')->select();
        //         echo '<pre>';
        // var_dump($qyArr);
        // exit;
        $qyArr = get_all_child($qyArr,$_POST['area']);
        $qy = implode(',',$qyArr);

        if(empty($qy)){
            $qy = $_POST['area'];
        }else{
            $qy .= ','.$_POST['area'];
        }
        $where['qy']=['in',$qy];
        $where['zt']=1;//状态
        switch ($_POST['type']) {
        	case '1':
        		$table = M('gzc');
        		break;
        	case '2':
        		$table = M('lvshisws');//律师事务所
        		break;
        	case '3':
        		$table = M('falvyz');
        		break;
        	case '4':
        		$table = M('twh');
                //$where['is_major'] = $_POST['is_major'];
        		break;
        	case '5':
        		$table = M('falvfw');//法律服务所
        		break;
        	case '6':
        		$table = M('sifajd');
                $where['jd_type'] = ['like',"%{$_POST['jd_type']}%"];
        		break;
        	case '7':
        		$table = M('sfs');//司法所
        		break;
            case '8':
                $table = M('xzjg');
                break;
        	default:
        		exit(json_encode(['state'=>'-1','msg'=>'无此类型机构或人员']));
        		break;
        }
      	
        $data = $table->where($where)->field('id,jgmc')->select();
		//var_dump($where);exit;
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//机构详情 / 机构人员列表
	public function details()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择机构ID']));
        }
        if(!$_POST['cate']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择分类']));
        }
        if(!$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        switch ($_POST['type']) {
        	case '1':
        		$table = M('gzc');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd,jgbm')->find();
        		$member_data = M('gz_member')->where("ssjgbm = '{$organ['jgbm']}'")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '2':
        		$table = M('lvshisws');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd')->find();
        		$member_data = M('ls')->where("zyjg = {$organ['id']}")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '3':
        		$table = M('falvyz');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd')->find();
        		$member_data = M('yz')->where("ssjg = {$organ['id']}")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '4':
        		$table = M('twh');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd')->find();
        		$member_data = M('tjy')->where("sstwh = {$organ['id']}")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '5':
        		$table = M('sfs');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd,sfsbm')->find();
        		$member_data = M('sfs_member')->where("sfsbm = '{$organ['sfsbm']}'")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '6':
        		$table = M('sifajd');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd,jgbh')->find();
        		$member_data = M('jd')->where("szjgbh = '{$organ['jgbh']}'")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
        	case '7':
        		$table = M('falvfw');
        		$organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,dh,jd,wd')->find();
        		$member_data = M('fw')->where("gzdw = {$organ['id']}")->field('id,xm,lxdh,zw,ywzc,zp')->select();
        		break;
            case '8':
                $table = M('xzjg');
                $organ = $table->where("id = {$_POST['id']}")->field('id,jgmc,dz,lxdh dh,jd,wd')->find();
                $member_data = M('xzjg_member')->where("ssjgbm = {$organ['jgbm']}")->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
        	default:
        		exit(json_encode(['state'=>'-1','msg'=>'无此类型机构或人员']));
        		break;
        }
        switch ($_POST['cate']) {
        	case '1':
        		$data = $organ;
        		break;
        	case '2':
        		$data = $member_data;
                foreach ($data as $key => $value) {
                    if($value['zp']){
                        $data[$key]['zp'] = $this->savepath($value['zp']);
                    }
                }
        		break;
        	default:
        		exit(json_encode(['state'=>'-1','msg'=>'无此分类']));
        		break;
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
    public function jg_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        switch ($_POST['type']) {
            case '1':
                $table = M('gzc');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '2':
                $table = M('lvshisws');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '3':
                $table = M('falvyz');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '4':
                $table = M('twh');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '5':
                $table = M('sfs');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '6':
                $table = M('sifajd');
                $organ = $table->field('id,jgmc')->select();
                break;
            case '7':
                $table = M('falvfw');
                $organ = $table->field('id,jgmc')->select();
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'无此类型机构']));
                break;
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$organ]));
    }
    public function jgry_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        switch ($_POST['type']) {
            case '1':
                $member_data = M('gz_member')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '2':
                $member_data = M('ls')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '3':
                $member_data = M('yz')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '4':
                $member_data = M('tjy')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '5':
                $member_data = M('sfs_member')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '6':
                $member_data = M('jd')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            case '7':
                $member_data = M('fw')->field('id,xm,lxdh,zw,ywzc,zp')->select();
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'无此类型人员']));
                break;
        }
        $data = $member_data;
        foreach ($data as $key => $value) {
            if($value['zp']){
                $data[$key]['zp'] = $this->savepath($value['zp']);
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //机构详情 / 机构人员详情
    public function info()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择机构ID']));
        }
        if(!$_POST['cate']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择分类']));
        }
        if(!$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        $where['id'] = $_POST['id'];
        switch ($_POST['type']) {
            case '1':
                if($_POST['cate']==1){
                    $table = M('gzc');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd,jgbm')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('gz_member')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '2':
                if($_POST['cate']==1){
                    $table = M('lvshisws');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('ls')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '3':
                if($_POST['cate']==1){
                    $table = M('falvyz');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('yz')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '4':
                if($_POST['cate']==1){
                    $table = M('twh');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('tjy')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '5':
                if($_POST['cate']==1){
                    $table = M('sfs');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd,sfsbm')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('sfs_member')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '6':
                if($_POST['cate']==1){
                    $table = M('sifajd');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd,jgbh')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('jd')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '7':
                if($_POST['cate']==1){
                    $table = M('falvfw');
                    $organ = $table->where($where)->field('id,jgmc,dz,dh,jd,wd')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('fw')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            case '8':
                if($_POST['cate']==1){
                    $table = M('xzjg');
                    $organ = $table->where($where)->field('id,jgmc,dz,lxdh dh,jd,wd')->find();
                }
                if($_POST['cate']==2){
                    $member_data = M('xzjg_member')->where($where)->field('id,xm,lxdh,zw,ywzc,zp')->find();
                }
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'无此类型机构或人员']));
                break;
        }
        switch ($_POST['cate']) {
            case '1':
                $data = $organ;
                break;
            case '2':
                $data = $member_data;
                $data['zp'] = $this->savepath($data['zp']);
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'无此分类']));
                break;
        }
        if(empty($data)){
            exit(json_encode(['state'=>'-1','msg'=>'无此详情数据']));
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //文书查询
    public function writ()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            // exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || !$_POST['cate']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误','type'=>I('post.'),'cate'=>$_POST['cate']]));
        }
        switch ($_POST['cate']) {
            case '1':
                $this->checkmobile($_POST['mobile']);
                if(!$_POST['id_card'] || !$_POST['name'])
                {
                    exit(json_encode(['state'=>'-1','msg'=>'请完善信息']));
                }
                if($_POST['verify_code'] == ""){
                    $value = array(
                        "state" => '-1',
                        "msg" => '验证码不能为空！',
                        "data" => '',
                    );
                    exit(json_encode($value));
                }
                $yzm = M('yzm')->where('tel="'. $_POST['mobile'].'"')->order('id desc')->find();
                //判断验证码
                if($_POST['verify_code'] != $yzm['yzm']){
                     $value = array(
                     "state" => '-1',
                     "msg" => '验证码不正确',
                     "data" => '',
                     );
                     exit(json_encode($value));
                }else{
                    M('yzm')->where('tel="'. $_POST['mobile'].'"')->delete();
                }
                $data = M('writ')->where("((name = {$_POST['name']} and id_card = {$_POST['id_card']} and mobile = {$_POST['mobile']}) or (name2 = {$_POST['name']} and id_card2 = {$_POST['id_card']} and mobile2 = {$_POST['mobile']}) or (name3 = {$_POST['name']} and id_card3 = {$_POST['id_card']} and mobile3 = {$_POST['mobile']})) and type = {$_POST['type']}")->select();
                foreach ($data as $key => $value) {
                    $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                }
                break;
            case '2':
                if(!$_POST['id_card'] || !$_POST['name'] || !$_POST['files_id'])
                {
                    exit(json_encode(['state'=>'-1','msg'=>'请完善信息']));
                }
                $data = M('writ')->where("((name = {$_POST['name']} and id_card = {$_POST['id_card']} and mobile = {$_POST['mobile']}) or (name2 = {$_POST['name']} and id_card2 = {$_POST['id_card']} and mobile2 = {$_POST['mobile']}) or (name3 = {$_POST['name']} and id_card3 = {$_POST['id_card']} and mobile3 = {$_POST['mobile']})) and type = {$_POST['type']} and files_id = {$_POST['files_id']}")->select();
                foreach ($data as $key => $value) {
                    $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                }
                break;
            case '3':
                $this->checkmobile($_POST['mobile']);
                if($_POST['verify_code'] == ""){
                    $value = array(
                        "state" => '-1',
                        "msg" => '验证码不能为空！',
                        "data" => '',
                    );
                    exit(json_encode($value));
                }
                $yzm = M('yzm')->where('tel="'. $_POST['mobile'].'"')->order('id desc')->find();
                //判断验证码
                if($_POST['verify_code'] != $yzm['yzm']){
                     $value = array(
                     "state" => '-1',
                     "msg" => '验证码不正确',
                     "data" => '',
                     );
                     exit(json_encode($value));
                }else{
                    M('yzm')->where('tel="'. $_POST['mobile'].'"')->delete();
                }
                if(!$_POST['id_card'] || !$_POST['name'] || !$_POST['files_id'])
                {
                    exit(json_encode(['state'=>'-1','msg'=>'请完善信息']));
                }
                $data = M('writ')->where("((name = '{$_POST['name']}' and id_card = {$_POST['id_card']} and mobile = {$_POST['mobile']}) or (name2 = '{$_POST['name']}' and id_card2 = {$_POST['id_card']} and mobile2 = {$_POST['mobile']}) or (name3 = '{$_POST['name']}' and id_card3 = {$_POST['id_card']} and mobile3 = {$_POST['mobile']})) and type = {$_POST['type']} and files_id = {$_POST['files_id']}")->select();
                foreach ($data as $key => $value) {
                    $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                    $data[$key]['path'] = $this->savepath($value['path']);
                }
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
                break;
        }
        if(empty($data)){
            exit(json_encode(['state'=>'1','msg'=>'查询不到信息，请前往人工查询']));
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //文书详情
    public function writ_info()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data = M('writ')->find($_POST['id']);
        $data['path'] = $this->savepath($data['path']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }

    //文书推送
    // public function writ_send(){
    //     $id = I('post.id');
    //     $writ = M('writ')->where('id',$id)->find();
    //     $phone[] = $writ['mobile'];
    //     $phone[] = $writ['mobile2'];
    //     $phone[] = $writ['mobile3'];

    //     foreach ($phone as $key => $value) {
    //         $uid = M('personal')->where('lxdh',$value)->field('id')->find();

    //     }


    // }
}