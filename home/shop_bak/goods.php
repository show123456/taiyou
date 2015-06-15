<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_shop_goods');
$cateModel=new Model_Subtable('sub_shop_cate');
//数据保存
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		//删除原图
		if($data['num'][id]){
			$vo=$model->find($data['num'][id]);
			if($vo['pic']!=$data['info'][pic]) @unlink("../../data/image_c/".$vo['pic']);;
		}
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$id=(int)$_GET['id'];
		if($id){
			$smarty->assign('vo',$model->find($id));
		}
		//获取商品类别
		$smarty->assign('cateList',$cateModel->dataArr());
		$smarty->setTpl('shop/templates/goods_add.html')->display();die();
	}
}
//数据删除
if($_REQUEST['a']=='del'){
	$vo=$model->find($_POST['id']);
	$res=$model->del($_POST['id']);
	//删除原图
	if($res) @unlink("../../data/image_c/".$vo['pic']);
	echo json_encode($res);die();
}
//数据列表
$cateList=$cateModel->dataArr();
foreach($cateList as $v){
	$cateRow[$v['id']]=$v['name'];
}
$condition=array();
if($_GET['keywords']) $condition[]=" name like '%".common_pg('keywords')."%' ";
if($_GET['cid']) $condition[]=" cid=".common_pg('cid')." ";
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$listArr[$key]['s_name']=cut_str(deletehtml($value['name']),6);
	$listArr[$key]['cname']=$cateRow[$value['cid']];
}
//获取商品类别
$smarty->assign('cateList',$cateList);
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('shop/templates/goods_index.html')->display();