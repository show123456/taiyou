<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_shop_order');
$shipModel=new Model_Subtable('sub_shop_ship');
//数据保存
if($_REQUEST['a']=='add'){
	$data=$_POST;
	$res=$model->add($data);
	echo json_encode($res);die();
}
//详情
if($_REQUEST['a']=='detail'){
	$m=new Model_Subtable('sub_shop_odetail');
	$id=(int)$_GET['id'];
	$vo=$model->find($id);
	//收货人信息
	$pmodel=new Model_Subtable('s_province');
	$cmodel=new Model_Subtable('s_city');
	$dmodel=new Model_Subtable('s_district');
	$shipRow=$shipModel->find($vo['shipid']);
	$prow=$pmodel->where("ProvinceID=".$shipRow['pid'])->dataRow();
	$shipRow['pname']=$prow['ProvinceName'];
	$crow=$cmodel->where("CityID=".$shipRow['cid'])->dataRow();
	$shipRow['cname']=$crow['CityName'];
	$drow=$dmodel->where("DistrictID=".$shipRow['did'])->dataRow();
	$shipRow['dname']=$drow['DistrictName'];
	$smarty->assign('shipRow',$shipRow);
	
	if($id){
		$smarty->assign('vo',$vo);
		$detailList=$m->where(" oid=$id ")->dataArr();
		foreach($detailList as $key=>$value){
			$detailList[$key]=unserialize($value['goods_row']);
			$detailList[$key]['oid']=$value['oid'];
			$detailList[$key]['num']=$value['num'];
			$detailList[$key]['money']=$value['money'];
			$detailList[$key]['name']=cut_str(deletehtml($detailList[$key]['name']),10);
		}
		$smarty->assign('detailList',$detailList);
	}
	$smarty->setTpl('shop/templates/order_add.html')->display();die();
}
//数据列表
$condition=array();
$filter=array();
if($_GET['keywords']) $condition[]=" id=".common_pg('keywords')." ";
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " addtime desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$listArr[$key]['addtime']=substr($value['addtime'],0,16);
	$row=$shipModel->find($value['shipid']);
	$listArr[$key]['name']=$row['name'];
	$listArr[$key]['phone']=$row['phone'];
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('shop/templates/order_index.html')->display();