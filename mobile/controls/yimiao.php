<?php
$model=new Model_Subtable('sub_job');
if($_REQUEST['a']=='detail'){
	$jobsignModel=new Model_Subtable('sub_jobsign');
	$id=(int)$_GET['id'];
	$vo=$model->find($id);
	$smarty->assign('vo',$vo);
	
	$row=$jobsignModel->where("jid='".$id."' and uid='".$_SESSION['tyuser']['id']."'")->dataRow();
	if($row){
		//任务若已领取，去填表单
		$smarty->setLayout('')->setTpl('mobile/templates/job_form.html')->display();die;
	}else{
		//否则，去查看详情
		$smarty->setLayout('')->setTpl('mobile/templates/job_detail.html')->display();die;
	}
}
//领取任务
if($_REQUEST['a']=='lq'){
	$jobsignModel=new Model_Subtable('sub_jobsign');
	$data=array();
	$data['info']['jid']=(int)$_REQUEST['jid'];
	$data['info']['uid']=$_SESSION['tyuser']['id'];
	$jobRow=$model->find($data['info']['jid']);
	
	$row=$jobsignModel->where("jid='".$data['info']['jid']."' and uid='".$data['info']['uid']."'")->dataRow();
	//已领取
	if($row){
		die('err');
	}else{
		$res=$jobsignModel->add($data);
		//领取数+1
		$jobsignModel->query("update sub_job set num=num+1 where id='".$data['info']['jid']."'");
		//发消息
		if($res){
			$configModel=new Model_CustomerConfig();
			$configModel->sendCustomerMsg('您领取了新任务：'.$jobRow['title']."，<a href='".$jobRow['msg']."'>点击这里</a>，查看详细任务密函",$userRow['fromuser']);
			die('suc');
		}else{
			die('err');
		}
	}
}
//推送消息页
if($_REQUEST['a']=='msg'){
	$id=(int)$_GET['id'];
	$smarty->assign('vo',$model->find($id));
	$smarty->setLayout('')->setTpl('mobile/templates/job_msg.html')->display();die;
}
//取消任务
if($_REQUEST['a']=='cancel_sign'){
	$jobsignModel=new Model_Subtable('sub_jobsign');
	$submitModel=new Model_Subtable('sub_job_submit');
	$jid=(int)$_GET['jid'];
	$jobsignModel->query("delete from sub_jobsign where jid='{$jid}' and uid='".$_SESSION['tyuser']['id']."'");
	//写取消日志
	$signcancelModel=new Model_Subtable('sub_jobsign_cancel');
	$data=array();
	$data['info']['jid']=$jid;
	$data['info']['uid']=$_SESSION['tyuser']['id'];
	$signcancelModel->add($data);
	//领取数-1
	$jobsignModel->query("update sub_job set num=num-1 where id='".$jid."'");
	die('suc');
}
//任务提报
if($_REQUEST['a']=='signadd'){
	$submitModel=new Model_Subtable('sub_job_submit');
	$data=$_POST;
	//查重
	$phoneRow=$submitModel->where("phone='".$data['str']['phone']."'")->dataRow();
	if($phoneRow) die('cf');
	
	$data['info']['uid']=$_SESSION['tyuser']['id'];
	$res=$submitModel->add($data);
	$res ? die('suc') : die('err');
}