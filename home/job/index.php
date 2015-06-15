<?php
	include_once("../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=new Model_Subtable('sub_job');

	//数据保存
	if($_REQUEST['a']=='add'){
		if(method_is('post')){
			$data=$_POST;
			$res=$model->add($data);
			echo json_encode($res);die();
		}else{
			$id=(int)$_GET['id'];
			if($id){
				$smarty->assign('vo',$model->find($id));
			}
			$smarty->setTpl('job/templates/add.html')->display();die();
		}
	}

	//数据删除
	if($_REQUEST['a']=='del'){
		$res=$model->del($_POST['id']);
		echo json_encode($res);die();
	}

	//数据删除
	if($_REQUEST['a']=='delsign'){
		$submitModel=new Model_Subtable('sub_job_submit');
		$res=$submitModel->del($_REQUEST['id']);
		echo json_encode($res);die();
	}

	//领取列表
	if($_REQUEST['a']=='sign'){
		$signModel=new Model_Subtable('sub_jobsign');
		$id=(int)$_GET['id'];
		$condition=array();
		$condition[]=" jid='{$id}' ";
		if($condition) $filter['where'] = implode('and',$condition);
		$filter['order'] = " id desc ";
		$data = $signModel->paginate($filter,'*',common_pg('p'),10);
		$listArr = $data['data'];
		
		$userModel=new Model_Subtable('sub_user');
		foreach($listArr as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$listArr[$key]['uname']=$userRow['nickname'];
			$listArr[$key]['uphone']=$userRow['username'];
			$userRow['sex']==1 ? $listArr[$key]['usex']='男' : $listArr[$key]['usex']='女';
		}
		$smarty->assign('list',$listArr);
		$smarty->assign('page',$signModel->pager($data['pager']));
		//人数统计
		$res1=$signModel->field('count(*) as countnum')->where("jid='{$id}'")->dataRow();
		$smarty->assign('countnum',$res1['countnum']);
		$smarty->setTpl('job/templates/sign.html')->display();die;
	}

	//取消领取列表
	if($_REQUEST['a']=='sign_cancel'){
		$signModel=new Model_Subtable('sub_jobsign_cancel');
		$id=(int)$_GET['id'];
		$condition=array();
		$condition[]=" jid='{$id}' ";
		if($condition) $filter['where'] = implode('and',$condition);
		$filter['order'] = " id desc ";
		$data = $signModel->paginate($filter,'*',common_pg('p'),10);
		$listArr = $data['data'];
		
		$userModel=new Model_Subtable('sub_user');
		foreach($listArr as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$listArr[$key]['uname']=$userRow['nickname'];
			$listArr[$key]['uphone']=$userRow['username'];
			$userRow['sex']==1 ? $listArr[$key]['usex']='男' : $listArr[$key]['usex']='女';
		}
		$smarty->assign('list',$listArr);
		$smarty->assign('page',$signModel->pager($data['pager']));
		//人数统计
		$res1=$signModel->field('count(*) as countnum')->where("jid='{$id}'")->dataRow();
		$smarty->assign('countnum',$res1['countnum']);
		$smarty->setTpl('job/templates/sign_cancel.html')->display();die;
	}

	//提报列表
	if($_REQUEST['a']=='submit'){
		$submitModel=new Model_Subtable('sub_job_submit');
		$id=(int)$_GET['id'];
		$condition=array();
		$condition[]=" jid='{$id}' ";
		if($_GET['keywords']) $condition[]=" name like '%".common_pg('keywords')."%' ";
		if($_GET['phone']) $condition[]=" phone = '".common_pg('phone')."' ";
		if($condition) $filter['where'] = implode('and',$condition);
		$filter['order'] = " id desc ";
		$data = $submitModel->paginate($filter,'*',common_pg('p'),10);
		$listArr = $data['data'];
		
		$userModel=new Model_Subtable('sub_user');
		foreach($listArr as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$listArr[$key]['uname']=$userRow['nickname'];
			$listArr[$key]['uphone']=$userRow['username'];
			$userRow['sex']==1 ? $listArr[$key]['usex']='男' : $listArr[$key]['usex']='女';
		}
		$smarty->assign('list',$listArr);
		$smarty->assign('page',$submitModel->pager($data['pager']));
		//人数统计
		$res1=$submitModel->field('count(*) as countnum')->where("jid='{$id}'")->dataRow();
		$smarty->assign('countnum',$res1['countnum']);
		$smarty->setTpl('job/templates/submit.html')->display();die;
	}

	//向单个用户发消息
	if($_REQUEST['a']=='send_msg'){
		if(method_is('post')){
			$id=$_POST['id'];
			$userModel=new Model_Subtable('sub_user');
			$userRow=$userModel->find($id);
			$configModel=new Model_CustomerConfig();
			$configModel->sendCustomerMsg('云姐对你说：'.$_POST['content'],$userRow['fromuser']);
		}
		die;
	}

	//数据列表
	$condition=array();
	if($_GET['keywords']) $condition[]=" title like '%".common_pg('keywords')."%' ";
	if($condition) $filter['where'] = implode('and',$condition);
	$filter['order'] = " ordernum desc,id desc ";
	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),20);
	}
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->pager($data['pager']));
	$smarty->setTpl('job/templates/index.html')->display();