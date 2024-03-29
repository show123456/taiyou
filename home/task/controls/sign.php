<?php
$model=new Model_Subtable('sub_sign');
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$data['info'][uid]=$userRow['id'];
		//获取该任务发布时的经纬度（手机端）
		$locationModel=new Model_Subtable('member_location');
		$locationRow=$locationModel->where("fromuser='".$userRow['fromuser']."'")->dataRow();
		$data['info'][latitude]=$locationRow['latitude'];
		$data['info'][longitude]=$locationRow['longitude'];
		
		$res=$model->add($data);
		$res ? die('suc') : die('err');
	}else{
		//省
		$pmodel=new Model_Subtable('s_province');
		$smarty->assign('parr',$pmodel->dataArr());
		
		if($_GET['id']){
			$smarty->assign('vo',$model->find($_GET['id']));
		}
		$smarty->setLayout('')->setTpl('mobile/templates/task_add.html')->display();die;
	}
}

if($_REQUEST['a']=='detail'){
	$vo=$model->find($_GET['id']);
	$smarty->assign('vo',$vo);
	//公司名称简介
	$smarty->assign('uRow',$userModel->field('nickname,introduce')->where("id=".$vo['uid'])->dataRow());
	//已报名人数
	$signModel=new Model_Subtable('sub_sign');
	$signRow=$signModel->field('count(*) as countnum')->where("tid=".$_GET['id'])->dataRow();
	$smarty->assign('signNum',$signRow['countnum']);
	//评论列表
	$replyModel=new Model_Subtable('sub_reply');
	$replyList=$replyModel->order('id desc')->limit('5')->dataArr();
	$smarty->assign('replyList',$replyList);
	$smarty->setLayout('')->setTpl('mobile/templates/task_detail.html')->display();die;
}
//申请职位
if($_REQUEST['a']=='sign'){
	$signModel=new Model_Subtable('sub_sign');
	$data['num']['tid']=$_GET['tid'];
	$data['num']['uid']=$userRow['id'];
	$s=$signModel->where("tid=".$data['num']['tid']." and uid=".$data['num']['uid'])->dataRow();
	if($s){
		die('ybm');
	}else{
		$res=$signModel->add($data);
		$res ? die('suc') : die('err');
	}
}
//评论
if($_REQUEST['a']=='reply'){
	$replyModel=new Model_Subtable('sub_reply');
	if($_GET['tid'] && $_GET['content']){
		$memberModel=new Model_Member();
		$data['num']['tid']=$_GET['tid'];
		$data['num']['uid']=$userRow['id'];
		$data['info']['name']=$userRow['nicheng'];
		$data['info']['head_pic']=$memberModel->getPic($userRow['fromuser']);
		$data['str']['content']=$_GET['content'];
		$res=$replyModel->add($data);
		$res ? die('suc') : die('err');
	}
}

if($_REQUEST['a']=='index'){
	//省
	$pmodel=new Model_Subtable('s_province');
	$smarty->assign('parr',$pmodel->dataArr());
	
	$pageSize=1;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;
	//搜索条件
	if($_GET['did'] && $_GET['did']!='clear'){
		$didWhere=" and did=".$_GET['did'];
	}else{
		$didWhere="";
	}
	
	$listArr = $model->where("sh_status=1 $didWhere")->order('id desc')->limit($limitStr)->dataArr();
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),5);
		//所在市 区
		/* $cmodel=new Model_Subtable('s_city');
		$crow=$cmodel->where("CityId=".$value['cid'])->dataRow(); */
		$dmodel=new Model_Subtable('s_district');
		$drow=$dmodel->where("DistrictId=".$value['did'])->dataRow();
		$listArr[$key]['addr']=$drow['DistrictName'];
		//所属公司
		$urow=$userModel->find($value['uid']);
		$listArr[$key]['nickname']=$urow['nickname'];
	}
	if($_GET['p']){
		if($listArr){
			echo json_encode($listArr);die;
		}else{
			echo json_encode('err');die;
		}
	}else{
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/task_index.html')->display();die;
	}
}

if($_REQUEST['a']=='ajax_reply'){
	$replyModel=new Model_Subtable('sub_reply');
	$pageSize=5;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;	
	$listArr = $replyModel->where("tid=".(int)$_GET['tid'])->order('id desc')->limit($limitStr)->dataArr();
	/* foreach($listArr as $key=>$value){
		//$listArr[$key]['title']=cut_str(deletehtml($value['title']),5);
		$listArr[$key]['addtime']=substr($value['addtime'],0,16);
	} */
	if($listArr){
		echo json_encode($listArr);die;
	}else{
		echo json_encode('err');die;
	}
}