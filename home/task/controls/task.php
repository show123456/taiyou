<?php
$model=new Model_Subtable('sub_task');
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$data['info']['uid']=$userRow['id'];
		$data['info']['work_time']=$data['work_date'].' '.$data['work_hour'].'-'.$data['work_hour_end'];
		$data['info']['jihe_time']=$data['jihe_date'].' '.$data['jihe_hour'];
		//获取该任务发布时的经纬度（手机端）
		$locationModel=new Model_Subtable('member_location');
		$locationRow=$locationModel->where("fromuser='".$userRow['fromuser']."'")->dataRow();
		$data['info'][latitude]=$locationRow['latitude'];
		$data['info'][longitude]=$locationRow['longitude'];
		
		$res=$model->add($data);
		$res ? die('suc') : die('err');
	}else{
		//苏州市下的区
		$dmodel=new Model_Subtable('s_district');
		$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
		//一天24小时的数组
		for($i=0;$i<25;$i++){
			$i<10 ? $hourArr[]='0'.$i.':00' : $hourArr[]=$i.':00';
		}
		$smarty->assign('hourArr',$hourArr);
		
		if($_GET['id']){
			$smarty->assign('vo',$model->find($_GET['id']));
		}
		$smarty->setLayout('')->setTpl('mobile/templates/task_add.html')->display();die;
	}
}

