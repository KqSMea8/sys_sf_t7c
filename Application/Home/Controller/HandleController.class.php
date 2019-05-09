<?php
namespace Home\Controller;
use Think\Controller;
class HandleController extends Controller{
	//公证处申办
	public function gzc_bid()
	{	
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){

            if(!$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile']){
                exit(json_encode(['state'=>'-1','msg'=>'请完整上传信息']));
            }
            $this->check_mobileverify($_POST['mobile'],$_POST['verify_code']);
            $data['proposer_name'] = $_POST['name'];
            $data['proposer_idnum'] = $_POST['id_card'];
            $data['proposer_tel'] = $_POST['mobile'];
            $is_black = M('black_list')->where("name = {$_POST['name']} and mobile = {$_POST['mobile']} and id_card = {$_POST['id_card']}")->find();
        }else{
            $data['uid'] =   $re['id'];
            $data['proposer_name']  =   $re['xm'];
            $data['proposer_idnum'] =   $re['zjh'];
            $data['proposer_tel']   =   $re['lxdh'];
            $is_black = M('black_list')->where("name = {$re['xm']} and mobile = {$_POST['mobile']} and id_card = {$_POST['id_card']}")->find();
        }
        if(empty($_POST['upload'])){
        	exit(json_encode(['state'=>'-1','msg'=>'未上传图片']));
        }
        if(!$_POST['jg_id'] || !$_POST['jgry_id'] || !$_POST['jgry_name'] || !$_POST['jgry_tel'] || !$_POST['bussiness_id'] || !$_POST['country_id'] || !$_POST['use_id'] || !$_POST['language_id'] || !$_POST['is_call'] || !$_POST['price'] || !$_POST['qy']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }

        foreach ($_POST['upload'] as $key => $value) {
            $upload = explode(';',rtrim($value['path'],';'));
          	
            foreach ($upload as $k => $v) {
                $path = $this->base64_upload($v,'Handle');
              	
                $file_Data['pid']=$value['id'];//不知道是啥，先注释
                $file_Data['savepath']=$path;
                $file_Data['create_time']=time();
                if($v){
                    $file.=M('gzc_file')->add($file_Data).',';
                }
				
            }
        }
      	
        $file = rtrim($file,',');

        $data['upload']=$file;
// [['pid'=>1,'path'='str;str'],['pid'=>2,'path'=>'str;str'],['pid'=>3,'path'=>'str;str']]
        $data['jg_id'] 			= 	$_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['jgry_id'] 		= 	$_POST['jgry_id'];
        $data['jgry_name'] 		= 	$_POST['jgry_name'];
        $data['jgry_tel'] 		= 	$_POST['jgry_tel'];
        $data['bussiness_id'] 	= 	$_POST['bussiness_id'];
        $data['country_id'] 	= 	$_POST['country_id'];
        $data['use_id'] 		= 	$_POST['use_id'];
        $data['language_id'] 	= 	$_POST['language_id'];
        $data['is_call'] 		= 	$_POST['is_call'];
        $data['price']          =   $_POST['price'];
        $data['qy']             =   $_POST['qy'];
        $data['ct_time']		=	time();
		
        $res = M('gzc_bid')->add($data);

        //$jgry = M('gz_member')->find($_POST['jgry_id']);
        //A('Qcloudsms')->sms('223326',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
      	
