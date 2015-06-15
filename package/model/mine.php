<?php

class Model_Mine extends Model_Table {

	protected $data = array();
	protected $customer_id = '';
	
	public function __construct(){
		
		$this->customer_id = $_SESSION['customer_id'] ? $_SESSION['customer_id'] : $_WGT['m_customer_id'];;
		parent::__construct();
	}
	
	//Processing POST Data
	public function PPD(){
		$data['customer_id'] = $this->customer_id;
		foreach($_POST as $key=>$val){
		$cate = substr($key,0,3);
			switch($cate){
				case 'in_':$data[substr($key,3)]= (int)$val;break;
				case 'te_':
					if(strlen($val)==11 && (int)$val || substr_count($val,'-')==1 && strlen($val)==12){
						$data[substr($key,3)] = (int)$val;
					}else{
						die('联系方式错误');
					}
					break;
				case "ur_":if($val)preg_match("/^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/",$val) ? $data[substr($key,3)]= str_inmysql($val) : die("您输入的网址不正确");
				break;
				default:$data[$key]= str_inmysql($val);
			}
		}
		//$data['customer_id'] = $this->customer_id;
		$data['id'] ? (int)$data['id'] : $data['create_date'] = date("Y-m-d H:i:s");
		$this->data = $data;
		return $this;
	}
	
	public function SAVE(){
		$Arr  = $this->data;
		$Arr['id'] ? $this->row_update($Arr,"id='$Arr[id]' and customer_id=".$this->customer_id) && $nowId = $Arr['id'] : $nowId = $this->insert($Arr);
		return $nowId;
	}
	
	public function DEL($del_id){
		strpos($del_id,"=") ? $oncest = $this->delete($del_id." and customer_id=".$this->customer_id) : $oncest = $this->delete("id='$del_id' and customer_id=".$this->customer_id);
		return $oncest;
	}
}

