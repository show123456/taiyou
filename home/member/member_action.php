<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
//$member_id = (int) $_SESSION['member_id'];
$customer_id = (int)$_SESSION['customer_id'];
$page        = (int) $_REQUEST["p"] > 0 ? (int) $_REQUEST["p"] : 1;
$fromuser = str_inmysql($_GET['fromuser']);

if($_POST['month'] && $_POST['year']){
	//
	$m = $_POST['month'];
	$y = $_POST['year'];
	$today = $_POST['year'].'-'.$_POST['month'].'-'.date('j',mktime(0,0,1,($m==12?1:$m+1),1,($m==12?$y+1:$y))-24*3600);
	
	//echo $d=die();
	$and =" and left(create_date,10)<='$today'";
	$start_date = $today = $_POST['year'].'-'.$_POST['month'].'-01';
	$smarty->assign('mon',$m);	
	$smarty->assign('yea',$y);	
}else{
	$today = date("Y-m-d");
	$start_date = date('Y-m-d', strtotime('-29 day'));	
	/* $smarty->assign('mon',date('m'));	
	$smarty->assign('yea',date('Y'));	 */
}
	$and = isset($and)? $and : '';

$member = new Model_Member();
$data = $member->fetchRow("select * from member where customer_id=$customer_id and fromuser='$fromuser'");
$smarty->assign("list",$data);

$message = new Model_Message();
$result = $message->fetchAll("select msg_content,create_date from message where customer_id='$customer_id' and fromuser='$fromuser'");
if($result){
	//互动关键字和日期
	$smarty->assign('Interactive_recording',$result);
}

//最近30天互动量
$filter['where']="customer_id='$customer_id' and fromuser='$fromuser' and left(create_date,10)>='$start_date'$and";
$filter['group']=" left(create_date,10) ";
$sql=$message->select($filter,'count(id) as count_num,left(create_date,10) as count_date');

$messagearray = $message->fetchAll($sql);
foreach($messagearray as $key=>$row){
	$countinfoc[$row['count_date']]=$row['count_num'];
	if ($row['count_date'] == $today) {
		$count['today_message'] = $row['count_num'];
    }
}

if($and!=''){
	$last_day = mktime(0,0,1,($m==12?1:$m+1),1,($m==12?$y+1:$y))-24*3600;
	$mon_day = date('j',mktime(0,0,1,($m==12?1:$m+1),1,($m==12?$y+1:$y))-24*3600);
	for ($i = 1; $i <= $mon_day; $i++) {
		$count_date = date('Y-m-d', $last_day-($mon_day-$i)*24*3600);
		$date_num = substr($count_date, 8, 2) * 1;
		$count_valuec = $countinfoc[$count_date] > 0 ? $countinfoc[$count_date] : '0';
		$days_str.='"' . $date_num . '",';
		$days_valuec_str.=$count_valuec . ',';
	}		
}else{
	for ($i = 29; $i >= 0; $i--) {
		$count_date = date('Y-m-d', strtotime("-$i days"));
		$date_num = substr($count_date, 8, 2) * 1;
		
		$count_valuec = $countinfoc[$count_date] > 0 ? $countinfoc[$count_date] : '0';
		$days_str.='"' . $date_num . '",';
		$days_valuec_str.=$count_valuec . ',';
	}
}
	


$chart_data1['label'] = rtrim($days_str, ',');
$chart_data1c['value'] = rtrim($days_valuec_str, ',');
$smarty->assign("chart_data1", $chart_data1);
$smarty->assign("chart_data1c", $chart_data1c);

$smarty->assign("count", $count);

$smarty->assign("fromuser", $fromuser);
//smarty
$smarty->setTpl('member/templates/member_action.html')->display();
