<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
$customer_id = (int)$_SESSION['customer_id'];

$Model_InfoCommonDetail   = new Model_InfoCommonDetail();
$Model_InfoCommon		  = new Model_InfoCommon();

$datatwo['title']    	  = str_inmysql($_POST['title']);
$datatwo['pic']     	  = str_inmysql($_POST['pic'])  ;
/* $msg = str_replace("\n", "<br/>", $_POST['msg_content']);
$msg = str_replace("\"", "", $msg); */
//$str = htmlspecialchars($_POST['msg_content']);
$datatwo['msg_content'] = str_inmysql($_POST['msg_content']);

$datatwo['url']    		  = str_inmysql($_POST['url']) ;
$datatwo['order_num']     = (int)$_POST['order_num'];


if($_POST['act']=='sele' && $_POST['d_id']){
	$id = (int)$_POST['d_id'];
	$p_id = (int)$_POST['id'];
	$rows = $Model_InfoCommonDetail->fetchRow("select * from info_common_detail where id='$id' and info_common_id='$p_id'");
	//$rowsstr = str_replace("\n", "", $rows['msg_content']);
	//$rows['msg_content'] = str_replace("\r", "", $rowsstr);
	$res = json_encode($rows);
	header('Content-Type: application/json');
	echo $res;die();

}
//The One
if($_POST['info_type_form']=='one'){
$data['keyword']          = str_inmysql(str_replace(array(';','，'),',',$_POST['keyword']));
$data['id']          	  =  (int)$_POST['id'] ;
$data['state']            =  (int)$_POST['state'];
$data['customer_id']      =  (int)$_SESSION['customer_id']   ;
$data['create_date']	  =  date('Y-m-d H:i:s', $_WGT['TIME']);
$data['info_type']    	  =  (int)(3);
//关键词过滤
	$keyArray = explode(',',$data['keyword']);
	$keyTable = new Model_KeywordList();

	foreach($keyArray as $v){
		$filter['where'] = "customer_id='$customer_id' and keyword='$v'";
		$nums            = $keyTable->count($filter);
		if($nums>0 && !$_POST['id']){
			$res = json_encode(array('1'=>'error','2'=>"关键词 \"".$v.'" 已存在，请更换'));
			header('Content-Type: application/json');
			echo $res;die();
			
		}elseif($_POST[id] && $nums>0){
			$num = $keyTable->fetchRow("select * from keyword_list where info_id!='$_POST[id]' and keyword='$v' and customer_id='$customer_id'");
			if($num==true){
				$res = json_encode(array('1'=>'error','2'=>"关键词 \"".$v.'" 已存在，请更换!!'));
				header('Content-Type: application/json');
				echo $res;die();
			}
		}
		
	}
	
	//info_common存一条
	$datatwo['info_common_id'] = $Model_InfoCommon->upsert($data);
	
	$list = array('customer_id'=>$customer_id,'info_id'=>$datatwo['info_common_id'],'keyword'=>$data['keyword'],'info_type'=>'multi');
	Model_Table::get('KeywordList')->saveForKeywords($list);
	//Info_Common_Detail存一条
	$datatwo['id'] = (int)$_POST['child_id']; 
	
	
	$chil = $Model_InfoCommonDetail->upsert($datatwo);

	$res = json_encode(array('1'=>'success','child_id'=>$chil,'parent_id'=>$datatwo['info_common_id']));
	header('Content-Type: application/json');
	echo $res;die();
}elseif($_POST['info_type_form']=='two'){
	$datatwo['info_common_id'] =(int)$_POST[id];
	$datatwo['id'] = (int)$_POST['child_id'];
	
	
	$num_id = $Model_InfoCommonDetail->upsert($datatwo);

	$res = json_encode(array('1'=>'success','parent_id'=>$datatwo['info_common_id']));
	header('Content-Type: application/json');
	echo $res;die();
}elseif($_POST['info_type_form']=='three'){
	$datatwo['info_common_id'] =(int)$_POST[id];

	$num_id = $Model_InfoCommonDetail->insert($datatwo);


	$res = json_encode(array('1'=>'success','parent_id'=>$datatwo['info_common_id']));
	header('Content-Type: application/json');
	echo $res;die();
}



?>