<?php
include_once("includes/config.inc.php");
if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']){
	js_alert('','/home/user/index.php?type=1');
}else{
	js_alert('','/home/suser/index.php?a=login');
}