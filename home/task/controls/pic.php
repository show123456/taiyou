<?php
$model=new Model_Subtable('sub_pic');
//数据保存
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$data['info']['fromuser']=$_SESSION['picuser']['fromuser'];
		$data['info']['name']=$_SESSION['picuser']['nickname'];
		$data['info']['head_pic']=$_SESSION['picuser']['headimgurl'];
		$res=$model->add($data);
		$res ? die('suc') : die('err');
	}
}

//鲜花
if($_REQUEST['a']=='flower'){
	$feModel=new Model_Subtable('sub_pic_fe');
	$pid=(int)$_GET['pid'];
	$feRow=$feModel->where("type=1 and pid={$pid} and fromuser='".$_SESSION['picuser']['fromuser']."'")->dataRow();
	if($feRow){
		die('err');
	}else{
		$data['info'][type]=1;
		$data['info'][pid]=$pid;
		$data['info'][fromuser]=$_SESSION['picuser']['fromuser'];
		$feModel->add($data);
		//鲜花数+1
		$model->query("update sub_pic set `fnum`=`fnum`+1 where id=$pid");
		die('suc');
	}
}

//鸡蛋
if($_REQUEST['a']=='egg'){
	$feModel=new Model_Subtable('sub_pic_fe');
	$pid=(int)$_GET['pid'];
	$feRow=$feModel->where("type=2 and pid={$pid} and fromuser='".$_SESSION['picuser']['fromuser']."'")->dataRow();
	if($feRow){
		die('err');
	}else{
		$data['info'][type]=2;
		$data['info'][pid]=$pid;
		$data['info'][fromuser]=$_SESSION['picuser']['fromuser'];
		$feModel->add($data);
		//鸡蛋数+1
		$model->query("update sub_pic set `enum`=`enum`+1 where id=$pid");
		die('suc');
	}
}

if($_REQUEST['a']=='index'){
	$list=$model->order('is_up desc,up_time desc,id desc')->dataArr();
	foreach($list as $key=>$value){
		$list[$key]['introduce']=cut_str(deletehtml($value['introduce']),15);
	}
	$smarty->assign('list',$list);
	$smarty->setLayout('')->setTpl('mobile/templates/pic_index.html')->display();die;
}

//评论
if($_REQUEST['a']=='reply'){
	$replyModel=new Model_Subtable('sub_pic_reply');
	if($_GET['pid'] && $_GET['content']){
		$memberModel=new Model_Member();
		$data['num']['pid']=$_GET['pid'];
		$data['info']['fromuser']=$_SESSION['picuser']['fromuser'];
		$data['info']['name']=$_SESSION['picuser']['nickname'];
		$data['info']['head_pic']=$_SESSION['picuser']['headimgurl'];
		$data['str']['content']=$_GET['content'];
		$res=$replyModel->add($data);
		$res ? die('suc') : die('err');
	}
}

if($_REQUEST['a']=='detail'){
	$vo=$model->find($_GET['id']);
	//拉取个人注册信息里的昵称
	if($vo['fromuser']){
		$userModel=D('sub_user');
		$userRow=$userModel->where("fromuser='".$vo['fromuser']."'")->dataRow();
		if($userRow['nicheng'])$vo['name']=$userRow['nicheng'];
	}
	$smarty->assign('vo',$vo);
	//评论列表
	$replyModel=new Model_Subtable('sub_pic_reply');
	$replyList=$replyModel->where("pid=".$_GET['id'])->order('id desc')->limit('5')->dataArr();
	$smarty->assign('replyList',$replyList);
	$smarty->setLayout('')->setTpl('mobile/templates/pic_detail.html')->display();die;
}

if($_REQUEST['a']=='ajax_reply'){
	$replyModel=new Model_Subtable('sub_pic_reply');
	$pageSize=5;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;	
	$listArr = $replyModel->where("pid=".(int)$_GET['pid'])->order('id desc')->limit($limitStr)->dataArr();
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