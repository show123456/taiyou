<?php
	/**
	 *每日提报电话号码
	 */
	 
	$model=new Model_Subtable('sub_phone');
	//充值
	if($_REQUEST['a']=='add'){
		if(method_is('post')){
			$data=$_POST;
			//职位信息
			$taskModel=D('sub_task');
			$row=$taskModel->find($data['num']['tid']);
			$data['info']['title']=$row['title'];
			$data['info']['work_date']=substr($row['work_time'],0,10);
			
			$data['info']['uid']=$userRow['id'];
			$res=$model->add($data);
			$res ? die('suc') : die('err');
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/phone_add.html')->display();die;
		}
	}