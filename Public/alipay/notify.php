<?php

include'aop/AopClient.php';
include'aop/request/AlipayTradeAppPayRequest.php';

$aop = new AopClient;
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0TusMUb78ST7mlOBZqIAjN2cScmBMtD6fmmnS4qVp0hX3wIB3dZt0lxe1PMn7T0TNhQtGiswow4eW9es95q353LkoXBBjnqddux97A3jzN3GwOYf7VhwNsX59bzEUg6ocuCZHhXm1iznHiww/HzK1xHCePURN9hV2mspP04ObWiMFps+knrOfA8A4/YBMBuW6GARlUjpx6XWqe8X9wnKe2O8gm2kj1h0H5996YVrv+TrQqm5U4ZKdtYYJsUV1KRMbXLsKu6LyNlx+8uhoVovvjvPLeTSzvx+o6XNLmp3TOL1nQjcDmJ/t1BGf3j7NFzHw3W0fbzYJxl8CBFyCpXhPwIDAQAB';
$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
echo 'success';



file_put_contents('1.txt',print_r($flag,true));
?>