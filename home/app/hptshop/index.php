<?php
	include_once("../../../includes/config.inc.php");
	check_login();//验证是否登录
	$model=new Model_ApplistHptShopGoods();
	$cateModel=new Model_ApplistHptShopCate();
	$smarty->assign('cateName','商品');
	//数据保存
	if($_REQUEST['a']=='add'){
		//$tagModel=new Model_ApplistHptShopTag();
		if(method_is('post')){
			$data=$_POST;
			$res=$model->add($data);
			echo json_encode('success');die();
		}else{
			$id=(int)$_GET['id'];
			if($id){
				$smarty->assign('vo',$model->find($id));
			}
			//获取商品类别
			$smarty->assign('cateList',$cateModel->findAll(" id>0 "));
			/* //获取商品标签
			$smarty->assign('tagList',$tagModel->findAll(" gid=$id ")); */
			$smarty->setTpl('app/hptshop/templates/goods_add.html')->display();die();
		}
	}
	//数据删除
	if($_REQUEST['a']=='del'){
		$vo=$model->find($_POST['id']);
		$res=$model->del($_POST['id']);
		//删除原图
		if($res) @unlink("../../../data/image_c/".$vo['pic']);
		echo json_encode($res);die();
	}
	//商品上下架
	if($_REQUEST['a']=='upDown'){
		$data['num'][state]=$_POST['state'];
		$data['num'][id]=$_POST['id'];
		$res=$model->add($data);
		echo json_encode($res);die();
	}
	//数据列表
	//搜索
	$condition=array();
	if($_GET['keywords']) $condition[]=" name like '%".common_pg('keywords')."%' ";
	if($_GET['state']) $condition[]=" state=".common_pg('state')." ";
	if($_GET['cid']) $condition[]=" cid=".common_pg('cid')." ";
	if($condition) $filter['where'] = implode('and',$condition);
	//排序
	$filter['order'] = " id desc ";
	if($_GET['order']==1) $filter['order']=" outnum desc ";
	if($_GET['order']==2) $filter['order']=" outnum asc ";
	if($_GET['order']==3) $filter['order']=" clicknum desc ";
	if($_GET['order']==4) $filter['order']=" clicknum asc ";

	$data = $model->paginate($filter,'*',common_pg('p'),10);
	$listArr = $data['data'];
	foreach($listArr as $key=>$value){
		$listArr[$key]['s_name']=cut_str(deletehtml($value['name']),15);
		$cate_row=$cateModel->find($value['cid']);
		$listArr[$key]['cname']=$cate_row['name'];
	}
	//获取商品类别
	$smarty->assign('cateList',$cateModel->findAll(" id>0 "));
	$smarty->assign('list',$listArr);
	$smarty->assign('page',$model->existPages($data['pager']));
	$smarty->setTpl('app/hptshop/templates/goods_index.html')->display();