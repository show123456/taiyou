<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

$customer_id = (int)$_SESSION['customer_id'];
$replyModel  = new Model_AutoReply();
if($_POST['save']=='1'){
	
	//params
	$data = array();
	$data['customer_id']   = $customer_id;
	$data['type_id']       = 1;
	$data['is_keyword']    = (int)$_POST['is_keyword'];
	$data['reply_content'] = $data['is_keyword'] ? '' : str_inmysql($_POST['reply_content']);
	$data['reply_keyword'] = $data['is_keyword'] ? str_inmysql($_POST['reply_keyword']) : '';
	$data['create_date']   = date('Y-m-d H:i:s', $_WGT['TIME']);
    $data['state']         = (int)$_POST['state'];
    
	//save
	$replyModel->upsert($data);
	
	die('success');
}

//find
$replyRow   = $replyModel->findByCustomerId($customer_id,'1');

//smarty
$smarty->assign('replyRow',$replyRow);
$smarty->setTpl('info/templates/follow.html')->display();
