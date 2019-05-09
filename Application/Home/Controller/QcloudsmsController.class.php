<?php
namespace Home\Controller;
use \Think\Controller;
use \Qcloud\Sms\SmsSingleSender;
// 腾讯云短信服务
class QcloudsmsController extends Controller{
  	/*
  	private $smsconfig = [
        'appid'=>'1400203820',                          //  短信应用SDK AppID 双鸭山
        'appkey'=>'688a56f3e2edca2062cbf57f4203dcb8',   //  短信应用SDK AppKey
       	'smsSign'=>'双鸭山市司法局'
    ];
  	*/
    private $smsconfig = [
        'appid'=>'1400150280',                          //  短信应用SDK AppID 牡丹江
        'appkey'=>'86036c462d947de69ae37c784667651c',   //  短信应用SDK AppKey
       	'smsSign'=>'牡丹江市司法局'
    ];
	
	/*
     *  指定模板ID单发短信
     *  $id     模板  ID               字符串
     *  $phone  模板  手机号            字符串
     *  $data   模板  模板 数据         数组    长度必须与模板一致
     */
	public function sms($id,$phone,$data)
    {
        if($id == '' || $phone == '') return false;
        $config = $this->smsconfig;
        vendor('qcloud.index');
        try {
             Vendor('Qcloud.SmsSingleSender');
           // $ssender = new \Qcloud\Sms\SmsSingleSender($config['appid'], $config['appkey']);
             $ssender = new SmsSingleSender($config['appid'], $config['appkey']);
            $result  = $ssender->sendWithParam("86",$phone,$id,$data,$config['smsSign'],"","");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result);
            //  成功  {"result":0,"errmsg":"OK","ext":"","sid":"18:3a2dd2c010234e92833c3c53c78604cc","fee":1}
            //  失败  {"result":1014,"errmsg":"package format error, template params error","ext":""}
            return $result;
        } catch(\Exception $e) {
            echo var_dump($e);
            return false;
        }
	}
}