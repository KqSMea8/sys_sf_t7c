<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once('../../config.inc.php');
require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';
$payno=trim($_POST["payno"]);

$jine=trim($_POST["jine"]);
//echo $payno."<br>";





if(isset($payno) && $payno != ""){
	$out_trade_no = $payno;
	$input = new WxPayOrderQuery();
	$input->SetOut_trade_no($out_trade_no);
	$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["trade_state"] == "SUCCESS"
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			$url="../../../order_xq.php?payno=".$payno;
		 echo "<script>location.href='".$url."';</script>"; 
        exit();				

		}

}



//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
$url1 = $notify->GetPrePayUrl("123456789");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
 
$input = new WxPayUnifiedOrder();
$input->SetBody("红酸汤");
$input->SetAttach($payno);
$input->SetOut_trade_no($payno);
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($jine);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($payno);
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");
$result = $notify->GetPayUrl($input);
//var_dump($result);
$url2 = $result["code_url"];
$tm1=MyDate("Y-m-d H:i:s",time()+60*60*2);
?>
<script src="js/jquery-1.7.2.min.js"></script>
 <script type="text/javascript">

function historyLotteryCode_1(){
$.ajax({
type: 'POST',    
url : 'ajaxReturn.php',  
data: "userid=<?=$payno;?>",  
data: {userid:"<?=$payno;?>",tm1:"$tm1"},
success: function(data){ 
$("#zfzt span" ).html(data);
//alert(data);
//if(data=="" ||undefined || null){

setTimeout(historyLotteryCode_1,5000);
}
});
}
setTimeout(historyLotteryCode_1,5000);



</script>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>订单<?php echo $payno;?>微信支付</title>
</head>
<body>
	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">订单号：<?php echo $payno;?></div><br/>
	<img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
	<br/><br/><br/>
	<div id="zfzt" style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;"><span></span></div><br/>
	
</body>
</html>