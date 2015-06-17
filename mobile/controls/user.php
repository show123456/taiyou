<?php
$model=new Model_Subtable('sub_user');
//添加银行卡号
if($_REQUEST['a']=='add_bank'){
	$data=$_POST;
	$data['info']['id']=$userRow['id'];
	$res=$model->add($data);
	$res ? die('suc') : die('err');
}
//个人信息完善
if($_REQUEST['a']=='user_add_personal'){
	if(method_is('post')){
		$data=$_POST;
		//推荐人返利
		if($data['recommend_phone'] && $data['recommend_phone']!=$userRow['username']){
			$uRow=$model->where("username='".$data['recommend_phone']."'")->dataRow();
			if($uRow){
				$data['info'][recommend_phone]=$data['recommend_phone'];
				//修改推荐人积分
				$tjrScore=(int)getTjrScore();
				$data1['info'][id]=$uRow['id'];
				$data1['info'][score]=$tjrScore+$uRow['score'];
				$data1['info'][score_all]=$tjrScore+$uRow['score_all'];
				$model->add($data1);
			}
		}
		$res=$model->add($data);
		echo $res;die;
	}else{
		//苏州市下的区
		$dmodel=new Model_Subtable('s_district');
		$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
		//微信头像
		$memberModel=new Model_Member();
		$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
		
		$smarty->assign('vo',$model->find($userRow['id']));
		$smarty->setLayout('')->setTpl('mobile/templates/user_add_personal.html')->display();die;
	}
}
//企业信息完善
if($_REQUEST['a']=='user_add_company'){
	if(method_is('post')){
		$data=$_POST;
		$res=$model->add($data);
		$res ? die('suc') : die('err');
	}else{
		//苏州市下的区
		$dmodel=new Model_Subtable('s_district');
		$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
		//微信头像
		$memberModel=new Model_Member();
		$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
		//行业
		$indModel=D('sub_industry');
		$smarty->assign('indArr',$indModel->order('id asc')->dataArr());
		$smarty->assign('vo',$model->find($userRow['id']));
		$smarty->setLayout('')->setTpl('mobile/templates/user_add_company.html')->display();die;
	}
}

if($_REQUEST['a']=='user_index'){
	//微信头像
	$memberModel=new Model_Member();
	$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
	
	$smarty->assign('userRow',$userRow);
	
	if($userRow['type']==2){
		$smarty->setLayout('')->setTpl('mobile/templates/user_index_company.html')->display();die;
	}else{
		$smarty->setLayout('')->setTpl('mobile/templates/user_index_personal.html')->display();die;
	}
}

//推荐人返利积分
function getTjrScore(){
	$scoreModel=new Model_SubScore('sub_score');
	return $scoreModel->tjr();
}

//城市级联
if($_REQUEST['a']=='get_citys'){
	$cmodel=new Model_Subtable('s_city');
	$carr=$cmodel->where("ProvinceID=".$_GET['pid'])->dataArr();
	echo json_encode($carr);die;
}
if($_REQUEST['a']=='get_districts'){
	$dmodel=new Model_Subtable('s_district');
	$darr=$dmodel->where("CityID=".$_GET['cid'])->order('DistrictId asc')->dataArr();
	echo json_encode($darr);die;
}

//提现
if($_REQUEST['a']=='tx'){
	$fee=(int)$_POST['fee'];
	if($userRow['money'] >= $fee && $fee >= 10){
		//提现表添加数据
		$outData['info']['uid']=$userRow['id'];
		$outData['info']['money']=$fee;
		$outRes=D('sub_out')->add($outData);
		//用户金额减少
		if($outRes && $userRow['id']){
			$userModel->query("update sub_user set money = money - ".$fee." where id='".$userRow['id']."'");
			//写金额日志
			$data1['info']['type']=2;
			$data1['info']['uid']=$userRow['id'];
			$data1['info']['money']=0 - $fee;
			$data1['info']['desc']='提现表id_'.$outRes;
			D('sub_money_log')->add($data1);
			die('suc');
		}else{
			die('err');
		}
	}
	die('err');
}

//退出登录
if($_REQUEST['a']=='logout'){
	unset($_SESSION['tyuser']);
	unset($_SESSION['picuser']);
	setCookie('tyuid','',time()-1);
	$_COOKIE['tyuid']='';
}

//金库
if($_REQUEST['a']=='jk'){
	$moneyLogModel=D('sub_money_log');
	$moneyLogRow=$moneyLogModel->field("sum(money) as totalmoney")->where("(`type`=3 or `type`=0) and uid='".$userRow['id']."' and addtime>='".date('Y-m-d')." 00:00:00'")->dataRow();
	$smarty->assign('totalmoney',$moneyLogRow['totalmoney']);
	$smarty->setLayout('')->setTpl('mobile/templates/user_jk.html')->display();die;
}

//由id获取个人基本信息
if($_REQUEST['a']=='get_person_info'){
	$id=(int)$_GET['id'];
	if($id && $userRow['id']==1){
		$res=$userModel->field('nickname,username')->where("id='{$id}'")->dataRow();
		$result=$res['nickname'].$res['username'];
	}
	$result ? die($result) : die('err');
}