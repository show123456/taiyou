<?php
	$model=new Model_Subtable('sub_agent');
	
	if($_REQUEST['a']=='add'){
		if(method_is('post')){
			$data=$_POST;
			$data['info']['uid']=$userRow['id'];
			$res=$model->add($data);
			$res ? die('suc') : die('err');
		}else{
			$vo=$model->where("uid='".$userRow['id']."'")->dataRow();
			$smarty->assign('vo',$vo);
			$smarty->setLayout('')->setTpl('mobile/templates/agent_add.html')->display();die;
		}
	}
	
	//企业信息完善
	if($_REQUEST['a']=='user_add_company'){
		if(method_is('post')){
			$data=$_POST;
			$res=$model->add($data);
			$res ? die('suc') : die('err');
		}else{
			//苏州市下的区
			$dmodel=new Model_Subtable('s_district');
			$smarty->assign('darr',$dmodel->where("CityID=78")->order("DistrictId asc")->dataArr());
			//微信头像
			$memberModel=new Model_Member();
			$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
			//行业
			$indModel=D('sub_industry');
			$smarty->assign('indArr',$indModel->order('id asc')->dataArr());
			
			$vo=$model->find($userRow['id']);
			//若为中介
			if($vo['industry_id']){
				$ind_row=$indModel->find($vo['industry_id']);
				if($ind_row['name']=='中介'){
					$code=substr(md5($vo['username']),-4);//4位推广码
					$smarty->assign('promote_code',$code);
					//同步sub_code表
					$codeModel=D('sub_code');
					$code_row=$codeModel->where("uid = '".$userRow['id']."'")->dataRow();
					if(!$code_row){
						$code_arr=array();
						$code_arr['info']['uid']=$userRow['id'];
						$code_arr['info']['code']=$code;
						$codeModel->add($code_arr);
					}
				}
			}
			$smarty->assign('vo',$vo);
			$smarty->setLayout('')->setTpl('mobile/templates/user_add_company.html')->display();die;
		}
	}

	if($_REQUEST['a']=='user_index'){
		//微信头像
		$memberModel=new Model_Member();
		$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
		
		$smarty->assign('userRow',$userRow);
		
		if($userRow['type']==2){
			$smarty->setLayout('')->setTpl('mobile/templates/user_index_company.html')->display();die;
		}else{
			//个人用户
			$userExtModel=D('sub_user_ext');
			$userExtRow=$userExtModel->where("uid='".$userRow['id']."'")->dataRow();
			$smarty->assign('userExtRow',$userExtRow);
			//今天参加职位情况
			$taskModel=D('sub_task');
			$signModel=D('sub_sign');
			$signRow=$signModel->where("uid='".$userRow['id']."' and is_qd=1 and task_date='".date('Y-m-d')."'")->dataRow();
			if($signRow){
				$taskRow=$taskModel->find($signRow['tid']);
				$smarty->assign('is_phone',$taskRow['is_phone']);
				$smarty->assign('tid',$signRow['tid']);
			}
			
			$smarty->setLayout('')->setTpl('mobile/templates/user_index_personal.html')->display();die;
		}
	}

	//推荐人返利积分
	function getTjrScore(){
		$scoreModel=new Model_SubScore('sub_score');
		return $scoreModel->tjr();
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

	//提现
	if($_REQUEST['a']=='tx'){
		$fee=(int)$_POST['fee'];
		if($userRow['money'] >= $fee && $fee >= 10){
			//提现表添加数据
			$outData['info']['uid']=$userRow['id'];
			$outData['info']['money']=$fee;
			$outRes=D('sub_out')->add($outData);
			//用户金额减少
			if($outRes && $userRow['id']){
				$userModel->query("update sub_user set money = money - ".$fee." where id='".$userRow['id']."'");
				//写金额日志
				$data1['info']['type']=2;
				$data1['info']['uid']=$userRow['id'];
				$data1['info']['money']=0 - $fee;
				$data1['info']['desc']='提现表id_'.$outRes;
				D('sub_money_log')->add($data1);
				die('suc');
			}else{
				die('err');
			}
		}
		die('err');
	}

	//退出登录
	if($_REQUEST['a']=='logout'){
		unset($_SESSION['tyuser']);
		unset($_SESSION['picuser']);
		setCookie('tyuid','',time()-1);
		$_COOKIE['tyuid']='';
	}

	//金库
	if($_REQUEST['a']=='jk'){
		$moneyLogModel=D('sub_money_log');
		$moneyLogRow=$moneyLogModel->field("sum(money) as totalmoney")->where("(`type`=3 or `type`=0) and uid='".$userRow['id']."' and addtime>='".date('Y-m-d')." 00:00:00'")->dataRow();
		$smarty->assign('totalmoney',$moneyLogRow['totalmoney']);
		$smarty->setLayout('')->setTpl('mobile/templates/user_jk.html')->display();die;
	}

	//由id获取个人基本信息
	if($_REQUEST['a']=='get_person_info'){
		$id=(int)$_GET['id'];
		if($id && $userRow['id']==1){
			$res=$userModel->field('nickname,username')->where("id='{$id}'")->dataRow();
			$result=$res['nickname'].$res['username'];
		}
		$result ? die($result) : die('err');
	}

	//绑定微信号手机号
	if($_REQUEST['a']=='bind_wxh'){
		if($_REQUEST['code'] && $fromuser==''){
			$wx_code=$_REQUEST['code'];
			$wx_user_json=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$wx_code."&grant_type=authorization_code");
			$wx_res=json_decode($wx_user_json);
			if(!$wx_res->openid){
				echo "<script>alert('绑定失败！');window.location.href='index.php?m=user&a=user_add_personal';</script>";
			}
			$fromuser=$wx_res->openid;
			$user_data['info']['id']=$userRow['id'];
			$user_data['info']['fromuser']=$fromuser;
			$userModel->add($user_data);
			echo "<script>alert('绑定成功！');window.location.href='index.php?m=user&a=user_add_personal';</script>";
		}else{
			if($fromuser==''){
				$redirectUri=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//openid处理页面(当前页面)
				echo '<script>window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirectUri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect"</script>';die;
			}
		}
	}