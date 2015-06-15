<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
$customer_id=(int)$_SESSION['customer_id'];
$customerconfigModel= new Model_CustomerConfig();
$filter['where'] = " customer_id='$customer_id' and c_type='appid' ";
$sql=$customerconfigModel->select($filter,'c_value');
$value_str = $customerconfigModel->fetchRow($sql);
$value_array=explode(',',$value_str['c_value']);

$smarty->assign('value_array',$value_array);
$smarty->setTpl('info/templates/auth.html')->display();
?>