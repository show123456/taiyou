<?php
$model=new Model_ApplistHptShopGoods();
if($_REQUEST['a']=='index'){
	$id=(int)$_GET['id'];
	$smarty->assign('vo',$model->find($id));
	$smarty->setLayout('')->setTpl('mobile/templates/detail.html')->display();
}