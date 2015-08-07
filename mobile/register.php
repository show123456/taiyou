<?php
include_once("../includes/config.inc.php");
if(!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) die("请在微信浏览器中打开");//判断是否微信打开
$customer_id=1378;
$model=new Model_Subtable('sub_user');
//中间跳转页
if($_REQUEST['a']=='mid_reg'){
	$smarty->setLayout('')->setTpl('mobile/templates/mid_reg.html')->display();
	die;
}
//注册
if($_REQUEST['a']=='doregister'){
	$data=$_POST;
	if($data['username']==$data['tjr_phone']){
		echo 'cf';die;
	}
	//openid查重
	$userRow=$model->where("fromuser='".$data['fromuser']."'")->dataRow();
	if($userRow){
		echo 'wx_exist';die;
	}
	//填写的推荐人是否存在
	if($data['tjr_phone']){
		$userRow=$model->where("username='".$data['tjr_phone']."'")->dataRow();
		if(!$userRow){
			echo 'no_tjr';die;
		}else{
			$tjr_uid=$userRow['id'];
		}
	}
	//账号查重
	$userRow=$model->where("username='".$data['username']."'")->dataRow();
	if($userRow){
		echo 'exist';die;
	}
	//手机验证码核对
	if($data['username']!=$_SESSION['mobile'] or $data['code']!=$_SESSION['mobile_code'] or empty($data['username']) or empty($data['code'])){
		echo 'codeerr';die;
	}
	//微信身份标识
	if($data['fromuser']==''){
		echo 'wxerr';die;
	}
	//数据插入
	$info['info']['fromuser']=$data['fromuser'];
	$info['info']['username']=$data['username'];
	$info['info']['pass']=md5($data['pass']);
	if($data['type']){
		$info['info']['type']=$data['type'];
		$info['info']['nickname']=$data['nickname'];
		$info['info']['contact_name']=$data['contact_name'];
		$info['info']['contact_post']=$data['contact_post'];
		$info['info']['bank_card']=$data['bank_card'];
		$info['info']['pic']=$data['pic'];
	}
	$res=$model->add($info);
	if($res){
		$_SESSION['mobile']='';
		$_SESSION['mobile_code']='';
		//写登录session
		$_SESSION['tyuser']=$model->field('id,username,pass,fromuser')->where("id={$res}")->dataRow();
		//推荐人处理
		if($data['tjr_phone']){
			$tj_data['info']['tjr_uid']=$tjr_uid;
			$tj_data['info']['reg_uid']=$res;
			$tj_data['info']['add_date']=date('Y-m-d');
			D('sub_tjr')->add($tj_data);
		}
	}
	echo $res;die;
}

//微信用户标识
include_once("get_openid.php");
$smarty->assign('fromuser',$fromuser);

//防止恶意请求的随机码
$_SESSION['send_code'] = code_random(6,1);
$smarty->assign('send_code',$_SESSION['send_code']);
$smarty->setLayout('')->setTpl('mobile/templates/register.html')->display();