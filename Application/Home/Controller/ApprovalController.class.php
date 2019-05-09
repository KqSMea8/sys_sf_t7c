<?php
namespace Home\Controller;
use Think\Controller;
class ApprovalController extends Controller{
	//申办
	public function tid_list()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status'])){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $where['status'] = $_POST['status'];
        if($_POST['status']!=0){
            $where['approver_id'] = $re['id'];
        }
        switch ($_POST['type']) {
			case '1':
                $jgry = M('gz_member')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
				$table = M('gzc_bid');
				break;
			case '2':
				$table = M('yz_bid');
				break;
			case '3':
				$table = M('tj_bid');
				break;
			case '4':
				$table = M('jd_bid');
				break;
        }
      	
        $data = $table->where($where)->order('ct_time desc')->field('id,uid,proposer_name,ct_time')->select();
      	//echo '<pre>';var_dump($data);exit;
      	//PC端审批，合并 审核中 到 待审核
      	if($_POST['is_pc'] && $_POST['status'] == 0){
          		$where['status'] = 1;
          		$where['approver_id'] = $re['id'];
        	 	$data_1 = $table->where($where)->order('ct_time desc')->field('id,uid,proposer_name,ct_time')->select();
          		if(empty($data)){
                	$data = $data_1;
                } else if($data && $data_1){
                  	
                	$data = array_merge($data,$data_1);
                } else {
                	
                }
          		
        }
      	
