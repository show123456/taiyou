<?php
	include_once("../../includes/config.inc.php");
	check_login();//验证是否登录
	$smarty->setTpl('app/templates/index.html')->display();