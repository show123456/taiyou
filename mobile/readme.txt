1==���ֻ���ΪΨһ��ʶ��ͼƬ������⣩

2==ͼƬ������û����������ѵ�¼�û�����Ϣֱ�Ӵ�member���ã�δ��¼�û���ҳ������Ȩдsession�����û���Ϣ������

3==���Ϳͷ���Ϣ
	$configModel=new Model_CustomerConfig();
	$configModel->sendCustomerMsg('����һ��','ow6AGuAOUkUcUrjCyT2isDn9rRJc');
	
4==��ְ�û�is_pay�ֶ�����������ְλ������ѣ���ֱ���ڱ�����������ݣ���������ѣ�����sub_temp_sign��������ݣ�֧������µ�sub_sign��

5==����������is_zj=0������

6==��ȡͷ��
		$memberModel=new Model_Member();
		$smarty->assign('headPic',$memberModel->getPic($userRow['fromuser']));
		
7==�����ݼ������ҳ��
$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;

8==��user���pid�ֶ��жϸ��û��Ƿ�������Ϣ

9==��ȡ����
	$scoreModel=new Model_SubScore('sub_score');
	$tjrScore=$scoreModel->tjr();
	$qdScore=$scoreModel->qd();