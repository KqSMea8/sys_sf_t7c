<?php

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
ignore_user_abort();
require_once "../lib/WxPay.Api.php";
require_once 'log.php';
$payno=$_POST["userid"];
$tm1=$_POST["tm1"];
$jine=$_POST["jine"];

//$payno="139761670220161023134215".$appid;
              //201610232142095665

//如果bid等于空更加pid查找
/*if($payno=="")
{
	$payno="201610140447201634";
	        201610232142095665	
}*/
//echo "您的订单".$_POST["userid"]."<br>";
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : $value <br/>";
    }
}
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
			//$time1=GetDateTime(time());
        $wei = rand(10,99);
		$url = "http://www.ymyg8888.com/Index/Defray/paywxpay.html?payno=".$payno.$wei.'&jine='.$jine;
		$result = api_notice_increment($url,'');
        echo "订单号:".$payno."&nbsp;&nbsp;支付成功"."<br>";
		
		echo '<script type=”text/javascript”> 
		//定时执行，5秒后执行show() 
		window.setTimeout(show,5000); 
		function show() 
		{ 
			location.href="/Index/Member/member_order.html";
		} 
		</script>';
            

         

			
			
			//return true;
		}else{
			
			$tm1=time();
			echo "订单未支付!!!";	
		}
	
	
	//printf_info($result);这个支付二维码将在".$tm1."后失效
	//printf_info($result["appid"]);
	//printf_info(WxPayApi::orderQuery($input));
		//$input = new WxPayOrderQuery();
		//$input->SetTransaction_id($transaction_id);
/*		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			//return true;
		}else{
			
		}
*/	
	
	
	
	
	
	}
function api_notice_increment($url, $data){
		 $ch = curl_init();
		 $header = "Accept-Charset: utf-8";
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		 @curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 $tmpInfo = curl_exec($ch);
		 if (curl_errno($ch)) {
		  curl_close( $ch );
		  return $ch;
		 }else{
		  curl_close( $ch );
		  return $tmpInfo;
		 }
		 
	}




?>



