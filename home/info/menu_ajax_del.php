<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

$BottomMenu = new Model_BottomMenu();
$customer_id = (int)$_SESSION['customer_id'];
$mid = (int)$_POST['mid'];
$where = "customer_id=$customer_id and id=$mid or parent_id=$mid";
echo $BottomMenu->delete($where);


?>