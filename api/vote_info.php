<?php
include_once '../includes/config.inc.php';
class vote_info{
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
		if($this->info_type=='vote'){
			return $this->getVote();
		}
	}

	//预约单图文回复
	public function getVote(){
		$applistvoteModel = new Model_ApplistVote();
		$filter['where'] = " customer_id='$this->customer_id' and state='1' ";
		$sql=$applistvoteModel->select($filter);
		$inforesult = $applistvoteModel->fetchRow($sql);
		$data['msgtype']	= 'news';
		$data['title']		= $inforesult['title'];
		$data['description']= $inforesult['info'];
		$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST']. "/data/image_c/" .$inforesult['pic'];
		$data['url'] = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/vote/index.php?fromuser=".$this->fromuser."&sign=".$this->timesign;
		return $data;
	}

}
?>