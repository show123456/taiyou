<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$customer_id = (int) $_SESSION['customer_id'];
$page        = (int) $_REQUEST["p"] > 0 ? (int) $_REQUEST["p"] : 1;





//smarty
$smarty->setTpl('member/templates/push.html')->display();
