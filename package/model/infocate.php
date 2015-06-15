<?php

class Model_InfoCate extends Model_Table {
	
	protected $_name = 'info_cate';
	protected static $_type = array('text','single','multi','lbs','music','pic','video');
	
	public static function exist_type($name){
		$name = strtolower(trim($name));
		return in_array($name,self::$_type);
	}
	
	public static function getTypeName($name){
		return in_array($name,array('text','single','multi')) || !self::exist_type($name) ? 'common': $name;
	}
	
	public function getCatesByType($info_type){
		$filter = array();
		$filter['where'] = "customer_id='".$_SESSION['customer_id']."' and info_type='{$info_type}'";
		$sql = $this->select($filter,'*');
		return $this->fetchAll($sql);
	}
	
	public function saveDefaultCate($customer_id,$info_type){
		$data = array();
		$data['customer_id'] = $customer_id;
		$data['info_type'] = $info_type;
		$data['cate_name'] = '默认';
		return $this->insert($data);
	}
}

