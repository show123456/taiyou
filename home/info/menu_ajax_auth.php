<?php
include_once("../../includes/config.inc.php");
function post($url, $postType, $jsonData) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $postType);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
$Customer_Config = new Model_CustomerConfig();

$data=array();
$data['customer_id'] = (int)$_SESSION['customer_id'];
$data['c_type'] = str_inmysql('appid');
$data['c_value'] = str_inmysql($_POST['app_id']).','.str_inmysql($_POST['app_Secret']);
$data['create_date'] = str_inmysql(date("Y-m-d H:i:s"));

$appid  = $_POST['app_id'];
$secret = $_POST['app_Secret'];

	$url       = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
	
	//获取 token
	$get_token = post($url, $postType  = 'GET', '');
	if (strstr($get_token, 'errmsg')) {
		die('无效的AppId');
	//如果有获取token成功
	} else if (strstr($get_token, 'access_token')) {
		
		$Customer_Config->upsert($data);
		die('success');
		
	} else {
		die( '微信无应答，请稍候再试！');
	}



?>