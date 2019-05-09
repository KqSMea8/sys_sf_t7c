<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends Controller {

	//司法要闻
	public function judicial()
	{
        if($_POST['title']){
			$where['j.title'] = array('like',"%{$_POST['title']}%");
		}
		if($_POST['type']){
			$where['t.id'] = $_POST['type'];
		}
		$where['status'] = 1;
		$data = M('judicial j')->join("pa_judicial_type t on j.type_id = t.id")->where($where)->order('j.createtime desc')->field('j.*,t.name')->select();
		foreach ($data as $key => $value) {
			$data[$key]['createtime'] = date("Y-m-d",$value['createtime']);
            $data[$key]['img_url'] = $this->savepath($value['img_url']);
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//司法要闻详情
	public function judicial_info()
	{
        if(!$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择指定文章']));
        }
        $data = M('judicial j')->join("pa_judicial_type t on j.type_id = t.id")->where("j.id = {$_POST['id']}")->field('j.*,t.name')->find();
        if(empty($data)){
            exit(json_encode(['state'=>'-1','msg'=>'无此文章']));
        }
        M('judicial')->where("id = {$_POST['id']}")->setInc('click',1);
        $data['img_url'] = $this->savepath($data['img_url']);
        $data['createtime'] = date("Y-m-d H:i:s",$data['createtime']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
    //案例
    public function case_list()
    {
        if($_POST['title']){
            $where['j.title'] = array('like',"%{$_POST['title']}%");
        }
        if($_POST['type']){
            $where['t.id'] = $_POST['type'];
        }
        $where['status'] = 1;
        $data = M('case j')->join("pa_case_type t on j.type_id = t.id")->where($where)->order('j.createtime desc')->field('j.*,t.name')->select();
        foreach ($data as $key => $value) {
            $data[$key]['createtime'] = date("Y-m-d",$value['createtime']);
            $data[$key]['img_url'] = $this->savepath($value['img_url']);
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //案例详情
    public function case_info()
    {
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择指定案例']));
        }
        $data = M('case j')->join("pa_case_type t on j.type_id = t.id")->where("j.id = {$_POST['id']}")->field('j.*,t.name')->find();
        // M('case')->where("id = {$_POST['id']}")->setInc('click',1);
        $data['img_url'] = $this->savepath($data['img_url']);
        $data['createtime'] = date("Y-m-d H:i:s",$data['createtime']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
	//普法宣传
	public function popularizelaw()
	{
        if(!$_POST['cate']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择类型']));
        }
        //if(!$_POST['type']){
        //    exit(json_encode(['state'=>'-1','msg'=>'未选择分类']));
        //}
        switch ($_POST['cate']) {
        	case '1':
        		$where['cate']=$_POST['cate'];
        		$type_table = 'pa_text_type';
        		break;
        	case '2':
        		$where['cate']=$_POST['cate'];
        		$type_table = 'pa_video_type';
        		break;
        	default:
        		exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
        		break;
        }
        $where['status'] = 1;
        if($_POST['title']){
			$where['p.title'] = array('like',"%{$_POST['title']}%");
		}
		if($_POST['type']){
			$where['t.id'] = $_POST['type'];
		}
		$data = M('popularizelaw p')->join("{$type_table} t on p.type_id = t.id")->where($where)->order('p.createtime desc')->field('p.*,t.name')->select();
		foreach ($data as $key => $value) {
			$data[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
            $data[$key]['img_url'] = $this->savepath($value['img_url']);
            if(!empty($value['cover'])){
            $data[$key]['cover'] = $this->savepath($value['cover']);
            }
		}
		exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
	//普法宣传详情
	public function popularizelaw_info()
	{
		// $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
  //       if(!$re){
  //           exit(json_encode(['state'=>'-2','msg'=>'未登录']));
  //       }
        if(!$_POST['id']){
        	exit(json_encode(['state'=>'-1','msg'=>'未选择指定文章']));
        }
        $popularizelaw = M('popularizelaw')->find($_POST['id']);
        if(empty($_POST['cate'])){
            $_POST['cate'] = $popularizelaw['cate'];
        }else{
            if($popularizelaw['cate']!=$_POST['cate']){
                exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
            }
        }
        switch ($_POST['cate']) {
        	case '1':
        		$where['p.cate']=$_POST['cate'];
        		$type_table = 'pa_text_type';
        		break;
        	case '2':
        		$where['p.cate']=$_POST['cate'];
        		$type_table = 'pa_video_type';
        		break;
        	default:
        		exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
        		break;
        }
        $where['p.id'] = $_POST['id'];
        $data = M('popularizelaw p')->join("{$type_table} t on p.type_id = t.id")->where($where)->field('p.*,t.name')->find();
        $data['img_url'] = $this->savepath($data['img_url']);
        if(!empty($data['cover'])){
            $data['cover'] = $this->savepath($data['cover']);
        }
        $data['createtime'] = date("Y-m-d H:i:s",$data['createtime']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
	}
    //政法要务
    public function affair()
    {
        // $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        // if(!$re){
        //     exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        // }
        if($_POST['cate']>2){
            exit(json_encode(['state'=>'-1','msg'=>'类型错误']));
        }
        if($_POST['cate']){
            $where['a.cate']=$_POST['cate'];
        }
        $where['a.status'] = 1;
        if($_POST['title']){
            $where['a.title'] = array('like',"%{$_POST['title']}%");
        }
        if($_POST['type']){
            $where['t.id'] = $_POST['type'];
        }
        $data = M('affair a')->join("pa_affair_type t on a.type_id = t.id")->where($where)->field('a.*')->order('a.createtime desc')->select();

        foreach ($data as $key => $value) {
            $data[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
            $data[$key]['img_url'] = $this->savepath($value['img_url']);
            switch ($value['cate']) {
                case '1':
                    $data[$key]['cate'] = '机构';
                    break;
                case '2':
                    $data[$key]['cate'] = '人员';
                    break;
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //政法要务详情
    public function affair_info()
    {
        // $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        // if(!$re){
        //     exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        // }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择指定文章']));
        }
        $where['a.id'] = $_POST['id'];
        $data = M('affair a')->join("pa_affair_type t on a.type_id = t.id")->where($where)->field('a.*')->find();
        if(empty($data)){
            exit(json_encode(['state'=>'-1','msg'=>'无此文章']));
        }
        switch ($data['cate']) {
            case '1':
                $data['cate'] = '机构';
                break;
            case '2':
                $data['cate'] = '人员';
                break;
        }
        $data['createtime'] = date("Y-m-d H:i:s",$data['createtime']);
        $data['img_url'] = $this->savepath($data['img_url']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //试题列表
    public function exam_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未选择指定考试类型']));
        }
        $exam_type = M('exam_type')->where("id = {$_POST['id']}")->find();
        if(!$exam_type){
            exit(json_encode(['state'=>'-1','msg'=>'无此考试类型']));
        }
        $exam_list = M('exam_list')->where("type_id = {$_POST['id']}")->order('order_number')->select();
        $data['exam_type'] = $exam_type;
        $data['exam_list'] = $exam_list;
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //试题提交
    public function exam_commit()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'未上传指定考试类型']));
        }
        $exam_type = M('exam_type')->where("id = {$_POST['id']}")->find();
        if(!$exam_type){
            exit(json_encode(['state'=>'-1','msg'=>'无此考试类型']));
        }
        $data = $_POST['data'];
        foreach ($data as $key => $value) {
           if(!$value['id']||!$value['choose']){
            exit(json_encode(['state'=>'-1','msg'=>'提交错误']));
           }
        }
        $exam_list = M('exam_list')->where("type_id = {$_POST['id']}")->order('order_number')->select();

        $count = 0;
        foreach ($exam_list as $key => $value) {
            $score[$key]['uid'] = $re['id'];
            $score[$key]['exam_type_id'] = $_POST['id'];
            $score[$key]['createtime'] = time();
            $score[$key]['choose'] = $data[$key]['choose'];
            if($data[$key]['choose']==$value['true_answer']){
                $count += $value['points'];
                $score[$key]['type'] = 1;
                $score[$key]['question_id'] = $value['id'];
            }else{
                $score[$key]['type'] = 2;
                $score[$key]['question_id'] = $value['id'];
            }
        }
        M()->startTrans();
        $arr['uid'] = $re['id'];
        $arr['exam_type_id'] = $_POST['id'];
        $arr['score'] = $count;
        $arr['createtime'] = time();
        $res=M('test_score')->add($arr);
        $res2 = M('test_question_scores')->addAll($score);
        if($res && $res2){
            M()->commit();
            exit(json_encode(['state'=>'1','msg'=>'提交成功']));
        }else{
            M()->rollback();
            exit(json_encode(['state'=>'2','msg'=>'提交失败']));
        }
    }
    //分数查询
    public function score_inquiry()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $data = M('test_score s')->join('pa_exam_type t on s.exam_type_id = t.id')->where("s.uid = {$re['id']}")->field('s.id,t.name,s.createtime')->select();
        foreach ($data as $key => $value) {
            $data[$key]['createtime'] = date("Y-m-d",$value['createtime']);
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //分数查询详情
    public function score_inquiry_detail(){
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $test_score = M('test_score s')->join('pa_exam_type t on s.exam_type_id = t.id')->where("s.uid = {$re['id']} and s.id = {$_POST['id']}")->field('s.score,s.exam_type_id,t.name,s.createtime')->find();
        if(!$test_score){
            exit(json_encode(['state'=>'-1','msg'=>'无此考试记录']));
        }
        $data['name'] = $re['xm'];
        $data['score'] = $test_score['score'];
        $data['exam_type'] = $test_score['name'];
        $data['createtime'] = date("Y-m-d",$test_score['createtime']);
        $data['question'] = M('test_question_scores t')->join('pa_exam_list e on t.question_id = e.id')->where("exam_type_id = {$test_score['exam_type_id']} and uid= {$re['id']}")->field('e.*,t.type,t.choose')->select();
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
}