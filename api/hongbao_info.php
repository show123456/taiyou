<?php
include_once '../includes/config.inc.php';
class hongbao_info{
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
		if($this->info_type=='hongbao'){
			return $this->gethongbao();
		}
	}

	//预约单图文回复
	public function gethongbao(){
		$applisthongbaoModel = new Model_Applisthongbao();
		$filter['where'] = " customer_id='$this->customer_id' and state='1' ";
		$sql=$applisthongbaoModel->select($filter);
		$inforesult = $applisthongbaoModel->fetchRow($sql);
		$data['msgtype']	= 'news';
		$data['title']		= $inforesult['title'];
		$data['description']= $inforesult['info'];
		$data['picurl']		= 'http://'.$_SERVER['HTTP_HOST']. "/data/image_c/" .$inforesult['pic'];
		if($this->customer_id=='4'){
			$addname='2';
		}else{
			$addname='';
		}
		$data['url'] = 'http://'.$_SERVER['HTTP_HOST'] . "/mobile/hongbao".$addname."/index.php?fromuser=".$this->fromuser."&sign=".$this->timesign;

		return $data;
	}

}
?>