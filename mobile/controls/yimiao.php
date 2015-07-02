<?php
$model=new Model_Subtable('sub_job');
/* $model=new Model_Subtable('sub_job');
if($_REQUEST['a']=='index'){
	$jobsignModel=new Model_Subtable('sub_jobsign');
	$pageSize=30;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;
	//条件
	$where='';
	if($_GET['user']==1){//我的任务
		$signArr=$jobsignModel->field("id,jid")->where(" uid='".$_SESSION['tyuser']['id']."' ")->order('id desc')->dataArr();
		if($signArr){
			$signRow=array();
			foreach($signArr as $k=>$v){
				$signRow[]=$v['jid'];
			}
			$idStr=implode(',',$signRow);
			$where=" id in (".$idStr.") ";
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
	}
	
	if($where){
		$listArr = $model->where($where)->order('ordernum desc,id desc')->limit($limitStr)->order('id desc')->dataArr();
	}else{
		$listArr = $model->order('ordernum desc,id desc')->limit($limitStr)->order('id desc')->dataArr();
	}
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),12);
		$listArr[$key]['content']=cut_str(deletehtml($value['content']),25);
	}
	//查询总数，判断是否分页********无需分页，只显示最新的30条
	/* if($where){
		$countRow=$model->field("count(id) as countnum")->where($where)->dataRow();
	}else{
		$countRow=$model->field("count(id) as countnum")->dataRow();
	}
	if($countRow['countnum'] > $pageSize) $smarty->assign('is_page',1); */
	
	if($p > 1){
		if($listArr){
			echo json_encode($listArr);die;
		}else{
			echo json_encode('err');die;
		}
	}else{
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/job_index.html')->display();die;
	}
}
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
} */