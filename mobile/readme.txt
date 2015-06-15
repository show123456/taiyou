1==以手机号为唯一标识（图片分享除外）

2==图片分享的用户处理，若是已登录用户，信息直接从member表拿，未登录用户网页弹窗授权写session，该用户信息不保存

3==发送客服消息
	$configModel=new Model_CustomerConfig();
	$configModel->sendCustomerMsg('测试一下','ow6AGuAOUkUcUrjCyT2isDn9rRJc');
	
4==兼职用户is_pay字段舍弃，若该职位无申请费，则直接在报名表添加数据；若有申请费，先在sub_temp_sign表添加数据，支付后更新到sub_sign表

5==报名人数是is_zj=0的人数

6==获取头像
		$memberModel=new Model_Member();
		$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
		
7==无数据加载这个页面
$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;

8==以user表的pid字段判断该用户是否完善信息

9==获取积分
	$scoreModel=new Model_SubScore('sub_score');
	$tjrScore=$scoreModel->tjr();
	$qdScore=$scoreModel->qd();