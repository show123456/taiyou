<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_industry');
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
		$smarty->setTpl('industry/templates/add.html')->display();die();
	}
}
//数据删除
if($_REQUEST['a']=='del'){
	$res=$model->del($_POST['id']);
	echo json_encode($res);die();
}
//数据列表
$condition=array();
if($_GET['keywords']) $condition[]=" name like '%".common_pg('keywords')."%' ";
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
/* foreach($listArr as $key=>$value){
	$listArr[$key]['title']=cut_str(deletehtml($value['title']),20);
} */
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('industry/templates/index.html')->display();