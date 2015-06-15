<?php
/**find($id)，根据id查询数据
 *fetchAll($sql)，返回二维数组
 *fetchRow($sql)，返回一行数据
 *count($filter)，返回总数
 */
class Model_Cctable extends Model_Table {
	protected $_field;
	protected $_where;
	protected $_order;
	protected $_limit;
	
	//数据添加则返回新增id，数据修改则返回影响行数
	public function add($arr){
		$data=$this->dataFilter($arr);
		$id = (int)$data['id'];
		if($id){
			$sqlArray = array();
			foreach($data as $key=>$value){
				if($key!='id') $sqlArray[] = "`{$key}`='{$value}'";
			}
			$sql = "update `{$this->_name}` set ".implode(',',$sqlArray)." where id={$id}";
			$this->query($sql);
			return mysql_affected_rows();
		}else{
			$sqlArray = array();
			foreach($data as $key=>$value){
				if($key!='id') $sqlArray[$key] = $value;
			}
			$keys = '`'.implode('`,`',array_keys($sqlArray)).'`';
			$values = "'".implode("','",$sqlArray)."'";
			$sql = "insert into {$this->_name} ({$keys}) values ({$values})";
			$this->_db->query($sql);
			return $this->_db->insertId();
		}
    }
	
	//按条件查询数据
	public function findAll($where=''){
		if($where){
			return $this->fetchAll("select * from {$this->_name} where ".$where);
		}else{
			return $this->fetchAll("select * from {$this->_name} ");
		}
		
	}
	
	//重写delete方法
	public function delete($where){
    	$sql = "delete from {$this->_name} where ".$where;
    	$this->query($sql);
		return mysql_affected_rows();
    }
	
	/**删除一条数据
	 */
	public function del($id){
    	$id = (int)$id;
    	$sql = "delete from {$this->_name} where id=".$id;
    	$this->query($sql);
		return mysql_affected_rows();
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
	
	/**判断是否需要分页
	 *SubPages第四个参数为每次显示的页数
     *SubPages第五个参数为分页的类型1普通2经典
	 */
	public function existPages($pageArr){
		$pagerhtml='';
		if ($pageArr['count'] > $pageArr['size']){
			$pagerhtml = new SubPages($pageArr['size'],$pageArr['count'],$pageArr['current'],$pageArr['range'],2,'');
		}
		return $pagerhtml;
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
		return $this->fetchAll($sql);
	}

	public function dataRow(){
		$sql="select ";
		if($this->_field){
			$sql.="{$this->_field} ";
		}else{
			$sql.="* ";
		}
		$sql.="from {$this->_name} ";
		if($this->_where) $sql.=$this->_where;
		return $this->fetchRow($sql);
	}
}
?>