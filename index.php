<?php
include_once("includes/config.inc.php");
if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']){
	js_alert('','/home/info/bind.php');
}else{
	js_alert('','/home/suser/index.php');
}