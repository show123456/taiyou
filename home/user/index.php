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
			$drow=D('s_district')->where("DistrictId=".$vo['did'])->order('DistrictId asc')->dataRow();
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
				$configModel->sendCustomerMsg('你的身份证照片与所填写身份证号码不符，审核未通过，请重新上传身份证照片',$uRow['fromuser']);
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
			$smarty->setTpl('user/templates/cz.html')->display();
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
	$condition=array();
	$condition[]=" id!=1 ";
	if($_GET['type']==1){
		$condition[]=" type in (1,3)";//个人用户或客服
		//人数统计
		$res1=$model->field('count(*) as countnum')->where("type in (1,3) and pid!=0")->dataRow();
		$smarty->assign('countnum',$res1['countnum']);
	}else{
		$condition[]=" type=2";
		//人数统计
		$res1=$model->field('count(*) as countnum')->where("type = 2 and pid!=0")->dataRow();
		$smarty->assign('countnum',$res1['countnum']);
	}
	$condition[]=" pid!=0";

	if($_GET['keywords']) $condition[]=" nickname like '%".common_pg('keywords')."%' ";
	if($_GET['nicheng']) $condition[]=" nicheng like '%".common_pg('nicheng')."%' ";
	if($condition) $filter['where'] = implode(' and ',$condition);
	$filter['order'] = " id desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	$memberModel=new Model_Member();
	foreach($listArr as $key=>$value){
		//微信头像
		$listArr[$key]['headPic']=$memberModel->getPic($value['fromuser']);
	}
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->pager($data['pager']));
	$smarty->setTpl('user/templates/index.html')->display();