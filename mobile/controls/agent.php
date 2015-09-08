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
		$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
	}
	
	//我的返现
	if($_REQUEST['a']=='back'){
		$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
	}