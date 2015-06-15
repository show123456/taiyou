<?php
$model=new Model_Subtable('sub_task');
//企业的历史发布
if($_REQUEST['a']=='index'){
	$pageSize=10;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;
	$listArr = $model->where("is_js=1 and uid=".$userRow['id'])->order('id desc')->dataArr();//无分页数据
	if(!$listArr){
		$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
	}
	//已结算资讯条数
	$taskRow=$model->field("count(*) as countnum")->where("is_js=1 and uid=".$userRow['id'])->dataRow();
	$smarty->assign('task_num',$taskRow['countnum']);
	
	$dmodel=new Model_Subtable('s_district');
	$signModel=new Model_Subtable('sub_sign');
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),15);
		$drow=$dmodel->where("DistrictId='".$value['did']."'")->dataRow();
		$listArr[$key]['addr']=$drow['DistrictName'];
	}
	
	if($_GET['p']){
		if($listArr){
			echo json_encode($listArr);die;
		}else{
			echo json_encode('err');die;
		}
	}else{
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/task_company_index.html')->display();die;
	}
}
//企业的报名管理（未结算的职位）
if($_REQUEST['a']=='subindex'){
	$pageSize=10;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;
	$listArr = $model->where("is_js=0 and uid=".$userRow['id'])->order('id desc')->dataArr();//无分页数据
	if(!$listArr){
		$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
	}
	//已结算资讯条数
	$taskRow=$model->field("count(*) as countnum")->where("is_js=0 and uid=".$userRow['id'])->dataRow();
	$smarty->assign('task_num',$taskRow['countnum']);
	
	$dmodel=new Model_Subtable('s_district');
	$signModel=new Model_Subtable('sub_sign');
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),15);
		$drow=$dmodel->where("DistrictId='".$value['did']."'")->dataRow();
		$listArr[$key]['addr']=$drow['DistrictName'];
		//报名人数
		$signRow=$signModel->field("count(*) as countnum")->where("tid=".$value['id'])->dataRow();
		$listArr[$key]['sign_num']=$signRow['countnum'];
	}
	
	if($_GET['p']){
		if($listArr){
			echo json_encode($listArr);die;
		}else{
			echo json_encode('err');die;
		}
	}else{
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/task_company_subindex.html')->display();die;
	}
}
//报名列表
if($_REQUEST['a']=='sign_index'){
	$signModel=new Model_Subtable('sub_sign');
	if(method_is('post')){
		/* $data1['info']['id']=$_POST['id'];
		$data1['info']['is_valid']=$_POST['is_valid'];
		$signModel->add($data1); */
	}else{
		$listArr=$signModel->where("tid=".$_GET['tid'])->order('distance asc')->limit('30')->dataArr();
		//已报名人数
		$signRow=$signModel->field('count(*) as countnum')->where("tid=".$_GET['tid']." and is_valid!=2")->dataRow();
		$smarty->assign('signNum',$signRow['countnum']);
		
		if($listArr){
			$memberModel=new Model_Member();
			$dmodel=new Model_Subtable('s_district');
			foreach($listArr as $key=>$value){
				//从快照中获取用户信息
				$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
			
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['nickname']=$uRow['nickname'];
				//是否可看手机号
				if($userRow['is_see']==0) $listArr[$key]['username']=substr($listArr[$key]['username'],0,3).'***'.substr($listArr[$key]['username'],-4);
				//距离处理
				$listArr[$key]['distance'] > 500 ? $listArr[$key]['distance']='未知' : $listArr[$key]['distance']=$listArr[$key]['distance'].'km';
				//序号处理
				$listArr[$key]['xuhao']=$key+1;
				//所属区
				$drow=$dmodel->where("DistrictId='".$uRow['did']."'")->dataRow();
				$listArr[$key]['district']=$drow['DistrictName'];
			}
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/sign_index.html')->display();die;
	}
}
if($_REQUEST['a']=='sign_index_ajax'){
	$signModel=new Model_Subtable('sub_sign');
	$pageSize=30;$p=$_GET['p'];$limitStr = ($p-1)*$pageSize.','.$pageSize;	
	$listArr=$signModel->where("tid=".$_GET['tid'])->order('distance asc')->limit($limitStr)->dataArr();
	if($listArr){
		foreach($listArr as $key=>$value){
			//从快照中获取用户信息
			$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
			
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			//是否可看手机号
			if($userRow['is_see']==0) $listArr[$key]['username']=substr($listArr[$key]['username'],0,3).'***'.substr($listArr[$key]['username'],-4);
			//距离处理
			$listArr[$key]['distance'] > 500 ? $listArr[$key]['distance']='未知' : $listArr[$key]['distance']=$listArr[$key]['distance'].'km';
			//序号处理
			$listArr[$key]['xuhao']=($p-1)*10+$key+1;
			//所属区
			$drow=$dmodel->where("DistrictId='".$uRow['did']."'")->dataRow();
			$listArr[$key]['district']=$drow['DistrictName'];
		}
		echo json_encode($listArr);die;
	}else{
		echo json_encode('err');die;
	}
}
//确认报名有效无效
if($_REQUEST['a']=='sign_valid'){
	$data=$_POST;
	foreach($data['info'] as $k=>$v){
		$temp_arr=explode('_',$k);
		if($v==2){
			$valid2IdRow[]=$temp_arr[1];
		}else{
			$valid1IdRow[]=$temp_arr[1];
		}
	}
	if($valid2IdRow)
		$model->query('update sub_sign set is_valid=2 where id in ('.implode(',',$valid2IdRow).')');
	if($valid1IdRow)
		$model->query('update sub_sign set is_valid=1 where id in ('.implode(',',$valid1IdRow).')');
	die('suc');
}
//结算页面
if($_REQUEST['a']=='sign_js'){
	$signModel=new Model_Subtable('sub_sign');
	$logModel=new Model_Subtable('sub_money_log');
	if(method_is('post')){
		$data=$_POST;
		//sign报名表是否结算状态修改
		foreach($data['info'] as $k=>$v){
			$temp_arr=explode('_',$k);
			if($v==2){
				$js2IdRow[]=$temp_arr[1];
			}else{
				$js1IdRow[]=$temp_arr[1];
			}
		}
		if($js2IdRow)
			$model->query('update sub_sign set is_js=2 where id in ('.implode(',',$js2IdRow).')');
		if($js1IdRow)
			$model->query('update sub_sign set is_js=1 where id in ('.implode(',',$js1IdRow).')');
		
		$taskRow=$model->field('id,pay_type,is_js')->where("id=".$data['tid'])->dataRow();
		//1现金日结，用户金额不增加，2转账日结，用户金额增加
		if($taskRow['pay_type']==2 && $taskRow['is_js']==0){
			$listArr=$signModel->where("is_js=1 and tid=".$data['tid'])->dataArr();
			foreach($listArr as $k=>$v){
				//用户金额增加
				$signModel->query("update sub_user set money = money + ".$v['fact_money']." where id='".$v['uid']."'");
				//写金额日志
				$logData=array();
				$logData['info']['type']=3;
				$logData['info']['uid']=$v['uid'];
				$logData['info']['money']=$v['fact_money'];
				$logData['info']['desc']=$v['tid'];
				$logModel->add($logData);
			}
		}
		//更改职位结算状态
		$taskData['num']['id']=$data['tid'];
		$taskData['num']['is_js']=1;
		$taskData['num']['is_shut']=1;
		$model->add($taskData);
		die('suc');
	}else{
		$listArr=$signModel->where("is_qd=1 and tid=".$_GET['tid'])->order('distance asc')->limit('30')->dataArr();
		//应结算人数
		$listRow=$signModel->field("count(*) as countnum")->where("is_qd=1 and tid=".$_GET['tid'])->dataRow();
		$smarty->assign('countnum',$listRow['countnum']);
		
		if($listArr){
			foreach($listArr as $key=>$value){
				//从快照中获取用户信息
				$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['nickname']=$uRow['nickname'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
				//是否可看手机号
				if($userRow['is_see']==0) $listArr[$key]['username']=substr($uRow['username'],0,3).'***'.substr($uRow['username'],-4);
				//序号处理
				$listArr[$key]['xuhao']=$key+1;
			}
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/sign_js.html')->display();die;
	}
}
if($_REQUEST['a']=='sign_js_ajax'){
	$signModel=new Model_Subtable('sub_sign');
	$pageSize=30;$p=$_GET['p'];$limitStr = ($p-1)*$pageSize.','.$pageSize;
	$listArr=$signModel->where("is_qd=1 and tid=".$_GET['tid'])->order('distance asc')->limit($limitStr)->dataArr();
	if($listArr){
		foreach($listArr as $key=>$value){
			//从快照中获取用户信息
			$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			//是否可看手机号
			if($userRow['is_see']==0) $listArr[$key]['username']=substr($uRow['username'],0,3).'***'.substr($uRow['username'],-4);
			//序号处理
			$listArr[$key]['xuhao']=($p-1)*10+$key+1;
		}
		echo json_encode($listArr);die;
	}else{
		echo json_encode('err');die;
	}
}
//ajax是否正常发放
if($_REQUEST['a']=='refuse_js'){
	$signModel=new Model_Subtable('sub_sign');
	$data1['info']['id']=$_POST['id'];
	$data1['info']['is_js']=$_POST['is_js'];
	$signModel->add($data1);
	die('suc');
}
//报名管理中间页
if($_REQUEST['a']=='sign_mid'){
	$signModel=new Model_Subtable('sub_sign');
	//是否已结算
	if(!$_GET['tid']) die('参数丢失');
	$taskRow=$model->find($_GET['tid']);
	if($taskRow['is_js']==1){
		$listArr=$signModel->where("tid=".$_GET['tid'])->dataArr();
		if($listArr){
			foreach($listArr as $key=>$value){
				$uRow=$userModel->find($value['uid']);
				$listArr[$key]['nickname']=$uRow['nickname'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			}
		}
		$smarty->assign('list',$listArr);
		$smarty->assign('pay_type',$taskRow['pay_type']);
		$smarty->setLayout('')->setTpl('mobile/templates/sign_index_js.html')->display();die;
	}else{
		$smarty->assign('tid',$_GET['tid']);
		$smarty->setLayout('')->setTpl('mobile/templates/sign_mid.html')->display();die;
	}
}
//签到页
if($_REQUEST['a']=='sign_qd'){
	$signModel=new Model_Subtable('sub_sign');
	$logModel=new Model_Subtable('sub_money_log');
	if(method_is('post')){
		$data=$_POST;
		foreach($data['info'] as $k=>$v){
			$temp_arr=explode('_',$k);
			if($v==2){
				$qd2IdRow[]=$temp_arr[1];
			}else{
				$qd1IdRow[]=$temp_arr[1];
			}
		}
		if($qd2IdRow)
			$model->query('update sub_sign set is_qd=2 where id in ('.implode(',',$qd2IdRow).')');
		if($qd1IdRow)
			$model->query('update sub_sign set is_qd=1 where id in ('.implode(',',$qd1IdRow).')');
		die('suc');
	}else{
		$listArr=$signModel->where("is_valid=1 and tid=".$_GET['tid'])->order('distance asc')->limit('30')->dataArr();
		//应到人数
		$listRow=$signModel->field("count(*) as countnum")->where("is_valid=1 and tid=".$_GET['tid'])->dataRow();
		$smarty->assign('countnum',$listRow['countnum']);
		
		if($listArr){
			foreach($listArr as $key=>$value){
				//从快照中获取用户信息
				$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['nickname']=$uRow['nickname'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
				//是否可看手机号
				if($userRow['is_see']==0) $listArr[$key]['username']=substr($uRow['username'],0,3).'***'.substr($uRow['username'],-4);
				//序号处理
				$listArr[$key]['xuhao']=$key+1;
			}
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/sign_qd.html')->display();die;
	}
}
if($_REQUEST['a']=='sign_qd_ajax'){
	$signModel=new Model_Subtable('sub_sign');
	$pageSize=30;$p=$_GET['p'];$limitStr = ($p-1)*$pageSize.','.$pageSize;
	$listArr=$signModel->where("is_valid=1 and tid=".$_GET['tid'])->order('distance asc')->limit($limitStr)->dataArr();
	if($listArr){
		foreach($listArr as $key=>$value){
			//从快照中获取用户信息
			$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			//是否可看手机号
			if($userRow['is_see']==0) $listArr[$key]['username']=substr($uRow['username'],0,3).'***'.substr($uRow['username'],-4);
			//序号处理
			$listArr[$key]['xuhao']=($p-1)*10+$key+1;
		}
		echo json_encode($listArr);die;
	}else{
		echo json_encode('err');die;
	}
}