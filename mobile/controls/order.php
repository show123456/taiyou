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
		//时间是否对
		$current_time=time();
		$time_12=strtotime(date('Y-m-d',$current_time).' 12:00:00');
		$time_cha=$time_12-$current_time;
		//if($time_cha > 0) die;
		$smarty->setLayout('')->setTpl('mobile/templates/delivery_lb.html')->display();die();
	}
	
	//更改订单状态
	if($_REQUEST['a']=='cancel'){
		$data[info]['id']=$_POST['oid'];
		$data[info]['status']=$_POST['status'];
		$res=$model->add($data);
		echo json_encode($res);exit;
	}
	
	/* 店铺名及折扣 */
	/************************* 折扣一律在卖价上打折，之后再乘以数量算总金额 ***************************/
	$discount=1;//折扣
	if($userRow['my_num']){
		$agent_user_row=$userModel->field("id,code,agent_num")->where("agent_num='".$userRow['my_num']."'")->dataRow();
		$smarty->assign('shop_name',$agent_user_row['code']);
		//登录之后，若有代理商编号，显示折扣价
		if($agent_user_row){
			$kvRow=D('sub_kv')->where("k='discount'")->dataRow();
			$discount=$kvRow['v'];
		}
	}
	$smarty->assign('discount',$discount);
	
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
				$totalMoney+=round($vo['fact_price']*$discount,2)*$value['num'];
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
				$data[info]['pay_time']=date('Y-m-d H:i:s');
				//获得禁闭卡
				$cardData=array();
				$cardData['info']['type']=1;
				$cardData['info']['uid']=$userRow['id'];
				$cardData['info']['out_time']=time()+3600*24*30;
				D('sub_card')->add($cardData);
				//增加累计消费
				$sumData=array();
				$sumModel=D('sub_sum');
				$sum_row=$sumModel->where("uid='".$userRow['id']."'")->dataRow();
				if($sum_row){
					$sumData['info']['id']=$sum_row['id'];
					$sumData['info']['money2']=$sum_row['money2']+$totalMoney;
					$sumData['info']['money3']=$sum_row['money3']+$totalMoney;
				}else{
					$sumData['info']['money2']=$totalMoney;
					$sumData['info']['money3']=$totalMoney;
				}
				if($sumData['info']['money2'] >= 368){
					//获得一张抢位卡
					$cardData=array();
					$cardData['info']['type']=2;
					$cardData['info']['uid']=$userRow['id'];
					$cardData['info']['out_time']=time()+3600*24*30;
					D('sub_card')->add($cardData);
					$sumData['info']['money2']-=368;
				}
				if($sumData['info']['money3'] >= 688){
					//获得一张提醒卡
					$cardData=array();
					$cardData['info']['type']=3;
					$cardData['info']['uid']=$userRow['id'];
					$cardData['info']['out_time']=time()+3600*24*30;
					D('sub_card')->add($cardData);
					$sumData['info']['money3']-=688;
				}
				$sumData['info']['uid']=$userRow['id'];
				$sumModel->add($sumData);
				//获取开乐迪券码
				$kldData=array();
				$kldData['info']['code']=strtolower(code_random(5));
				$kldData['info']['uid']=$userRow['id'];
				D('sub_kld')->add($kldData);
			}
		}
		//保存订单表
		$data[info]['num']=$totalNum;
		$data[info]['money']=$totalMoney;
		$data[info]['uid']=$userRow['id'];
		$data[info]['agent_num']=$userRow['my_num'];//代理商编号
		$data[info]['discount']=$discount;//代理商折扣
		$res=$model->add($data);
		//写金额日志
		if($data['num']['pay_type']==1){
			$logData=array();
			$logData['info']['type']=8;//余额支付
			$logData['info']['uid']=$userRow['id'];
			$logData['info']['money']=0-$totalMoney;
			$logData['info']['desc']=$res;
			D('sub_money_log')->add($logData);
		}
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
				$listArr[info]['one_code']=$vo['one_code'];
				$listArr[info]['name']=$vo['name'];
				$listArr[info]['pic']=$vo['pic'];
				$listArr[info]['ori_price']=$vo['ori_price'];
				$listArr[info]['price']=$vo['fact_price'];
				$listArr[info]['discount']=$discount;
				$listArr[info]['money']=round($vo['fact_price']*$discount,2)*$value['num'];
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
		//今日剩礼包数量
		$lbModel=D('sub_lb');
		$lb_count_row=$lbModel->field('count(*) as count_num')->where("left(addtime,10)='".date('Y-m-d')."'")->dataRow();
		if($lb_count_row['count_num'] >= 10){
			echo json_encode('man');die;
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
				$data[info]['pay_time']=date('Y-m-d H:i:s');
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
		//今日礼包+1
		$lbData['info']['uid']=$userRow['id'];
		$lbModel->add($lbData);
		//保存订单详情表
		if($list){
			foreach($list as $key=>$value){
				$listArr=array();
				$listArr[info]['oid']=$res;
				$listArr[info]['gid']=$value['id'];
				$listArr[info]['num']=1;
				$listArr[info]['one_code']=$value['one_code'];
				$listArr[info]['name']=$value['name'];
				$listArr[info]['pic']=$value['pic'];
				$listArr[info]['ori_price']=$value['ori_price'];
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