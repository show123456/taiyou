<?php
	//删除购物车信息
	if($_REQUEST['a']=='del'){
		$id=(int)$_POST['id'];
		unset($_SESSION["trolley"][$id]);
		echo json_encode('success');
	}
	//改变购物车商品数量
	if($_REQUEST['a']=='changeNum'){
		$id=(int)$_POST['id'];
		$num=(int)$_POST['num'];
		$_SESSION['trolley'][$id]['num']=$num;
		echo json_encode('success');
	}
	//保存购物车信息
	if($_REQUEST['a']=='add'){
		$gid=(int)$_POST['gid'];
		$num=(int)$_POST['num'];
		if(isset($_SESSION['trolley'][$gid])){
			$_SESSION['trolley'][$gid]['num']=$_SESSION['trolley'][$gid]['num']+$num;
		}else{
			$_SESSION['trolley'][$gid]['num']=$num;
		}
		echo json_encode('suc');
	}
	
	/* 店铺名及折扣 */
	$discount=1;//折扣
	if($_SESSION['tyuser']['id']){
		$userModel=D('sub_user');
		$userRow=$userModel->find($_SESSION['tyuser']['id']);
		if($userRow['my_num']){
			$agent_user_row=$userModel->field("id,code,agent_num")->where("agent_num='".$userRow['my_num']."'")->dataRow();
			$smarty->assign('shop_name',$agent_user_row['code']);
			//登录之后，若有代理商编号，显示折扣价
			if($agent_user_row){
				$kvRow=D('sub_kv')->where("k='discount'")->dataRow();
				$discount=$kvRow['v'];
			}
		}
	}
	$smarty->assign('discount',$discount);
	
	//购物车列表
	if($_REQUEST['a']=='index'){
		$listArr=array();
		$totalNum=0;//商品数量
		$totalMoney=0;//总价
		$numArr=$_SESSION['trolley'];
		$goodsModel=new Model_ApplistHptShopGoods();
		if($numArr){
			foreach($numArr as $key=>$value){
				$listArr[$key]['id']=$key;
				$listArr[$key]['num']=$value['num'];
				//获取商品信息
				$vo=$goodsModel->find($key);
				$listArr[$key]['name']=cut_str(deletehtml($vo['name']),10);
				$listArr[$key]['pic']=$vo['pic'];
				$listArr[$key]['fact_price']=round($vo['fact_price']*$discount,2);
				$listArr[$key]['money']=$listArr[$key]['fact_price']*$value['num'];
				$totalNum++;//商品数量
				$totalMoney+=$listArr[$key]['money'];//总价
			}
		}else{
			$smarty->assign('info','您尚未购买商品');
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		$smarty->assign('list',$listArr);
		$smarty->assign('totalNum',$totalNum);
		$smarty->assign('totalMoney',$totalMoney);
		//是否登录
		if($_COOKIE['tyuid'] && !$_SESSION['tyuser']['id']) $_SESSION['tyuser']['id']=$_COOKIE['tyuid'];
		if(empty($_SESSION['tyuser'])){
			$smarty->assign('no_login',1);
		}
		//总价+运费
		if($totalMoney < $moneyToFree){
			$smarty->assign('fact_yunfei',$yunfei);
			$smarty->assign('finalMoney',$totalMoney+$yunfei);
		}else{
			$smarty->assign('fact_yunfei',0);
			$smarty->assign('finalMoney',$totalMoney);
		}
		$smarty->setLayout('')->setTpl('mobile/templates/trolley.html')->display();
	}
	
	//购物车列表
	if($_REQUEST['a']=='index_lb'){
		$goodsModel=D('applist_hpt_shop_goods');
		$list=$goodsModel->where('is_lb=1')->dataArr();
		$smarty->assign('list',$list);
		$smarty->assign('yunfei',$yunfei);
		//是否登录
		if($_COOKIE['tyuid'] && !$_SESSION['tyuser']['id']) $_SESSION['tyuser']['id']=$_COOKIE['tyuid'];
		if(empty($_SESSION['tyuser'])){
			$smarty->assign('no_login',1);
		}
		//时间是否对
		$current_time=time();
		$time_12=strtotime(date('Y-m-d',$current_time).' 12:00:00');
		$time_cha=$time_12-$current_time;
		if($time_cha > 0) die;
		$smarty->setLayout('')->setTpl('mobile/templates/trolley_lb.html')->display();die;
	}