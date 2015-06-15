<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
if($_REQUEST['typenum']==1){
	$model=new Model_Subtable('sub_reply');
}else{
	$model=new Model_Subtable('sub_pic_reply');
}
//数据保存
if($_REQUEST['a']=='reply'){
	if(method_is('post')){
		//管理员头像
		$uRow=D('sub_user')->find('1');
		$data['info']['head_pic']='/data/image_c/'.$uRow['head_pic'];
		
		$id=$_POST['id'];
		$talkRow=$model->find($id);
		
		$data['info']['name']='云姐';
		$data['info']['tomanname']=$talkRow['name'];
		$data['info']['content']=$_POST['content'];
		if($_POST['typenum']=='2'){
			$data['info']['pid']=$talkRow['pid'];
			$data['info']['fromuser']=1;
			$data['info']['tomanfromuser']=$talkRow['fromuser'];
		}else{
			$data['info']['tid']=$talkRow['tid'];
			$data['info']['uid']=1;
			$data['info']['tomanid']=$talkRow['uid'];
		}
		$res=$model->add($data);
		
		if($res) echo json_encode($res);exit;
	}
}
//数据删除
if($_REQUEST['a']=='del'){
	$res=$model->del($_POST['id']);
	echo json_encode($res);die();
}
//数据列表
$taskModel=D('sub_task');
$condition=array();
if($_GET['keywords']) $condition[]=" manname like '%".common_pg('keywords')."%' ";
if($condition) $filter['where'] = implode('and',$condition);
$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	//$listArr[$key]['content']=cut_str(deletehtml($value['content']),20);
	if(!$value['name']) $listArr[$key]['name']='匿名';
	//显示职位名
	$taskRow=$taskModel->field('id,title')->where("id='".$value['tid']."'")->dataRow();
	$listArr[$key]['title']=$taskRow['title'];
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('reply/templates/talk_index.html')->display();