        if($data){
            foreach ($data as $key => $value) {
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
        }else{
            exit(json_encode(['state'=>'-1','msg'=>'暂无数据']));
        }
	}
	//投诉
	public function complain()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();

        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status'])){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $where['type'] = $_POST['type'];
        $where['status'] = $_POST['status'];
        if($_POST['status']!=0){
            $where['approver_id'] = $re['id'];
        }
        $data = M('complain')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
      	//PC端审批，合并 审核中 到 待审核
      	if($_POST['is_pc'] && $_POST['status'] == 0){
          		
          		$where['status'] = 1;
          		$where['approver_id'] = $re['id'];
        	 	$data_1 = M('complain')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
          		if(empty($data)){
                	$data = $data_1;
                } else if($data && $data_1){
                  	
                	$data = array_merge($data,$data_1);
                } else {
                	
                }
          		
          		
        }
        foreach ($data as $key => $value) {
        	$personal = M('personal')->where("id = '{$value['uid']}'")->field('id,tx,xm,lxdh,yx,zjh,lxdz')->find();
			$data[$key]['xm'] = $personal['xm'];
            $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//预约
	public function order()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status'])){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $where['status'] = $_POST['status'];
        if($_POST['status']!=0){
            $where['approver_id'] = $re['id'];
        }

        switch ($_POST['type']) {
			case '1':
                $jgry = M('gz_member')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
                $where['type'] = 1;
				$data = M('order')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '2':
                $where['type'] = 2;
				$data = M('order')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '3':
				$data = M('tj_order')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '4':
                $jgry = M('jd')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
				$data = M('jd_order')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '5':
                $jgry = M('ls')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
				$data = M('ls_order')->where($where)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
        }
      	//PC端审批，合并待审核 和 审核中
      	if($_POST['is_pc'] && $_POST['status'] == 0){
          
          	$where_1 = $where;
        	$where_1['status'] = 1;
          	$where_1['approver_id'] = $re['id'];
          	switch ($_POST['type']) {
			case '1':
                $jgry = M('gz_member')->where("uid = {$re['id']}")->field('id')->find();
                $where_1['jgry_id'] = $jgry['id'];
                $where_1['type'] = 1;
				$data_1 = M('order')->where($where_1)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '2':
                $where_1['type'] = 2;
				$data_1 = M('order')->where($where_1)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '3':
				$data_1 = M('tj_order')->where($where_1)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '4':
                $jgry = M('jd')->where("uid = {$re['id']}")->field('id')->find();
                $where_1['jgry_id'] = $jgry['id'];
				$data_1 = M('jd_order')->where($where_1)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
			case '5':
                $jgry = M('ls')->where("uid = {$re['id']}")->field('id')->find();
                $where_1['jgry_id'] = $jgry['id'];
				$data_1 = M('ls_order')->where($where_1)->order('ct_time desc')->field('id,uid,ct_time')->select();
				break;
        	}
          	if(empty($data)){
                	$data = $data_1;
            } else if($data && $data_1){
                	$data = array_merge($data,$data_1);
            } else {
                	
            }
          	//
        }
        foreach ($data as $key => $value) {
        	$personal = M('personal')->where("id = '{$value['uid']}'")->field('id,tx,xm,lxdh,yx,zjh,lxdz')->find();
			$data[$key]['xm'] = $personal['xm'];
            $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//申办修改状态
	public function tid_changeStatus()
	{
                // var_dump($_POST);
                // exit;
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status']) || !$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误','data'=>['status'=>$_POST['status'],'id'=>$_POST['id']]]));
        }
        switch ($_POST['type']) {
			case '1':
				$table = M('gzc_bid');
                $gzc_bid = M('gzc_bid')->find($_POST['id']);
                $member = M('personal')->find($gzc_bid['uid']);//当事人
                $jg = M('gzc')->find($gzc_bid['jg_id']);
                $jgry = M('gz_member')->find($gzc_bid['jgry_id']);
                if($_POST['status']==2){//可用
                    A('Qcloudsms')->sms('223332',$member['lxdh'],[$member['xm'],$gzc_bid['price'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==3){
                    A('Qcloudsms')->sms('223335',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223337',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                  	
                }
				break;
			case '2':
				$table = M('yz_bid');
                $yz_bid = M('yz_bid')->find($_POST['id']);
                $member = M('personal')->find($yz_bid['uid']);
                $jg = M('falvyz')->find($yz_bid['jg_id']);
                $jgry = M('yz')->find($yz_bid['jgry_id']);
                if($_POST['status']==2){
                    A('Qcloudsms')->sms('223349',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==3){
                    A('Qcloudsms')->sms('223351',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223353',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
			case '3':
				$table = M('tj_bid');
				$tj_bid = M('tj_bid')->find($_POST['id']);
                $member = M('personal')->find($tj_bid['uid']);
                $jg = M('twh')->find($tj_bid['jg_id']);
            	$jgry = M('tjy')->find($tj_bid['jgry_id']);
                if($_POST['status']==2){
                    A('Qcloudsms')->sms('223349',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==3){
                    A('Qcloudsms')->sms('223351',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223353',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
			case '4':
				$table = M('jd_bid');
            	$jd_bid = M('jd_bid')->find($_POST['id']);
            	$member = M('personal')->find($jd_bid['uid']);//当事人
            	$jg = M('sifajd')->find($jd_bid['jg_id']);
            	$jgry = M('jd')->find($jd_bid['jgry_id']);
                if($_POST['status']==2){
                  	
                    A('Qcloudsms')->sms('223403',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==3){
                    A('Qcloudsms')->sms('223406',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223410',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
        }
        $data['status']=$_POST['status'];
        if($_POST['status']==7){
            if(empty($_POST['refuse_cause'])){
                exit(json_encode(array('status'=>'1','msg'=>'请填写未通过原因')));
            }
            $data['refuse_cause'] = $_POST['refuse_cause'];
            $data['finish_time'] = time();
        }
        if($_POST['status']==3){
            if(empty($_POST['replenish_cause'])){
                exit(json_encode(array('status'=>'1','msg'=>'请填写需补充材料原因')));
            }
            $data['replenish_cause'] = $_POST['replenish_cause'];
        }
        //if(in_array($_POST['status'],array(1,2,3,7))){
            $data['approver_id'] = $re['id'];
        //}
        $res = $table->where("id = {$_POST['id']}")->save($data);
        if($res){
            exit(json_encode(array('status'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('status'=>'-1','msg'=>'提交失败')));
        }
	}
	//投诉修改状态
	public function complain_changeStatus()
	{
        // echo '<pre>';
        // var_dump($_POST);
        // exit;
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status']) || !$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($_POST['result']){
            $data['result'] = $_POST['result'];
        }
        $complain = M('complain')->find($_POST['id']);
        switch ($_POST['type']) {
            case '1':
                $jg = M('gzc')->find($complain['jg_id']);
                $jgry = M('gz_member')->find($complain['jgry_id']);
                break;
            case '2':
                $jg = M('falvyz')->find($complain['jg_id']);
                $jgry = M('yz')->find($complain['jgry_id']);
                break;
            case '3':
                $jg = M('twh')->find($complain['jg_id']);
                $jgry = M('tjy')->find($complain['jgry_id']);
                break;
            case '4':
                $jg = M('sifajd')->find($complain['jg_id']);
                $jgry = M('jd')->find($complain['jgry_id']);
                break;
            case '5':
                $jg = M('lvshisws')->find($complain['jg_id']);
                $jgry = M('ls')->find($complain['jgry_id']);
                break;
            default:
                exit(json_encode(['state'=>'-1','msg'=>'投诉类型错误']));
                break;
        }
        $member = M('member')->find($complain['uid']);
        if($member['zjh'] == $jgry['sfzh'] && $member['lxdh']==$jgry['sjhm']){
            exit(json_encode(['state'=>'-1','msg'=>'不可审批投诉自己的投诉记录']));
        }
        $data['status']=$_POST['status'];
        if($_POST['status']==7){
            if(empty($_POST['refuse_cause'])){
                exit(json_encode(array('status'=>'1','msg'=>'请填写未通过原因')));
            }
            $data['refuse_cause'] = $_POST['refuse_cause'];
            $data['finish_time'] = time();
        }
        if($_POST['status']==1 || $_POST['status']==2){
            $data['approver_id'] = $re['id'];
        }
        $res = M('complain')->where("id = {$_POST['id']} and type = {$_POST['type']}")->save($data);
      	$applicant_person = M('personal')->find($complain['uid']);//申请人信息
        //发送短信
        switch ($_POST['type']) {
            case '1':
                if($_POST['status']==1){
                   // A('Qcloudsms')->sms('223346',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh']]);
                }
            	if($_POST['status'] == 2){
                	//A('Qcloudsms')->sms('327018',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                  A('Qcloudsms')->sms('223346',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh']]);
                }
            	if($_POST['status'] == 7){
                	A('Qcloudsms')->sms('327011',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                break;
            case '2':
                if($_POST['status']==1){
                    //A('Qcloudsms')->sms('223369',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==2){
                   // A('Qcloudsms')->sms('223349',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                  	A('Qcloudsms')->sms('327454',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==7){
                    A('Qcloudsms')->sms('327044',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                break;
            case '3':
                if($_POST['status']==1){
                    //A('Qcloudsms')->sms('223395',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==2){
                   // A('Qcloudsms')->sms('223349',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                  A('Qcloudsms')->sms('223395',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==7){
                    A('Qcloudsms')->sms('327044',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                break;
            case '4':
                if($_POST['status']==1){
                    //A('Qcloudsms')->sms('223423',$member['lxdh'],[$member['xm'],$jg['zscz']]);
                }
            	if($_POST['status']==2){
                   // A('Qcloudsms')->sms('327411',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                  A('Qcloudsms')->sms('223423',$member['lxdh'],[$member['xm'],$jg['zscz']]);
                }
            	if($_POST['status']==7){
                    A('Qcloudsms')->sms('327419',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                break;
            case '5':
                if($_POST['status']==1){
                    //A('Qcloudsms')->sms('223435',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==2){
                   // A('Qcloudsms')->sms('223435',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                  	A('Qcloudsms')->sms('223435',$member['lxdh'],[$member['xm'],$jg['dh']]);
                }
            	if($_POST['status']==7){
                    A('Qcloudsms')->sms('223435',$applicant_person['lxdh'],[$applicant_person['xm'],$jg['dh'],$jgry['sjhm']]);
                }
                break;
        }
        if($res){
            exit(json_encode(array('status'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('status'=>'-1','msg'=>'提交失败')));
        }
	}
	//预约修改状态
	public function order_changeStatus()
	{
        // echo '<pre>';
        // var_dump($_POST);
        // exit;
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }

        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status']) || !$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data['status']=$_POST['status'];
        if($_POST['status']==1 || $_POST['status']==2){
            $data['approver_id'] = $re['id'];
        }
        if($_POST['status']==7){
            if(empty($_POST['refuse_cause'])){
                exit(json_encode(array('status'=>'1','msg'=>'请填写未通过原因')));
            }
            $data['refuse_cause'] = $_POST['refuse_cause'];
            $data['finish_time'] = time();
        }
        switch ($_POST['type']) {
			case '1':
				$res = M('order')->where("id = {$_POST['id']}")->save($data);
                $order = M('order')->find($_POST['id']);
                $member = M('member')->find($order['uid']);
                $jg = M('gzc')->find($order['jg_id']);
                $jgry = M('gz_member')->find($jd_order['jgry_id']);
                if($_POST['status']==2){
                    A('Qcloudsms')->sms('223340',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223344',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
			case '2':
				$res = M('order')->where("id = {$_POST['id']}")->save($data);
                $order = M('order')->find($_POST['id']);
                $member = M('member')->find($order['uid']);
                $jg = M('falvyz')->find($order['jg_id']);
                $jgry = M('yz')->find($jd_order['jgry_id']);
                if($_POST['status']==2){
                    A('Qcloudsms')->sms('223357',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223359',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
			case '3':
				$res = M('tj_order')->where("id = {$_POST['id']}")->save($data);
                $tj_order = M('tj_order')->find($_POST['id']);
                $member = M('personal')->find($tj_order['uid']);
                $jg = M('twh')->find($tj_order['jg_id']);
                $jgry = M('tjy')->find($tj_order['jgry_id']);
            	if($_POST['status']==2){
                   A('Qcloudsms')->sms('223386',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['zscz'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                   A('Qcloudsms')->sms('223389',$member['lxdh'],[$member['xm'],$jg['zscz'],$jgry['sjhm']]);
                }
				break;
			case '4':
				$res = M('jd_order')->where("id = {$_POST['id']}")->save($data);
                $jd_order = M('jd_order')->find($_POST['id']);
                $member = M('personal')->find($jd_order['uid']);
                $jg = M('sifajd')->find($jd_order['jg_id']);
                $jgry = M('jd')->find($jd_order['jgry_id']);
                if($_POST['status']==2){
                   A('Qcloudsms')->sms('223413',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['zscz'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223418',$member['lxdh'],[$member['xm'],$jg['zscz'],$jgry['sjhm']]);
                }
				break;
			case '5':
				$res = M('ls_order')->where("id = {$_POST['id']}")->save($data);
                $ls_order = M('ls_order')->find($_POST['id']);
                $member = M('personal')->find($ls_order['uid']);
                $jg = M('lvshisws')->find($ls_order['jg_id']);
                $jgry = M('ls')->find($ls_order['jgry_id']);
                if($_POST['status']==2){
                    A('Qcloudsms')->sms('223444',$member['lxdh'],[$member['xm'],$jg['dz'],$jg['dh'],$jgry['sjhm']]);
                }
                if($_POST['status']==7){
                    A('Qcloudsms')->sms('223431',$member['lxdh'],[$member['xm'],$jg['dh'],$jgry['sjhm']]);
                }
				break;
        }
        if($res){
            exit(json_encode(array('status'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('status'=>'-1','msg'=>'提交失败')));
        }
	}

    //工作人员审批
    public function shen_pi(){
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=2 || $re['splx']!=$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'用户类型错误']));
        }
        if(!isset($_POST['status'])){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        switch ($_POST['type']) {
            case '1':
                $jgry = M('gz_member')->where("uid = {$re['id']}")->field('id')->find();
                $where['jgry_id'] = $jgry['id'];
                $table = M('gzc_bid');
                break;
            case '2':
                $table = M('yz_bid');
                break;
            case '3':
                $table = M('tj_bid');
                break;
            case '4':
                $table = M('jd_bid');
                break;
        }
        $data_total = $table->where($where)->order('ct_time desc')->field('id,uid,proposer_name,ct_time')->select();
        if($data){
            foreach ($data as $key => $value) {
                $data[$key]['ct_time'] = date("Y-m-d",$value['ct_time']);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
        }else{
            exit(json_encode(['state'=>'-1','msg'=>'暂无数据']));
        }
    }

    //工作人员流转
    public function liu_zhuan(){
        
    }

    //工作人员统计
    public function statistical(){

    }
}