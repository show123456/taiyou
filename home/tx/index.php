<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_out');
//数据保存
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$id=(int)$_GET['id'];
		if($id){
			$smarty->assign('vo',$model->find($id));
		}
		$smarty->setTpl('tx/templates/add.html')->display();die();
	}
}
//更改支付状态
if($_REQUEST['a']=='kf'){
	$data['info'][is_pay]=(int)$_GET['is_pay'];
	$data['info'][id]=(int)$_GET['id'];
	$res=$model->add($data);
	echo $res;die();
}
//数据删除
if($_REQUEST['a']=='del'){
	$res=$model->del($_POST['id']);
	echo json_encode($res);die();
}
//数据列表
$userModel=D('sub_user');
$condition=array();
if($_GET['keywords']){//搜索姓名
	$uArr=$userModel->where("nickname like '%".$_GET['keywords']."%'")->dataArr();
	if($uArr){
		foreach($uArr as $v){
			$idRow[]=$v['id'];
		}
	}
	if($idRow){
		$condition[]=" uid in (".implode(',',$idRow).") ";
	}else{
		$condition[]=" uid = 0 ";
	}
}
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$userRow=$userModel->find($value['uid']);
	$listArr[$key]['nickname']=$userRow['nickname'];
	$listArr[$key]['username']=$userRow['username'];
	$listArr[$key]['bank_name']=$userRow['bank_name'];
	$listArr[$key]['bank_card']=$userRow['bank_card'];
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('tx/templates/index.html')->display();