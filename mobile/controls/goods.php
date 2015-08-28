<?php
	$model=new Model_ApplistHptShopGoods();
	
	if($_REQUEST['a']=='detail'){
		$id=(int)$_GET['id'];
		$vo=$model->find($id);
		$smarty->assign('vo',$vo);
		$model->query("update applist_hpt_shop_goods set clicknum=clicknum+1 where id='{$id}'");//人气加一
		if($vo['id']==1){
			$current_time=time();
			$time_12=strtotime(date('Y-m-d',$current_time).' 12:00:00');
			$time_cha=$time_12-$current_time;
			$smarty->assign('time_cha',$time_cha);
			$smarty->setLayout('')->setTpl('mobile/templates/detail_lb.html')->display();
		}else{
			$smarty->setLayout('')->setTpl('mobile/templates/detail.html')->display();
		}
	}
	
	if($_REQUEST['a']=='index'){
		//获取商品类别
		$cateModel=new Model_ApplistHptShopCate();
		$smarty->assign('cateList',$cateModel->findAll(" id>0 "));
		//获取幻灯片
		$picModel=new Model_ApplistHptShopPic();
		$smarty->assign('picList',$picModel->fetchAll("select * from applist_hpt_shop_pic order by ordernum desc limit 3"));
		//数据列表
		$condition=array();
		$url=array();
		$condition[]=" state=2 ";//在售
		if($_GET['keyword']){
			$condition[]=" name like '%".common_pg('keyword')."%' ";
		}
		if($_GET['cid']){
			$condition[]=" cid=".common_pg('cid')." ";
		}
		if($condition) $where = implode('and',$condition);
		//排序
		$order=" ordernum desc,id desc ";
		if($_GET['clicknum']){
			$order=" clicknum desc ";
		}
		if($_GET['outnum']){
			$order=" outnum desc ";
		}
		//分页
		$pageSize=10;//页大小
		$p=((int)$_GET['p'])<1 ? 1 : (int)$_GET['p'];//当前页数
		$limitStr = ($p-1)*$pageSize.','.$pageSize;
		
		$listArr = $model->where($where)->order($order)->limit($limitStr)->dataArr();
		foreach($listArr as $key=>$value){
			$listArr[$key]['name']=cut_str(deletehtml($value['name']),10);
			$listArr[$key]['content']=cut_str(deletehtml($value['content']),25);
		}
		
		if($_GET['p']){
			if($listArr){
				echo json_encode($listArr);die;
			}else{
				echo json_encode('err');die;
			}
		}else{
			//购物车数量
			$numArr=$_SESSION['trolley'];
			if($numArr){
				foreach($numArr as $key=>$value){
					$totalNum++;//商品数量
				}
			}
			$smarty->assign('totalNum',$totalNum);
			$smarty->assign('list',$listArr);
			$smarty->setLayout('')->setTpl('mobile/templates/shop.html')->display();die;
		}
	}