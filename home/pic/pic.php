<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_pic');
//数据保存
if($_REQUEST['a']=='add'){
	if(method_is('post')){
		$data=$_POST;
		$data['str'][name]='云姐';
		$data['info'][up_time]=time();
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
		$smarty->setTpl('pic/templates/pic_add.html')->display();die();
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
//评论列表
if($_REQUEST['a']=='detail'){
	$vo=$model->find($_GET['id']);
	$smarty->assign('vo',$vo);
	
	$replyModel=new Model_Subtable('sub_pic_reply');
	$replyList=$replyModel->order('id desc')->limit('10')->dataArr();
	$smarty->assign('replyList',$replyList);
	$smarty->setLayout('')->setTpl('mobile/templates/pic_detail.html')->display();die;
}
//数据列表
$filter['order'] = " is_up desc,up_time desc,id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$listArr[$key]['introduce']=cut_str(deletehtml($value['introduce']),20);
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('pic/templates/pic_index.html')->display();