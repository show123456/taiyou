<?php
	include_once("../../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=new Model_ApplistHptShopOrder();
	$smarty->assign('cateName','订单');
	//数据保存
	if($_REQUEST['a']=='add'){
		$data=$_POST;
		$res=$model->add($data);
		echo json_encode($res);die();
	}
	//详情
	if($_REQUEST['a']=='detail'){
		$odetailModel=new Model_ApplistHptShopOdetail();
		$id=(int)$_GET['id'];
		if($id){
			$smarty->assign('vo',$model->find($id));
			$smarty->assign('detailList',$odetailModel->findAll(" oid=$id "));
		}
		$smarty->setTpl('app/hptshop/templates/order_add.html')->display();die();
	}
	//数据删除
	if($_REQUEST['a']=='del'){
		$res=$model->del($_POST['id']);
		echo json_encode($res);die();
	}
	//查看医师绑定的病人的消费
	if($_REQUEST['a']=='bind'){
		$did=(int)$_GET['did'];
		$condition=array();$filter=array();
		$condition[]=" did=$did ";
		if($_GET['keywords']) $condition[]=" id=".common_pg('keywords')." ";
		if($condition) $filter['where'] = implode('and',$condition);
		$filter['order'] = " id desc ";
		$data = $model->paginate($filter,'*',common_pg('p'),10);
		$listArr = $data['data'];
		$smarty->assign('list',$listArr);
		$smarty->assign('page',$model->existPages($data['pager']));
		$smarty->setTpl('app/hptshop/templates/order_bind.html')->display();die();
	}
	//数据列表
	$condition=array();$filter=array();
	if($_GET['keywords']) $condition[]=" id=".common_pg('keywords')." ";
	if($_GET['date']){
		$date=date('Y-m-d',strtotime($_GET['date']));
		$condition[]=" left(addtime,10)='{$date}' ";
	}
	$condition[]=" is_pay=1 ";
	if($condition) $filter['where'] = implode('and',$condition);
	$filter['order'] = " addtime desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->existPages($data['pager']));
	$smarty->setTpl('app/hptshop/templates/order_index.html')->display();