        if($res){
          	//发送给当事人信息
          	$this->send_sms('321741',$re['lxdh'],[$re['xm']]);
          	//发送给工作人员信息
          	$this->send_sms('223326',$_POST['jgry_tel'],[$re['xm'],$re['lxdh']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
	}
  

    //公证申办修改材料
    public function gzc_bid_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('gzc_bid')->where("id = {$_GET['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            $where['a.id'] = ['in',$list['upload']];
            $file_Data = M('gzc_file a')->join('pa_price b on a.pid=b.id')->where($where)->field('a.id,a.savepath,a.pid,b.title')->select();
            foreach ($file_Data as $key => $value) {
                $file_Data[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$file_Data]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('gzc_bid')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if(!empty($_POST['upload'])){
                M()->startTrans();
                foreach ($_POST['upload'] as $key => $value) {
                    $gzc_file = M('gzc_file')->find($value['id']);
                    if(!empty($gzc_file)){
                        $new_path = $this->base64_upload($value['path'],'Handle');
                        $res = M('gzc_file')->where("id = {$value['id']}")->save(['savepath'=>$new_path]);
                        if(!$res){
                            exit(json_encode(['state'=>'0','msg'=>'修改失败']));
                            M()->rollback();
                        }
                    }else{
                        exit(json_encode(['state'=>'-1','msg'=>'修改记录不存在']));
                        M()->rollback();
                    }
                }
                M()->commit();
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }
    }

	//公证处申办查询
	public function gzc_bid_list()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['id']){
        	$data = M('gzc_bid')->where("id = {$_POST['id']}")->find();

            $member_data = M('gz_member')->where("id = {$data['jgry_id']}")->field('id,xm,lxdh,zp')->find();
            $bussiness = M('bussiness')->where("id = {$data['bussiness_id']}")->find();
            $country = M('country')->where("id = {$data['country_id']}")->find();
            $data['jgry_zp'] = $this->savepath($member_data['zp']);
            $data['country'] = $country['title'];
            $data['name'] = $bussiness['title'];

            $replenish = explode(';',$data['replenish']);
            unset($data['replenish']);
            foreach ($replenish as $key => $value) {
                $where['id'] = array('in',$value);
                $images = M('images')->where($where)->field('id,savepath')->select();
                foreach ($images as $k => $v) {
                    $images[$k]['savepath'] = $this->savepath($v['savepath']);
                }
                $data['replenish'][] = $images;
            }
            $data['ct_time'] = date("Y-m-d",$data['ct_time']);
            $where2['a.id'] = ['in',$data['upload']];
            $file_Data = M('gzc_file a')->join('pa_price b on a.pid=b.id')->where($where2)->field('a.savepath,a.pid,b.title')->select();
            foreach ($file_Data as $key => $value) {
                $file_Data[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            $data['upload'] = $file_Data;
            $data['priceData'] = M('price')->where("bussiness_id = {$data['bussiness_id']}")->field('id,title')->select();
            $jg = M('gzc')->field('dz')->find($data['jg_id']);
            $data['jg_address'] = $jg['dz'];
        }else{
        	$arr = M('gzc_bid')->where("( uid = {$re['id']} ) or ( proposer_name ='{$re['xm']}' and proposer_idnum = '{$re['zjh']}' and proposer_tel = '{$re['lxdh']}' )")->order('ct_time desc')->select();
          	
            foreach ($arr as $key => $value) {
                $bussiness = M('bussiness')->where("id = {$value['bussiness_id']}")->find();
                $data[$key]['id'] = $value['id'];
                $data[$key]['name'] = $bussiness['title'];
                $data[$key]['jgry_name'] = $value['jgry_name'];
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                $data[$key]['jgry_mobile'] = $value['jgry_tel'];
                $data[$key]['status'] = $value['status'];
            }
        }
      	
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//公证处申办补充
	public function gzc_bid_replenish()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $gzc_bid = M('gzc_bid')->where("id = {$_POST['id']}")->find();
        // if($gzc_bid['replenish']!=''){
        //     exit(json_encode(['state'=>'-1','msg'=>'已经补充过材料了']));
        // }
        $upload = explode(';',$_POST['upload']);
        foreach ($upload as $key => $value) {
          	if($value == 'undefined' || $value == ''){continue;}
            $path = $this->base64_upload($value,'Handle');
            $img_data['savepath']=$path;
            $img_data['create_time']=time();
            if($value)
            $image_id[$key]=M('images')->add($img_data);
        }
        $data['replenish']=$gzc_bid.';'.implode(',',$image_id);
        $data['status'] = 0;
      	//echo '<pre>';var_dump($data);exit;
        $res = M('gzc_bid')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
	}
    //公证处申办撤销
    public function gzc_bid_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $gzc_bid = M('gzc_bid')->where("id = {$_POST['id']}")->find();
        if(!$gzc_bid){
            exit(json_encode(['state'=>'-1','msg'=>'申办记录不存在']));
        }
        if(!$_POST['cause']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['cause'] = $_POST['cause'];
        $data['status']=4;
        $res = M('gzc_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //公证处申办满意度评价
    public function gzc_bid_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('gzc_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }

	//投诉
	public function complain()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>5 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if(!$_POST['jg_id'] || !$_POST['jgry_id'] || !$_POST['title'] || !$_POST['text'] || !$_POST['complain_type'] || !$_POST['qy']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($_POST['upload']){
            $upload = explode(';',$_POST['upload']);
            foreach ($upload as $key => $value) {
                $path = $this->base64_upload($value,'Handle');
                $img_data['savepath']=$path;
                $img_data['create_time']=time();
                if($value)
                $image_id[$key]=M('images')->add($img_data);
            }
            $data['img']=implode(',',$image_id);
        }

        $data['uid']			=	$re['id'];
        if($_POST['area_id']){
            $data['area_id'] = $_POST['area_id'];
        }
        if($_POST['video']){
            $data['video'] = $_POST['video'];
        }
        $data['type']           =   $_POST['type'];
        $data['jg_id'] 			= 	$_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['jgry_id'] 		= 	$_POST['jgry_id'];
        $data['jgry_name']      =   $_POST['jgry_name'];
        $data['title'] 			= 	$_POST['title'];
        $data['text'] 			= 	$_POST['text'];
        $data['complain_type']  =   $_POST['complain_type'];
        $data['ct_time']		=	time();
        $data['qy']             =   $_POST['qy'];

        $res = M('complain')->add($data);
		
        switch ($_POST['type']) {
            case '1':
                $jgry = M('gz_member')->find($_POST['jgry_id']);
                //A('Qcloudsms')->sms('223345',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);//发送短信给工作人员
            	A('Qcloudsms')->sms('326190',$re['lxdh'],[$re['xm']]);//发给公证投诉当事人
                break;
            case '2':
                $jgry = M('yz')->find($_POST['jgry_id']);
                //A('Qcloudsms')->sms('223361',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
            	A('Qcloudsms')->sms('326238',$re['lxdh'],[$re['xm']]);//发给法律援助投诉当事人
                break;
            case '3':
                $jgry = M('tjy')->find($_POST['jgry_id']);
                //A('Qcloudsms')->sms('223393',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
                break;
            case '4':
                break;
            case '5':
                $jgry = M('ls')->find($_POST['jgry_id']);
                //A('Qcloudsms')->sms('223434',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
                break;
        }
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
	}
    //投诉修改材料
    public function complain_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('complain')->where("id = {$_GET['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            $where['id'] = array('in',$list['img']);
            $images = M('images')->where($where)->field('id,savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$images]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('complain')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if(!empty($_POST['upload'])){
                M()->startTrans();
                foreach ($_POST['upload'] as $key => $value) {
                    $images = M('images')->find($value['id']);
                    if(!empty($images)){
                        $new_path = $this->base64_upload($value['path'],'Handle');
                        $res = M('images')->where("id = {$value['id']}")->save(['savepath'=>$new_path]);
                        if(!$res){
                            exit(json_encode(['state'=>'0','msg'=>'修改失败']));
                            M()->rollback();
                        }
                    }else{
                        exit(json_encode(['state'=>'-1','msg'=>'修改记录不存在']));
                        M()->rollback();
                    }
                }
                M()->commit();
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }
    }
	//投诉查询
	public function complain_list()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>5 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if($_POST['id']){
        	$data = M('complain')->where("type = {$_POST['type']} and id = {$_POST['id']}")->find();
            $complain_type = M('complain_type')->where("id = {$data['complain_type']}")->find();
            $data['complain_type'] = $complain_type['title'];
            $where['id'] = array('in',$data['img']);
            $images = M('images')->where($where)->field('savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            $data['img'] = $images;
            $data['ct_time'] = date("Y-m-d",$data['ct_time']);
            switch ($_POST['type']) {
                case '1':
                    $jgry = M('gz_member')->find($data['jgry_id']);
                    break;
                case '2':
                    $jgry = M('yz')->find($data['jgry_id']);
                    break;
                case '3':
                    $jgry = M('tjy')->find($data['jgry_id']);
                    break;
                case '4':
                    $jgry = M('jd')->find($data['jgry_id']);
                    break;
                case '5':
                    $jgry = M('ls')->find($data['jgry_id']);
                    break;
            }
            $data['jgry_mobile'] = $jgry['sjhm'];
            $personal = M('personal')->field('id,lxdh,xm,zjh')->find($data['uid']);
            $data['personal_mobile'] = $personal['lxdh'];
            $data['personal_name'] = $personal['xm'];
            $data['personal_id_card'] = $personal['zjh'];
        }else{
        	$arr = M('complain')->where("uid = {$re['id']} and type = {$_POST['type']}")->order('ct_time desc')->select();
            foreach ($arr as $key => $value) {
                $complain_type = M('complain_type')->where("id = {$value['complain_type']}")->find();
                $personal = M('personal')->where("id = '{$value['uid']}'")->find();
                $data[$key]['id'] = $value['id'];
                $data[$key]['name'] = $complain_type['title'];
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                $data[$key]['status'] = $value['status'];
                $data[$key]['mobile'] = $personal['lxdh'];
                $data[$key]['jgry_name'] = $value['jgry_name'];
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
    //投诉满意度评价
    public function complain_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>5 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('complain')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //投诉撤销
    public function complain_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>5 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        $complain = M('complain')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->find();
        if(!$complain){
            exit(json_encode(['state'=>'-1','msg'=>'投诉记录不存在']));
        }
        $data['status']=4;
        $res = M('complain')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //公证/法律援助 预约
    public function order()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>2 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if($_POST['type']==1){
            if(!$_POST['jgry_id'] || !$_POST['jgry_name']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $data['jgry_id']        =   $_POST['jgry_id'];
            $data['jgry_name']      =   $_POST['jgry_name'];
        }
        if(!$_POST['type'] || !$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['order_note'] || !$_POST['order_time'] || !$_POST['order_type'] || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($_POST['upload']){
            $upload = explode(';',$_POST['upload']);
        
            foreach ($upload as $key => $value) {
                $path = $this->base64_upload($value,'Handle');
                $img_data['savepath']=$path;
                $img_data['create_time']=time();
                if($value)
                $image_id[$key]=M('images')->add($img_data);
            }
            $data['img']=implode(',',$image_id);
        }

        $data['uid']            =   $re['id'];

        $data['type']           =   $_POST['type'];
        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        
        $data['order_note']     =   $_POST['order_note'];
        $data['order_time']     =   $_POST['order_time'];
        $data['order_type']     =   $_POST['order_type'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];

        $res = M('order')->add($data);
        switch ($_POST['type']) {
            case '1':
                $jgry = M('gz_member')->find($_POST['jgry_id']);
            	A('Qcloudsms')->sms('326212',$re['lxdh'],[$re['xm']]);//发给公证预约当事人
                break;
            case '2':
                $jgry = M('yz')->find($_POST['jgry_id']);
            	A('Qcloudsms')->sms('326244',$re['lxdh'],[$re['xm']]);//发给法律援助预约当事人
                break;
        }
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'0','msg'=>'提交失败')));
        }
    }
    //预约修改材料
    public function order_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('order')->where("id = {$_GET['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            $where['id'] = array('in',$list['img']);
            $images = M('images')->where($where)->field('id,savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$images]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('order')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if(!empty($_POST['upload'])){
                M()->startTrans();
                foreach ($_POST['upload'] as $key => $value) {
                    $images = M('images')->find($value['id']);
                    if(!empty($images)){
                        $new_path = $this->base64_upload($value['path'],'Handle');
                        $res = M('images')->where("id = {$value['id']}")->save(['savepath'=>$new_path]);
                        if(!$res){
                            exit(json_encode(['state'=>'0','msg'=>'修改失败']));
                            M()->rollback();
                        }
                    }else{
                        exit(json_encode(['state'=>'-1','msg'=>'修改记录不存在']));
                        M()->rollback();
                    }
                }
                M()->commit();
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }
    }
    //公证/法律援助 预约查询
    public function order_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>2 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if($_POST['search']){
            
        }
        $where['type'] = $_POST['type'];
        
        if($_POST['id']){
            $where['id'] = $_POST['id'];
            $data = M('order')->where($where)->find();
            if($data){
                $order_type = M('order_type')->where("id = {$data['order_type']}")->find();
                switch ($_POST['type']) {
                    case '1':
                        $jgry = M('gz_member')->find($data['jgry_id']);
                        $data['jgry_mobile'] = $jgry['sjhm'];
                        break;
                    case '2':
                        $jgry = M('yz')->find($data['jgry_id']);
                        break;
                }
                $data['ct_time'] = date("Y-m-d H:i",$data['ct_time']);
                $data['order_time'] = date("Y-m-d H:i",$data['order_time']);
                $data['order_type'] = $order_type['title'];
                $data['jgry_mobile'] ='机构人员电话';
                $data['jgry_name'] = '机构人员名称';

                if($data['img']){
                    $wheres['id'] = array('in',$data['img']);
                    $images = M('images')->where($wheres)->field('savepath')->select();
                    foreach ($images as $k => $v) {
                        $images[$k]['savepath'] = $this->savepath($v['savepath']);
                    }
                    $data['img'] = $images;
                }
                $personal = M('personal')->field('id,lxdh,xm,zjh')->find($data['uid']);
                $data['personal_mobile'] = $personal['lxdh'];
                $data['personal_name'] = $personal['xm'];
                $data['personal_id_card'] = $personal['zjh'];
            }else{
                exit(json_encode(['state'=>'1','msg'=>'暂无数据']));
            }
        }else{
            $where['uid'] = $re['id'];
            $data = M('order')->where($where)->order('ct_time desc')->select();
            foreach ($data as $key => $value) {
                $order_type = M('order_type')->where("id = {$value['order_type']}")->find();
                $data[$key]['ct_time'] = date("Y-m-d H:i",$value['ct_time']);
                $data[$key]['order_time'] = date("Y-m-d H:i",$value['order_time']);
                $data[$key]['order_type'] = $order_type['title'];
                switch ($_POST['type']) {
                    case '1':
                        $jgry = M('gz_member')->find($value['jgry_id']);
                        $data[$key]['jgry_mobile'] = $jgry['sjhm'];
                        break;
                    case '2':
                        $jgry = M('yz')->find($value['jgry_id']);
                        $data[$key]['jgry_mobile'] ='机构人员电话';
                        $data[$key]['jgry_name'] = '机构人员名称';
                        break;
                }
                $wheres['id'] = array('in',$value['img']);
                $images = M('images')->where($wheres)->field('savepath')->select();
                foreach ($images as $k => $v) {
                    $images[$k]['savepath'] = $this->savepath($v['savepath']);
                }
                $data[$key]['img'] = $images;
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //公证/法律援助 预约满意度评价
    public function order_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>2 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('order')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //公证/法律援助 修改时间
    public function order_savetime()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>2 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $order = M('order')->find($_POST['id']);
        if(time()>$order['order_time']){
            exit(json_encode(['state'=>'-1','msg'=>'预约时间已过，不可修改']));
        }
        if($tj_order['save_status']==1){
            exit(json_encode(['state'=>'-1','msg'=>'此记录已修改过，不可再次修改']));
        }
        $data['save_status']=1;
        $data['order_time']=$_POST['order_time'];
        $res = M('order')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->save($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //公证/法律援助 预约撤销
    public function order_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type'] || $_POST['type']>2 || $_POST['type']<0){
            exit(json_encode(['state'=>'-1','msg'=>'未选择类型或类型错误']));
        }
        $order = M('order')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->find();
        if(!$order){
            exit(json_encode(['state'=>'-1','msg'=>'预约记录不存在']));
        }
        $data['status']=4;
        $res = M('order')->where("uid = {$re['id']} and type = {$_POST['type']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //调解申办
    public function tj_bid()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            if(!$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile']){
                exit(json_encode(['state'=>'-1','msg'=>'请完整上传信息']));
            }
            $this->check_mobileverify($_POST['mobile'],$_POST['verify_code']);
            $data['proposer_name'] = $_POST['name'];
            $data['proposer_idnum'] = $_POST['id_card'];
            $data['proposer_tel'] = $_POST['mobile'];
        }else{
            $data['proposer_name']  =   $re['xm'];
            $data['uid']            =   $re['id'];
            $data['proposer_idnum'] = $re['zjh'];
            $data['proposer_tel']   = $re['lxdh'];
        }
        if(empty($_POST['proposerfront_img']) || empty($_POST['proposercontrary_img']) || empty($_POST['adversefront_img']) || empty($_POST['adversecontrary_img'])){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传图片']));
        }
        if(!$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['tj_type'] || !$_POST['qy'] || !$_POST['adverse_name'] || !$_POST['adverse_idnum'] || !$_POST['adverse_tel'] || !$_POST['id_consent']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['proposerfront_img']      = $this->base64_upload($_POST['proposerfront_img'],'User');
        $data['proposercontrary_img']   = $this->base64_upload($_POST['proposercontrary_img'],'User');
        $data['adversefront_img']       = $this->base64_upload($_POST['adversefront_img'],'User');
        $data['adversecontrary_img']    = $this->base64_upload($_POST['adversecontrary_img'],'User');

        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        
        $data['tj_type']        =   $_POST['tj_type'];
        //$data['qy']        =   $_POST['qy'];
        $data['adverse_name']   =   $_POST['adverse_name'];
        $data['adverse_idnum']  =   $_POST['adverse_idnum'];
        $data['adverse_tel']    =   $_POST['adverse_tel'];
        $data['id_consent']     =   $_POST['id_consent'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];

        $res = M('tj_bid')->add($data);
		//var_dump($data);
     // exit;
        if($res){
          	//发送给当事人信息
          	$this->send_sms('326252',$re['lxdh'],[$re['xm']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解申办 修改材料
    public function tj_bid_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('tj_bid')->where("id = {$_GET['id']} and status=0")->field('id,proposerfront_img,proposercontrary_img,adversefront_img,adversecontrary_img')->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$list]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('tj_bid')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if($_POST['proposerfront_img']){
                $data['proposerfront_img'] = $this->base64_upload($_POST['proposerfront_img']);
            }
            if($_POST['proposercontrary_img']){
                $data['proposercontrary_img'] = $this->base64_upload($_POST['proposercontrary_img']);
            }
            if($_POST['adversefront_img']){
                $data['adversefront_img'] = $this->base64_upload($_POST['adversefront_img']);
            }
            if($_POST['adversecontrary_img']){
                $data['adversecontrary_img'] = $this->base64_upload($_POST['adversecontrary_img']);
            }
            $res = true;
            if(!empty($data)){
                $res = M('tj_bid')->where("id = {$_POST['id']}")->save($data);
            }
            if($res){
                exit(json_encode(['state'=>'1','msg'=>'成功']));
            }else{
                exit(json_encode(['state'=>'0','msg'=>'修改失败']));
            }
        }
    }

    //调解申办查询
    public function tj_bid_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['id']){
            $data = M('tj_bid')->where("id = {$_POST['id']}")->find();
            $tj_type = M('tj_type')->where("id = {$data['tj_type']}")->find();
            $data['tj_type'] = $tj_type['title'];
            if($data['replenish']!=0 || $data['replenish']!=''){
                $images = M('images')->where("id = {$data['replenish']}")->field('savepath')->find();
                $data['replenish'] = $this->savepath($images['savepath']);
            }
            $data['proposerfront_img']=$this->savepath($data['proposerfront_img']);
            $data['proposercontrary_img']=$this->savepath($data['proposercontrary_img']);
            $data['adversefront_img']=$this->savepath($data['adversefront_img']);
            $data['adversecontrary_img']=$this->savepath($data['adversecontrary_img']);
            $jg = M('twh')->find($data['jg_id']);
            $data['jg_address'] = $jg['dz'];
            $data['jg_mobile'] = $jg['dh'];
            $data['ct_time'] = date("Y-m-d",$data['ct_time']);
        }else{
            $arr = M('tj_bid')->where("( uid = {$re['id']} ) or ( proposer_name ='{$re['xm']}' and proposer_idnum = '{$re['zjh']}' and proposer_tel = '{$re['lxdh']}' )")->order('ct_time desc')->select();
            foreach ($arr as $key => $value) {
                $tj_type = M('tj_type')->where("id = {$value['tj_type']}")->find();
                $data[$key]['id'] = $value['id'];
                $data[$key]['status'] = $value['status'];
                $data[$key]['name'] = $tj_type['title'];
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                $data[$key]['jg_name'] = $value['jg_name'];
                $jg = M('twh')->find($value['jg_id']);
                $data[$key]['jg_address'] = $jg['dz'];
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }

    //调解申办补充
    public function tj_bid_replenish()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $tj_bid = M('tj_bid')->where("id = {$_POST['id']}")->find();
        if($tj_bid['replenish']!=''){
            exit(json_encode(['state'=>'-1','msg'=>'已经补充过材料了']));
        }
        $upload = explode(';',$_POST['upload']);
        
        foreach ($upload as $key => $value) {
            $path = $this->base64_upload($value,'Handle');
            $img_data['savepath']=$path;
            $img_data['create_time']=time();
            if($value)
            $image_id[$key]=M('images')->add($img_data);
        }
        $data['replenish']=implode(',',$image_id);
        $data['status'] = 0;
        $res = M('tj_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }

    //调解申办满意度评价
    public function tj_bid_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id'] || empty($_POST['evaluate'])){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('tj_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解申办撤销
    public function tj_bid_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $tj_bid = M('tj_bid')->where("id = {$_POST['id']}")->find();
        if(!$tj_bid){
            exit(json_encode(['state'=>'-1','msg'=>'申办记录不存在']));
        }
        if(!$_POST['cause']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['cause'] = $_POST['cause'];
        $data['status']=4;
        $res = M('tj_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //调解 预约
    public function tj_order()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }

        if(!$_POST['area_id'] || !$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['order_note'] || !$_POST['order_time'] || !$_POST['order_type'] || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if(empty($_POST['proposerfront_img']) || empty($_POST['proposercontrary_img']) || empty($_POST['adversefront_img']) || empty($_POST['adversecontrary_img'])){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传图片']));
        }
        $data['proposerfront_img']      = $this->base64_upload($_POST['proposerfront_img'],'User');
       	$data['proposercontrary_img']   = $this->base64_upload($_POST['proposercontrary_img'],'User');
        $data['adversefront_img']       = $this->base64_upload($_POST['adversefront_img'],'User');
        $data['adversecontrary_img']    = $this->base64_upload($_POST['adversecontrary_img'],'User');

        $data['uid']            =   $re['id'];

        $data['area_id']        =   $_POST['area_id'];
        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['order_note']     =   $_POST['order_note'];
        $data['order_time']     =   $_POST['order_time'];
        $data['order_type']     =   $_POST['order_type'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];

        $res = M('tj_order')->add($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解预约 修改材料
    public function tj_order_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('tj_order')->where("id = {$_GET['id']} and status=0")->field('id,proposerfront_img,proposercontrary_img,adversefront_img,adversecontrary_img')->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$list]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('tj_order')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if($_POST['proposerfront_img']){
                $data['proposerfront_img'] = $this->base64_upload($_POST['proposerfront_img']);
            }
            if($_POST['proposercontrary_img']){
                $data['proposercontrary_img'] = $this->base64_upload($_POST['proposercontrary_img']);
            }
            if($_POST['adversefront_img']){
                $data['adversefront_img'] = $this->base64_upload($_POST['adversefront_img']);
            }
            if($_POST['adversecontrary_img']){
                $data['adversecontrary_img'] = $this->base64_upload($_POST['adversecontrary_img']);
            }
            $res = true;
            if(!empty($data)){
                $res = M('tj_order')->where("id = {$_POST['id']}")->save($data);
            }
            if($res){
                exit(json_encode(['state'=>'1','msg'=>'成功']));
            }else{
                exit(json_encode(['state'=>'0','msg'=>'修改失败']));
            }
        }
    }
    //调解 预约查询
    public function tj_order_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['search']){
            
        }
        //$where['uid'] = $re['id'];
        if($_POST['id']){
            $data = M('tj_order')->where("id = {$_POST['id']}")->find();
            $order_type = M('order_type')->where("id = {$data['order_type']}")->find();
            $data['order_type'] = $order_type['title'];
            $data['order_time'] = date("Y-m-d H:i",$data['order_time']);
          	//附件路径
          	$data['adversecontrary_img'] = 'https://sys.t7c.net'.$data['adversecontrary_img'];
          	$data['adversefront_img'] = 'https://sys.t7c.net'.$data['adversefront_img'];
          	$data['proposercontrary_img'] = 'https://sys.t7c.net'.$data['proposercontrary_img'];
          	$data['proposerfront_img'] = 'https://sys.t7c.net'.$data['proposerfront_img'];
          	//
            $personal = M('personal')->field('id,lxdh,xm,zjh')->find($data['uid']);
            $data['personal_mobile'] = $personal['lxdh'];
            $data['personal_name'] = $personal['xm'];
            $data['personal_id_card'] = $personal['zjh'];
        }else{
           $data = M('tj_order')->where($where)->order('ct_time desc')->select();
            foreach ($data as $key => $value) {
                $order_type = M('order_type')->where("id = {$value['order_type']}")->find();
                $data[$key]['order_type'] = $order_type['title'];
                $data[$key]['order_time'] = date("Y-m-d H:i",$value['order_time']);
            } 
        }
        
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //调解 预约满意度评价
    public function tj_order_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('tj_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解 预约修改时间
    public function tj_order_savetime()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $tj_order = M('tj_order')->find($_POST['id']);
        if(time()>$tj_order['order_time']){
            exit(json_encode(['state'=>'-1','msg'=>'预约时间已过，不可修改']));
        }
        if($tj_order['save_status']==1){
            exit(json_encode(['state'=>'-1','msg'=>'此记录已修改过，不可再次修改']));
        }
        $data['save_status']=1;
        $data['order_time']=$_POST['order_time'];
        
        $res = M('tj_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解 预约撤销
    public function tj_order_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $order = M('tj_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->find();
        if(!$order){
            exit(json_encode(['state'=>'-1','msg'=>'预约记录不存在']));
        }
        $data['status']=4;
        $res = M('tj_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }

    //律师 预约
    public function ls_order()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }

        if(!$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['jgry_id'] || !$_POST['jgry_name'] || !$_POST['order_note'] || !$_POST['order_time'] || !$_POST['order_type'] || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($_POST['adjunct']){
            $upload = explode(';',$_POST['adjunct']);

            foreach ($upload as $key => $value) {
                $path = $this->base64_upload($value,'Handle');
                $img_data['savepath']=$path;
                $img_data['create_time']=time();
                if($value)
                $image_id[$key]=M('images')->add($img_data);
            }
            $data['adjunct']=implode(',',$image_id);
        }
        
        $data['uid']            =   $re['id'];

        // $data['area_id']        =   $_POST['area_id'];
        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['jgry_id']        =   $_POST['jgry_id'];
        $data['jgry_name']      =   $_POST['jgry_name'];
        $data['order_note']     =   $_POST['order_note'];
        $data['order_time']     =   $_POST['order_time'];
        $data['order_type']     =   $_POST['order_type'];
        $data['ct_time']        =   time();
        $data['area_id']             =   $_POST['qy'];

        $res = M('ls_order')->add($data);

        if($res){
            $jgry = M('ls')->find($_POST['jgry_id']);
            //A('Qcloudsms')->sms('223425',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //律师预约修改材料
    public function ls_order_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('ls_order')->where("id = {$_GET['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            $where['id'] = array('in',$list['adjunct']);
            $images = M('images')->where($where)->field('id,savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$images]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('ls_order')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if(!empty($_POST['upload'])){
                M()->startTrans();
                foreach ($_POST['upload'] as $key => $value) {
                    $images = M('images')->find($value['id']);
                    if(!empty($images)){
                        $new_path = $this->base64_upload($value['path'],'Handle');
                        $res = M('images')->where("id = {$value['id']}")->save(['savepath'=>$new_path]);
                        if(!$res){
                            exit(json_encode(['state'=>'0','msg'=>'修改失败']));
                            M()->rollback();
                        }
                    }else{
                        exit(json_encode(['state'=>'-1','msg'=>'修改记录不存在']));
                        M()->rollback();
                    }
                }
                M()->commit();
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }
    }
    //律师 预约查询
    public function ls_order_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['search']){
            
        }
        //$where['uid'] = $re['id'];
        if($_POST['id']){
            $data = M('ls_order')->where("id = {$_POST['id']}")->find();
            $order_type = M('order_type')->where("id = {$data['order_type']}")->find();
            $data['order_type'] = $order_type['title'];
            $data['order_time'] = date("Y-m-d H:i",$data['order_time']);
            $data['ct_time'] = date("Y-m-d H",$data['ct_time']);
            if($data['adjunct']){
                $wheres['id'] = array('in',$data['adjunct']);
                $images = M('images')->where($wheres)->field('savepath')->select();
                foreach ($images as $k => $v) {
                    $images[$k]['savepath'] = $this->savepath($v['savepath']);
                }
                $data['adjunct'] = $images;
            }
            $personal = M('personal')->field('id,lxdh,xm,zjh')->find($data['uid']);
            $data['personal_mobile'] = $personal['lxdh'];
            $data['personal_name'] = $personal['xm'];
            $data['personal_id_card'] = $personal['zjh'];
        }else{
            $data = M('ls_order')->where($where)->order('ct_time desc')->select();
            foreach ($data as $key => $value) {
                $order_type = M('order_type')->where("id = {$value['order_type']}")->find();
                $data[$key]['order_type'] = $order_type['title'];
                $data[$key]['order_time'] = date("Y-m-d H:i",$value['order_time']);
                $data[$key]['ct_time'] = date("Y-m-d H:i",$value['ct_time']);
                $jgry = M('ls')->find($data['jg_id']);
                $data[$key]['jgry_mobile'] = $jgry['sjhm'];
            }
        }
        
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //律师 预约满意度评价
    public function ls_order_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('ls_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //律师 预约修改时间
    public function ls_order_savetime()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $ls_order = M('ls_order')->find($_POST['id']);
        if(time()>$ls_order['order_time']){
            exit(json_encode(['state'=>'-1','msg'=>'预约时间已过，不可修改']));
        }
        if($tj_order['save_status']==1){
            exit(json_encode(['state'=>'-1','msg'=>'此记录已修改过，不可再次修改']));
        }
        $data['save_status']=1;
        $data['order_time']=$_POST['order_time'];
        $res = M('ls_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //律师 预约撤销
    public function ls_order_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $order = M('ls_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->find();
        if(!$order){
            exit(json_encode(['state'=>'-1','msg'=>'预约记录不存在']));
        }
        $data['status']=4;
        $res = M('ls_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }

    //鉴定 预约
    public function jd_order()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }

        if(!$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['jgry_id'] || !$_POST['jgry_name'] || !$_POST['order_note'] || !$_POST['order_time'] || !$_POST['jd_type'] || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($_POST['adjunct']){
            $data['adjunct']         = $this->base64_upload($_POST['adjunct'],'User');
        }
        $data['uid']            =   $re['id'];

        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['jgry_id']        =   $_POST['jgry_id'];
        $data['jgry_name']      =   $_POST['jgry_name'];
        $data['order_note']     =   $_POST['order_note'];
        $data['order_time']     =   $_POST['order_time'];
        $data['jd_type']        =   $_POST['jd_type'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];

        $res = M('jd_order')->add($data);

        if($res){
            $jgry = M('jd')->find($_POST['jgry_id']);
            //A('Qcloudsms')->sms('223412',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //调解申办 修改材料
    public function jd_order_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('jd_order')->where("id = {$_GET['id']} and status=0")->field('id,adjunct')->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$list]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('jd_order')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if($_POST['adjunct']){
                $data['adjunct'] = $this->base64_upload($_POST['adjunct']);
            }
            $res = true;
            if(!empty($data)){
                $res = M('jd_order')->where("id = {$_POST['id']}")->save($data);
            }
            if($res){
                exit(json_encode(['state'=>'1','msg'=>'成功']));
            }else{
                exit(json_encode(['state'=>'0','msg'=>'修改失败']));
            }
        }
    }
    //鉴定 预约查询
    public function jd_order_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['search']){
            
        }
        //$where['uid'] = $re['id'];
        if($_POST['id']){
            $data = M('jd_order')->where("id = {$_POST['id']}")->find();
            $jd_type = M('jd_type')->where("id = {$data['jd_type']}")->find();
            $data['jd_type'] = $jd_type['title'];
            $data['order_time'] = date("Y-m-d H:i",$data['order_time']);
            $jgry = M('jd')->find($data['jgry_id']);
            $data['jgry_mobile'] = $jgry['sjhm'];
            if($data['adjunct']){
                $data['adjunct'] = $this->savepath($data['adjunct']);
            }
            $personal = M('personal')->field('id,lxdh,xm,zjh')->find($data['uid']);
            $data['personal_mobile'] = $personal['lxdh'];
            $data['personal_name'] = $personal['xm'];
            $data['personal_id_card'] = $personal['zjh'];
        }else{
            $data = M('jd_order')->where($where)->order('ct_time desc')->select();
            foreach ($data as $key => $value) {
                $jd_type = M('jd_type')->where("id = {$value['jd_type']}")->find();
                $data[$key]['jd_type'] = $jd_type['title'];
                $data[$key]['ct_time'] = date("Y-m-d H:i",$value['ct_time']);
                $data[$key]['order_time'] = date("Y-m-d H:i",$value['order_time']);
                $jgry = M('jd')->find($value['jgry_id']);
                $data[$key]['jgry_mobile'] = $jgry['sjhm'];
            }
        }
        if(empty($data)){
            $data = '';
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //鉴定 预约修改时间
    public function jd_order_savetime()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $jd_order = M('jd_order')->find($_POST['id']);
        if(time()>$jd_order['order_time']){
            exit(json_encode(['state'=>'-1','msg'=>'预约时间已过，不可修改']));
        }
        if($jd_order['save_status']==1){
            exit(json_encode(['state'=>'-1','msg'=>'此记录已修改过，不可再次修改']));
        }
        $data['save_status']=1;
        $data['order_time']=$_POST['order_time'];
        
        $res = M('jd_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //鉴定 预约满意度评价
    public function jd_order_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('jd_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //鉴定 预约撤销
    public function jd_order_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $order = M('jd_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->find();
        if(!$order){
            exit(json_encode(['state'=>'-1','msg'=>'预约记录不存在']));
        }
        $data['status']=4;
        $res = M('jd_order')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //鉴定申办
    public function jd_bid()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            if(!$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile']){
                exit(json_encode(['state'=>'-1','msg'=>'请完整上传信息']));
            }
            $this->check_mobileverify($_POST['mobile'],$_POST['verify_code']);
            $data['proposer_name'] = $_POST['name'];
            $data['proposer_idnum'] = $_POST['id_card'];
            $data['proposer_tel'] = $_POST['mobile'];
        }else{
            $data['proposer_name']  =   $re['xm'];
            $data['uid']            =   $re['id'];
            $data['proposer_idnum'] = $re['zjh'];
            $data['proposer_tel']   = $re['lxdh'];
        }
        if(!$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['jd_type'] || !isset($_POST['jd_cate']) || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        switch ($_POST['jd_cate']) {
            case '1':
                if(!$_POST['adverse_name'] || !$_POST['adverse_idnum'] || !$_POST['adverse_tel']){
                    exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
                }
                $data['adverse_name']   =   $_POST['adverse_name'];
                $data['adverse_idnum']  =   $_POST['adverse_idnum'];
                $data['adverse_tel']    =   $_POST['adverse_tel'];
                break;
            case '2':
                if(!$_POST['adverse_jg_name'] || !$_POST['adverse_jg_address'] || !$_POST['adverse_jg_mobile']){
                    exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
                }
                $data['adverse_jg_name']  =   $_POST['adverse_jg_name'];
                $data['adverse_jg_address']    =   $_POST['adverse_jg_address'];
                $data['adverse_jg_mobile']    =   $_POST['adverse_jg_mobile'];
                break;
        }
        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['jd_type']        =   $_POST['jd_type'];
        $data['jd_cate']        =   $_POST['jd_cate'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];
        $res = M('jd_bid')->add($data);

        if($res){
            // $jgry = M('ls')->find($_POST['jgry_id']);
            // //A('Qcloudsms')->sms('223434',$jgry['sjhm'],[$re['xm'],$re['lxdh']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //鉴定申办查询
    public function jd_bid_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['id']){
            $data = M('jd_bid')->where("id = {$_POST['id']}")->find();
            $jd_type = M('jd_type')->where("id = {$data['jd_type']}")->find();
            $data['jd_type'] = $jd_type['title'];
            $data['ct_time'] = date("Y-m-d",$data['ct_time']);
            if($data['replenish']){
                $data['replenish'] = $this->savepath($data['replenish']);
            }
        }else{
            $arr = M('jd_bid')->where("( uid = {$re['id']} ) or ( proposer_name ='{$re['xm']}' and proposer_idnum = '{$re['zjh']}' and proposer_tel = '{$re['lxdh']}' )")->order('ct_time desc')->select();
            foreach ($arr as $key => $value) {
                $jd_type = M('jd_type')->where("id = {$value['jd_type']}")->find();
                $data[$key]['id'] = $value['id'];
                $data[$key]['name'] = $jd_type['title'];
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                $data[$key]['status'] = $value['status'];
                $data[$key]['jgry_name'] = '机构人员名称';
                $data[$key]['jgry_mobile'] = '机构人员手机';
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //鉴定申办补充
    public function jd_bid_replenish()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $jd_bid = M('jd_bid')->where("id = {$_POST['id']}")->find();
        if($jd_bid['replenish']!=''){
            exit(json_encode(['state'=>'-1','msg'=>'已经补充过材料了']));
        }
        $data['replenish']=$this->base64_upload($_POST['upload'],'Handle');
        $data['status'] = 0;
        $res = M('jd_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }

    //鉴定申办满意度评价
    public function jd_bid_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('jd_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //鉴定申办撤销
    public function jd_bid_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $jd_bid = M('jd_bid')->where("id = {$_POST['id']}")->find();
        if(!$jd_bid){
            exit(json_encode(['state'=>'-1','msg'=>'申办记录不存在']));
        }
        if(!$_POST['cause']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['cause'] = $_POST['cause'];
        $data['status']=4;
        $res = M('jd_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //鉴定司法协同
    public function jd_synergy()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['adverse_name'] || !$_POST['adverse_idnum'] || !$_POST['adverse_tel'] || !$_POST['qy']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if(empty($_POST['aid']) || empty($_POST['decide']) || empty($_POST['decide2']) || empty($_POST['inform']) || empty($_POST['take_down']) || empty($_POST['report']) || empty($_POST['opinion'])){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传文件或图片']));
        }
        $data['uid']            = $re['id'];
        $data['aid']            = $this->base64_upload($_POST['aid'],'Handle');
        $data['decide']         = $this->base64_upload($_POST['decide'],'Handle');
        $data['decide2']        = $this->base64_upload($_POST['decide2'],'Handle');
        $data['inform']         = $this->base64_upload($_POST['inform'],'Handle');
        $data['take_down']      = $this->base64_upload($_POST['take_down'],'Handle');
        $data['report']         = $this->base64_upload($_POST['report'],'Handle');
        $data['opinion']        = $this->base64_upload($_POST['opinion'],'Handle');

        $data['proposer_name']  =   $re['xm'];
        $data['proposer_tel']   =   $re['lxdh'];
        $data['uid']            =   $re['id'];
        
        $data['adverse_name']   =   $_POST['adverse_name'];
        $data['adverse_idnum']  =   $_POST['adverse_idnum'];
        $data['adverse_tel']    =   $_POST['adverse_tel'];
        $data['ct_time']        =   time();
        $data['qy']             =   $_POST['qy'];

        $res = M('jd_synergy')->add($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //法律援助申办
    public function yz_bid()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            if(!$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile']){
                exit(json_encode(['state'=>'-1','msg'=>'请完整上传信息']));
            }
            $this->check_mobileverify($_POST['mobile'],$_POST['verify_code']);
            $data['proposer_name'] = $_POST['name'];
            $data['proposer_idnum'] = $_POST['id_card'];
            $data['proposer_tel'] = $_POST['mobile'];
        }else{
            $data['proposer_name']  =   $re['xm'];
            $data['uid']            =   $re['id'];
            $data['proposer_idnum'] = $re['zjh'];
            $data['proposer_tel']   = $re['lxdh'];
        }
        if(!$_POST['jg_id'] || !$_POST['jg_name'] || !$_POST['item'] || !$_POST['item_info'] || !$_POST['proposer_type']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if(!$_POST['upload']){
            exit(json_encode(['state'=>'-1','msg'=>'未上传附件']));
        }
        $upload = explode(';',$_POST['upload']);
        
        foreach ($upload as $key => $value) {
            $path = $this->base64_upload($value,'Handle');
            $img_data['savepath']=$path;
            $img_data['create_time']=time();
            if($value)
            $image_id[$key]=M('images')->add($img_data);
        }
        $data['upload']=implode(',',$image_id);
        $data['jg_id']          =   $_POST['jg_id'];
        $data['jg_name']        =   $_POST['jg_name'];
        $data['item']           =   $_POST['item'];
        $data['item_info']      =   $_POST['item_info'];
        
        $data['proposer_type']  =   $_POST['proposer_type'];
        $data['ct_time']        =   time();

        $res = M('yz_bid')->add($data);

        if($res){
          	//发送给当事人信息
          	$this->send_sms('326231',$re['lxdh'],[$re['xm']]);
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //预约修改材料
    public function yz_bid_save()
    {
        if(IS_GET){
            $re = M('personal')->where("access_token = '{$_GET['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_GET['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('yz_bid')->where("id = {$_GET['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            $where['id'] = array('in',$list['upload']);
            $images = M('images')->where($where)->field('id,savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$images]));
        }
        if(IS_POST){
            $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
            if(!$re){
                exit(json_encode(['state'=>'-2','msg'=>'未登录']));
            }
            if(!$_POST['id']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
            }
            $list = M('yz_bid')->where("id = {$_POST['id']} and status=0")->find();
            if(empty($list)){
                exit(json_encode(['state'=>'-1','msg'=>'记录不存在']));
            }
            if(!empty($_POST['upload'])){
                M()->startTrans();
                foreach ($_POST['upload'] as $key => $value) {
                    $images = M('images')->find($value['id']);
                    if(!empty($images)){
                        $new_path = $this->base64_upload($value['path'],'Handle');
                        $res = M('images')->where("id = {$value['id']}")->save(['savepath'=>$new_path]);
                        if(!$res){
                            exit(json_encode(['state'=>'0','msg'=>'修改失败']));
                            M()->rollback();
                        }
                    }else{
                        exit(json_encode(['state'=>'-1','msg'=>'修改记录不存在']));
                        M()->rollback();
                    }
                }
                M()->commit();
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }
    }
    //法律援助申办查询
    public function yz_bid_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['id']){
            $data = M('yz_bid')->where("id = {$_POST['id']}")->find();
            $proposer_type = M('proposer_type')->where("id = {$data['proposer_type']}")->find();
            $data['proposer_type'] = $proposer_type['title'];
            $where['id'] = array('in',$data['upload']);
            $images = M('images')->where($where)->field('savepath')->select();
            foreach ($images as $key => $value) {
                $images[$key]['savepath'] = $this->savepath($value['savepath']);
            }
            if($data['replenish']!=''){
                // $replenish = M('images')->where("id = {$data['replenish']}")->field('savepath')->find();
                $data['replenish'] = $this->savepath($data['replenish']);
            }
            $data['ct_time'] = date("Y-m-d",$data['ct_time']);
            $data['upload'] = $images;
        }else{
            $arr = M('yz_bid')->where("( uid = {$re['id']} ) or ( proposer_name ='{$re['xm']}' and proposer_idnum = '{$re['zjh']}' and proposer_tel = '{$re['lxdh']}' )")->order('ct_time desc')->select();
            foreach ($arr as $key => $value) {
                $proposer_type = M('proposer_type')->where("id = {$value['proposer_type']}")->find();
                $data[$key]['id'] = $value['id'];
                $data[$key]['status'] = $value['status'];
                $data[$key]['name'] = $proposer_type['title'];
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
                $data[$key]['jgry_name'] = '机构人员名称';
                $data[$key]['jgry_mobile'] = '机构人员手机';
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //法律援助申办补充
    public function yz_bid_replenish()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $yz_bid = M('yz_bid')->where("id = {$_POST['id']}")->find();
        if($yz_bid['replenish']!=''){
            exit(json_encode(['state'=>'-1','msg'=>'已经补充过材料了']));
        }
        $data['replenish']=$this->base64_upload($_POST['upload'],'Handle');
        $data['status'] = 0;
        $res = M('yz_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }

    //法律援助申办满意度评价
    public function yz_bid_evaluate()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['evaluate']=$_POST['evaluate'];
        $data['status'] = 6;
        $res = M('yz_bid')->where("id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //法律援助申办撤销
    public function yz_bid_cancel()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $yz_bid = M('yz_bid')->where("id = {$_POST['id']}")->find();
        if(!$yz_bid){
            exit(json_encode(['state'=>'-1','msg'=>'申办记录不存在']));
        }
        if(!$_POST['cause']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['cause'] = $_POST['cause'];
        $data['status']=4;
        $res = M('yz_bid')->where("uid = {$re['id']} and id = {$_POST['id']}")->save($data);

        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'撤销成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'撤销失败')));
        }
    }
    //法律援助司法协同
    public function yz_synergy()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
        }
        switch ($_POST['type']) {
            case '1':
                if(empty($_POST['ga_decide']) || empty($_POST['ga_decide2']) || empty($_POST['ga_form']) || empty($_POST['ga_inform']) || empty($_POST['ga_take_down']) || empty($_POST['ga_report']) || empty($_POST['ga_opinion'])){
                    exit(json_encode(['state'=>'-1','msg'=>'请完整上传文件或图片']));
                }
                $data['ga_decide']    = $this->base64_upload($_POST['ga_decide'],'Handle');
                $data['ga_decide2']   = $this->base64_upload($_POST['ga_decide2'],'Handle');
                $data['ga_form']      = $this->base64_upload($_POST['ga_form'],'Handle');
                $data['ga_inform']    = $this->base64_upload($_POST['ga_inform'],'Handle');
                $data['ga_take_down'] = $this->base64_upload($_POST['ga_take_down'],'Handle');
                $data['ga_report']    = $this->base64_upload($_POST['ga_report'],'Handle');
                $data['ga_opinion']   = $this->base64_upload($_POST['ga_opinion'],'Handle');
                break;
            case '2':
                if(!$_POST['jc_name'] || !$_POST['jc_idcard'] || !$_POST['jc_mobile']){
                    exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
                }
                if(empty($_POST['jc_inform']) || empty($_POST['jc_opinion']) || empty($_POST['jc_prosecute'])){
                    exit(json_encode(['state'=>'-1','msg'=>'请完整上传文件或图片']));
                }
                $data['jc_inform']  = $this->base64_upload($_POST['jc_inform'],'Handle');
                $data['jc_opinion'] = $this->base64_upload($_POST['jc_opinion'],'Handle');
                $data['jc_prosecute']  = $this->base64_upload($_POST['jc_prosecute'],'Handle');
                $data['jc_name']    =   $_POST['jc_name'];
                $data['jc_idcard']  =   $_POST['jc_idcard'];
                $data['jc_mobile']  =   $_POST['jc_mobile'];
                break;
            case '3':
                if(empty($_POST['rm_inform']) || empty($_POST['rm_prosecute']) || empty($_POST['rm_advice_note']) || empty($_POST['rm_writ'])){
                    exit(json_encode(['state'=>'-1','msg'=>'请完整上传文件或图片']));
                }
                $data['rm_inform']  = $this->base64_upload($_POST['rm_inform'],'Handle');
                $data['rm_prosecute'] = $this->base64_upload($_POST['rm_prosecute'],'Handle');
                $data['rm_advice_note']  = $this->base64_upload($_POST['rm_advice_note'],'Handle');
                $data['rm_writ'] = $this->base64_upload($_POST['rm_writ'],'Handle');
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
                break;
        }
        
        $data['uid']            = $re['id'];
        $data['type']           = $_POST['type'];
        $data['ct_time']        =   time();
        $res = M('yz_synergy')->add($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //交通事故调解 甲方填写
    public function traffic_accident()
    {
        if(empty($_POST['license']) || empty($_POST['driving_license']) || empty($_POST['front_img']) || empty($_POST['contrary_img'])|| empty($_POST['upload'])){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传图片']));
        }
        if(!$_POST['id'] || !$_POST['place'] || !$_POST['address']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        //甲方
        $upload = explode(';',$_POST['upload']);
        foreach ($upload as $key => $value) {
            $path = $this->base64_upload($value,'Handle');
            $img_data['savepath']=$path;
            $img_data['create_time']=time();
            if($value)
            $image_id[$key]=M('images')->add($img_data);
        }
        $data['place'] = $_POST['place'];
        $data['address'] = $_POST['address'];
        $data['upload']=implode(',',$image_id);
        $data['license']  = $this->base64_upload($_POST['license'],'Handle');
        $data['driving_license']  = $this->base64_upload($_POST['driving_license'],'Handle');
        $data['front_img']  = $this->base64_upload($_POST['front_img'],'Handle');
        $data['contrary_img']  = $this->base64_upload($_POST['contrary_img'],'Handle');

        $res = M('traffic_accident')->where("id = {$_POST['id']}")->save($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //交通事故调解 乙方填写
    public function traffic_accident2()
    {
        $this->check_mobileverify($_POST['mobile'],$_POST['verify_code']);
        if(!$_POST['name'] || !$_POST['id_card'] || !$_POST['mobile']){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传信息']));
        }
        if(!$_POST['id'] || !$_POST['place'] || !$_POST['address2']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if(empty($_POST['license']) || empty($_POST['driving_license']) || empty($_POST['front_img']) || empty($_POST['contrary_img'])|| empty($_POST['upload'])){
            exit(json_encode(['state'=>'-1','msg'=>'请完整上传图片']));
        }
        $data['name2'] = $_POST['name'];
        $data['id_card2'] = $_POST['id_card'];
        $data['mobile2'] = $_POST['mobile'];
        $data['place2'] = $_POST['place'];
        $data['address2'] = $_POST['address2'];
        //乙方
        $upload = explode(';',$_POST['upload']);
        foreach ($upload as $key => $value) {
            $path = $this->base64_upload($value,'Handle');
            $img_data['savepath']=$path;
            $img_data['create_time']=time();
            if($value)
            $image_id[$key]=M('images')->add($img_data);
        }
        $data['upload2']=implode(',',$image_id);
        $data['license2']  = $this->base64_upload($_POST['license'],'Handle');
        $data['driving_license2']  = $this->base64_upload($_POST['driving_license'],'Handle');
        $data['front_img2']  = $this->base64_upload($_POST['front_img'],'Handle');
        $data['contrary_img2']  = $this->base64_upload($_POST['contrary_img'],'Handle');
        $res = M('traffic_accident')->where("id = {$_POST['id']}")->save($data);
        if($res){
            exit(json_encode(array('state'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('state'=>'-1','msg'=>'提交失败')));
        }
    }
    //交通调解乙方二维码
    public function tr_code()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $data['ct_time'] = time();
        $data['uid'] = $re['id'];
        $data['name'] = $re['xm'];
        $data['id_card'] = $re['zjh'];
        $data['mobile'] = $re['lxdh'];
        $res = M('traffic_accident')->add($data);
        $url="https://sft.t7c.net/web/mediationtwo_1.html?id={$res}";
        $level=3;
        $size=4;
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        $qrcode = "Uploads/qrcode/qrcode_{$res}.png";
        $object->png($url,$qrcode, $errorCorrectionLevel, $matrixPointSize,2);
        $qrcode = '/'.$qrcode;
        $qrcode = $this->savepath($qrcode);
        $data = [$res,$qrcode];
        exit(json_encode(array('state'=>'1','msg'=>'提交成功','data'=>$data)));
    }
    public function id_card()
    {
        if(validation_filter_id_card($_POST['id_card'])!=true){
            exit(json_encode(['state'=>'-1','msg'=>'身份证号格式有误']));
        }
    }
  
  	//前台获取援助类别
  	public function get_yuanzhu_types(){
    	$types = M('yuanzhu_type')->where(['p_id'=>0])->field('id,title')->select();
      	$ids = array_column($types,'id');
      	$titles = array_column($types,'title');
      	if(empty($types)){
        	exit(json_encode(array('status'=>'0','info'=>'没有数据或查询出错','data'=>[])));
        } else {
        	exit(json_encode(array('status'=>'1','info'=>'获取援助类别成功','data'=>['id'=>$ids,'title'=>$titles])));
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
  
  	//前台申办、投诉、预约提交发送短信
  	public function send_sms($temple_id,$tel="",$params){
    	//$sms['name'] = $params['name'];//申请人姓名
       	$sms['tel'] = $tel;//发送短信的对象
        //调用短信
        $result = A('Qcloudsms')->sms($temple_id,$sms['tel'],$params);
    }
}