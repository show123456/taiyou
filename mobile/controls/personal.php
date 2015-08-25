<?php
	//商城购买记录
	if($_REQUEST['a']=='orderhistory'){
		$model=D('applist_hpt_shop_order');
		//数据列表
		$where = "uid='".$userRow['id']."'";
		$order=" id desc ";
		//分页
		$pageSize=10;//页大小
		$p=$_GET['p']<1 ? 1 : (int)$_GET['p'];//当前页数
		$limitStr = ($p-1)*$pageSize.','.$pageSize;
		$listArr = $model->where($where)->order($order)->limit($limitStr)->dataArr();
		foreach($listArr as $k=>$v){
			$listArr[$k]['addtime']=substr($v['addtime'],0,10);
		}
		
		if($_GET['p']){
			if($listArr){
				echo json_encode($listArr);die;
			}else{
				echo json_encode('err');die;
			}
		}else{
			if(!$listArr){
				$smarty->assign('info','还没有购买记录');
				$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
			}
			$smarty->assign('orderArr',$listArr);
			$smarty->setLayout('')->setTpl('mobile/templates/personal_orderhistory.html')->display();die;
		}
	}
	
	//订单详情
	if($_REQUEST['a']=='orderdetail'){
		$oid=$_GET['oid'];
		$smarty->assign('orderRow',D('applist_hpt_shop_order')->find($oid));
		$smarty->assign('odetailArr',D('applist_hpt_shop_odetail')->where(" oid='{$oid}' ")->dataArr());
		$smarty->setLayout('')->setTpl('mobile/templates/personal_orderdetail.html')->display();
	}