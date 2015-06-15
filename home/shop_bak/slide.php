<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_shop_pic');
//数据保存
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		//删除原图
		if($data['num'][id]){
			$vo=$model->find($data['num'][id]);
			if($vo['pic']!=$data['info'][pic]) @unlink("../../data/image_c/".$vo['pic']);;
		}
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$id=(int)$_GET['id'];
		if($id){
			$smarty->assign('vo',$model->find($id));
		}
		$smarty->setTpl('shop/templates/slide_add.html')->display();die();
	}
}
//数据删除
if($_REQUEST['a']=='del'){
	$vo=$model->find($_POST['id']);
	$res=$model->del($_POST['id']);
	//删除原图
	if($res) @unlink("../../data/image_c/".$vo['pic']);
	echo json_encode($res);die();
}
//数据列表
$filter['order'] = " ordernum desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
$smarty->assign('list',$listArr);
$smarty->setTpl('shop/templates/slide_index.html')->display();