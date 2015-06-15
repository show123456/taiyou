<?php
include_once("../includes/config.inc.php");
if(!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) die("请在微信浏览器中打开");//判断是否微信打开

//微信用户标识
include_once("get_openid.php");

$model=new Model_Subtable('sub_user');
$scoreModel=new Model_SubScore('sub_score');
$qdScore=$scoreModel->qd();
$uRow=$model->where("fromuser='".$fromuser."'")->dataRow();
$time=time();
$startTime=strtotime(date('Y-m-d',$time).' 00:00:00');//今天0点的时间戳
if($uRow['qd_time']>$startTime){
	die('<script type="text/javascript">alert("请勿重复签到，请关闭网页")</script>');
}else if($uRow){
	//签到获取积分
	$data1['info'][id]=$uRow['id'];
	$data1['info'][score]=$qdScore+$uRow['score'];
	$data1['info'][score_all]=$qdScore+$uRow['score_all'];
	$data1['info'][qd_time]=time();
	$model->add($data1);
	die('<script type="text/javascript">alert("签到成功，请关闭网页")</script>');
}else{
	die('<script type="text/javascript">alert("您还未注册，请关闭网页")</script>');
}