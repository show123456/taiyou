<?php
	/**
	 *任务分拨
	 */
	$model=new Model_Subtable('sub_task');
	
	//每日的职位列表
	if($_REQUEST['a']=='task_index'){
		$assignModel=D('sub_assign');
		if($_GET['date']){
			$date=$_GET['date'];
		}else{
			$date=date('Y-m-d');
		}
		
		$listArr=$model->where("left(addtime,10) = '{$date}'")->order('id desc')->dataArr();
		foreach($listArr as $key=>$value){
			$listArr[$key]['title']=cut_str(deletehtml($value['title']),15);
			//是否分拨
			$assignRow=$assignModel->where("tid='".$value['id']."'")->dataRow();
			if($assignRow) $listArr[$key]['is_fb']=1;
			//区经理是否审核
			$listArr[$key]['is_qujingli']=$assignRow['is_qujingli'];
			//督导是否全部提报
			$assignRow1=$assignModel->where("tid='".$value['id']."' and isnull(final_pic)")->dataRow();
			if($assignRow && empty($assignRow1)){
				$listArr[$key]['is_dudao']=1;
			}
		}
		
		$smarty->assign('list',$listArr);
		$smarty->assign('date',$date);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_task_index.html')->display();die;
	}
	
	//任务分拨界面
	if($_REQUEST['a']=='main'){
		$userModel=D('sub_user');
		$tid=(int)$_GET['tid'];//任务id
		$vo=$model->find($tid);
		$vo['title']=cut_str($vo['title'],15);
		$smarty->assign('vo',$vo);
		//工作日可出勤的督导
		//$dudao_list=D('sub_user_ext')->where("group_id=2")->dataArr();
		$dudao_list=D('sub_attend')->where("attend_date='".substr($vo['work_time'],0,10)."'")->dataArr();
		foreach($dudao_list as $k=>$v){
			$row=$userModel->field('id,nickname,username')->where("id='".$v['uid']."'")->dataRow();
			$dudao_list[$k]['nickname']=$row['nickname'];
		}
		$smarty->assign('dudao_list',$dudao_list);
		//可指定的人员
		$zhiding_list=D('sub_sign')->where("tid='{$tid}' and is_valid != 2")->dataArr();
		foreach($zhiding_list as $k=>$v){
			$row=$userModel->field('id,nickname,username')->where("id='".$v['uid']."'")->dataRow();
			$zhiding_list[$k]['nickname']=$row['nickname'];
		}
		$smarty->assign('zhiding_list',$zhiding_list);
		//如果是修改分拨任务时
		$assignModel=D('sub_assign');
		$list=$assignModel->where("tid='{$tid}'")->dataArr();
		foreach($list as $k=>$v){
			$list[$k]['xuhao']=$k+1;
			if($v['zd']){
			$list[$k]['zd_arr']=explode(',',$v['zd']);
			foreach($list[$k]['zd_arr'] as $zdv){
				$zd_row=$userModel->field('id,nickname,username')->where("id='".$zdv."'")->dataRow();
				$list[$k]['zd_list'][$zdv]=$zd_row['nickname'];
			}
			}
		}
		$smarty->assign('list',$list);
		//当前角色
		$userExtModel=D('sub_user_ext');
		$userExtRow=$userExtModel->where("uid='".$userRow['id']."'")->dataRow();
		$smarty->assign('userExtRow',$userExtRow);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_main.html')->display();die;
	}
	
	//任务分拨-保存
	if($_REQUEST['a']=='main_save'){
		$assignModel=D('sub_assign');
		$data=$_POST;
		$tid=(int)$data['tid'];//任务id
		$vo=$model->find($tid);
		if($vo['work_time']) $work_date=substr($vo['work_time'],0,10);
		//先把所有报名人员的dudao_uid置为0
		$model->query("update sub_sign set dudao_uid=0 where tid='{$tid}'");
		
		for($i=1;$i<=$data['group_number'];$i++){
			$info=array();
			$info['info']['tid']=$tid;
			$info['info']['work_date']=$work_date;
			$id_key='id'.$i;
			if($data[$id_key]) $info['info']['id']=$data[$id_key];
			$dudao_uid_key='dudao_uid'.$i;
			$info['info']['dudao_uid']=$data[$dudao_uid_key];
			$num_key='num'.$i;
			$info['info']['num']=$data[$num_key];
			$zd_key='zd'.$i;
			if($data[$zd_key]){
				$info['info']['zd']=implode(',',$data[$zd_key]);
				//同时修改sub_sign表dudao_uid
				$model->query("update sub_sign set dudao_uid='".$data[$dudao_uid_key]."' where tid='{$tid}' and uid in (".$info['info']['zd'].")");
			}
			$pic_key='pic'.$i;
			$info['info']['pic']=$data[$pic_key];
			$pic_text_key='pic_text'.$i;
			$info['info']['pic_text']=$data[$pic_text_key];
			$dudao_jihe_time_key='dudao_jihe_time'.$i;
			$info['info']['dudao_jihe_time']=$data[$dudao_jihe_time_key];
			$notice_key='notice'.$i;
			$info['info']['notice']=$data[$notice_key];
			$work_key_key='work_key'.$i;
			$info['info']['work_key']=$data[$work_key_key];
			$jihe_key='jihe'.$i;
			$info['info']['jihe']=$data[$jihe_key];
			$assignModel->add($info);
		}
		die('suc');
	}
	
	//督导自己的任务列表
	if($_REQUEST['a']=='dudao_index'){
		$taskModel=D('sub_task');
		$assignModel=D('sub_assign');
		$list=$assignModel->where("dudao_uid='".$userRow['id']."' and is_qujingli=1")->order('work_date desc')->limit('30')->dataArr();//只记录20条数据
		if(!$list){
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		foreach($list as $k=>$v){
			$row=$taskModel->find($v['tid']);
			$list[$k]['title']=$row['title'];
		}
		$smarty->assign('list',$list);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_dudao_index.html')->display();die;
	}
	
	//督导报出勤
	if($_REQUEST['a']=='dudao_attend'){
		$attendModel=D('sub_attend');
		$date=date('Y-m-d',time()+3600*24);
		$attendRow=$attendModel->where("uid='".$userRow['id']."' and attend_date='{$date}'")->dataRow();
		if(!$attendRow){
			$attendData['info']['uid']=$userRow['id'];
			$attendData['info']['attend_date']=$date;
			$attendModel->add($attendData);
			die("系统已记录您可以于".$date."出勤");
		}else{
			die('err');
		}
		
	}
	
	//督导自己的任务详情
	if($_REQUEST['a']=='dudao_detail'){
		$assignModel=D('sub_assign');
		$aid=(int)$_GET['aid'];
		$vo=$assignModel->find($aid);
		$smarty->assign('vo',$vo);
		$smarty->assign('task_row',$model->find($vo['tid']));
		$smarty->setLayout('')->setTpl('mobile/templates/assign_dudao_detail.html')->display();die;
	}
	
	//查看行动路线图
	if($_REQUEST['a']=='pic'){
		$assignModel=D('sub_assign');
		$aid=(int)$_GET['aid'];
		$vo=$assignModel->find($aid);
		$smarty->assign('vo',$vo);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_pic.html')->display();die;
	}
	
	//查看督导提报图
	if($_REQUEST['a']=='pic_dudao'){
		$assignModel=D('sub_assign');
		$userModel=D('sub_user');
		$tid=(int)$_GET['tid'];
		$list=$assignModel->where("tid='".$tid."'")->dataArr();
		foreach($list as $k=>$v){
			$r=$userModel->field('id,nickname')->where("id='".$v['dudao_uid']."'")->dataRow();
			$list[$k]['nickname']=$r['nickname'];
		}
		$smarty->assign('list',$list);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_pic.html')->display();die;
	}
	
	//督导做签到管理
	if($_REQUEST['a']=='dudao_qd'){
		$signModel=new Model_Subtable('sub_sign');
		$assignModel=D('sub_assign');
		$aid=(int)$_GET['aid'];
		$aRow=$assignModel->find($aid);
		$smarty->assign('aRow',$aRow);
		//我被指定的非追加的人数
		$zd_sign_row_my=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."' and is_zj=0")->dataRow();
		//查询其他id较小的督导从即非指定也非追加的人员池里拿走了多少人数
		$assign_arr=$assignModel->where("tid='".$aRow['tid']."'")->dataArr();
		$out_num=0;
		foreach($assign_arr as $k=>$v){
			if($v['id'] < $aid){
				//被指定的非追加的人员
				$zd_sign_row1=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$v['dudao_uid']."' and is_zj=0")->dataRow();
				//拿走人数
				$out_num+=($v['num']-$zd_sign_row1['count_num']);
			}
		}
		//非追加非指定的人员
		$limit_str=(int)$out_num.','.($aRow['num']-$zd_sign_row_my['count_num']);
		$listArr=$signModel->where("is_valid=1 and is_zj=0 and dudao_uid=0 and tid='".$aRow['tid']."'")->order('id asc')->limit($limit_str)->dataArr();
		if(!$listArr) $listArr=array();
		//已追加给该督导和指定给该督导的报名人员
		$zj_list=$signModel->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."'")->order('id asc')->dataArr();
		if(!$zj_list) $zj_list=array();
		$listArr=array_merge($listArr,$zj_list);
		
		if($listArr){
			foreach($listArr as $key=>$value){
				//从快照中获取用户信息
				$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['nickname']=$uRow['nickname'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
				/* //是否可看手机号
				if($userRow['is_see']==0) $listArr[$key]['username']=substr($uRow['username'],0,3).'***'.substr($uRow['username'],-4); */
				//序号处理
				$listArr[$key]['xuhao']=$key+1;
			}
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_dudao_qd.html')->display();die;
	}
	
	//督导群发消息
	if($_REQUEST['a']=='dudao_qd_qunfa'){
		$signModel=new Model_Subtable('sub_sign');
		$assignModel=D('sub_assign');
		$aid=(int)$_POST['aid'];
		$aRow=$assignModel->find($aid);
		//我被指定的非追加的人数
		$zd_sign_row_my=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."' and is_zj=0")->dataRow();
		//查询其他id较小的督导从即非指定也非追加的人员池里拿走了多少人数
		$assign_arr=$assignModel->where("tid='".$aRow['tid']."'")->dataArr();
		$out_num=0;
		foreach($assign_arr as $k=>$v){
			if($v['id'] < $aid){
				//被指定的非追加的人员
				$zd_sign_row1=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$v['dudao_uid']."' and is_zj=0")->dataRow();
				//拿走人数
				$out_num+=($v['num']-$zd_sign_row1['count_num']);
			}
		}
		//非追加非指定的人员
		$limit_str=(int)$out_num.','.($aRow['num']-$zd_sign_row_my['count_num']);
		$listArr=$signModel->where("is_valid=1 and is_zj=0 and dudao_uid=0 and tid='".$aRow['tid']."'")->order('id asc')->limit($limit_str)->dataArr();
		if(!$listArr) $listArr=array();
		//已追加给该督导和指定给该督导的报名人员
		$zj_list=$signModel->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."'")->order('id asc')->dataArr();
		if(!$zj_list) $zj_list=array();
		$listArr=array_merge($listArr,$zj_list);
		
		if($listArr){
			$configModel=new Model_CustomerConfig();
			foreach($listArr as $key=>$value){
				//从快照中获取用户信息
				$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
				$configModel->sendCustomerMsg($_POST['content'],array($uRow['fromuser']));
				usleep(100000);
			}
		}
		die;
	}
	
	//追加报名
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
		$data['num']['dudao_uid']=$_GET['dudao_uid'];
		//已报名
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
			//职位工作日期
			if($trow['work_time']){
				$tempWorkArr=explode(' ',$trow['work_time']);
				$data['info']['task_date']=$tempWorkArr[0];
			}
			//同一个工作日不能报名两次
			$result1=$signModel->where("is_valid!=2 and uid=".$data['num']['uid']." and task_date='".$tempWorkArr[0]."'")->dataRow();
			if($result1) die('chongfu');
			
			$res=$signModel->add($data);
			$res ? die('suc') : die('err');
		}
	}
	
	//督导做结算管理
	if($_REQUEST['a']=='dudao_js'){
		$signModel=new Model_Subtable('sub_sign');
		$assignModel=D('sub_assign');
		$aid=(int)$_GET['aid'];
		$aRow=$assignModel->find($aid);
		$smarty->assign('aRow',$aRow);
		
		//我被指定的非追加的人数
		$zd_sign_row_my=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."' and is_zj=0")->dataRow();
		//echo $zd_sign_row_my['count_num'];
		//查询其他id较小的督导从即非指定也非追加的人员池里拿走了多少人数
		$assign_arr=$assignModel->where("tid='".$aRow['tid']."'")->dataArr();
		$out_num=0;
		foreach($assign_arr as $k=>$v){
			if($v['id'] < $aid){
				//被指定的非追加的人员
				$zd_sign_row1=$signModel->field("count(id) as count_num")->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$v['dudao_uid']."' and is_zj=0")->dataRow();
				//拿走人数
				$out_num+=($v['num']-$zd_sign_row1['count_num']);
			}
		}
		//非追加非指定的人员
		$limit_str=(int)$out_num.','.($aRow['num']-$zd_sign_row_my['count_num']);
		$listArr=$signModel->where("is_valid=1 and is_zj=0 and dudao_uid=0 and tid='".$aRow['tid']."'")->order('id asc')->limit($limit_str)->dataArr();
		if(!$listArr) $listArr=array();
		//已追加给该督导和指定给该督导的报名人员
		$zj_list=$signModel->where("is_valid=1 and tid='".$aRow['tid']."' and dudao_uid='".$userRow['id']."'")->order('id asc')->dataArr();
		if(!$zj_list) $zj_list=array();
		$listArr=array_merge($listArr,$zj_list);
		
		if($listArr){
			$index_key=1;
			foreach($listArr as $key=>$value){
				if($value['is_qd']!=1){
					unset($listArr[$key]);
				}else{
					//从快照中获取用户信息
					$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
					
					$listArr[$key]['username']=$uRow['username'];
					$listArr[$key]['nickname']=$uRow['nickname'];
					$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
					//序号处理
					$listArr[$key]['xuhao']=$index_key;
					$index_key++;
				}
			}
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
		}
		$smarty->assign('list',$listArr);
		$smarty->setLayout('')->setTpl('mobile/templates/assign_dudao_js.html')->display();die;
	}
	
	//结算页面
	if($_REQUEST['a']=='sign_js'){
		$signModel=new Model_Subtable('sub_sign');
		$assignModel=D('sub_assign');
		$data=$_POST;
		//保存照片
		$assignData['info']['id']=$data['aid'];
		$assignData['info']['final_pic']=$data['final_pic'];
		$assignModel->add($assignData);
		//sign报名表是否结算状态修改，10000号决定报名人员是否可结算
		foreach($data['info'] as $k=>$v){
			$temp_arr=explode('_',$k);
			if($v==2){
				$js2IdRow[]=$temp_arr[1];
			}else{
				$js1IdRow[]=$temp_arr[1];
			}
		}
		if($js2IdRow)
			$model->query('update sub_sign set is_js=2 where id in ('.implode(',',$js2IdRow).')');
		if($js1IdRow)
			$model->query("update sub_sign set is_js=1,dudao_uid='".$userRow['id']."' where id in (".implode(',',$js1IdRow).")");
		
		/* //更改职位经理确认状态
		$taskData=array();
		$taskData['num']['id']=$data['tid'];
		$taskData['num']['jingli_queren']=1;
		$taskData['num']['is_shut']=1;
		$model->add($taskData); */
		die('suc');
	}
	
	//经理审核
	if($_REQUEST['a']=='jingli_sh'){
		$tid=$_GET['tid'];
		$model->query("update sub_assign set is_qujingli=1 where tid='{$tid}'");
		die;
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