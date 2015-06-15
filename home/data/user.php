<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$customer_id = (int) $_SESSION['customer_id'];
$carerecordModel = new Model_CareRecord();
$today = date("Y-m-d");
$yestoday = date('Y-m-d', strtotime('-1 day'));

//最近2天关注量
$filter['where']="customer_id='$customer_id' and is_care='1' and left(create_date,10)>='$yestoday'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$carearray = $carerecordModel->fetchAll($sql);
foreach($carearray as $key=>$row){
	if ($row['count_date'] == $today) {
		$count['today_carenum'] = $row['count_num'];
    }elseif($row['count_date'] == $yestoday){
		$count['yestoday_carenum'] = $row['count_num'];
	}
}
//最近2天取消量
$filter['where']="customer_id='$customer_id' and is_care='0' and left(create_date,10)>'$yestoday'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$uncarearray = $carerecordModel->fetchAll($sql);
foreach($uncarearray as $key=>$row){
	if ($row['count_date'] == $today) {
		$count['today_uncarenum'] = $row['count_num'];
    }elseif($row['count_date'] == $yestoday){
		$count['yestoday_uncarenum'] = $row['count_num'];
	}
}
//累计关注
$filter['where']="customer_id='$customer_id' ";
$filter['group']=" is_care ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,is_care');
$uncarearray = $carerecordModel->fetchAll($sql);
foreach($uncarearray as $key=>$row){
	$count[$row['is_care']]=$row['count_num'];
}

$start_date = date('Y-m-d', strtotime('-29 day'));
//最近30天关注量
$filter=array();
$filter['where']="customer_id='$customer_id' and is_care='1' and left(create_date,10)>'$start_date'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$carearray = $carerecordModel->fetchAll($sql);
foreach($carearray as $key=>$row){
	$countinfo[$row['count_date']]=$row['count_num'];
}
//取消量
$filter=array();
$filter['where']="customer_id='$customer_id' and is_care='0' and left(create_date,10)>'$start_date'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$uncarearray = $carerecordModel->fetchAll($sql);
foreach($uncarearray as $key=>$row){
	$countinfob[$row['count_date']]=$row['count_num'];
}
for ($i = 29; $i >= 0; $i--) {
	$count_date		= date('Y-m-d', strtotime("-$i days"));
	$date_num		= substr($count_date, 8, 2) * 1;
	$count_value	= $countinfo[$count_date] > 0 ? $countinfo[$count_date] : '0';
	$count_valueb	= $countinfob[$count_date] > 0 ? $countinfob[$count_date] : '0';
	$count_valuec	= $count_value-$count_valueb;
	$days_str.='"' . $date_num . '",';
	$days_value_str.=$count_value . ',';
	$days_valueb_str.=$count_valueb . ',';
	$days_valuec_str.=$count_valuec . ',';
	$detail[$i]['count_date']	=$count_date;
	$detail[$i]['care']			=$count_value;
	$detail[$i]['uncare']		=$count_valueb;
	$detail[$i]['factcare']		=$count_value-$count_valueb;
}

//数组倒置
$detail=array_reverse($detail);

$smarty->assign("count", $count);
$chart_data1['label'] = rtrim($days_str, ',');
$chart_data1['value'] = rtrim($days_value_str, ',');
$chart_data1b['value'] = rtrim($days_valueb_str, ',');
$chart_data1c['value'] = rtrim($days_valuec_str, ',');
$smarty->assign("chart_data1", $chart_data1);
$smarty->assign("chart_data1b", $chart_data1b);
$smarty->assign("chart_data1c", $chart_data1c);
$smarty->assign("detail", $detail);

if($_REQUEST['type']=='action'){
	$smarty->setTpl('data/templates/user_action.html')->display();
}elseif($_REQUEST['type']=='attribute'){
	$smarty->setTpl('data/templates/user_attribute.html')->display();
}else{
	$smarty->setTpl('data/templates/user.html')->display();
}
?>