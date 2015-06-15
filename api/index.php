<?php
include '../includes/config.inc.php';
include 'baseapi.php';

$customer_id=(int)$_WGT['m_customer_id'];
$token=substr(md5('wgt'.$customer_id),0,15);
$wechatObj = new baseapi($token, $customer_id);
$custModel = new Model_Customer(); 
$filter['where'] = " id='$customer_id' and is_valid='1' ";
$sql=$custModel->select($filter,'is_yz');
$result = $custModel->fetchRow($sql);
if($result['is_yz']=='1'){
	echo $wechatObj->responseMsg();
}else{
	echo $wechatObj->valid();
}
?>