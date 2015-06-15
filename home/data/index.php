<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$customer_id = (int) $_SESSION['customer_id'];

$carerecordModel = new Model_CareRecord();
$messageModel = new Model_Message();

$today = date("Y-m-d");
$start_date = date('Y-m-d', strtotime('-29 day'));

//最近30天关注量
$filter['where']="customer_id='$customer_id' and is_care='1' and left(create_date,10)>'$start_date'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$carearray = $carerecordModel->fetchAll($sql);
foreach($carearray as $key=>$row){
	$countinfo[$row['count_date']]=$row['count_num'];
	if ($row['count_date'] == $today) {
		$count['today_carenum'] = $row['count_num'];
    }
}
//取消量
$filter['where']="customer_id='$customer_id' and is_care='0' and left(create_date,10)>'$start_date'";
$filter['group']=" left(create_date,10) ";
$sql=$carerecordModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$uncarearray = $carerecordModel->fetchAll($sql);
foreach($uncarearray as $key=>$row){
	$countinfob[$row['count_date']]=$row['count_num'];
	if ($row['count_date'] == $today) {
		$count['today_uncarenum'] = $row['count_num'];
    }
}
//最近30天互动量
$filter['where']="customer_id='$customer_id' and left(create_date,10)>'$start_date'";
$filter['group']=" left(create_date,10) ";
$sql=$messageModel->select($filter,'count(id) as count_num,left(create_date,10) as count_date');
$messagearray = $messageModel->fetchAll($sql);
foreach($messagearray as $key=>$row){
	$countinfoc[$row['count_date']]=$row['count_num'];
	if ($row['count_date'] == $today) {
		$count['today_message'] = $row['count_num'];
    }
}

for ($i = 29; $i >= 0; $i--) {
	$count_date = date('Y-m-d', strtotime("-$i days"));
	$date_num = substr($count_date, 8, 2) * 1;
	$count_value = $countinfo[$count_date] > 0 ? $countinfo[$count_date] : '0';
	$count_valueb = $countinfob[$count_date] > 0 ? $countinfob[$count_date] : '0';
	$count_valuec = $countinfoc[$count_date] > 0 ? $countinfoc[$count_date] : '0';
	$days_str.='"' . $date_num . '",';
	$days_value_str.=$count_value . ',';
	$days_valueb_str.=$count_valueb . ',';
	$days_valuec_str.=$count_valuec . ',';
}


$filter['where']="customer_id = '$customer_id' and msg_content!='subscribe' and msg_content!='unsubscribe' and msg_content!='' and create_date >= '$start_date'";
$filter['group']="msg_content";
$filter['order']="count_num desc";
$filter['limit']="0,100";
$sql=$messageModel->select($filter,'count(id) as count_num,msg_content');
$topmessage = $messageModel->fetchAll($sql);
foreach ($topmessage as $key => $value) {
	$topmessage[$key]['msg_content']=cut_str($value['msg_content'],6, $start = 0, $code = 'UTF-8');
}
$topmessage = array_slice($topmessage, 0, 10, true);

//互动内容统计
$infocommonModel = new Model_InfoCommon();
$filter=array();
$filter['where']="customer_id = '$customer_id' and state='1' ";
$filter['group']="info_type";
$sql=$infocommonModel->select($filter,'count(id) as count_num,info_type');
$infocommonarray = $infocommonModel->fetchAll($sql);
foreach ($infocommonarray as $key => $value) {
	$infocount[$value['info_type']]=$value['count_num'];
}
//图片统计
$infopicModel = new Model_InfoPic();
$filter=array();
$filter['where'] = "customer_id = '$customer_id'";
$infocount['pic']   = $infopicModel->count($filter);
//音乐统计
$infomusicModel = new Model_InfoMusic();
$filter=array();
$filter['where'] = "customer_id = '$customer_id'";
$infocount['music']   = $infomusicModel->count($filter);
//视频统计
$infovideoModel = new Model_InfoVideo();
$filter=array();
$filter['where'] = "customer_id = '$customer_id'";
$infocount['video']   = $infovideoModel->count($filter);

$chart_data1['label'] = rtrim($days_str, ',');
$chart_data1['value'] = rtrim($days_value_str, ',');
$chart_data1b['value'] = rtrim($days_valueb_str, ',');
$chart_data1c['value'] = rtrim($days_valuec_str, ',');

$smarty->assign("count", $count);
$smarty->assign("chart_data1", $chart_data1);
$smarty->assign("chart_data1b", $chart_data1b);
$smarty->assign("chart_data1c", $chart_data1c);
$smarty->assign("topmessage", $topmessage);
$smarty->assign("infocount", $infocount);

$smarty->setTpl('data/templates/index.html')->display();