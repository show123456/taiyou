<?php
	$model=new Model_Subtable('sub_agent');
	
	if($_REQUEST['a']=='add'){
		if(method_is('post')){
			$data=$_POST;
			$res=$model->add($data);
			$res ? die('suc') : die('err');
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/agent_add.html')->display();die;
		}
	}
	
	if($_REQUEST['a']=='mid'){
		$smarty->setLayout('')->setTpl('mobile/templates/agent_mid.html')->display();die;
	}
	
	//信息设置
	if($_REQUEST['a']=='info'){
		if(method_is('post')){
			$data=$_POST;
			if($data['num']['id'] && $data['str']['code']){
				$res=D('sub_user')->add($data);
			}
			die('suc');
		}else{
			$kvRow=D('sub_kv')->where("k='discount'")->dataRow();
			$smarty->assign('discount',$kvRow['v']*10);
			$smarty->setLayout('')->setTpl('mobile/templates/agent_info.html')->display();die;
		}
	}
	
	//成交记录
	if($_REQUEST['a']=='deal'){
		$orderModel=D('applist_hpt_shop_order');
		$current_time=time();
		$_GET['start_date'] ? $start_date=$_GET['start_date'] : $start_date=date('Y-m-d',$current_time);
		$_GET['end_date'] ? $end_date=$_GET['end_date'] : $end_date=date('Y-m-d',$current_time);
		$smarty->assign('start_date',$start_date);$smarty->assign('end_date',$end_date);
		
		$where="is_pay=1 and agent_num='".$userRow['agent_num']."' and pay_time >= '".$start_date." 00:00:00' and pay_time <= '".$end_date." 23:59:59'";
		$list=$orderModel->where($where)->dataArr();
		
		$num=0;$total_money=0;
		foreach($list as $k=>$v){
			$list[$k]['pay_time']=substr($v['pay_time'],2,14);
			$num++;
			$total_money+=($v['money']-12);
		}
		$smarty->assign('list',$list);
		$smarty->assign('num',$num);
		$smarty->assign('total_money',$total_money);
		$smarty->setLayout('')->setTpl('mobile/templates/agent_deal.html')->display();die;
	}

	//我的返现
	if($_REQUEST['a']=='back'){
		$orderModel=D('applist_hpt_shop_order');
		//年份
		$yarr=array();
		for($i=2015;$i<2036;$i++){
			$yarr[]=$i;
		}
		$smarty->assign('yarr',$yarr);
		//月份
		$marr=array();
		for($i=1;$i<13;$i++){
			if($i<10) $i='0'.$i;
			$marr[]=$i;
		}
		$smarty->assign('marr',$marr);
		
		$current_time=time();
		$_GET['year'] ? $year=$_GET['year'] : $year=date('Y',$current_time);
		$_GET['month'] ? $month=$_GET['month'] : $month=date('m',$current_time);
		$smarty->assign('year',$year);
		$smarty->assign('month',$month);
		
		$where="is_pay=1 and agent_num='".$userRow['agent_num']."' and left(pay_time,7) = '".$year."-".$month."'";
		$row=$orderModel->field("id,money,sum(money) as sum_money,count(id) as num")->where($where)->dataRow();		
		$smarty->assign('num',$row['num']);
		$smarty->assign('money',$row['sum_money']-$row['num']*12);
		
		$ret=0.15;//返现比
		$return_money=round(($row['sum_money']-$row['num']*12)*$ret,2);//每笔减12
		$tax_row=count_tax($return_money);
		$smarty->assign('return_money',$return_money);//返现金额
		$smarty->assign('tax',$tax_row[0]);//税金
		$smarty->assign('final_money',$tax_row[1]);//实收金额
		$smarty->setLayout('')->setTpl('mobile/templates/agent_back.html')->display();die;
	}
	
	//输入工资，返回税金和实际工资组成的数组
	function count_tax($mon=0){
		$mon_mid=3500;
		$cha=$mon-$mon_mid;
		if($cha<=0){
			$fee=0;
			$final_money=$mon;
		}elseif($cha<=1500){
			$fee=$cha*0.03;
			$final_money=$mon-$fee;
		}elseif($cha<=4500){
			$fee=$cha*0.1-105;
			$final_money=$mon-$fee;
		}elseif($cha<=9000){
			$fee=$cha*0.2-555;
			$final_money=$mon-$fee;
		}elseif($cha<=35000){
			$fee=$cha*0.25-1005;
			$final_money=$mon-$fee;
		}elseif($cha<=55000){
			$fee=$cha*0.3-2755;
			$final_money=$mon-$fee;
		}elseif($cha<=80000){
			$fee=$cha*0.35-5505;
			$final_money=$mon-$fee;
		}else{
			$fee=$cha*0.45-13505;
			$final_money=$mon-$fee;
		}
		$final=array($fee,round($final_money,2));
		return $final;
	}