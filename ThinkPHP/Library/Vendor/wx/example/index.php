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
						<span class="bg tel">:<a href="tel:<?=Getconfg("cfg_dianhua");?>"    style="color: #F00;"><?=Getconfg("cfg_dianhua");?></a></span>
			</li>
		</ul>
		<ul class="fr lh">
<li class="fore1"><span id="ECS_MEMBERZONE"><div id="append_parent"></div>
<a href="../../../index.php">[ 返回首页 ]</a>&nbsp;&nbsp;<a href="../../../center.php">[ 用户中心]</a>
</span></li>
			<li class="fore2 ld">
				<s></s>
				<a href="../../../order.php" rel="nofollow">我的订单</a>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>
<div class="wrapper clearfix">

   <div class="ulogo"><a href="index.php"><img src="../../../images/logo.jpg" width="260" height="60"></a></div>

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

		

		

		

		

 

              

              

             

              

              </div></td>

            </tr>

                      </table>

                    <p style="text-align:center; margin-bottom:20px;">您可以 <a href="../../../index.php">返回首页</a> 或去 <a href="../../../center.php">用户中心</a></p>

        </div>

                </div>

<div class="blank5" style="margin:20px auto; min-height:150px;"></div>



<? require_once('../../../footer.php');?>





</body>





</html>