<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$id          = isset($_REQUEST['id'])  ? (int)$_REQUEST['id'] : '';
$act         = isset($_REQUEST['act']) ? $_REQUEST['act']     : '';
$customer_id = (int)$_SESSION['customer_id'];
$info_type   = 'lbs';
$data        = array();

//delete
if($act=='del' && $id){
	Model_Table::get('InfoLbs')->delete("id='{$id}' and customer_id='{$customer_id}'");
	Model_Table::get('KeywordList')->delete("info_id='{$id}' and customer_id='{$customer_id}' and info_type='{$info_type}'");
    header("location:/home/info/index.php?type=<{$info_type}>");
}

//method:add||modify
if($id){
    $music_table = new Model_InfoLbs();
	$data = $music_table->fetchRow("select * from info_lbs where id='$id' and customer_id='$customer_id'");
    $smarty->assign('infoRow',$data);
}

//submit
if(method_is('post')){
	$data['customer_id']      = $customer_id;
	$data['create_date']	  = date('Y-m-d H:i:s', $_WGT['TIME']);
	
    $data['location_name']    = isset($_POST['location_name']) ? str_inmysql($_POST['location_name']) : '';
    $data['location_intro']   = isset($_POST['location_intro'])   ? str_inmysql($_POST['location_intro'])   : '';
    $data['location_desc']    = isset($_POST['location_desc'])  ? str_inmysql($_POST['location_desc'])  : '';
    $data['location_pic']     = isset($_POST['location_pic']) ? str_inmysql($_POST['location_pic']) : '';
    $data['state']            = isset($_POST['state'])   ? str_inmysql($_POST['state'])   : '';
    $data['x_dian']            = isset($_POST['x_dian'])   ? str_inmysql($_POST['x_dian'])   : '';
    $data['y_dian']            = isset($_POST['y_dian'])   ? str_inmysql($_POST['y_dian'])   : '';
   
   
   
	if(!$data['location_name']) ajax_feedback(0,'1310');
	if(!$data['x_dian']) ajax_feedback(0,'1311');
	if(!$data['y_dian']) ajax_feedback(0,'1312');
	

	//save-InfoLbs
	$info_id     = Model_Table::get('InfoLbs')->upsert($data);

	//save-keywordlist
	$list = array('customer_id'=>$customer_id,'info_id'=>$info_id,'keyword'=>$data['keyword'],'info_type'=>$info_type);
	Model_Table::get('KeywordList')->saveForKeywords($list);

	//return
	ajax_feedback(1);

}

//category
$cateModel = new Model_InfoCate();
$cates     = $cateModel->getCatesByType($info_type);

$smarty->assign("cates",$cates);
$smarty->assign("today",date("Y-m-d"));
$smarty->setTpl('info/templates/lbs.html')->display();