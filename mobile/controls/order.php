<?php
	$model=new Model_ApplistHptShopOrder();
	$m=new Model_ApplistHptShopOdetail();
	//订单地址
	if($_REQUEST['a']=='delivery'){
		if(empty($_SESSION['trolley'])){
			header("Location:index.php?m=goods&a=index");die;
		}
		//减少库存，增加售出量
		$numArr=$_SESSION['trolley'];
		if($numArr){
			foreach($numArr as $key=>$value){
				$listArr=array();
				$listArr[info]['oid']=$res;
				$listArr[info]['gid']=$key;
				$listArr[info]['num']=$value['num'];
				$goodsModel=new Model_ApplistHptShopGoods();
				//$goodsModel->query("update applist_hpt_shop_goods set store=store-{$value[num]},outnum=outnum+{$value[num]} where id={$key}");
				$goodsModel->query("update applist_hpt_shop_goods set outnum=outnum+{$value[num]} where id={$key}");
			}
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
			}
		}
		//保存订单表
		$data[info]['num']=$totalNum;
		$data[info]['money']=$totalMoney;
		$data[info]['uid']=$userRow['id'];
		$res=$model->add($data);
		//保存订单详情表
		if($numArr){
			foreach($numArr as $key=>$value){
				$listArr=array();
				$listArr[info]['oid']=$res;
				$listArr[info]['gid']=$key;
				$listArr[info]['num']=$value['num'];
				//获取商品信息
				$goodsModel=new Model_ApplistHptShopGoods();
				$vo=$goodsModel->find($key);
				$listArr[info]['name']=$vo['name'];
				$listArr[info]['pic']=$vo['pic'];
				$listArr[info]['price']=$vo['fact_price'];
				$listArr[info]['money']=$vo['fact_price']*$value['num'];
				$result=$m->add($listArr);//保存订单详情
			}
		}
		unset($_SESSION["trolley"]);
		if($data['num']['pay_type']==1){
			echo json_encode('yezf');die;
		}else{
			echo json_encode($res);die;
		}
	}