<?php
/**
 * Model_InfoCommon
 *
 * @package    model
 */
class Model_InfoCommon extends Model_Table {
	
	protected $_name = 'info_common';
	protected static $_type = array(1=>'text',2=>'single',3=>'mutli');
	
	public static function get_type($name){
		$name = strtolower(trim($name));
		return array_search($name, self::$_type);
	}
	
	public function findByWhere($where){
		$filter = array();
		$filter['where'] = $where;
		$sql = $this->select($filter);
		return $this->fetchRow($sql);
	}
}

