<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
$customer_id = (int)$_SESSION['customer_id'];
$infoModel = new Model_InfoCate();

if(method_is('post') && $_POST['cate_name_two']){
	$cate_name = str_inmysql($_POST['cate_name_two']);
	$id        = (int)$_POST['id'];
	$infoModel->query("update info_cate set cate_name='$cate_name' where id='$id' and customer_id='$customer_id'"); 
	echo "success";die();

}

if(method_is('post') && $_POST['del_id']){
	$id        = (int)$_POST['del_id'];
	$infoModel->delete("id='$id' and customer_id='$customer_id'"); 
	echo "success";die();
}
$type = $_POST['info_type'];
$data['info_type'] = str_inmysql($_POST['info_type']);
$data['customer_id'] = $customer_id;
$data['cate_name'] = $_POST['cate_name'];



$filter['where'] = "info_type='$data[info_type]' and customer_id='$customer_id' and cate_name='$data[cate_name]'";
$nums            = $infoModel->count($filter);

if($nums>0){
	
	echo '1';die();
}
$num = $infoModel->insert($data);

if($num>0){
	$row = array('id'=>$num,'cate_name'=>$data['cate_name']);
	$res = json_encode($row);
	header('Content-Type: application/json');
	echo $res;
};
