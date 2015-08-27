<?php
	$model=new Model_ApplistHptShopOrder();
	$m=new Model_ApplistHptShopOdetail();
	//订单地址
	if($_REQUEST['a']=='delivery'){
		if(empty($_SESSION['trolley'])){
			header("Location:index.php?m=goods&a=index");die;
		}
		//收货人信息
		if($_COOKIE['tyuid'] && !$_SESSION['tyuser']['id']) $_SESSION['tyuser']['id']=$_COOKIE['tyuid'];
		if(empty($_SESSION['tyuser'])){
			header("Location:./login.php");die;
		}
		$userModel=D('sub_user');
		$userRow=$userModel->find($_SESSION['tyuser']['id']);
		//省市区
		$pRow=D('s_province')->where("ProvinceID='".$userRow['pid']."'")->dataRow();
		$userRow['pname']=$pRow['ProvinceName'];
		$cRow=D('s_city')->where("CityID='".$userRow['cid']."'")->dataRow();
		$userRow['cname']=$cRow['CityName'];
		$dRow=D('s_district')->where("DistrictID='".$userRow['did']."'")->dataRow();
		$userRow['dname']=$dRow['DistrictName'];
		$smarty->assign('userRow',$userRow);
		$smarty->setLayout('')->setTpl('mobile/templates/delivery.html')->display();die();
	}
	
	if($_REQUEST['a']=='delivery_lb'){
		$userModel=D('sub_user');
		$userRow=$userModel->find($_SESSION['tyuser']['id']);
		//省市区
		$pRow=D('s_province')->where("ProvinceID='".$userRow['pid']."'")->dataRow();
		$userRow['pname']=$pRow['ProvinceName'];
		$cRow=D('s_city')->where("CityID='".$userRow['cid']."'")->dataRow();
		$userRow['cname']=$cRow['CityName'];
		$dRow=D('s_district')->where("DistrictID='".$userRow['did']."'")->dataRow();
		$userRow['dname']=$dRow['DistrictName'];
		$smarty->assign('userRow',$userRow);
		$smarty->setLayout('')->setTpl('mobile/templates/delivery_lb.html')->display();die();
	}
	
	//更改订单状态
	if($_REQUEST['a']=='cancel'){
		$data[info]['id']=$_POST['oid'];
		$data[info]['status']=$_POST['status'];
		$res=$model->add($data);
		echo json_encode($res);exit;
	}
	//生成订单
	if($_REQUEST['a']=='add'){
		$data=$_POST;
		$goodsModel=new Model_ApplistHptShopGoods();
		//订单信息
		$numArr=$_SESSION['trolley'];
		$totalNum=0;//商品件数
		$totalMoney=0;
		if($numArr){
			foreach($numArr as $key=>$value){
				$totalNum++;//商品件数
				//获取商品信息
				$vo=$goodsModel->find($key);
				$totalMoney+=$vo['fact_price']*$value['num'];
			}
		}
		//运费
		if($totalMoney>=$moneyToFree){
			$data[info]['yunfei']=0;
		}else{
			$data[info]['yunfei']=$yunfei;
			$totalMoney+=$yunfei;
		}
		//若为余额支付
		if($data['num']['pay_type']==1){
			if($userRow['money'] < $totalMoney){//余额不足
				echo json_encode('yebz');die;
			}else{
				//减少用户余额
				$userMoneyRes=$userModel->query("update sub_user set money = money - ".$totalMoney." where id='".$userRow['id']."'");
				//直接设置为已支付
				$data[info]['is_pay']=1;
				//获得禁闭卡
				$cardData=array();
				$cardData['info']['type']=1;
				$cardData['info']['uid']=$userRow['id'];
				$cardData['info']['out_time']=time()+3600*24*30;
				D('sub_card')->add($cardData);
			}
		}
		//保存订单表
		$data[info]['num']=$totalNum;
		$data[info]['money']=$totalMoney;
		$data[info]['uid']=$userRow['id'];
		$res=$model->add($data);
		//保存订单详情表
		if($numArr){
			$goodsModel=new Model_ApplistHptShopGoods();
			foreach($numArr as $key=>$value){
				$listArr=array();
				$listArr[info]['oid']=$res;
				$listArr[info]['gid']=$key;
				$listArr[info]['num']=$value['num'];
				//获取商品信息
				$vo=$goodsModel->find($key);
				$listArr[info]['name']=$vo['name'];
				$listArr[info]['pic']=$vo['pic'];
				$listArr[info]['price']=$vo['fact_price'];
				$listArr[info]['money']=$vo['fact_price']*$value['num'];
				$result=$m->add($listArr);//保存订单详情
				//增加售出量
				$goodsModel->query("update applist_hpt_shop_goods set outnum=outnum+{$value[num]} where id={$key}");
			}
		}
		
		unset($_SESSION["trolley"]);
		if($data['num']['pay_type']==1){
			echo json_encode('yezf');die;
		}else{
			echo json_encode($res);die;
		}
	}
	
	if($_REQUEST['a']=='add_lb'){
		$data=$_POST;
		$goodsModel=D('applist_hpt_shop_goods');
		//订单信息
		$totalMoney=$yunfei;
		$list=$goodsModel->where('is_lb=1')->dataArr();
		//核对优惠码
		if($data['code']=='' || $data['code']!=$userRow['code'] || $userRow['is_use']==1){
			echo json_encode('code_err');die;
		}
		//若为余额支付
		if($data['num']['pay_type']==1){
			if($userRow['money'] < $totalMoney){//余额不足
				echo json_encode('yebz');die;
			}else{
				//减少用户余额
				$userMoneyRes=$userModel->query("update sub_user set money = money - ".$totalMoney." where id='".$userRow['id']."'");
				//直接设置为已支付
				$data[info]['is_pay']=1;
			}
		}
		//保存订单表
		$data[info]['num']=count($list);
		$data[info]['money']=$totalMoney;
		$data[info]['uid']=$userRow['id'];
		$data[info]['yunfei']=$yunfei;
		$data[info]['is_lb']=1;
		$res=$model->add($data);
		//将用户的优惠码设置为已使用
		$userData=array();
		$userData['info']['id']=$userRow['id'];
		$userData['info']['is_use']=1;
		$userModel->add($userData);
		//保存订单详情表
		if($list){
			foreach($list as $key=>$value){
				$listArr=array();
				$listArr[info]['oid']=$res;
				$listArr[info]['gid']=$value['id'];
				$listArr[info]['num']=1;
				$listArr[info]['name']=$value['name'];
				$listArr[info]['pic']=$value['pic'];
				$listArr[info]['price']=$value['fact_price'];
				$listArr[info]['money']=$value['fact_price']*1;
				$result=$m->add($listArr);//保存订单详情
				//增加售出量
				$goodsModel->query("update applist_hpt_shop_goods set outnum=outnum+1 where id=".$value['id']);
			}
		}
		//礼包售出量+1
		$goodsModel->query("update applist_hpt_shop_goods set outnum=outnum+1 where id=1");
		
		if($data['num']['pay_type']==1){
			echo json_encode('yezf');die;
		}else{
			echo json_encode($res);die;
		}
	}