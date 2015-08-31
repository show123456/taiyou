<?php
	$model=new Model_Subtable('sub_card');
	if($_REQUEST['a']=='index'){
		$current_time=time();
		$uid=$userRow['id'];
		$list=$model->where("uid='{$uid}' and out_time > ".$current_time)->dataArr();
		$num1=0;$num2=0;$num3=0;
		foreach($list as $v){
			if($v['type']==1){
				$num1++;
			}elseif($v['type']==2){
				$num2++;
			}elseif($v['type']==3){
				$num3++;
			}
		}
		$smarty->assign('num1',$num1);
		$smarty->assign('num2',$num2);
		$smarty->assign('num3',$num3);
		//禁闭期内的记录
		$banModel=D('ban');
		if($userRow['cardnum']){
			$where_str="end_time > '".date('Y-m-d',$current_time)."' and (username='".$userRow['username']."' or cardnum='".$userRow['cardnum']."')";
		}else{
			$where_str="end_time > '".date('Y-m-d',$current_time)."' and username='".$userRow['username']."'";
		}
		$banList=$banModel->where($where_str)->dataArr();
		if($banList){
			$smarty->assign('in_ban',1);
		}
		//开乐迪优惠码
		$kld_list=D('sub_kld')->where("uid='".$userRow['id']."'")->dataArr();
		$current_time=time();
		foreach($kld_list as $kk=>$kv){
			$kld_list[$kk]['day']=ceil((strtotime($kv['addtime'])+15*3600*24-$current_time)/(3600*24));
		}
		$smarty->assign('kld_list',$kld_list);
		$smarty->setLayout('')->setTpl('mobile/templates/card_index.html')->display();die;
	}
	
	
	if($_REQUEST['a']=='del_ban'){
		$current_time=time();
		$uid=$userRow['id'];
		$row=$model->where("type=1 and uid='{$uid}' and out_time > ".$current_time)->order('id asc')->dataRow();
		if(!$row){
			die('no');
		}
		$model->del($row['id']);
		//删除禁闭
		if($userRow['cardnum']){
			$where_str="end_time > '".date('Y-m-d',$current_time)."' and (username='".$userRow['username']."' or cardnum='".$userRow['cardnum']."')";
		}else{
			$where_str="end_time > '".date('Y-m-d',$current_time)."' and username='".$userRow['username']."'";
		}
		$model->query("update ban set ban_day=0,end_time=start_time where ".$where_str." order by id desc limit 1");
		die('suc');
	}