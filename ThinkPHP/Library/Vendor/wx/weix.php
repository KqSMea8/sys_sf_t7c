<?php
//入口函数
weChatPay();
function weChatPay(){
   $json = array();
   //生成预支付交易单的必选参数:
   $newPara = array();
   //应用ID
   $newPara["appid"] = "wx2421b1c4370ec43b";
   //商户号
   $newPara["mch_id"] = "10000100";
   //设备号
   $newPara["device_info"] = "WEB";
   //随机字符串,这里推荐使用函数生成
   $newPara["nonce_str"] = "1add1a30ac87aa2db72f57a2375d8fec";
   //商品描述
   $newPara["body"] = "APP支付测试";
   //商户订单号,这里是商户自己的内部的订单号
   $newPara["out_trade_no"] = "1415659990";
   //总金额
   $newPara["total_fee"] = 1;
   //终端IP
   $newPara["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
   //通知地址，注意，这里的url里面不要加参数
   $newPara["notify_url"] = "http://wxpay.wxutil.com/pub_v2/pay/notify.v2.php";
   //交易类型
   $newPara["trade_type"] = "APP";
   //第一次签名
   $newPara["sign"] = produceWeChatSign($newPara);
   //把数组转化成xml格式
   $xmlData = getWeChatXML($newPara);
   //利用PHP的CURL包，将数据传给微信统一下单接口，返回正常的prepay_id
   $get_data = sendPrePayCurl($xmlData);
   //返回的结果进行判断。
   if($get_data['return_code'] == "SUCCESS" && $get_data['result_code'] == "SUCCESS"){
    //根据微信支付返回的结果进行二次签名
    //二次签名所需的随机字符串
    $newPara["nonce_str"] = "5K8264ILTKCH16CQ2502SI8ZNMTM67VS";
    //二次签名所需的时间戳
    $newPara['timeStamp'] = time()."";
    //二次签名剩余参数的补充
    $secondSignArray = array(
     "appid"=>$newPara['appid'],
     "noncestr"=>$newPara['nonce_str'],
     "package"=>"Sign=WXPay",
     "prepayid"=>$get_data['prepay_id'],
     "partnerid"=>$newPara['mch_id'],
     "timestamp"=>$newPara['timeStamp'],
    );
    $json['datas'] = $secondSignArray;
    $json['ordersn'] = $newPara["out_trade_no"];
    $json['datas']['sign'] = weChatSecondSign($newPara,$get_data['prepay_id']);
    $json['message'] = "预支付完成";
    //预支付完成,在下方进行自己内部的业务逻辑
    /*****************************/
    return json_encode($json);
   }
   else{
    $json['message'] = $get_data['return_msg'];
   }
  
  return json_encode($json);
 }
 
 $aa = weChatPay();
 var_dump($aa);
//第一次签名的函数produceWeChatSign
function produceWeChatSign($newPara){
  $stringA = getSignContent($newPara);
  $stringSignTemp=$stringA."&key=192006250b4c09247ec02edce69f6a2d";
  return strtoupper(MD5($stringSignTemp));
 }
 
//生成xml格式的函数
function getWeChatXML($newPara){
  $xmlData = "<xml>";
  foreach ($newPara as $key => $value) {
   $xmlData = $xmlData."<".$key.">".$value."</".$key.">";
  }
  $xmlData = $xmlData."</xml>";
  return $xmlData;
 }
 
//通过curl发送数据给微信接口的函数
function sendPrePayCurl($xmlData) {
  $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
  $header[] = "Content-type: text/xml";
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlData);
  $data = curl_exec($curl);
  if (curl_errno($curl)) {
   print curl_error($curl);
  }
  curl_close($curl);
  return self::XMLDataParse($data);
 
 }
 
//xml格式数据解析函数
function XMLDataParse($data){
  $msg = array();
  $msg = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
  return $msg;
 }
 
//二次签名的函数
function weChatSecondSign($newPara,$prepay_id){
  $secondSignArray = array(
   "appid"=>$newPara['appid'],
   "noncestr"=>$newPara['nonce_str'],
   "package"=>"Sign=WXPay",
   "prepayid"=>$prepay_id,
   "partnerid"=>$newPara['mch_id'],
   "timestamp"=>$newPara['timeStamp'],
  );
  $stringA = self::getSignContent($secondSignArray);
  $stringSignTemp=$stringA."&key=192006250b4c09247ec02edce69f6a2d";
  return strtoupper(MD5($stringSignTemp));
 }

?>