<?php
namespace Admin\Controller;
use Think\Controller;
class StatController extends CommonController {
	public function index()
	{
        if(!isset($_GET['zcfs'])){
            $_GET['zcfs'] = 1;
        }
        if($_GET['zcfs']!=5){
            $this->display("Stat/index2");
            die;
        }
        $where['yhlx']=['NEQ','2'];
         // $where['zcfs']=$_GET['zcfs'];
        $where['zcfs']=1;
        // if($name=I('get.name'))$map['name']=array('like',$name);
        // if($id=I('get.id'))$map['id']=array('like',$id);
        // if($tel=I('get.tel'))$map['tel']=array('like',$tel);
        $count=M('personal')->where($where)->count();
        $page= new \Think\Page($count,15);
        $show=$page->show();
        $list=M('personal')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        // foreach ($list as $key => $value) {
        //     switch ($value['yhlx']) {
        //         case '1':
        //             $list[$key]['yhlx'] = '普通用户';
        //             break;
        //         case '2':
        //             $list[$key]['yhlx'] = '工作人员';
        //             break;
        //         case '3':
        //             $list[$key]['yhlx'] = '特殊人员';
        //             break;
        //     }
        // }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
	}
  
  	public function index2(){
    	$this->display();
    }
    public function acount()
    {
        $id = $_GET['id'];
        $where['uid'] = $id;
        $gzc_bid = M('gzc_bid')->where($where)->count();
        $yz_bid  = M('yz_bid')->where($where)->count();
        $tj_bid  = M('tj_bid')->where($where)->count();
        $jd_bid  = M('jd_bid')->where($where)->count();
        $data['bid_count1'] = $gzc_bid+$yz_bid+$tj_bid+$jd_bid;

        $where['status'] = 5;
        $gzc_bid2 = M('gzc_bid')->where($where)->count();
        $yz_bid2  = M('yz_bid')->where($where)->count();
        $tj_bid2  = M('tj_bid')->where($where)->count();
        $jd_bid2  = M('jd_bid')->where($where)->count();
        $data['bid_count2'] = $gzc_bid2+$yz_bid2+$tj_bid2+$jd_bid2;

        $where['status'] = 7;
        $gzc_bid3 = M('gzc_bid')->where($where)->count();
        $yz_bid3  = M('yz_bid')->where($where)->count();
        $tj_bid3  = M('tj_bid')->where($where)->count();
        $jd_bid3  = M('jd_bid')->where($where)->count();
        $data['bid_count3'] = $gzc_bid3+$yz_bid3+$tj_bid3+$jd_bid3;

        unset($where['status']);
        $gzc_order = M('order')->where($where)->where("type = 1")->count();
        $yz_order  = M('order')->where($where)->where("type = 2")->count();
        $tj_order  = M('tj_order')->where($where)->count();
        $jd_order  = M('jd_order')->where($where)->count();
        $ls_order  = M('ls_order')->where($where)->count();
        $data['order_count1'] = $gzc_order+$yz_order+$tj_order+$jd_order+$ls_order;

        $where['status'] = 5;
        $gzc_order2 = M('order')->where($where)->where("type = 1")->count();
        $yz_order2  = M('order')->where($where)->where("type = 2")->count();
        $tj_order2  = M('tj_order')->where($where)->count();
        $jd_order2  = M('jd_order')->where($where)->count();
        $ls_order2  = M('ls_order')->where($where)->count();
        $data['order_count2'] = $gzc_order2+$yz_order2+$tj_order2+$jd_order2+$ls_order2;

        $where['status'] = 7;
        $gzc_order3 = M('order')->where($where)->where("type = 1")->count();
        $yz_order3  = M('order')->where($where)->where("type = 2")->count();
        $tj_order3  = M('tj_order')->where($where)->count();
        $jd_order3  = M('jd_order')->where($where)->count();
        $ls_order3  = M('ls_order')->where($where)->count();
        $data['order_count3'] = $gzc_order3+$yz_order3+$tj_order3+$jd_order3+$ls_order3;

        unset($where['status']);
        $gzc_complain = M('complain')->where($where)->where("type = 1")->count();
        $yz_complain  = M('complain')->where($where)->where("type = 2")->count();
        $tj_complain  = M('complain')->where($where)->where("type = 3")->count();
        $jd_complain  = M('complain')->where($where)->where("type = 4")->count();
        $ls_complain  = M('complain')->where($where)->where("type = 5")->count();
        $data['complain_count1'] = $gzc_complain+$yz_complain+$tj_complain+$jd_complain+$ls_complain;

        $where['status'] = 5;
        $gzc_complain2 = M('complain')->where($where)->where("type = 1")->count();
        $yz_complain2  = M('complain')->where($where)->where("type = 2")->count();
        $tj_complain2  = M('complain')->where($where)->where("type = 3")->count();
        $jd_complain2  = M('complain')->where($where)->where("type = 4")->count();
        $ls_complain2  = M('complain')->where($where)->where("type = 5")->count();
        $data['complain_count2'] = $gzc_complain2+$yz_complain2+$tj_complain2+$jd_complain2+$ls_complain2;

        $where['status'] = 7;
        $gzc_complain3 = M('complain')->where($where)->where("type = 1")->count();
        $yz_complain3  = M('complain')->where($where)->where("type = 2")->count();
        $tj_complain3  = M('complain')->where($where)->where("type = 3")->count();
        $jd_complain3  = M('complain')->where($where)->where("type = 4")->count();
        $ls_complain3  = M('complain')->where($where)->where("type = 5")->count();
        $data['complain_count3'] = $gzc_complain3+$yz_complain3+$tj_complain3+$jd_complain3+$ls_complain3;

        for ($i=1; $i <5; $i++) {
            $data["evaluate_{$i}"] = $this->e_count($i,$id);
        }
        $data['id'] = $id;
        $this->assign("list",$data);
        $this->display();
    }

    private function e_count($i,$uid)
    {
        $where['uid'] = $uid;
        $where['evaluate'] = $i;
        $gzc = M('gzc_bid')->where($where)->count() + M('complain')->where($where)->where("type = 1")->count() + M('order')->where($where)->where("type = 1")->count();
        $yz  = M('yz_bid')->where($where)->count() + M('complain')->where($where)->where("type = 2")->count() + M('order')->where($where)->where("type = 2")->count();
        $tj  = M('tj_bid')->where($where)->count() + M('complain')->where($where)->where("type = 3")->count() + M('tj_order')->where($where)->count();
        $jd  = M('jd_bid')->where($where)->count() + M('complain')->where($where)->where("type = 4")->count() + M('jd_order')->where($where)->count();
        $ls  = M('complain')->where($where)->where("type = 5")->count() + M('ls_order')->where($where)->count();

        $count = $gzc + $yz + $tj + $jd +$ls;
        return $count;
    }
}