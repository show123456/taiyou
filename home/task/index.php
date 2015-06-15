<?php
include_once("../../includes/config.inc.php");
$userModel=new Model_Subtable('sub_user');
//路由
$_GET['m'] ? $controllerName=$_GET['m'] : $controllerName='index';
if(!$_GET['a']) $_GET['a']='index';
require_once "./controls/".$controllerName.".php";