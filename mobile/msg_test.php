<?php
include_once("../includes/config.inc.php");
//send_phone_msg('18789251158','您的验证码是：568972');

$configModel=new Model_CustomerConfig();
$ss=$configModel->sendCustomerMsg('测试一下',array('ow6AGuAOUkUcUrjCyT2isDn9rRJc'));
var_dump($ss);