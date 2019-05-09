<?php
namespace Home\Controller;
use \Think\Controller;
class PersonalController extends Controller{

    //我的信息
    public function mine()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->field('id,tx,xm,lxdh,yx,zjh,lxdz,qq')->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录','data'=>null]));
        }
        $re['tx'] = $this->savepath($re['tx']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$re]));
    }
     //消息状态
     public function xxred()
     {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->field('id')->find();
        $res = M('message')->where("uid = {$re['id']} and status=0")->count('*');
        if($res)
        {
            exit(json_encode(['state'=>'1','msg'=>'有的','data'=>$res]));
        }else
        {
            exit(json_encode(['state'=>'0','msg'=>'没有']));
        }
     }
    //个人信息修改
    public function mine_update()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $old_file = __EMAIL__.$re['tx'];
        $data = $_POST;
        if(empty($data['avator'])){
            unset($data['avator']);
        }
        if($_FILES['avator']['name']){
            $upload = $this->upload_file('User');
            $data['tx'] = '/Uploads'.$upload['avator']['savepath'].$upload['avator']['savename'];
            $file = __EMAIL__.$data['tx'];
        }
        if($data['born']){
            $born = substr($data['born'],0,4);
            $now = date('Y',time());
            $age = $now-$born;
            $data['age'] = $age;
        }
        unset($data['access_token']);
        $data['zhxgsj'] = date("Y-m-d H:i:s",time());
        $res =M('personal')->where("id = {$re['id']}")->save($data);
        if($res){
            if($_FILES['avator']['name']){
                @unlink($old_file);
            }
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }else{
            @unlink($file);
            exit(json_encode(['state'=>'2','msg'=>'失败']));
        }
    }
    //消息中心
    public function message()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if($_POST['title']){
            $data = M('message')->where("uid = {$re['id']} and status=0 and title like %{$_POST['title']}%")->order('ct_time desc')->select();
        }else{
            $data = M('message')->where("uid = {$re['id']} and status=0")->order('ct_time desc')->select();
        }
        if(empty($data)){
            $data = array();
        }
        if($_POST['title']){
            $data2 = M('message')->where("uid = {$re['id']} and status=1 and title like %{$_POST['title']}%")->order('ct_time desc')->select();
        }else{
            $data2 = M('message')->where("uid = {$re['id']} and status=1")->order('ct_time desc')->select();
        }
        if(empty($data2)){
            $data2 = array();
        }
        $arr = array_merge($data,$data2);
        foreach ($arr as $key => $value) {
            $arr[$key]['img_url'] = $this->savepath($value['img_url']);
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$arr]));
    }
    //消息详情
    public function message_info()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data = M('message')->find($_POST['id']);
        //文书类型消息只能读一次
        if($data['is_writ'] == 1 && $data['status'] == 1){
            exit(json_encode(['state'=>'-3','msg'=>'此消息是文书类消息，只能读一次']));
        }
        $data['img_url'] = $this->savepath($data['img_url']);
        $data['adjunct'] = $this->savepath($data['adjunct']);
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //消息已读
    public function message_change()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $res = M('message')->where("id = {$_POST['id']}")->save(['status'=>1]);
        if($res){
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }else{
            exit(json_encode(['state'=>'-1','msg'=>'失败']));
        }
    }
    //最近消息记录
    public function chat_history()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $data = M('chat')->query("select * from (select * from pa_chat where uid = {$re['id']} or uid2 = {$re['id']} order by ct_time desc ) pa_chat group by uid");
        foreach ($data as $key => $value) {
            foreach ($data as $k => $v) {
                if($value['uid']==$v['uid2'] && $value['uid2']==$v['uid']){
                    if($value['ct_time']>$v['ct_time']){
                        unset($data[$k]);
                    }else{
                        unset($data[$key]);
                    }
                }
            }
        }
        // $data = M('chat')->where("uid = {$re['id']}")->group('touser_id')->order('createtime desc')->field('touser_id,msg,createtime,status')->select();
        foreach ($data as $key => $value) {
            if($value['uid2']==$re['id']){
                $user = M('personal')->where("id = {$value['uid']}")->field('id,xm,tx,lxdh')->find();
            }
            if($value['uid']==$re['id']){
                $user = M('personal')->where("id = {$value['uid2']}")->field('id,xm,tx,lxdh')->find();
            }
            $user['tx'] = $this->savepath($user['tx']);
            $data[$key]['user']=$user;
            if($value['uid']==$re['id'] && $value['status']==0){
                $data[$key]['status']=1;
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$data]));
    }
    //消息记录+头像+是否在线+详情
    public function chatlist()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data = M('chat')->query("select * from `pa_chat` where ( ( `uid2` = {$_POST['id']} and `uid` = {$re['id']} ) or ( `uid2` = {$re['id']} and `uid` = {$_POST['id']} ) ) ORDER BY ct_time desc");
        $data2 = M('chat')->query("select * from `pa_chat` where `uid` = {$_POST['id']} or `uid2` = {$_POST['id']} ORDER BY ct_time asc");
        $tx = M('personal p')->field('p.id,p.tx,p.xm,p.lxdh,g.jgmc')
                             ->join('LEFT JOIN pa_gz_member m on p.lxdh = m.sjhm and p.zjh = m.sfzh')
                             ->join('LEFT JOIN pa_gzc g on g.jgbm = m.ssjgbm')
                             ->where('p.id = '.$re['id'].' or p.id = '.$_POST['id'])
                             ->select();
        foreach ($tx as $v) {
            $txdata[$v['id']] = $v['tx'];
            if($v['id'] == $_POST['id']) $info = $v;
        }
        $t = 180;
        $hid = [];
        $ttime = 0;
        $stime = 0;
        foreach ($data2 as $k => $v) {
            if(!$hid[$v['uid']] && $v['uid'] != $_POST['id']) $hid[$v['uid']] = 1;
            if($v['uid'] == $_POST['id']){
                if($stime != 0){
                    if($stime+$t < $v['ct_time']) $ttime += $t;
                    else $ttime += $v['ct_time']-$stime;
                }
                $stime = $v['ct_time'];
            }
        }
        if($ttime != 0) $ttime += $t;
        $info['count'] = count($data2);
        $info['hnum'] = count($hid);
        $info['ctime'] = floor($ttime/60).'分钟'.($ttime%60).'秒';
        // 修改会员表 在线 字段
        M('personal')->where('id = '.$re['id'])->save(array('online'=>json_encode(array('id'=>$_POST['id'],'time'=>time()))));
        $res = json_decode(M('personal')->where('id = '.$_POST['id'])->find()['online'],true);
        // 判断 对象是否是自己 && 判断 是否离线未超30秒
        if($res['id'] == $re['id'] && $res['time']+10 > time()) $online = 1;
        else $online = 0;
        $rdata['data'] = $data;
        $rdata['txdata'] = $txdata;
        $rdata['online'] = $online;
        $rdata['info'] = $info;
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$rdata]));
    }
    //  查询最新消息
    public function timechatlist()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id'] || !$_POST['time']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $data = M('chat')->query("select * from `pa_chat` where ( ( `uid2` = {$_POST['id']} and `uid` = {$re['id']} ) or ( `uid2` = {$re['id']} and `uid` = {$_POST['id']} )) and ct_time > {$_POST['time']} ORDER BY ct_time desc");
        // 修改会员表 在线 字段
        $importing = $_POST['importing'] ? '1' : '0';
        M('personal')->where('id = '.$re['id'])->save(array('online'=>json_encode(array('id'=>$_POST['id'],'time'=>time(),'importing'=>$importing))));
        $res = json_decode(M('personal')->where('id = '.$_POST['id'])->find()['online'],true);
        // 判断 对象是否是自己 && 判断 是否离线未超30秒
        if($res['id'] == $re['id'] && ($res['time']+10) > time()) $online = 1;
        else $online = 0;
        $rdata['data'] = $data;
        $rdata['online'] = $online;
        $rdata['importing'] = $res['importing'];
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$rdata]));
    }
    //添加聊天记录
    public function chat_add()
    {
        file_put_contents('res1.txt',print_r($_POST,true));
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        $data = $_POST;
        if(!$data['uid2']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        if($data['type'] == '0'){
            if(!$data['text']){
                exit(json_encode(['state'=>'-1','msg'=>'参数错误','data'=>$_POST]));
            }
        }elseif($data['type'] == '1' || $data['type'] == '2' || $data['type'] == '3'){
            if($data['type'] == '1' || $data['type'] == '3'){
                if(!$_POST['duration'])  exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
                $add['duration'] = round($_POST['duration']);
                if($add['duration'] < 1) $add['duration'] = 1;
            }
            if($_FILES['text']['name']){
                $upload = $this->upload_file('Chatsmg');
                $data['text'] = '/Uploads'.$upload['text']['savepath'].$upload['text']['savename'];
            }else exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
           /* vendor("getID3.getid3.getid3");
            $getID3 = new \getID3();
            $ThisFileInfo = $getID3->analyze(ltrim($data['text'],'/')); //分析文件，$path为音频文件的地址
            //  $ThisFileInfo['playtime_seconds']  // 音频长度  秒
            $add['duration'] = round($ThisFileInfo['playtime_seconds']);
            if($add['duration'] < 1) $add['duration'] = 1;*/
        }else exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        $add['uid2']    = $data['uid2'];
        $add['text']    = $data['text'];
        $add['type']    = $data['type'];
        $add['ct_time'] = time();
        $add['uid']     = $re['id'];
        $res = M('chat')->add($add);
        if($res){
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }else{
            exit(json_encode(['state'=>'2','msg'=>'失败']));
        }
    }
    //工作人员列表
    public function worker_list()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['area'] || !$_POST['type']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $serve_member = M('serve_member')->where("type = {$_POST['type']}")->select();
        foreach ($serve_member as $key => $value) {
            $member = M('personal')->field('id,xm,tx')->find($value['uid']);
            if($member){
                $member['tx'] = $this->savepath($member['tx']);
                $chat = M('chat')->query("select text,status,uid,type from `pa_chat` where ( ( `uid2` = {$member['id']} and `uid` = {$re['id']} ) or ( `uid2` = {$re['id']} and `uid` = {$member['id']} ) ) ORDER BY ct_time desc limit 1");
                if($chat){
                    if($re['id']==$chat['uid'] && $chat['status']==0){
                        $chat['status']=1;
                    }
                    $member['news'] = $chat;
                }
                $arr[] = $member;
            }
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$arr]));
    }
    //修改查看状态
    public function chat_change()
    {
        $re = M('personal')->where("access_token = '{$_POST['access_token']}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'-2','msg'=>'未登录']));
        }
        if(!$_POST['id']){
            exit(json_encode(['state'=>'-1','msg'=>'参数错误']));
        }
        $res = M('chat')->query("update `pa_chat` set status = 1 where ( ( `uid2` = {$_POST['id']} and `uid` = {$re['id']} ) or ( `uid2` = {$re['id']} and `uid` = {$_POST['id']} ) )");
        if($res){
            exit(json_encode(['state'=>'1','msg'=>'成功']));
        }else{
            exit(json_encode(['state'=>'-1','msg'=>'失败']));
        }
    }
  
  	//社交人员信息
    public function social_correct_info(){
        $account = $_REQUEST['account'];
        if(empty($account)){
            exit(json_encode(['state'=>'0','msg'=>'请传递参数']));
        }
        $re = M('personal')->where("zjh = '{$account}'")->find();
        if(!$re){
            exit(json_encode(['state'=>'0','msg'=>'用户不存在']));
        }
        exit(json_encode(['state'=>'1','msg'=>'成功','data'=>$re]));
    }
}