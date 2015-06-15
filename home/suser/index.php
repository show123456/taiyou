<?php
//后台管理员管理
include_once("../../includes/config.inc.php");
//登录页面显示
if($_REQUEST['a']=='login'){
	$smarty->setLayout('layout_nologin.html')->setTpl('suser/templates/login.html')->display();die();
}
$model=new Model_Subtable('sub_suser');
//登录验证
if($_REQUEST['a']=='doLogin'){
	$name=str_inmysql($_POST['name']);
	$pass=md5($_POST['pass']);
	$res=$model->where(" `name`='".$name."' and `pass`='".$pass."'")->dataRow();
	if($res){
		$_SESSION['suser']=$res;
		$_SESSION['customer_id']=1378;
		echo 'success';die;
	}
	die;
}
//注销
if($_REQUEST['a']=='logout'){
	$_SESSION['suser']=null;
	$_SESSION['customer_id']=null;
	echo '<script type="text/javascript">window.location.href="/home/suser/index.php?a=login"</script>';die();
}
//判断是否登录
if(empty($_SESSION['suser']['name'])){
	echo '<script type="text/javascript">window.location.href="/home/suser/index.php?a=login"</script>';die();
}
//修改密码页显示
if($_REQUEST['a']=='editpShow'){
	$smarty->assign('vo',$model->find($_GET['id']));
	$smarty->setTpl('suser/templates/editp.html')->display();
	die();
}