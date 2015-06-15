<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

$customer_id = $_SESSION['customer_id'];

//submit
if(method_is('post')){
	
	//save
	$infoHelper = new Helper_Info();
	$result = $infoHelper->save('common');
	

	//return
	ajax_feedback($result['success'], $result['content']);

}


