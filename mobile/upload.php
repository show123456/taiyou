<?php
include_once("../includes/config.inc.php");
include_once("../includes/class-upload.php");
$customer_id=$_WGT['m_customer_id'];
if(method_is('post')){
	$upload = new class_upload();
	$upload->upload_form_field= 'imgfile';
	$upload->out_file_dir     = '../data/image_c/'.$customer_id.'/'.date("Ymd");
	$upload->max_file_size    = 1024*1024*4;//4M
	$upload->make_script_safe = 1;
	$upload->allowed_file_ext = array( 'gif', 'jpg', 'jpeg', 'png');
	$upload->upload_process();
	if($upload->error_no) ajax_feedback(0,$upload->error_no);
	$save_path=str_replace('../data/image_c/','',$upload->saved_upload_name);
	
	makethumb($upload->saved_upload_name,$upload->saved_upload_name,200,200);
	//更新到数据库
	$model=new Model_Subtable('sub_user');
	$model->query("update sub_user set head_pic='{$save_path}' where id=".$_POST['id']);
	
	ajax_feedback(1,array('path'=>$save_path));
}