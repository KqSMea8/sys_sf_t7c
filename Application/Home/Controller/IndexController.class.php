<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{

	public function index(){
		echo "<script>window.location.href='./web/index.html'</script>";//入口
	}
	public function getRegionCat()
	{
  		$RegionData = M('city')->field('id,REGION_NAME')->select();
  		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$RegionData]));
	}
	//关于我们
	public function about()
	{
		$data = M('about')->find();
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//首页推荐司法要闻
	public function recommend()
	{
		$qy = isset($_POST['qy'])?$_POST['qy']:13;
		// exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$qy]));
		$where['is_recommend'] = 1;
		$where['qy'] = $qy;
		$limit = 5;
		$data = M('judicial')->where($where)->limit($limit)->select();
		foreach ($data as $key => $value) {
			$data[$key]['createtime'] = date("Y-m-d",$value['createtime']);
			$data[$key]['img_url'] = $this->savepath($value['img_url']);
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//更多新闻类型
	public function news()
	{
		$image_set = M('image_set')->find();
		$data[] = ['title'=>'司法要闻','img_url'=> $this->savepath($image_set['judicial'])];
		$data[] = ['title'=>'政务公开','img_url'=> $this->savepath($image_set['affair'])];
		$data[] = ['title'=>'普法宣传','img_url'=> $this->savepath($image_set['popularizelaw'])];
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	public function search()
	{
		if(empty($_POST['keyword'])){
			exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
		}
		//搜索机构
		$where['jgmc'] = ['like',"%{$_POST['keyword']}%"];
		$gzc = M('gzc')->where($where)->field('id,jgmc')->select()?:[];
		$lvshisws = M('lvshisws')->where($where)->field('id,jgmc')->select()?:[];
		$falvyz = M('falvyz')->where($where)->field('id,jgmc')->select()?:[];
		$twh = M('twh')->where($where)->field('id,jgmc')->select()?:[];
		$sfs = M('sfs')->where($where)->field('id,jgmc')->select()?:[];
		$sifajd = M('sifajd')->where($where)->field('id,jgmc')->select()?:[];
		$falvfw = M('falvfw')->where($where)->field('id,jgmc')->select()?:[];
		$xzjg = M('xzjg')->where($where)->field('id,jgmc')->select()?:[];
		//搜索人员
		$where2['xm'] = ['like',"%{$_POST['keyword']}%"];
		$gz_member = M('gz_member')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
		$ls = M('ls')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		$yz = M('yz')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		$tjy = M('tjy')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
		$sfs_member = M('sfs_member')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		$jd = M('jd')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		$fw = M('fw')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		$xzjg_member = M('xzjg_member')->where($where2)->field('id,xm,sjhm,zp')->select()?:[];
 		//搜索新闻
 		$where3['title'] = ['like',"%{$_POST['keyword']}%"];
 		$judicial = M('judicial')->where($where3)->field('id,title')->select()?:[];
 		$case = M('case')->where($where3)->field('id,title')->select()?:[];
 		$affair = M('affair')->where($where3)->field('id,title')->select()?:[];
 		$popularizelaw = M('popularizelaw')->where($where3)->field('id,title')->select()?:[];
 		//机构/人员
 		$data['gzc']['jg']=$gzc;
 		$data['gzc']['jgry']=$gz_member;
 		$data['ls']['jg']=$lvshisws;
 		$data['ls']['jgry']=$ls;
 		$data['yz']['jg']=$falvyz;
 		$data['yz']['jgry']=$yz;
 		$data['tj']['jg']=$twh;
 		$data['tj']['jgry']=$tjy;
 		$data['sfs']['jg']=$sfs;
 		$data['sfs']['jgry']=$sfs_member;
 		$data['jd']['jg']=$sifajd;
 		$data['jd']['jgry']=$jd;
 		$data['fw']['jg']=$falvfw;
 		$data['fw']['jgry']=$fw;
 		$data['xzjg']['jg']=$xzjg;
 		$data['xzjg']['jgry']=$xzjg_member;
 		//新闻
 		$data['judicial'] = $judicial;
 		$data['case'] = $case;
 		$data['affair'] = $affair;
 		$data['popularizelaw'] = $popularizelaw;

 		exit(json_encode(['state'=>1,'msg'=>'success','data'=>$data]));
	}
	 //地区查询
     public function city_list()
     {
        $list = M('qy')->where('pid = 13')->select();
        if($list)
        {
           exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$list]));
       }else{
           exit(json_encode(['state'=>'1','msg'=>'请求失败']));
       }
     }
}