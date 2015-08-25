<?php
	include_once("../../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=new Model_ApplistHptShopPic();
	$smarty->assign('cateName','幻灯片');
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
			$smarty->setTpl('app/hptshop/templates/slide_add.html')->display();die();
		}
	}
	//数据删除
	if($_REQUEST['a']=='del'){
		$res=$model->del($_POST['id']);
		echo json_encode($res);die();
	}
	//数据列表
	$filter['order'] = " ordernum desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	$smarty->assign('list',$listArr);
	$smarty->setTpl('app/hptshop/templates/slide_index.html')->display();