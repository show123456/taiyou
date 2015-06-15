<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
$message = new Model_Message();
$c_id = (int)$_SESSION['customer_id'];
$now_time = time();
$n_time = $now_time-(48*3600);

if($_POST['act']=='new'){

	//获取新信息条数
	$filter['where'] = " customer_id='$c_id' and is_read='0' and create_time > $n_time and fromuser!=''";
	$nums = $message->count($filter);
	echo $nums;
}elseif($_POST['act']=='reply'){

	//回复信息处理
	$id = isset($_POST['id']) ? (int)$_POST['id'] : '';
	$data = array();
	$data['is_reply'] = 1;
	$data['reply_list'] = isset($_POST['reply_list']) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_inmysql($_POST['reply_list']).'&nbsp;&nbsp; Date:'.date('Y-m-d H:i:s').'<br/>' : '';
	//在原数据上追加
	$message->query("set names utf8");
	$message->query("update message set reply_list=concat(reply_list,'$data[reply_list]') where customer_id = '$c_id' and id='$id'");
	unset($data['reply_list']);
	//新加入一条信息
	$data['msg_content'] = isset($_POST['reply_list']) ? str_inmysql($_POST['reply_list']): '';
	$res = $message->fetchRow("select * from message where customer_id = '$c_id' and id='$id'");
	$data['touser'] = str_inmysql($res['fromuser']);
    if(!$_POST['id']){
		$data['create_date'] =  date("Y-m-d H:i:s");
	}
	$data['create_time'] = time();
	$data['is_reply'] =	'1';
	$data['is_read'] = '1';
	$data['customer_id'] = (int)$customer_id;
	$message->insert($data);
	$res = $message->fetchRow("select reply_list from message where id='$id' and customer_id = '$c_id'");
	echo $res['reply_list'];
	
	}elseif($_POST['act']=='isread'){
	//将当前信息设置为已读，
	$message->query("update message set is_read='1' where customer_id ='$c_id'");
}