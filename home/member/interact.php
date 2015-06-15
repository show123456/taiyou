<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
//var
$customer_id = (int)$_SESSION['customer_id'];
$status      = isset($_REQUEST['status']) ? (int)$_REQUEST['status'] : 1;
$page        = (int) $_REQUEST["p"] > 0 ? (int) $_REQUEST["p"] : 1;

//submit-reply
if(isXmlHttpRequest() && method_is('post')){
	//TODO 验证身份合法性
	$id = isset($_POST['id']) ? (int)$_POST['id'] : '';
	$data = array();
	$data['is_reply'] = 1;
	$data['reply_list'] = isset($_POST['reply_list']) ? str_inmysql($_POST['reply_list']) : '';
	$sqlStatus = Model_Table::get('Message')->updateById($id, $data);  
	ajax_feedback(1,array('is_reply_str'=>Model_Table::get('Message')->is_reply(1)));
}


//sql
$filter = array();
$now_time = time();
$n_time = $now_time-(48*3600);
$filter['where'] = "customer_id = '$customer_id' and create_time > '$n_time' and fromuser !=''";
$filter['order'] = "id desc";
//$filter['order'] .= $status ? ',status asc' : ',status desc';
if($_POST['search']){
	$sea=str_inmysql($_POST['search']);
	$filter['where'] = "customer_id = '$customer_id' and msg_content like '%$sea%' and fromuser !=''";
//如果是ajax请求48小时外信息
}elseif(method_is('get') && $_GET['act']=='history'){
	$filter['where'] = "customer_id = '$customer_id' and create_time < '$n_time' and fromuser!=''";
	//smarty
	$smarty->assign("val", true);
}

//result
$msgModel = new Model_Message();
$result      = $msgModel->paginate($filter, '*', $page);
$pager       = $result['pager'];

//var_dump($_SESSION['customer_id']);die();
//pager

if ($pager['count'] > $pager['size']) {
	$pagerhtml = new SubPages($pager['size'], $pager['count'], $pager['current'], $pager['range'], 2,'');
    $smarty->assign("pagerhtml", $pagerhtml);
}

foreach($result['data'] as $key=>$row){
	$result['data'][$key]['create_time']   = $msgModel->formatTime($row['create_time']);
	$result['data'][$key]['is_reply_str']  = $msgModel->is_reply($row['is_reply']);
}
$filte['where'] = " customer_id='$customer_id' and is_read='0' and create_time > $n_time and fromuser!=''";
$nums = $msgModel->count($filte);
	//smarty
$smarty->assign("nums", $nums);

//smarty
$smarty->assign("list", $result['data']);

//smarty
$smarty->setTpl('member/templates/interact.html')->display();
