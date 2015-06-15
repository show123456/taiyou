<?php
include_once '../includes/config.inc.php';
class home_info{
	private $info_type;
	private $info_id;
	public  $customer_id;
	public  $location_x;
	public  $location_y;
	public  $fromwhere;
	public  $fromuser;
	public  $web_domain;
	public  $timesign;
	public function getData($result){
		$this->info_type=$result['info_type'];
		$this->info_id=$result['info_id'];
		$this->customer_id=$result['customer_id'];
		$this->location_x=$result['location_x'];
		$this->location_y=$result['location_y'];
		$this->fromwhere=$result['fromwhere'];
		$this->fromuser=$result['fromuser'];
		$this->timesign=$result['timesign'];
		$this->web_domain=$_WGT['WEB_DOMAIN'];
		if($this->info_type=='home'){
			return $this->getHome();
		}
	}

	//预约单图文回复
	public function getHome(){
		$applisthomeModel = new Model_ApplistHome();
		$filter['where'] = " customer_id='$this->customer_id' ";
		$sql=$applisthomeModel->select($filter);
		$inforesult = $applisthomeModel->fetchRow($sql);
		$data['msgtype']	= 'news';
		$data['title']		= $inforesult['info_title'];
		$data['description']= $inforesult['info_intro'];
		$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST']. "/data/image_c/" .$inforesult['info_pic'];
		$data['url'] = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/home/".$inforesult['tpl_ename']."/index.php?fromuser=".$this->fromuser."&sign=".$this->timesign;

		return $data;
	}

}
?>