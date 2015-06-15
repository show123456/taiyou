<?php
class Model_Subtable extends Model_Table{
	protected $_field;
	protected $_where;
	protected $_order;
	protected $_limit;
    
    public function __construct($name){
		parent::__construct();
		$this->_name=$name;
	}
	
	//**************根据条件删除
	public function delete($where){
    	$sql = "delete from {$this->_name} where ".$where;
    	$this->query($sql);
		return mysql_affected_rows();
    }
    
	//**************根据id删除一条数据
	public function del($id){
    	$id = (int)$id;
    	$sql = "delete from {$this->_name} where id=".$id;
    	$this->query($sql);
		return mysql_affected_rows();
    }
    
    /**
	 **************分页
	 *SubPages第四个参数为每次显示的页数
     *SubPages第五个参数为分页的类型1普通2经典
	 */
	public function pager($pageArr){
		$pagerhtml='';
		if ($pageArr['count'] > $pageArr['size']){
			$pagerhtml = new SubPages($pageArr['size'],$pageArr['count'],$pageArr['current'],$pageArr['range'],2,'');
		}
		return $pagerhtml;
	}
	
	//**************数据保存，$data一维数组形式，$pk为主键名，默认为id
	public function add($data,$pk='id'){
		$data=$this->dataFilter($data);
		$id = (int)$data[$pk];
		if($id){
			$sqlArray = array();
			foreach($data as $key=>$value){
				if($key!=$pk) $sqlArray[] = "`{$key}`='{$value}'";
			}
			$sql = "update `{$this->_name}` set ".implode(',',$sqlArray)." where {$pk}={$id}";
			$this->query($sql);
			return mysql_affected_rows();
		}else{
			$keys = '`'.implode('`,`',array_keys($data)).'`';
			$values = "'".implode("','",$data)."'";
			$sql = "insert into {$this->_name} ({$keys}) values ({$values})";
			$this->_db->query($sql);
			return $this->_db->insertId();
		}
    }
	
	public function field($field){
		$this->_field=$field;
		return $this;
	}
	
	public function where($where){
		$this->_where=" where $where";
		return $this;
	}
	
	public function order($order){
		$this->_order=" order by $order";
		return $this;
	}
	
	public function limit($limit){
		if(strpos($limit,',')){
			$arr=explode(',',$limit);
			$this->_limit=" limit ".$arr[0].",".$arr[1];
		}else{
			$this->_limit=" limit ".intval($limit);
		}
		return $this;
	}

	//**************查询多条
	public function dataArr(){
		$sql="select ";
		if($this->_field){
			$sql.="{$this->_field} ";
		}else{
			$sql.="* ";
		}
		$sql.="from {$this->_name} ";
		if($this->_where) $sql.=$this->_where;
		if($this->_order) $sql.=$this->_order;
		if($this->_limit) $sql.=$this->_limit;
		$this->clearCondition();
		return $this->fetchAll($sql);
	}

	//**************查询单条
	public function dataRow(){
		$sql="select ";
		if($this->_field){
			$sql.="{$this->_field} ";
		}else{
			$sql.="* ";
		}
		$sql.="from {$this->_name} ";
		if($this->_where) $sql.=$this->_where;
		$this->clearCondition();
		return $this->fetchRow($sql);
	}
	
	//清空条件
	private function clearCondition(){
		$this->_field=null;
		$this->_where=null;
		$this->_order=null;
		$this->_limit=null;
	}
	
	//过滤输入数据
	public function dataFilter($arr){
		if($arr[info]) $data=$arr[info];
		if($arr[num]){
			foreach($arr[num] as $k=>$v){
				$data[$k]=(int)$v;
			}
		}
		if($arr[str]){
			foreach($arr[str] as $k=>$v){
				$data[$k]=str_inmysql($v);
			}
		}
		return $data;
	}
}