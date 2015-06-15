<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");


$memberModel = new Model_Member();
//var
$customer_id = (int)$_SESSION['customer_id'];
$status      = isset($_REQUEST['status'])    ? (int)$_REQUEST['status']    : 1;
$have_cent   = isset($_REQUEST['have_cent']) ? (int)$_REQUEST['have_cent'] : 1;
$page        = (int) $_REQUEST["p"] > 0      ? (int) $_REQUEST["p"]        : 1;

if(method_is('post') && $_POST['act']=='upda'){
	$have_cent = (int)$_POST['have_cent'];
	$id        = (int)$_POST['id'];
	$memberModel->query("update member set have_cent='$have_cent' where customer_id='$customer_id' and id='$id'");
	echo $have_cent;die();
}

//sql
$filter = array();
$filter['where'] = "customer_id = {$customer_id}";
$filter['order'] = "id desc";
$filter['order'] .= $status ? ',status asc' : ',status desc';
if($have_cent) $filter['order'] .= ',have_cent desc';

//result

$result      = $memberModel->paginate($filter, '*', $page,10);
$pager       = $result['pager'];

//pager
if ($pager['count'] > $pager['size']) {
	$pagerhtml = new SubPages($pager['size'], $pager['count'], $pager['current'], $pager['range'], 2,'');
    $smarty->assign("pagerhtml", $pagerhtml);
}

foreach($result['data'] as $key=>$row){
	$result['data'][$key]['subscribe_time']   = date('Y-m-d',$row['subscribe_time']);
	$result['data'][$key]['unsubscribe_time'] = date('Y-m-d',$row['unsubscribe_time']);
	$result['data'][$key]['status']           = $memberModel->getStatus($row['status']);
}

//smarty
$smarty->assign("memberlist", $result['data']);
$smarty->assign('statusUri',Helper_Page::status(!$status)); //排序uri拼装
$smarty->assign('haveCentUri',Helper_Page::haveCent(!$have_cent)); ////排序uri拼装
$smarty->setTpl('member/templates/list.html')->display();
