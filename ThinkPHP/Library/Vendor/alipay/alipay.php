<?php
include'aop/AopClient.php';
include'aop/request/AlipayTradeAppPayRequest.php';
date_default_timezone_set("PRC");
$aop = new AopClient;
$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
$aop->appId = "2017081408190637";
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEA50CktemwbEtpwyojBeW6rFdtTq36vrhAqyq3bJ60bFqGkSp+y4+NG2UjI/0BOXoO/Hn20bpx5zVHnQYeetdx35t7I8fMLubbVjlPK6JdfOfse/u+jmjRqCGHmvvUTvmidYvagNS3+ZDcFiRhX8ge6Z28uNNDp5Ta0Ef9uLIbspqZe1TvBzexzvSVU22c7tlFw6TFOwlsedVu5I4agEew9ioZTbG+f3Gj2Xf5oJMcvh9+H008Wlnbf7U8kVDBsTFmzn2B+lyxHlg5UuVrsxs/+vcBsXqaJMqkiiU8V+sw4a89NiSpKdvLHJvXnQJYhZ9l5HaSc6oKzd8BcPmyUWXkoQIDAQABAoIBAQCesqcWTAQNnYc8LvuP/YKo6OqTlyO/pJSEorDz8sngnhNBJgeTzLTKexKtjOA9QH1uWIjAjxHB2LeHk/5w0M9N49aSzHdcLIOTXYruZ+N+Z27L+wxfkKFon3H/MxkVBY2u20YdKV4D/5x5+Yd1gtFsAxrTP/Bq3eV4EB+Xk2scmlwLpA0xQVk9xkJ1FiiVEDG+z3u2DCBomzBvzZwoBWbdhO3KJ44aCD84UkZ9oV1HF/OCizVYtGiNRPCmYqSGam+yn+8coqKbnpb3dkZZdaJebyhMK2JyooW8nOWyjZp4INx5gVvxKzDrD/8P6mxLWd16LKuQXLvFRXF1WxkVxErRAoGBAPQXM4V2l/UdDegX5PduJzhHY85GTA3MhABRcqaaHoWAsmqSvZRBWcjvMGyNLMQrlyyrzZo7f2DA8Xpug4knXALL3RZd8dSzIERNXLSTyfiZ2di8KzfbrSE+M9v65FEy5q9WrUP7RvCFi3iqthiKB7vNA2YbRtGiXQaUjifbkmZ1AoGBAPKJFqTaAi2kP6o2u1vefBZhy7pseRLyIDrG6oBWmqcf84i8QVp8KzCJlfdBfq4WUEVTBACnk/Eud+gSVTdB4uFTe/yuWsG+kmUQS9zYVnQxRmCueuJ/uw4rqN6KzmoHkmQRgwhuw6dGWeT3YMqV5ENpl07PeZkXpZ6Qx+ubdbf9AoGAYHqLMsWdapUBWiuXpPU1K1mMaYv/o7SiuD15Q8DHL52e5+D4bhEmm/Vu/nkOqLtSFaYSrlO2FVguGmZ0N1IHXFOYH68iVyA2MMkjS3ze6BRntvMpYfrCg9FCTkDs3etwov1vnt9DOu6OIjPXpfto8yIlpcZCR7oVavRs+/Qd+cECgYAb2RvNf+7CGNrqi3BF4yzyUPZYiH36a+zBcveYFtmZGPUYnS0P27QF1bZvdoi6Knu7CgYqoRM+nSxNbFV2i80mPDS/VbG8D0q16gIb8Ic0vjergD1B5pKu1NqF+wAqcoYfkVcvT7CNc2l8/d6SRFW6W9RwyyfWKT3uEAe0iNwibQKBgQC/m1HR3EgytnOWdkyjQNuvqchoPYkOADu75nbM3QUaZO7/6XjT+/Ep4X6QeXumuUB+M3ReFX85MmUA8RrKD6FV3RuQiKMWYjjuyA9A0w9rw3Zg/NcCSePGUBjeXG4yEFg0oE92yJ59fuHyDMZM+jAJfRZna7eZPmHbYgvDz8PC/g==';
$aop->format = "json";
$aop->charset = "UTF-8";
$aop->signType = "RSA2";
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0TusMUb78ST7mlOBZqIAjN2cScmBMtD6fmmnS4qVp0hX3wIB3dZt0lxe1PMn7T0TNhQtGiswow4eW9es95q353LkoXBBjnqddux97A3jzN3GwOYf7VhwNsX59bzEUg6ocuCZHhXm1iznHiww/HzK1xHCePURN9hV2mspP04ObWiMFps+knrOfA8A4/YBMBuW6GARlUjpx6XWqe8X9wnKe2O8gm2kj1h0H5996YVrv+TrQqm5U4ZKdtYYJsUV1KRMbXLsKu6LyNlx+8uhoVovvjvPLeTSzvx+o6XNLmp3TOL1nQjcDmJ/t1BGf3j7NFzHw3W0fbzYJxl8CBFyCpXhPwIDAQAB';
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
$request = new AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数
$bizcontent = "{\"body\":\"我是测试数据\","
                . "\"subject\": \"App支付测试11\","
                . "\"out_trade_no\": \"2017012qq5taa\","
                . "\"timeout_express\": \"30m\","
                . "\"total_amount\": \"0.01\","
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";
		
$request->setNotifyUrl("http://47.94.106.188/Public/1/a1.php");
$request->setBizContent($bizcontent);

//这里和普通的接口调用不同，使用的是sdkExecute
$response = $aop->sdkExecute($request);

//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
//echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
//$response = str_replace('alipay_sdk=alipay-sdk-php-20161101&','',$response);

// $value = array(
		// "state" => '0',
		// "msg" => '成功',
		// "data" => $response ,
	// );

exit($response);