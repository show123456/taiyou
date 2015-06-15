<?php
include_once("../includes/config.inc.php");
$customer_id = $_WGT['m_customer_id'];
$id=(int)$_REQUEST['id'];
$t=$_REQUEST['t'];
if($t=='single'){
	$infocommonModel= new Model_InfoCommon();
	$filter['where'] = " customer_id='$customer_id' and id='$id' ";
	$sql=$infocommonModel->select($filter,'*');
	$info = $infocommonModel->fetchRow($sql);
	$info['info_intro']=nl2br($info['info_intro']);
}elseif($t=='multi'){
	$infocommondetailModel= new Model_InfoCommonDetail();
	$filter['where'] = " id='$id' ";
	$sql=$infocommondetailModel->select($filter,'*');
	$info = $infocommondetailModel->fetchRow($sql);
	$info['info_title']=$info['title'];
	$info['info_intro']=nl2br($info['msg_content']);
	$info['pic_showincontent']=1;
	$info['info_pic']=$info['pic'];

}
$customerModel= new Model_Customer();
$filter['where'] = " id='$customer_id' ";
$sql=$customerModel->select($filter,'id,weixin_name');
$customerinfo = $customerModel->fetchRow($sql);

$smarty->assign("customerinfo",$customerinfo);
$smarty->assign("info",$info);
$smarty->assign("today",date("Y-m-d"));
$smarty->setLayout('')->setTpl('/mobile/templates/info.html')->display();
?>