<?php
	//判断登录
	if(empty($_SESSION['tyuser'])){
		header("Location:./login.php");die;
	}else{
		$userModel=new Model_Subtable('sub_user');
		$userRow=$userModel->find($_SESSION['tyuser']['id']);
		$smarty->assign('userRow',$userRow);
	}
	
	$memberModel=new Model_Subtable('member');
	$picUserRow=$memberModel->where("fromuser='".$_SESSION['tyuser']['fromuser']."'")->dataRow();
	if($picUserRow){
		$_SESSION['picuser']=$picUserRow;
	}
	
	$_SESSION['picuser']['fromuser']=$userRow['fromuser'];
	if($userRow['nicheng']) $_SESSION['picuser']['nickname']=$userRow['nicheng'];
	if($userRow['head_pic']) $_SESSION['picuser']['headimgurl']='../data/image_c/'.$userRow['head_pic'];