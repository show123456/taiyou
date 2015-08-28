<?php
	include_once("../../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=D('sub_kld');
	$smarty->assign('cateName','开乐迪优惠券');
	//数据保存
	if($_REQUEST['a']=='add'){
		if(method_is('post')){
			$data=$_POST;
			$res=$model->add($data);
			echo json_encode($res);die();
		}else{
			$id=(int)$_GET['id'];
			if($id){
				$smarty->assign('vo',$model->find($id));
			}
			$smarty->setTpl('app/hptshop/templates/slide_add.html')->display();die();
		}
	}
	//数据删除
	if($_REQUEST['a']=='del'){
		$res=$model->del($_POST['id']);
		echo json_encode($res);die();
	}
	//更改使用状态
	if($_REQUEST['a']=='use_act'){
		$data=array();
		$data['info']['id']=$_GET['id'];
		$data['info']['is_use']=$_GET['is_use'];
		$model->add($data);die;
	}
	//数据列表
	$userModel=D('sub_user');
	if($_GET['code']){
		$filter['where'] = " code='".$_GET['code']."' ";
	}
	$filter['order'] = " id asc ";
	$data = $model->paginate($filter,'*',common_pg('p'),15);
	$listArr = $data['data'];
	$memberModel=new Model_Member();
	$_GET['p']>0 ? $p=$_GET['p'] : $p=1;
	$current_time=time();
	foreach($listArr as $key=>$value){
		$userRow=$userModel->find($value['uid']);
		$listArr[$key]['info']=$userRow['nickname'].'/'.$userRow['username'];
		$listArr[$key]['xuhao']=($p-1)*15+$key+1;
		//是否过期
		$s=ceil((strtotime($value['addtime'])+15*3600*24-$current_time)/(3600*24));
		if($s <= 0){
			$listArr[$key]['is_out']=1;
		}
	}
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->pager($data['pager']));
	$smarty->setTpl('app/hptshop/templates/kld_index.html')->display();