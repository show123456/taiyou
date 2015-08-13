<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_task');
$userModel=new Model_Subtable('sub_user');
//数据修改
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$data['info']['work_time']=$data['work_date'].' '.$data['work_hour'].'-'.$data['work_hour_end'];
		$data['info']['jihe_time']=$data['jihe_date'].' '.$data['jihe_hour'];
		//截止时间戳
		$data['info']['jiezhi_time']=strtotime($data['str']['start_time'].' '.$data['str']['start_hour'].':00');
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$id=(int)$_GET['id'];
		$vo=$model->find($id);
		//发布方公司名
		$userRow=$userModel->find($vo['uid']);
		$vo['uname']=$userRow['nickname'];
		//苏州市下的区
		$dmodel=new Model_Subtable('s_district');
		$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
		//一天24小时的数组
		for($i=0;$i<25;$i++){
			$i<10 ? $hourArr[]='0'.$i.':00' : $hourArr[]=$i.':00';
		}
		$smarty->assign('hourArr',$hourArr);
		
		if($vo['work_time']) $work_time_row=explode(' ',$vo['work_time']);
		if($work_time_row[1]) $work_time_row2=explode('-',$work_time_row[1]);
		$vo['work_date']=$work_time_row[0];
		$vo['work_hour']=$work_time_row2[0];
		$vo['work_hour_end']=$work_time_row2[1];
		
		if($vo['jihe_time']) $jihe_time_row=explode(' ',$vo['jihe_time']);
		$vo['jihe_date']=$jihe_time_row[0];
		$vo['jihe_hour']=$jihe_time_row[1];
		$smarty->assign('vo',$vo);
		
		$smarty->setTpl('task/templates/task_add.html')->display();die();
	}
}
//发送消息提醒
if($_REQUEST['a']=='send_msg'){
	$tid=$_GET['tid'];
	$taskRow=$model->find($tid);
	//发布方公司名
	if($taskRow['company_name']){
		$cname=$taskRow['company_name'];
	}else if($taskRow['uid']==1){
		$cname='云客驿站';
	}else{
		$userRow=$userModel->find($taskRow['uid']);
		$cname=$userRow['nickname'];
	}
	$configModel=new Model_CustomerConfig();
	$jzUserArr=$userModel->where("`type`=1 and fromuser!=''")->dataArr();
	if($jzUserArr){
		$jzUserRow=array();
		foreach($jzUserArr as $k=>$v){
			$jzUserRow[]=$v['fromuser'];
		}
		$configModel->sendCustomerMsg($cname.'发布了一个新职位：'.$taskRow['title']."，<a href='http://www.yunfanke.com/mobile/index.php?m=task&a=detail&id=".$tid."'>点击查看</a>",$jzUserRow);
	}
	die;
}
//给单个人发送消息提醒
if($_REQUEST['a']=='reply'){
	$uid=$_POST['uid'];
	$con=$_POST['content'];
	$uModel=D('sub_user');
	$configModel=new Model_CustomerConfig();
	
	$uRow=$uModel->find($uid);
	if($uRow['fromuser']){
		$configModel->sendCustomerMsg($con,$uRow['fromuser']);
	}
	die;
}
//平台发布
if($_REQUEST['a']=='taskadd'){
	if(method_is('post')){
		$data=$_POST;
		$data['info']['uid']=1;//uid为1是平台方
		//$data['info']['sh_status']=1;//默认通过审核
		$data['info']['work_time']=$data['work_date'].' '.$data['work_hour'].'-'.$data['work_hour_end'];
		$data['info']['jihe_time']=$data['jihe_date'].' '.$data['jihe_hour'];
		//截止时间戳
		$data['info']['jiezhi_time']=strtotime($data['str']['start_time'].' '.$data['str']['start_hour'].':00');
		//经纬度处理
		if($_POST['maplocation']){
			$mapArr=explode(',',$_POST['maplocation']);
			$data['info']['latitude']=$mapArr[1];
			$data['info']['longitude']=$mapArr[0];
		}
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		//省
		$pmodel=new Model_Subtable('s_province');
		$smarty->assign('parr',$pmodel->dataArr());
		//苏州市下的区
		$dmodel=new Model_Subtable('s_district');
		$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
		//一天24小时的数组
		for($i=0;$i<25;$i++){
			$i<10 ? $hourArr[]='0'.$i.':00' : $hourArr[]=$i.':00';
		}
		$smarty->assign('hourArr',$hourArr);
		
		$id=(int)$_GET['id'];
		if($id){
			$vo=$model->find($id);
			if($vo['work_time']) $work_time_row=explode(' ',$vo['work_time']);
			if($work_time_row[1]) $work_time_row2=explode('-',$work_time_row[1]);
			$vo['work_date']=$work_time_row[0];
			$vo['work_hour']=$work_time_row2[0];
			$vo['work_hour_end']=$work_time_row2[1];
			
			if($vo['jihe_time']) $jihe_time_row=explode(' ',$vo['jihe_time']);
			$vo['jihe_date']=$jihe_time_row[0];
			$vo['jihe_hour']=$jihe_time_row[1];
			$smarty->assign('vo',$vo);
		}
		$smarty->setTpl('task/templates/task_taskadd.html')->display();die();
	}
}
//数据删除
if($_REQUEST['a']=='del'){
	$res=$model->del($_POST['id']);
	echo json_encode($res);die();
}
//开放关闭
if($_REQUEST['a']=='kf'){
	$data['info'][is_shut]=(int)$_GET['type'];
	$data['info'][id]=(int)$_GET['id'];
	$res=$model->add($data);
	echo $res;die();
}
//是否允许提报电话
if($_REQUEST['a']=='phone'){
	$data['info'][is_phone]=(int)$_GET['type'];
	$data['info'][id]=(int)$_GET['id'];
	$res=$model->add($data);
	echo $res;die();
}
//兼职用户列表
if($_REQUEST['a']=='sign'){
	//职位标题
	$smarty->assign('vo',$model->field('id,title')->where("id=".$_GET['tid'])->dataRow());
		
	$signModel = new Model_Subtable('sub_sign');
	$filter['where'] = " tid=".$_GET['tid'];
	$filter['order'] = " distance asc ";
	$data = $signModel->paginate($filter,'*',$_GET['p'],10);
	$listArr = $data['data'];
	if($listArr){
		foreach($listArr as $key=>$value){
			$uRow=$userModel->find($value['uid']);
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			$listArr[$key]['sex']=$uRow['sex'];
			//距离处理
			$listArr[$key]['distance'] > 500 ? $listArr[$key]['distance']='未知' : $listArr[$key]['distance']=$listArr[$key]['distance'].'km';
			//序号处理
			$_GET['p']>0 ? $p=$_GET['p'] : $p=1;
			$listArr[$key]['xuhao']=($p-1)*10+$key+1;
			//报名时间
			$listArr[$key]['addtime']=substr($listArr[$key]['addtime'],0,16);
		}
	}
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->pager($data['pager']));
	$smarty->setTpl('task/templates/task_sign.html')->display();die;
}
//城市级联
if($_REQUEST['a']=='get_citys'){
	$cmodel=new Model_Subtable('s_city');
	$carr=$cmodel->where("ProvinceID=".$_GET['pid'])->dataArr();
	echo json_encode($carr);die;
}
if($_REQUEST['a']=='get_districts'){
	$dmodel=new Model_Subtable('s_district');
	$darr=$dmodel->where("CityID=".$_GET['cid'])->order('DistrictId asc')->dataArr();
	echo json_encode($darr);die;
}
//数据列表
$signModel = new Model_Subtable('sub_sign');
$condition=array();
if($_GET['keywords']) $condition[]=" title like '%".common_pg('keywords')."%' ";
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$listArr[$key]['title']=cut_str(deletehtml($value['title']),10);
	//总数
	$countRow=$signModel->field('count(id) as c')->where(" tid='{$value['id']}'")->dataRow();;
	$listArr[$key]['countnum']=$countRow['c'];
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('task/templates/task_index.html')->display();