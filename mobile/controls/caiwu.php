<?php
	$model=new Model_Subtable('sub_task');
	
	//职位列表页
	if($_REQUEST['a']=='task_index'){
		$pageSize=15;//页大小
		$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
		$limitStr = ($p-1)*$pageSize.','.$pageSize;
		
		$listArr=$model->where("sh_status=1")->order('id desc')->limit($limitStr)->dataArr();
		foreach($listArr as $key=>$value){
			$listArr[$key]['title']=cut_str(deletehtml($value['title']),15);
		}
		
		if($_GET['p']){
			if($listArr){
				echo json_encode($listArr);die;
			}else{
				echo json_encode('err');die;
			}
		}else{
			$smarty->assign('list',$listArr);
			$smarty->setLayout('')->setTpl('mobile/templates/caiwu_task_index.html')->display();die;
		}
	}
	
	//结算页面
	if($_REQUEST['a']=='sign_js'){
		$signModel=new Model_Subtable('sub_sign');
		$logModel=new Model_Subtable('sub_money_log');
		if(method_is('post')){
			$data=$_POST;
			$taskRow=$model->field('id,pay_type,is_js')->where("id=".$data['tid'])->dataRow();
			//1现金日结，用户金额不增加
			if($taskRow['pay_type']==1 && $taskRow['is_js']==0){
				$listArr=$signModel->where("is_js=1 and tid=".$data['tid'])->dataArr();
				foreach($listArr as $k=>$v){
					//写金额日志
					$logData=array();
					$logData['info']['type']=7;//现金日结
					$logData['info']['uid']=$v['uid'];
					$logData['info']['money']=$v['fact_money'];
					$logData['info']['desc']=$v['tid'];
					$logModel->add($logData);
				}
			}
			//2转账日结，用户金额增加
			if($taskRow['pay_type']==2 && $taskRow['is_js']==0){
				$listArr=$signModel->where("is_js=1 and tid=".$data['tid'])->dataArr();
				foreach($listArr as $k=>$v){
					//用户金额增加
					$signModel->query("update sub_user set money = money + ".$v['fact_money']." where id='".$v['uid']."'");
					//写金额日志
					$logData=array();
					$logData['info']['type']=3;
					$logData['info']['uid']=$v['uid'];
					$logData['info']['money']=$v['fact_money'];
					$logData['info']['desc']=$v['tid'];
					$logModel->add($logData);
				}
			}
			//更改职位结算状态
			$taskData['num']['id']=$data['tid'];
			$taskData['num']['is_js']=1;
			$model->add($taskData);
			die('suc');
		}else{
			$listArr=$signModel->where("is_qd=1 and tid=".$_GET['tid'])->order('distance asc')->dataArr();
			//应结算人数
			$listRow=$signModel->field("count(*) as countnum")->where("is_qd=1 and tid=".$_GET['tid'])->dataRow();
			$smarty->assign('countnum',$listRow['countnum']);
			
			if($listArr){
				foreach($listArr as $key=>$value){
					//从快照中获取用户信息
					$value['user_json'] ? $uRow=unserialize($value['user_json']) : $uRow=$userModel->find($value['uid']);
					
					$listArr[$key]['username']=$uRow['username'];
					$listArr[$key]['nickname']=$uRow['nickname'];
					$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
					//序号处理
					$listArr[$key]['xuhao']=$key+1;
				}
			}else{
				$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
			}
			$smarty->assign('list',$listArr);
			$smarty->setLayout('')->setTpl('mobile/templates/caiwu_sign_js.html')->display();die;
		}
	}
	
	//改变金额
	if($_REQUEST['a']=='change_money'){
		$signModel=new Model_Subtable('sub_sign');
		$data=array();
		$data['info']['id']=(int)$_POST['id'];
		$data['info']['fact_money']=(int)$_POST['fact_money'];
		$signModel->add($data);
	}
	
	//驳回处理
	if($_REQUEST['a']=='back_act'){
		$data=array();
		$data['info']['id']=(int)$_POST['tid'];
		$data['info']['jingli_queren']=0;
		$model->add($data);
	}