<?php
/**

 */

/**
 * 支付 逻辑定义
 * Class 
 * @package Home\Payment
 */
// new weixin;
class weixin 
{    
    public $tableName = 'plugin'; // 插件表        
   /// public $alipay_config = array();// 支付宝支付配置参数
	public $key = '';
    
    /**
     * 析构流函数
     */
    public function  __construct() {   
     
         
require_once "../lib/WxPay.Api.php"; 
// require_once "WxPay.JsApiPay.php";
// require_once 'log.php';
         
        WxPayConfig::$appid ='wx33715f77a6554167';// * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
        WxPayConfig::$mchid = '1494721582';// * MCHID：商户号（必须配置，开户邮件中可查看）
        WxPayConfig::$key ='yichong123456789yichong123456789'; // KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
        WxPayConfig::$appsecret = 'ed01b8f9ff07a32e6217dc44eb46e327'; // 公众帐号secert（仅JSAPI支付的时候需要配置)， 
		$this->key =  'yichong123456789yichong123456789';
		
    }    
    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $config_value    支付方式信息
     */
    function get_code($order, $config_value)
    {       
            $notify_url ='http://47.94.106.188/Public/1/alipay.php'; // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            //$notify_url = C('site_url').U('Home/Payment/notifyUrl',array('pay_code'=>'weixin')); // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            //$notify_url = C('site_url')."/index.php?m=Home&c=Payment&a=notifyUrl&pay_code=weixin";
            $input = new WxPayUnifiedOrder();
            $input->SetBody('54545'); // 商品描述
            $input->SetAttach("weixin"); // 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
            $input->SetOut_trade_no('54564564654'); // 商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
            $input->SetTotal_fee(1*100); // 订单总金额，单位为分，详见支付金额
            $input->SetNotify_url($notify_url); // 接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            $input->SetTrade_type("APP"); // 交易类型   取值如下：JSAPI，NATIVE，APP，详细说明见参数规定  
            $order_data = WxPayApi::unifiedOrder($input);  //统一下单 
			$order_data['timestamp'] = time();
			//dump($order_data);die;
			//$str = 'appid='.$order_data['appid'].'&noncestr='.$order_data['nonce_str'].'&package=Sign=WXPay&partnerid='.$order_data['mch_id'].'&prepayid='.$order_data['prepay_id'].'&timestamp='.$order_data['timestamp'];
			$str = array('appid'=>'wx33715f77a6554167','noncestr'=>$order_data['nonce_str'],'package'=>'Sign=WXPay','partnerid'=>'1307032501','prepayid'=>$order_data['prepay_id'],'timestamp'=>$order_data['timestamp']);
			//重新生成签名
			$str['sign'] = $this->MakeSign($str);
			//$order_data['sign'] = strtoupper(md5($str));
			//将$order_data数据返回给APP端调用
			//dump($str);die;
			return $str;

            //dump($order_data);die;
            // NATIVE--原生扫码支付
            // $input->SetProduct_id("123456789"); // 商品ID trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
            // $notify = new NativePay();
            // $result = $notify->GetPayUrl($input); // 获取生成二维码的地址
            // $url2 = $result["code_url"];
            // return '<img alt="模式二扫码支付" src="/index.php?m=Home&c=Index&a=qr_code&data='.urlencode($url2).'" style="width:110px;height:110px;"/>';        
    }  

	public function MakeSign($params){ 
        //签名步骤一：按字典序排序数组参数 
        ksort($params); 
        $string = $this->ToUrlParams($params); 
        //签名步骤二：在string后加入KEY
//dump($string);die;		
        $string = $string . "&key=".$this->key; 
        //签名步骤三：MD5加密 
		//$string = 'appid=wx36d35bae9d00e5d8&noncestr=jvmIzrRaC5F5XIll&package=Sign=WXPay&partnerid=1480013672&prepayid=wx201708021445266a174bb08f0700804994&timestamp=1501656367&key=sdfsdfsdfsdfsd123123123123123213';
        $string = md5($string); 
		//dump($string);die;
        //签名步骤四：所有字符转为大写 
        $result = strtoupper($string); 
        return $result; 
    } 
 
    /** 
     * 将参数拼接为url: key=value&key=value 
     * @param   $params 
     * @return  string 
     */ 
    public function ToUrlParams($params){ 
        $string = ''; 
        if( !empty($params) ){ 
            $array = array(); 
            foreach( $params as $key => $value ){ 
                $array[] = $key.'='.$value; 
            } 
            $string = implode("&",$array); 
        } 
        return $string; 
    } 
    /**
     * 服务器点对点响应操作给支付接口方调用
     * 
     */
    function response()
    {                        
        require_once("example/notify.php");  
        $notify = new PayNotifyCallBack();
        $notify->Handle(false);       
    }
    
    /**
     * 页面跳转响应操作给支付接口方调用
     */
    function respond2()
    {
        // 微信扫码支付这里没有页面返回
    }

    function getJSAPI($order){
    	if(stripos($order['order_sn'],'recharge') !== false){
    		$go_url = U('Mobile/User/points',array('type'=>'recharge'));
    		$back_url = U('Mobile/User/recharge',array('order_id'=>$order['order_id']));
    	}else{
    		$go_url = U('Mobile/User/order_detail',array('id'=>$order['order_id']));
    		$back_url = U('Mobile/Cart/cart4',array('order_id'=>$order['order_id']));
    	}
        //①、获取用户openid
        $tools = new JsApiPay();
        //$openId = $tools->GetOpenid();
        $openId = $_SESSION['openid'];
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("支付订单：".$order['order_sn']);
        $input->SetAttach("weixin");
        $input->SetOut_trade_no($order['order_sn'].time());
        $input->SetTotal_fee($order['order_amount']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("tp_wx_pay");
        $input->SetNotify_url(SITE_URL.'/index.php/Home/Payment/notifyUrl/pay_code/weixin');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order2 = WxPayApi::unifiedOrder($input);
        //echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        //printf_info($order);exit;  
        $jsApiParameters = $tools->GetJsApiParameters($order2);
        $html = <<<EOF
	<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',$jsApiParameters,
			function(res){
				//WeixinJSBridge.log(res.err_msg);
				 if(res.err_msg == "get_brand_wcpay_request:ok") {
				    location.href='$go_url';
				 }else{
				 	alert(res.err_code+res.err_desc+res.err_msg);
				    location.href='$back_url';
				 }
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	callpay();
	</script>
EOF;
        
    return $html;

    }

}