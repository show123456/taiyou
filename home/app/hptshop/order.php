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
	if($_GET['is_pay']==1){
		$condition[]=" is_pay=1 ";
	}elseif($_GET['is_pay']==2){
		$condition[]=" is_pay=0 ";
	}
	if($_GET['is_lb']==1){
		$condition[]=" is_lb=1 ";
	}elseif($_GET['is_lb']==2){
		$condition[]=" is_lb=0 ";
	}
	if($_GET['status']){
		if($_GET['status']==10){
			$condition[]=" status=0 ";
		}else{
			$condition[]=" status='".$_GET['status']."' ";
		}
	}
	
	if($_GET['date']) $condition[]=" left(addtime,10)='".$_GET['date']."' ";
	if($_GET['pay_date']){
		$now_time=strtotime($_GET['pay_date'].' 16:00:00');
		$time_before=date('Y-m-d H:i:s',$now_time-24*3600);
		$condition[]=" pay_time > '{$time_before}' and pay_time <= '".$_GET['pay_date']." 16:00:00' ";
	}
	if($condition) $filter['where'] = implode('and',$condition);
	$filter['order'] = " addtime desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->existPages($data['pager']));
	
	//总订单数总额
	if($filter['where']){
		$countRow=$model->field("count(*) as count_num,sum(money) as sum_money")->where($filter['where'])->dataRow();
	}else{
		$countRow=$model->field("count(*) as count_num,sum(money) as sum_money")->dataRow();
	}
	$smarty->assign('countRow',$countRow);
	//每次访问订单列表，删除昨天之前未付款大礼包订单
	$lb_list=$model->where("is_lb=1 and is_pay=0 and addtime<'".date('Y-m-d')." 00:00:00'")->dataArr();
	if($lb_list){
		foreach($lb_list as $lk=>$lv){
			$model->query("update sub_user set is_use=0 where id='".$lv['uid']."' limit 1");//优惠码继续可用
			$model->del($lv['id']);
		}
	}
	$smarty->setTpl('app/hptshop/templates/order_index.html')->display();