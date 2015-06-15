<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$id          = isset($_REQUEST['id'])  ? (int)$_REQUEST['id'] : '';
$act         = isset($_REQUEST['act']) ? $_REQUEST['act']     : '';
$customer_id = (int)$_SESSION['customer_id'];
$info_type   = 'multi';
$data        = array();

//delete
    $music_table = new Model_InfoCommon();
	$Model_InfoCommonDetail = new Model_InfoCommonDetail();

if($act=='del' && $_POST['child_id']){
	$Model_InfoCommonDetail->delete("id='{$_POST[child_id]}'");
	//echo $_POST['child_id'];
	die('success');
}
if($act=='del' && $id){
	Model_Table::get('InfoCommon')->delete("id='{$id}' and customer_id='{$customer_id}'");
	Model_Table::get('InfoCommonDetail')->delete("info_common_id='{$id}'");
	
	Model_Table::get('KeywordList')->delete("info_id='{$id}' and customer_id='{$customer_id}' and info_type='{$info_type}'");
    header("location:/home/info/index.php?type=multi");
	die();
}

//method:add||modify
if($id){
    $music_table = new Model_InfoCommon();
	$Model_InfoCommonDetail = new Model_InfoCommonDetail();
	$data = $music_table->fetchRow("select * from info_common where id='$id' and customer_id='$customer_id' and info_type=3");
	$datas = $Model_InfoCommonDetail->fetchAll("select * from info_common_detail where info_common_id='$id' order by order_num asc");
	//echo "<pre>";var_dump($datas);
    $smarty->assign('infoRow',$data);
	if($datas){
		foreach ($datas as $key => $value) {
		$datas[$key]['title']=cut_str($value['title'],10, $start = 0, $code = 'UTF-8');		
		}
		$smarty->assign('infoAll',$datas);
		$smarty->assign('json_true',true);
	}else{
		$smarty->assign('json_true',false);
	}

	
	//echo "<pre>";var_dump($datas);die();
}




$smarty->setTpl('info/templates/multi.html')->display();