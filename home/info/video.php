<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");

//var
$id          = isset($_REQUEST['id'])  ? (int)$_REQUEST['id'] : '';
$act         = isset($_REQUEST['act']) ? $_REQUEST['act']     : '';
$customer_id = (int)$_SESSION['customer_id'];
$info_type   = 'video';
$data        = array();

//delete
if($act=='del' && $id){
	Model_Table::get('InfoVideo')->delete("id='{$id}' and customer_id='{$customer_id}'");
	Model_Table::get('KeywordList')->delete("info_id='{$id}' and customer_id='{$customer_id}' and info_type='{$info_type}'");
    header("location:/home/info/index.php?type=video");
	die();
}

//method:add||modify
if($id){
    $music_table = new Model_InfoVideo();
	$data = $music_table->fetchRow("select * from info_video where id='$id' and customer_id='$customer_id'");
    $smarty->assign('infoRow',$data);
}

//submit
if(method_is('post')){
	$data['customer_id']      = $customer_id;
	//$data['info_type']        = Model_InfoVideo::get_type($info_type);
	$data['keyword']          = isset($_POST['keyword'])    ? str_inmysql(str_replace(array(';','，'),',',$_POST['keyword']))    : '';
    $data['cate_id']          = isset($_POST['cate_id'])    ? (int)$_POST['cate_id']            : '';
	$data['create_date']	  = date('Y-m-d H:i:s', $_WGT['TIME']);
	
    $data['video_name']       = isset($_POST['video_name']) ? str_inmysql($_POST['video_name']) : '';
    $data['video_pic']         = isset($_POST['video_pic'])   ? str_inmysql($_POST['video_pic'])   : '';
    $data['video_desc']        = isset($_POST['video_desc'])  ? str_inmysql($_POST['video_desc'])  : '';
    $data['video_url']       = isset($_POST['video_url']) ? str_inmysql($_POST['video_url']) : '';
    $data['state']         = isset($_POST['state'])   ? str_inmysql($_POST['state'])   : '';
    
    
	if(!$data['keyword']) ajax_feedback(0,'1201');
	if(!$data['video_name']) ajax_feedback(0,'1309');
	if(!$data['video_url']) ajax_feedback(0,'1308');
	if(!$data['cate_id']) {$data['cate_id'] = Model_Table::get('InfoCate')->saveDefaultCate($customer_id, $info_type);}

	//关键词过滤
	$keyArray = explode(',',$data['keyword']);
	$keyTable = new Model_KeywordList();
	foreach($keyArray as $v){
		$filter['where'] = "customer_id='$customer_id' and keyword='$v'";
		$nums            = $keyTable->count($filter);
		if($nums>0 && !$_POST['id']){
			ajax_feedback(0,'',"关键词 \"".$v.'" 已存在，请更换');die();
		}elseif($_POST[id]){
			$num = $keyTable->fetchRow("select * from keyword_list where info_id!='$_POST[id]' and keyword='$v' and customer_id='$customer_id'");
			if($num==true){
				ajax_feedback(0,'',"关键词 \"".$v.'" 已存在，请更换');die();
			}
		}
		
	}
	//save-InfoVideo
	$info_id     = Model_Table::get('InfoVideo')->upsert($data);
	
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
$smarty->setTpl('info/templates/video.html')->display();