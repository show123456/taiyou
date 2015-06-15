<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$customer_id = (int) $_SESSION['customer_id'];
$messageModel = new Model_Message();
$today = date("Y-m-d");
$yestoday = date('Y-m-d', strtotime('-1 day'));
$start_date = date('Y-m-d', strtotime('-29 day'));

//最近2天消息次数
$filter['where']="customer_id='$customer_id' and left(create_date,10)>='$yestoday'";
$filter['group']=" left(create_date,10) ";
$sql=$messageModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$messagearray = $messageModel->fetchAll($sql);
foreach($messagearray as $key=>$row){
	if ($row['count_date'] == $today) {
		$count['today_sendnum'] = $row['count_num'];
    }elseif($row['count_date'] == $yestoday){
		$count['yestoday_sendnum'] = $row['count_num'];
	}
}
//最近2天消息人数
$filter['where']="customer_id='$customer_id' and left(create_date,10)>='$yestoday'";
$filter['group']=" fromuser,left(create_date,10) ";
$sql=$messageModel->select($filter,'left(create_date,10) as count_date');

$memberarray = $messageModel->fetchAll($sql);
foreach($memberarray as $key=>$row){
	if ($row['count_date'] == $today) {
		$count['today_sendmember']++;
    }elseif($row['count_date'] == $yestoday){
		$count['yestoday_sendmember']++;
	}
}


//最近30天消息次数
$messagearray='';
$monthmember='';
$filter['where']="customer_id='$customer_id' and left(create_date,10)>='$yestoday'";
$filter['group']=" left(create_date,10) ";
$sql=$messageModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$messagearray = $messageModel->fetchAll($sql);
foreach($messagearray as $key=>$row){
	$monthnum[$row['count_date']]=$row['count_num'];
}
//最近30天消息人数
$filter['where']="customer_id='$customer_id' and left(create_date,10)>='$yestoday'";
$filter['group']=" fromuser,left(create_date,10) ";
$sql=$messageModel->select($filter,'fromuser,left(create_date,10) as count_date');
$memberarray = $messageModel->fetchAll($sql);
foreach($memberarray as $key=>$row){
	$monthmember[$row['count_date']]++;
}

for ($i = 29; $i >= 0; $i--) {
	$count_date		= date('Y-m-d', strtotime("-$i days"));
	$date_num		= substr($count_date, 8, 2) * 1;
	$count_member	= $monthmember[$count_date] > 0 ? $monthmember[$count_date] : '0';
	$count_num		= $monthnum[$count_date] > 0 ? $monthnum[$count_date] : '0';
	
	$count_pernum	= @round($count_num/$count_member,1);
	$days_str.='"' . $date_num . '",';
	$days_num_str.=$count_num . ',';
	$days_member_str.=$count_member . ',';
	$days_pernum_str.=$count_pernum . ',';
	$detail[$i]['count_date']	=$count_date;
	$detail[$i]['member']		=$count_member;
	$detail[$i]['num']			=$count_num;
	$detail[$i]['pernum']		=$count_pernum;
}
//人均次数
$count['today_pernum']=@round($count['today_sendnum']/$count['today_sendmember'],1);
$count['yestoday_pernum']=@round($count['yestoday_sendnum']/$count['yestoday_sendmember'],1);
$smarty->assign("count", $count);
$chart_data['label'] = rtrim($days_str, ',');
$chart_data['value1'] = rtrim($days_member_str, ',');
$chart_data['value2'] = rtrim($days_num_str, ',');
$chart_data['value3'] = rtrim($days_pernum_str, ',');
$smarty->assign("chart_data", $chart_data);

//数组倒置
$detail=array_reverse($detail);

$smarty->assign("detail", $detail);

$smarty->setTpl('data/templates/message.html')->display();