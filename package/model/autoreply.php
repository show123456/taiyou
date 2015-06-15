<?php

class Model_AutoReply extends Model_Table {
	
	protected $_name = 'auto_reply';
	
	public function findByCustomerId($customer_id,$type_id){
		$filter = array();
		$filter['where'] = "customer_id={$customer_id} and type_id={$type_id}";
		$sql = $this->select($filter);
		return $this->fetchRow($sql);
	}
}

