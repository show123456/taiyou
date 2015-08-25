<?php
	include_once("../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=new Model_Subtable('sub_user');
	
	//详细信息
	if($_REQUEST['a']=='detail'){
		$id=(int)$_GET['id'];
		$vo=$model->find($id);
		if($vo['pid'] && $vo['cid'] && $vo['did']){
			//所属省市区
			$prow=D('s_province')->where("ProvinceID=".$vo['pid'])->dataRow();
			$crow=D('s_city')->where("CityId=".$vo['cid'])->dataRow();
			$drow=D('s_district')->where("DistrictID=".$vo['did'])->order('DistrictID asc')->dataRow();
			$vo['province']=$prow['ProvinceName'];
			$vo['city']=$crow['CityName'];
			$vo['district']=$drow['DistrictName'];
		}
		$smarty->assign('vo',$vo);
		$smarty->setTpl('user/templates/detail.html')->display();die();
	}
	
	//加V
	if($_REQUEST['a']=='addv'){
		$data=$_POST;
		$res=$model->add($data);
		if($res){
			if($data['info']['is_see']=='1' && $data['fromuser']){
				$configModel=new Model_CustomerConfig();
				$configModel->sendCustomerMsg('亲~，你的个人资料已通过云姐的审核；审核通过后，除绑定银行卡信息外，其它信息均无法修改；遇手机号码有变动时，请在小窗口第一时间与云姐联系，云姐对个人信息审核无误后，方可进行修改。',$data['fromuser']);
			}
			echo json_encode('suc');die;
		}
	}
	
	//删除身份证照片
	if($_REQUEST['a']=='del_pic'){
		$uRow=$model->find($_GET['id']);
		
		$data['num']['id']=$_GET['id'];
		$data['str']['pic']='';
		$res=$model->add($data);
		@unlink('../../data/image_c/'.$uRow['pic']);
		if($res){
			$configModel=new Model_CustomerConfig();
			if($uRow['fromuser']){
				$configModel->sendCustomerMsg('你的照片审核未通过，请重新上传完整身份证正面照片',$uRow['fromuser']);
			}
			die('suc');
		}else{
			die('err');
		}
	}
	
	//可查看用户详细信息
	if($_REQUEST['a']=='addsee'){
		$data=$_POST;
		$res=$model->add($data);
		if($res){
			echo json_encode('suc');die;
		}
	}

	if($_REQUEST['a']=='detail_company'){
		$id=(int)$_GET['id'];
		$vo=$model->find($id);
		if($vo['pid'] && $vo['cid'] && $vo['did']){
			//所属省市区
			$prow=D('s_province')->where("ProvinceID=".$vo['pid'])->dataRow();
			$crow=D('s_city')->where("CityId=".$vo['cid'])->dataRow();
			$drow=D('s_district')->where("DistrictId=".$vo['did'])->dataRow();
			$vo['province']=$prow['ProvinceName'];
			$vo['city']=$crow['CityName'];
			$vo['district']=$drow['DistrictName'];
		}
		//所属行业
		if($vo['industry_id']){
			$indRow=D('sub_industry')->find($vo['industry_id']);
			$vo['industry']=$indRow['name'];
		}
		$smarty->assign('vo',$vo);
		$smarty->setTpl('user/templates/detail_company.html')->display();die();
	}
	
	//数据删除
	if($_REQUEST['a']=='del'){
		$res=$model->del($_POST['id']);
		echo json_encode($res);die();
	}
	
	//设置客服
	if($_REQUEST['a']=='kf'){
		$data['info'][type]=(int)$_GET['type'];
		$data['info'][id]=(int)$_GET['id'];
		$res=$model->add($data);
		echo $res;die();
	}

	//直接充值
	if($_REQUEST['a']=='cz'){
		if(method_is('post')){
			$uid=(int)$_POST['uid'];
			$fee=(int)$_POST['fee'];
			$desc=$_POST['desc'];
			//用户金额增加
			$res=$model->query("update sub_user set money = money + ".$fee." where id='".$uid."' limit 1");
			if($res){
				//写金额日志
				$data1=array();
				$data1['info']['type']=6;//系统充值
				$data1['info']['uid']=$uid;
				$data1['info']['money']=$fee;
				$data1['info']['desc']=$desc;
				D('sub_money_log')->add($data1);
				echo json_encode('suc');die;
			}else{
				echo json_encode('err');die;
			}
		}else{
			$id=(int)$_GET['id'];
			if($id){
				$smarty->assign('vo',$model->find($id));
			}
			$smarty->setTpl('user/templates/cz.html')->display();die;
		}
	}

	//向单个用户发消息
	if($_REQUEST['a']=='send_msg'){
		if(method_is('post')){
			$id=$_POST['id'];
			$userModel=new Model_Subtable('sub_user');
			$userRow=$userModel->find($id);
			$configModel=new Model_CustomerConfig();
			$configModel->sendCustomerMsg($_POST['content'],$userRow['fromuser']);
		}
		die;
	}
	
	//数据列表
	$dmodel=new Model_Subtable('s_district');
	$condition=array();
	$condition[]=" id!=1 ";
	if($_GET['did']){
		$condition[]=" did='".$_GET['did']."' ";
		$count_district_str=" and did='".$_GET['did']."' ";
	}
	
	if($_GET['type']==1){
		$condition[]=" type in (1,3)";//个人用户或客服
	}else{
		$condition[]=" type=2";
	}
	
	//信息完善
	if($_GET['wanshan']==1){
		$condition[]=" pic!='' ";
	}elseif($_GET['wanshan']==2){
		$condition[]=" pic='' ";
	}
	if($_GET['bank_card']==1){
		$condition[]=" bank_name!='' and bank_card!='' ";
	}elseif($_GET['bank_card']==2){
		$condition[]=" bank_name is Null and bank_card is Null ";
	}

	if($_GET['keywords']) $condition[]=" nickname like '%".common_pg('keywords')."%' ";
	if($_GET['nicheng']) $condition[]=" nicheng like '%".common_pg('nicheng')."%' ";
	if($_GET['username']) $condition[]=" username = '".common_pg('username')."' ";
	if($_GET['cardnum']) $condition[]=" cardnum = '".common_pg('cardnum')."' ";
	if($_GET['industry_id']) $condition[]=" industry_id = '".common_pg('industry_id')."' ";
	//搜索微信昵称
	if($_GET['wx_nickname']){
		$memberArr=D('member')->where("nickname='".$_GET['wx_nickname']."'")->dataArr();
		if($memberArr){
			$fromuserArr=array();
			foreach($memberArr as $mv){
				$fromuserArr[]=$mv['fromuser'];
			}
			$condition[]=" fromuser in ('".implode("','",$fromuserArr)."') ";
		}else{
			$condition[]=" fromuser='no' ";
		}
	}
	if($_GET['is_see']=='1'){
		$condition[]=" is_see=1 ";
	}elseif($_GET['is_see']=='2'){
		$condition[]=" is_see=0 ";
	}
	if($condition) $filter['where'] = implode(' and ',$condition);
	
	//人数统计
	$res1=$model->field('count(*) as countnum')->where($filter['where'])->dataRow();
	$smarty->assign('countnum',$res1['countnum']);
	
	$filter['order'] = " id desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	$memberModel=new Model_Member();
	foreach($listArr as $key=>$value){
		//微信头像
		$listArr[$key]['headPic']=$memberModel->getPic($value['fromuser']);
		//所属区
		$drow=$dmodel->where("DistrictID='".$value['did']."'")->dataRow();
		$listArr[$key]['district']=$drow['DistrictName'];
	}
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->pager($data['pager']));
	
	//苏州下的区
	$smarty->assign('darr',$dmodel->where("CityID=78")->order('DistrictID asc')->dataArr());
	//本日平台活跃量
	$now_date=date('Y-m-d');//$now_date='2015-05-21';
	$arr=D('message')->field('id,fromuser')->where("left(create_date,10) = '".$now_date."' group by fromuser")->dataArr();
	$activenum=count($arr);
	$smarty->assign('activenum',$activenum);
	$smarty->setTpl('user/templates/index.html')->display();