if($_REQUEST['a']=='detail'){
	$vo=$model->find($_GET['id']);
	//浏览量+1
	$seeData['info']['id']=$_GET['id'];
	$seeData['info']['see_num']=$vo['see_num']+1;
	$model->add($seeData);
	//发布时间
	$current_time=time();//当前时间戳
	$lc_time=strtotime(date('Y-m-d',$current_time)." 00:00:00");//今天凌晨时间戳
	$xs_time=strtotime(date('Y-m-d H',$current_time).":00:00");//当前小时时间戳
	$fabu_time=strtotime($vo['addtime']);//发布时间戳
	if($fabu_time>=$xs_time){//本小时内发布的
		$cha_fen=ceil(($current_time-$fabu_time)/60);
		$cha_fen<1 ? $vo['fb_str']="刚刚" : $vo['fb_str']=$cha_fen."分钟前";
	}else if($fabu_time>=$lc_time){//今天内发布的
		$cha_hour=floor(($current_time-$fabu_time)/3600);
		$cha_hour ? $vo['fb_str']=$cha_hour."小时前" : $vo['fb_str']="1小时内";
	}else{
		$cha_day=ceil(($lc_time-$fabu_time)/3600/24);
		$vo['fb_str']=$cha_day."天前";
	}
	//公司名称简介
	$smarty->assign('uRow',$userModel->field('nickname,introduce')->where("id=".$vo['uid'])->dataRow());
	//已报名人数
	$signModel=new Model_Subtable('sub_sign');
	$signRow=$signModel->field('count(*) as countnum')->where("is_valid!=2 and tid=".$_GET['id'])->dataRow();
	$smarty->assign('signNum',$signRow['countnum']);
	//报名状态
	if($_SESSION['tyuser']){
		$signRow1=$signModel->where("tid=".$_GET['id']." and uid=".$userRow['id'])->dataRow();
		$signRow1 ? $vo['sign_status']=1 : $vo['sign_status']=0;
	}
	//是否人数已满
	$signRow['countnum'] >= $vo['num'] ? $out_num=1 : $out_num=0;
	$smarty->assign('out_num',$out_num);
	//报名列表头像、昵称
	$memberModel=D('member');
	$signList=$signModel->where("tid=".$_GET['id'])->order('id desc')->limit('5')->dataArr();
	if($signList){
		foreach($signList as $sk=>$sv){
			$suRow=$userModel->find($sv['uid']);
			$memberRow=$memberModel->fetchRow("select headimgurl from member where fromuser='".$suRow['fromuser']."'");
			if($suRow['head_pic'])
				$picStr="/data/image_c/".$suRow['head_pic'];
			else if($memberRow['headimgurl'])
				$picStr=$memberRow['headimgurl'];
			else
				$picStr="images/men_icon.gif";
			$signList[$sk]['head_pic']=$picStr;
			$signList[$sk]['nicheng']=sub_cut_str(deletehtml($suRow['nicheng']),3);;
		}
	}
	$smarty->assign('signList',$signList);
	//评论列表
	$replyModel=new Model_Subtable('sub_reply');
	$replyList=$replyModel->where("tid=".$_GET['id'])->order('id desc')->limit('5')->dataArr();
	$smarty->assign('replyList',$replyList);
	//是否过期
	strtotime($vo['start_time'].' '.$vo['start_hour'].':00') > time() ? $out_date=0 : $out_date=1;
	$smarty->assign('out_date',$out_date);
	$smarty->assign('vo',$vo);
	$smarty->setLayout('')->setTpl('mobile/templates/task_detail.html')->display();die;
}
if($_REQUEST['a']=='detail_ajax'){
	//报名列表头像、昵称
	$memberModel=D('member');
	$userModel=D('sub_user');
	$signModel=D('sub_sign');
	$pageSize=5;$p=$_GET['p'];$limitStr = ($p-1)*$pageSize.','.$pageSize;
	$signList=$signModel->where("tid=".$_GET['id'])->order('id desc')->limit($limitStr)->dataArr();
	if($signList){
		foreach($signList as $sk=>$sv){
			$suRow=$userModel->find($sv['uid']);
			$memberRow=$memberModel->fetchRow("select headimgurl from member where fromuser='".$suRow['fromuser']."'");
			if($suRow['head_pic'])
				$picStr="/data/image_c/".$suRow['head_pic'];
			else if($memberRow['headimgurl'])
				$picStr=$memberRow['headimgurl'];
			else
				$picStr="images/men_icon.gif";
			$signList[$sk]['head_pic']=$picStr;
			$signList[$sk]['nicheng']=sub_cut_str(deletehtml($suRow['nicheng']),3);;
		}
		echo json_encode($signList);die;
	}else{
		echo json_encode('err');die;
	}
}
//申请职位
if($_REQUEST['a']=='sign'){
	$signModel=new Model_Subtable('sub_sign');
	$data['num']['tid']=$_GET['tid'];
	$data['num']['uid']=$userRow['id'];
	$s=$signModel->where("tid=".$data['num']['tid']." and uid=".$data['num']['uid'])->dataRow();
	if($s){//已报名
		die('ybm');
	}else{
		$trow=$model->find($data['num']['tid']);
		$data['num']['fact_money']=$trow['money'];
		//我的坐标
		$memLocModel=D('member_location');
		$memLocRow=$memLocModel->where("fromuser='".$userRow['fromuser']."'")->dataRow();
		//发布方若无经纬度，则默认为吴江市政府的坐标,我的坐标默认为0,0
		if(!$trow['latitude']){$trow['latitude']=31.144744;$trow['longitude']=120.651331;}
		if(!$memLocRow['latitude']){$memLocRow['latitude']=0;$memLocRow['longitude']=0;}
		//计算距离
		$data['info']['distance']=get_distance($trow['latitude'],$trow['longitude'],$memLocRow['latitude'],$memLocRow['longitude']);
		$res=$signModel->add($data);
		$res ? die('suc') : die('err');
		/* if($trow['sq_fee']>0){//有申请费
			$data['num']['fee']=$trow['sq_fee'];
			$res=D('sub_temp_sign')->add($data);
			echo $res;die;
		}else{
			$data['num']['fact_money']=$trow['money'];
			$res=$signModel->add($data);
			$res ? die('suc') : die('err');
		} */
	}
}
//追加
if($_REQUEST['a']=='sign_zj'){
	$phone=$_GET['phone'];
	//判断手机账号是否存在
	$uRow=$userModel->where("type=1 and pid!=0 and username='{$phone}'")->dataRow();
	if(!$uRow){
		die('no');
	}
	
	$signModel=new Model_Subtable('sub_sign');
	$data['num']['tid']=$_GET['tid'];
	$data['num']['uid']=$uRow['id'];
	$data['num']['is_valid']=1;
	$data['num']['is_zj']=1;
	$s=$signModel->where("tid=".$data['num']['tid']." and uid=".$data['num']['uid'])->dataRow();
	if($s){
		die('ybm');
	}else{
		//实际应获得钱
		$trow=$model->find($data['num']['tid']);
		$data['num']['fact_money']=$trow['money'];
		//我的坐标
		$memLocModel=D('member_location');
		$memLocRow=$memLocModel->where("fromuser='".$uRow['fromuser']."'")->dataRow();
		//发布方若无经纬度，则默认为吴江市政府的坐标,我的坐标默认为0,0
		if(!$trow['latitude']){$trow['latitude']=31.144744;$trow['longitude']=120.651331;}
		if(!$memLocRow['latitude']){$memLocRow['latitude']=0;$memLocRow['longitude']=0;}
		//计算距离
		$data['info']['distance']=get_distance($trow['latitude'],$trow['longitude'],$memLocRow['latitude'],$memLocRow['longitude']);
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
//开放关闭职位
if($_REQUEST['a']=='open_shut'){
	$data['info']['id']=$_GET['tid'];
	$data['info']['is_shut']=$_GET['is_shut'];
	$model->add($data);
}

if($_REQUEST['a']=='index'){
	//省
	$pmodel=new Model_Subtable('s_province');
	$smarty->assign('parr',$pmodel->dataArr());
	//市
	$cmodel=new Model_Subtable('s_city');
	$smarty->assign('carr',$cmodel->where("ProvinceID=10")->dataArr());
	//区
	$dmodel=new Model_Subtable('s_district');
	$smarty->assign('darr',$dmodel->where("CityID=78")->order('DistrictId asc')->dataArr());
	
	$pageSize=6;//页大小
	$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
	$limitStr = ($p-1)*$pageSize.','.$pageSize;
	//搜索条件
	if($_GET['did'] && $_GET['did']!='clear'){
		$didWhere=" and did=".$_GET['did'];
	}else{
		$didWhere="";
	}
	if($_SESSION['tyuser']){//企业只能看自己的
		$userRow['type']==2 ? $typeWhere=' and uid='.$userRow['id'] : $typeWhere='';
	}
	
	$listArr = $model->where("sh_status=1 $didWhere $typeWhere ")->order('is_shut asc,is_recommend desc,id desc')->limit($limitStr)->dataArr();
	
	$signModel=D('sub_sign');
	foreach($listArr as $key=>$value){
		$listArr[$key]['title']=cut_str(deletehtml($value['title']),10);
		$drow=$dmodel->where("DistrictId=".$value['did'])->dataRow();
		$listArr[$key]['addr']=$drow['DistrictName'];
		//所属公司
		if($value['company_name']){//平台代发布
			$listArr[$key]['nickname']=$value['company_name'];
		}else{
			$urow=$userModel->find($value['uid']);
			$listArr[$key]['nickname']=$urow['nickname'];
		}
		//我的报名状态
		if($_SESSION['tyuser']){
			$signRow=$signModel->where("tid=".$value['id']." and uid=".$userRow['id'])->dataRow();
			$signRow ? $listArr[$key]['sign_status']=1 : $listArr[$key]['sign_status']=0;
		}
		//是否报名已满
		$signRow1=$signModel->field('count(*) as countnum')->where("is_valid!=2 and tid=".$value['id'])->dataRow();
		$signRow1['countnum'] >= $value['num'] ? $listArr[$key]['man_status']=1 : $listArr[$key]['man_status']=0;
		//发布时的时间
		$current_time=time();//当前时间戳
		$lc_time=strtotime(date('Y-m-d',$current_time)." 00:00:00");//今天凌晨时间戳
		$xs_time=strtotime(date('Y-m-d H',$current_time).":00:00");//当前小时时间戳
		$listArr[$key]['fb_str']=getTimeStr($current_time,$lc_time,$xs_time,$value['addtime']);
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

if($_REQUEST['a']=='cancel_sign'){
	$tid=$_GET['tid'];
	$uid=$userRow['id'];
	$taskRow=$model->find($tid);
	if($taskRow['is_js']==1){
		die('no');
	}else{
		$model->query("delete from sub_sign where tid=$tid and uid=$uid limit 1");
	}
}

//发布时间(当前时间戳,今天凌晨时间戳,当前小时时间戳)
function getTimeStr($current_time,$lc_time,$xs_time,$s){
	$fabu_time=strtotime($s);//发布时间戳
	if($fabu_time>=$xs_time){//本小时内发布的
		$cha_fen=ceil(($current_time-$fabu_time)/60);
		$cha_fen<1 ? $fb_str="刚刚" : $fb_str=$cha_fen."分钟前";
	}else if($fabu_time>=$lc_time){//今天内发布的
		$cha_hour=floor(($current_time-$fabu_time)/3600);
		$cha_hour ? $fb_str=$cha_hour."小时前" : $fb_str="1小时内";
	}else{
		$cha_day=ceil(($lc_time-$fabu_time)/3600/24);
		$fb_str=$cha_day."天前";
	}
	return $fb_str;
}

//根据经纬度计算距离，单位公里
function get_distance($lat1, $lng1, $lat2, $lng2) {
	//将角度转为狐度
	$radLat1 = deg2rad($lat1);
	$radLat2 = deg2rad($lat2);
	$radLng1 = deg2rad($lng1);
	$radLng2 = deg2rad($lng2);
	$a = $radLat1 - $radLat2; //两纬度之差,纬度<90
	$b = $radLng1 - $radLng2; //两经度之差纬度<180
	$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
	return round($s, 2);
}