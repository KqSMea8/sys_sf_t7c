<?php
namespace Home\Controller;
use Think\Controller;
class ClockController extends Controller{

	//打卡
	public function clock()
	{
		$re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($re['yhlx']!=3){
        	exit(json_encode(['state'=>'-1','msg'=>'用户类型不符']));
        }
        if(!$_POST['jd'] || !$_POST['wd'] || !$_POST['place']){
            exit(json_encode(['state'=>'-1','msg'=>'请上传地址信息']));
        }
        $special_personnel = M('special_personnel')->where("uid = {$re['id']}")->field('status')->find();
        if($special_personnel['status']!=1){
            exit(json_encode(['state'=>'-1','msg'=>'不可打卡']));
        }
        $clock['img'] = $this->base64_upload($_POST['img'],'User');
        $clock['jd'] = $_POST['jd'];
        $clock['wd'] = $_POST['wd'];
        $clock['place'] = $_POST['place'];
        $clock['ct_time'] = time();
        $clock['uid'] = $re['id'];
        $res = M('clock')->add($clock);
        if($res){
            exit(json_encode(array('status'=>'1','msg'=>'提交成功')));
        }else{
            exit(json_encode(array('status'=>'-1','msg'=>'提交失败')));
        }
	}
    //检测
    public function check()
    {
        switch ($_GET['type']) {
            case '1':
                $start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
                $end_time = strtotime(date("Y-m-d",time()));
                $where['supervise_type'] = 1;
                break;
            case '2':
                $start_time = strtotime(date("Y-m-d",time()));
                $end_time = strtotime(date("Y-m-d",time()))+3600*12;
                $where['supervise_type'] = 2;
                break;
            case '3':
                $start_time = strtotime(date("Y-m-d",time()))+3600*12;
                $end_time = strtotime(date("Y-m-d",time()));
                $where['supervise_type'] = 2;
                break;
            default:
                exit(json_encode(array('status'=>'0','msg'=>'类型错误')));
                break;
        }
        $sfs_member = M('sfs_member')->field('id,uid')->select();
        foreach ($sfs_member as $key => $value) {
            $where['mid'] = $value['id'];
            $where['status'] = 1;
            $special_personnel = M('special_personnel')->where($where)->select();
            if(empty($special_personnel)){
                continue;
            }
            $count = M('special_personnel')->where($where)->count();
            $i=0;
            foreach ($special_personnel as $key => $value) {
                if(empty($value['uid'])){
                    $i++;
                    continue;
                }
                $wheres['ct_time'] = array(array('EGT', $start_time, array('ELT', $end_time)));
                $wheres['uid'] = $value['uid'];
                $clock = M('clock')->where($wheres)->find();
                if(empty($clock)){
                    $i++;
                }
            }
            message($value['uid'],'昨日打卡记录',"昨日总打卡人数：{$count},未打卡人数：{$i}");
        }
        return true;
    }
}