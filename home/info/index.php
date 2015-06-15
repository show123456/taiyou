<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
//var
$customer_id = (int)$_SESSION['customer_id'];
$type        = Model_InfoCate::exist_type($_REQUEST['type']) ? $_REQUEST['type'] : 'text';
$cate_id     = (int)$_REQUEST["cate_id"];
$page        = (int) $_REQUEST["p"] > 0 ? (int) $_REQUEST["p"] : 1;


//sql
$filter = array();
$filter['where'] = "customer_id = {$customer_id}";
if($cate_id) $filter['where'] .= " and cate_id='{$cate_id}'";
if($type=='multi'){
	$filter['where'] .=" and info_type='3'";
	
	
	
}else{
	if(Model_InfoCate::getTypeName($type)=='common') $filter['where'] .= " and info_type='".Model_InfoCommon::get_type($type)."'";
}
$filter['order'] = "id desc";

//result
$table = 'Model_Info'.ucfirst(Model_InfoCate::getTypeName($type));
$infoModel = new $table();
//echo "<pre>";var_dump($filter);die();
$result    = $infoModel->paginate($filter, '*', $page,$size='20');
$pager     = $result['pager'];

//pager
if ($pager['pagenum'] > $pager['size']) {
	$pagerhtml = new SubPages($pager['size'], $pager['count'], $pager['current'], $pager['range'], 2);
    $smarty->assign("pagerhtml", $pagerhtml);
}

//category
$cateModel = new Model_InfoCate();
$cates     = $cateModel->getCatesByType($type);
//获取分类条数
foreach($cates as &$v){
	$filter['where'] = "customer_id='$customer_id' and cate_id='$v[id]'";
	 $v['cate_num'] =$infoModel->count($filter);
}
 //echo "<pre>";var_dump($cates);die(); 
//smarty
if($cate_id){
	$smarty->assign("cate_id", $cate_id);
}
$smarty->assign("type", $type);
$smarty->assign("typeyhtml", $type."_table");
$smarty->assign("cates",$cates);

//var_dump($result[data]);die();
foreach ($result['data'] as $key => $value) {
	//$result['data'][$key]['keyword']=cut_str($value['keyword'],8, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['info_intro']=cut_str($value['info_intro'],13, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['info_title']=cut_str($value['info_title'],13, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['music_name']=cut_str($value['music_name'],9, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['music_url']=cut_str($value['music_url'],20, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['video_name']=cut_str($value['video_name'],9, $start = 0, $code = 'UTF-8');
	$result['data'][$key]['location_name']=cut_str($value['location_name'],9, $start = 0, $code = 'UTF-8');
	
}

if($type=='multi'){
	$Model_InfoCommonDetail = new Model_InfoCommonDetail();
	foreach($result['data'] as &$v){
		
		$filter['where'] = "info_common_id = '$v[id]'";
		$filter['group'] = 'info_common_id';
		$v['multi_num']       = $Model_InfoCommonDetail->count($filter);
	}

}
//echo "<pre>";var_dump($result['data']);die();
$smarty->assign("infolist", $result['data']);

$smarty->setTpl('info/templates/index.html')->display();
