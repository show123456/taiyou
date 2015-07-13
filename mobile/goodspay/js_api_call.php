<?php
/**
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	//******************获取订单信息
	include_once("../../includes/config.inc.php");
	$orderTable=D('sub_temp_sign');
	$oid=$_GET['oid'];//订单号
	if(!$oid) die("<script>alert('传递参数丢失');</script>");
	$orderRow=$orderTable->find($oid);
	
	$orderfee=$orderRow['fee'];//单位为元

	include_once "../weixinPay/WxPayPubHelper.php";
	
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	
	//当前登录用户信息
	$userRow=D('sub_user')->find($_SESSION['tyuser']['id']);
	$openid=$userRow['fromuser'];
	
	if(!$openid){
		if (!isset($_GET['code']))
		{	
			//******************组装链接所带参数
			$returnPayUrl=urlencode(WxPayConf_pub::JS_API_CALL_URL."goodspay/js_api_call.php?oid=".$oid);
			
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode($returnPayUrl);
			Header("Location: $url");
		}else
		{
			//获取code码，以获取openid
			$code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
		}
	}
	
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	$unifiedOrder->setParameter("openid","$openid");
	$unifiedOrder->setParameter("body","开发平台-云客驿站");//商品描述
	$unifiedOrder->setParameter("out_trade_no","$oid");//商户订单号
	//$orderfee=$orderfee*100;
	$unifiedOrder->setParameter("total_fee",$orderfee);//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL."goodspay/notify_url.php");//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>微信安全支付</title>
</head>
<body>
	<div align="center">
		<button style="width:70%;height:3em;margin-top:2em;background-color:#FE6714;border:0px #FE6714 solid;border-radius:0.2em;color:white;font-size:1em;" type="button" onclick="location.href='../index.php?m=sign_history&a=index'">支付等待中。。。<br>若支付成功，点我返回</button>
	</div>
	<script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+res.err_desc+res.err_msg);
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
</body>
</html>