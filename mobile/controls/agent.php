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