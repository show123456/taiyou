<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

$customer_id = $_SESSION['customer_id'];
$token=substr(md5('wgt'.$customer_id),0,15);

$smarty->assign('customer_id',$customer_id);
$smarty->assign('token',$token);
$smarty->setTpl('info/templates/bind.html')->display();
