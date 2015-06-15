<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$id          = isset($_REQUEST['id'])  ? (int)$_REQUEST['id'] : '';
$act         = isset($_REQUEST['act']) ? $_REQUEST['act']     : '';
$customer_id = (int)$_SESSION['customer_id'];
$info_type   = 'text';
$data        = array();

//delete
if($act=='del' && $id){
	Model_Table::get('InfoCommon')->delete("id='{$id}' and customer_id='{$customer_id}'");
	Model_Table::get('KeywordList')->delete("info_id='{$id}' and customer_id='{$customer_id}' and info_type='{$info_type}'");
	//js_alter('','/home/info/test.php');
	header("location:/home/info/index.php?type=text");
    die();
}

//method:add||modify
if($id){
    $data = Model_Table::get('InfoCommon')->findByWhere("id='{$id}' and customer_id='{$customer_id}' and info_type='".Model_InfoCommon::get_type($info_type)."'");
    $smarty->assign('infoRow',$data);
}

//submit
if(method_is('post')){
	$data['customer_id']= $customer_id;
	$data['info_type']  = Model_InfoCommon::get_type($info_type);
	$data['keyword']    = isset($_POST['keyword'])    ? str_inmysql(str_replace(array(';','，'),',',$_POST['keyword']))    : '';
	$data['info_intro'] = isset($_POST['info_intro']) ? str_inmysql($_POST['info_intro']) : '';
	$data['state']    = isset($_POST['state'])    ? (int)$_POST['state']            : '';
	$data['cate_id']    = isset($_POST['cate_id'])    ? (int)$_POST['cate_id']            : '';
    $data['create_date']= date('Y-m-d H:i:s', $_WGT['TIME']);
     
	if(!$data['keyword']) ajax_feedback(0,'1201');
	if(!$data['info_intro']) ajax_feedback(0,'1202');
	if(!$data['cate_id']) {$data['cate_id'] = Model_Table::get('InfoCate')->saveDefaultCate($customer_id, $info_type);}

	//关键词过滤
	$keyArray = explode(',',$data['keyword']);
	$keyTable = new Model_KeywordList();
	foreach($keyArray as $v){
		$filter['where'] = "customer_id='$customer_id' and keyword='$v'";
		$nums            = $keyTable->count($filter);
		if($nums>0 && !$_POST['id']){
			ajax_feedback(0,'',"关键词 \"".$v.'" 已存在，请更换');die();
		}elseif($_POST['id']){
			$num = $keyTable->fetchRow("select * from keyword_list where info_id!='$_POST[id]' and keyword='$v' and customer_id='$customer_id'");
			if($num==true){
				ajax_feedback(0,'',"关键词 \"".$v.'" 已存在，请更换!');die();
			}
		}
		
	}
	//save-infocommon
	$info_id     = Model_Table::get('InfoCommon')->upsert($data);

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
$smarty->setTpl('info/templates/text.html')->display();