<?php
	$model=new Model_Subtable('sub_job');
	
	if($_REQUEST['a']=='index'){
		//疫苗任务
		$yimiaoModel=D('sub_job_yimiao');
		$yimiaoRow=$yimiaoModel->dataRow();
		$yimiaoRow['content']=cut_str(deletehtml(html_entity_decode($yimiaoRow['content'])),25);
		$smarty->assign('yimiaoRow',$yimiaoRow);
		
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
		$phoneRow=$submitModel->where("name='".$data['str']['name']."' and phone='".$data['str']['phone']."'")->dataRow();
		if($phoneRow) die('cf');
		
		if($data['license']) $data['info']['license']=implode(',',$data['license']);
		$data['info']['uid']=$_SESSION['tyuser']['id'];
		$res=$submitModel->add($data);
		$res ? die('suc') : die('err');
	}
	
	//疫苗任务详情
	if($_REQUEST['a']=='yimiao_detail'){
		$yimiaoModel=D('sub_job_yimiao');
		$signModel=new Model_Subtable('sub_yimiao_sign');
		$id=(int)$_GET['id'];
		$vo=$yimiaoModel->find($id);
		$vo['content']=html_entity_decode($vo['content']);
		$smarty->assign('vo',$vo);
		//医院列表
		$hptModel=D('sub_job_hospital');
		$hptArr=$hptModel->order('id asc')->dataArr();
		foreach($hptArr as $k=>$v){
			//该医院当天的领取情况
			$now_date=date('Y-m-d');
			$row=$signModel->where("left(addtime,10)='{$now_date}' and hpt_id='".$v['id']."'")->dataRow();
			$row ? $hptArr[$k]['lp_status']=1 : $hptArr[$k]['lp_status']=0;
			//是否在领取期内
			$hptArr[$k]['allow_lp']=0;
			if($v['lq_time']){
				$lq_time_arr=explode(',',$v['lq_time']);
				$w=date('w');
				if($w==0) $w=7;
				if(in_array($w,$lq_time_arr)) $hptArr[$k]['allow_lp']=1;
			}
		}
		$smarty->assign('hptArr',$hptArr);
		
		$smarty->setLayout('')->setTpl('mobile/templates/yimiao_detail.html')->display();die;
	}
	
	//领取疫苗任务
	if($_REQUEST['a']=='yimiao_lq'){
		$signModel=new Model_Subtable('sub_yimiao_sign');
		$data=array();
		$data['info']['hpt_id']=(int)$_REQUEST['hpt_id'];
		$data['info']['uid']=$_SESSION['tyuser']['id'];
		$jobRow=D('sub_job_yimiao')->dataRow();
		
		$now_date=date('Y-m-d');
		$row=$signModel->where("left(addtime,10)='{$now_date}' and uid='".$data['info']['uid']."'")->dataRow();
		//已领取
		if($row){
			die('err');
		}else{
			$res=$signModel->add($data);
			//领取数+1
			$signModel->query("update sub_job_yimiao set num=num+1");
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
	
	//任务提报
	if($_REQUEST['a']=='yimiao_submit'){
		if(method_is('post')){
			$signModel=D('sub_yimiao_sign');
			$data=$_POST;
			$signRow=$signModel->find($data['num']['id']);
			if($signRow['num'] > 0){//重复提交
				die('err');
			}
			$res=$signModel->add($data);
			$res ? die('suc') : die('err');
		}else{
			//疫苗任务
			$smarty->assign('vo',D('sub_job_yimiao')->dataRow());
			
			$sid=(int)$_REQUEST['sid'];//sub_yimiao_sign表id
			$signRow=D('sub_yimiao_sign')->find($sid);
			
			//医院名称
			$hptRow=D('sub_job_hospital')->find($signRow['hpt_id']);
			$signRow['hpt_name']=$hptRow['name'];
			$smarty->assign('signRow',$signRow);
			$smarty->setLayout('')->setTpl('mobile/templates/yimiao_submit.html')->display();die;
		}
	}
	
	//个人中心-任务管理
	if($_REQUEST['a']=='index_user'){
		//我是否领取
		$signModel=new Model_Subtable('sub_yimiao_sign');
		$row=$signModel->where("uid='".$userRow['id']."'")->dataRow();
		if(!$row){
			$smarty->assign('no_yimiao',1);
		}
		//疫苗任务
		$yimiaoModel=D('sub_job_yimiao');
		$yimiaoRow=$yimiaoModel->dataRow();
		$yimiaoRow['content']=cut_str(deletehtml(html_entity_decode($yimiaoRow['content'])),25);
		$smarty->assign('yimiaoRow',$yimiaoRow);
		
		//我的任务
		$jobsignModel=D('sub_jobsign');
		$signArr=$jobsignModel->field("id,jid")->where(" uid='".$_SESSION['tyuser']['id']."' ")->order('id desc')->dataArr();
		if($signArr){
			$signRow=array();
			foreach($signArr as $k=>$v){
				$signRow[]=$v['jid'];
			}
			$idStr=implode(',',$signRow);
			$where=" id in (".$idStr.") ";
		}
		if($where){
			$listArr=$model->where($where)->order('ordernum desc,id desc')->dataArr();
			foreach($listArr as $key=>$value){
				$listArr[$key]['title']=cut_str(deletehtml($value['title']),12);
				$listArr[$key]['content']=cut_str(deletehtml($value['content']),25);
			}
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/job_index_user.html')->display();die;
	}
	
	//个人中心-任务管理-领取历史
	if($_REQUEST['a']=='lp_history'){
		$signModel=new Model_Subtable('sub_yimiao_sign');
		$hptModel=D('sub_job_hospital');
		$list=$signModel->where("uid='".$userRow['id']."'")->order('id desc')->dataArr();
		foreach($list as $k=>$v){
			$hptRow=$hptModel->find($v['hpt_id']);
			$list[$k]['hpt_name']=$hptRow['name'];
		}
		$smarty->assign('list',$list);
		$smarty->setLayout('')->setTpl('mobile/templates/yimiao_lp_history.html')->display();die;
	}
	
	//10000查看每日的领取记录
	if($_REQUEST['a']=='lp_day'){
		if($_GET['date']){
			$date=$_GET['date'];
		}else{
			$date=date('Y-m-d');
		}
		$where="left(addtime,10) = '{$date}'";
		$signModel=new Model_Subtable('sub_yimiao_sign');
		$hptModel=D('sub_job_hospital');
		$userModel=D('sub_user');
		$list=$signModel->where($where)->order('id desc')->dataArr();
		foreach($list as $k=>$v){
			$hptRow=$hptModel->find($v['hpt_id']);
			$list[$k]['hpt_name']=$hptRow['name'];
			$uRow=$userModel->find($v['uid']);
			$list[$k]['nickname']=$uRow['nickname'];
			$list[$k]['username']=$uRow['username'];
		}
		$smarty->assign('date',$date);
		$smarty->assign('list',$list);
		$smarty->setLayout('')->setTpl('mobile/templates/yimiao_lp_day.html')->display();die;
	}
	
	//删除疫苗领取记录
	if($_REQUEST['a']=='del_yimiao_sign'){
		D('sub_yimiao_sign')->del((int)$_GET['id']);
		die;
	}