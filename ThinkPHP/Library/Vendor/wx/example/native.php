<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

$payno=$_POST['payno'];

$jine=$_POST['jine'];

if($jine.'1' != $_POST['jifen']){
	 echo "<script>location.href='/Index/Member/member_order.html';</script>"; 
}
if($_POST["jine"] == '0'){
	exit('<script>alert("遇到未知错误，请重新付款!");location.href="/Index/Member/member_order.html";</script>');
}
//echo $payno."<br>";


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
$input->SetBody("老王");
$input->SetAttach($payno);
$input->SetOut_trade_no($payno);
// $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($jine);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($payno);
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");
$result = $notify->GetPayUrl($input);

$url2 = $result["code_url"];
$tm1=time();
?>
<script src="./js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">

function historyLotteryCode_1(){
$.ajax({
type: 'POST',    
url : 'ajaxReturn.php',  
data: "userid=<?php echo $payno;?>",  
data: {userid:"<?php echo $payno;?>",tm1:"$tm1",jine:"<?php echo $jine;?>"},
success: function(data){ 
$("#zfzt span" ).html(data);

setTimeout(historyLotteryCode_1,5000);
}
});
}
setTimeout(historyLotteryCode_1,5000);



</script>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>订单<?php echo $payno;?>微信支付</title>


   

<link href="style.css" rel="stylesheet" type="text/css" />
<link href="base.css" rel="stylesheet" type="text/css">
<link href="flow.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="shortcut-2013">
	<div class="w">
		<ul class="fl lh">
			<li class="fore1 ld"><b></b><a href="javascript:" rel="nofollow">收藏本站</a>
			</li>
            <li class="fore1 ld"><i>|</i>
						
                        
			</li>
		</ul>
		<ul class="fr lh">
<li class="fore1"><span id="ECS_MEMBERZONE"><div id="append_parent"></div>
<a href="/">[ 返回首页 ]</a>&nbsp;&nbsp;<a href="/Index/Member/member_car.html">[ 用户中心]</a>
</span></li>
			<li class="fore2 ld">
				<s></s>
				<a href="/Index/Member/member_order.html" rel="nofollow">我的订单</a>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>
<div class="wrapper clearfix">

   <div class="ulogo"></div>

   	<div class="progress">

		<ul class="progress-3">

			<li class=""><b></b>1.我的购物车</li>

			<li  class=""><b></b>2.填写核对订单信息</li>

			<li class="step-3"><b></b>3.成功提交订单</li>

		</ul>

	</div>

</div>

<div class="wrapper clearfix">
  <div class="blank"></div>

        <div class="flowBox" style="margin:0px auto 10px auto;padding-top:10px;">


         <h6 style="text-align:center; height:30px; line-height:30px;">感谢您在本店购物！您的订单已提交成功，请记住您的订单号: <font style="color:red"><?php echo $payno;?></font></h6>

          <table width="99%" align="center" border="0" cellpadding="15" cellspacing="0" bgcolor="#fff" style="margin:20px auto; min-height:260px;" >

            <tr>

              <td align="center" bgcolor="#FFFFFF">

              您的应付款金额为: <strong>￥<?php echo $jine/100;?></strong>

              </td>

            </tr>

            

                        

            <tr>

            <tr>

              <td align="center" bgcolor="#FFFFFF">

              

              

              </td>

            </tr>
  

            <tr>

              <td align="center" bgcolor="#FFFFFF">

              <div style="text-align:center">

<img alt="订单<?php echo $payno;?>扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>		

		

		

		

		<br/><br/>
	<div id="zfzt" style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;"><span></span></div>

 

              

              

             

              

              </div></td>

            </tr>

                      </table>

                    <p style="text-align:center; margin-bottom:20px;">您可以 <a href="/">返回首页</a> 或去 <a href="/Index/Member/member_order.html">用户中心</a></p>

        </div>

                </div>

<div class="blank5" style="margin:20px auto; min-height:150px;"></div>




</body>





</html>