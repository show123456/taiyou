<?php
class Model_Table {

    protected $_db;
	protected $_name;
	static $tables = array();
    protected static $_defaultDb;
    
    public function __construct(){ $this->_db = self::getAdapter();}
    public static function getAdapter(){ return self::$_defaultDb; }
    public static function setAdapter($config){ 
    	self::$_defaultDb = new DB_Sql($config['Host'],$config['User'],$config['Password'],$config['Database']); 
    }

	static public function get($table_name){
	    if(isset(Model_Table::$tables[$table_name])){
	        return Model_Table::$tables[$table_name];
	    }
	    $class_name = "Model_".$table_name;
        Model_Table::$tables[$table_name] = new $class_name;
        return Model_Table::$tables[$table_name];
	}

    public function query($sql){
        
		$id = $this->_db->query($sql);
		if($this->_db->insertId()){
			return $this->_db->insertId();
		}else{
			return $id ;
		}
    }
    
	public function insert($data){
		$keys = '`'.implode('`,`',array_keys($data)).'`';
		$values = "'".implode("','",$data)."'";
		$sql = "insert into {$this->_name} ({$keys}) values ({$values})";

		$this->_db->query($sql);
		return $this->_db->insertId();
	}

	public function upsert($data){
		$keys = '`'.implode('`,`',array_keys($data)).'`';
		$values = "'".implode("','",$data)."'";
		$suffix = '';

		foreach($data as $k=>$v){ $suffix .= '`'.$k.'`'."= '".$v."' ,"; }
		$sql = "insert into {$this->_name} ({$keys}) values ({$values}) on duplicate key update ".trim($suffix,',');
		return $this->query($sql);
	}

    public function fetchAll($sql = ""){
        $result = array();        
        $query = $this->query($sql);

        while($row = mysql_fetch_assoc($query)){
             $result[] = $row;
        }
        return $result;
    }
    
    public function fetchRow($sql = ""){
        $row = array();
        $query = $this->query($sql);

        $row = @mysql_fetch_array($query, MYSQL_ASSOC);
        return $row;
    }
    
    public function find($id){
    	$id = (int)$id;
    	$sql = "select * from {$this->_name} where id={$id}";
    	return $this->fetchRow($sql);
    }
    public function select_data()
    {
    	$sql = "select * from {$this->_name}";
    	return $this->fetchRow($sql);
    }
    
    public function delete($where){
    	$sql = "delete from {$this->_name} where ".$where;
       
    	return $this->query($sql);
    }
    
    public function updateById($id, $data){
    	$id = (int)$id;
    	$sqlArray = array();
	    foreach($data as $key=>$value){
	        $sqlArray[] = "`{$key}`='{$value}'";
	    }
	    $sql = "update `{$this->_name}` set ".implode(',',$sqlArray)." where id={$id}";
	 
	    return $this->query($sql);
    }
    //根据ID查询
    public function select_one($where){
    	//$id = (int)$id;
    	$sql = "select * from {$this->_name} where $where";
    	return $this->fetchRow($sql);
    }
    public function row_update($data,$where){
    	$sqlArray = array();
    	foreach($data as $key=>$value){
    		$sqlArray[] = "`{$key}`='{$value}'";
    	}
    	$sql = "update `{$this->_name}` set ".implode(',',$sqlArray)." where $where limit 1";
    
    	return $this->query($sql);
    }
    
    /**
     * 
     * 分页查询
     * @param array $filter
     * @param array|string $field
     * @param string $page
     * @param string $size
     * @param string $range
     * @paginate like  <prev 1 2 3 4 5 next>
     */
    public function paginate($filter=null, $field='*', $page=1, $size=10, $range=5)
    {
    	$page    = (int)$page   >  0 ? (int)$page : 1;  //当前页
    	$size    = (int)$size   >  0 ? (int)$size : 10; //每页条数
    	$range   = (int)$range  >= 0 ? (int)$range : 5; //步长
    	$count   = $this->count($filter);               //总条数
    	$pagenum = ceil($count/$size);                  //总页数
    	$pos     = max(0,min($page-1,$pagenum-1));
		$offset  = $pos*$size;    //查询起始位置
		
		$filter['limit']  = $size;
		$filter['offset'] = $offset;
		
		$result         = array();
    	$sql            = $this->select($filter, $field);
    	$result['data'] = $this->fetchAll($sql);
    	
    	if($range > $pagenum) $range = $pagenum;
    	
    	$rangeLeft  = max($page-floor($range/2), 1);
    	$rangeRight = min(ceil($range/2)+$page , $pagenum);

    	$result['pager'] = array(
            
    	    'pagenum' => $pagenum, //总页数
    	    'count'   => $count,   //总条数
    	    'size'    => $size,    //每页条数
 
    	    'prev'    => max(1,$page-1),         //上一页
    	    'current' => $page,                  //当前页
    	    'next'    => min($pagenum, $page+1), //下一页
    	    
    	    'range'   => $range,                       //步长
    	    'left'    => $rangeLeft,                   //步长开始位置
    	    'right'   => max($rangeLeft, $rangeRight), //步长结束位置
    	);
		return $result;
    }
    
    
    public function count($filter){
    	$countSql = $this->select($filter, 'count(*)');
    	$countRes = $this->fetchRow($countSql);
    	return $countRes['count(*)'];
    }
    
    /**
     * 
     * sql拼装
     * @param string|array $filter
     * @param string|array $field
     * @param boolean $count
     */
    public function select($filter, $field='*'){
    	
    	if(is_array($field)) $field = '`'.implode('`,`',$field).'`';

    	$sql = "select {$field} from {$this->_name}";
        if(is_array($filter)){
        	foreach ($filter as $key=>$value){
        		
        		$key = strtolower($key);
        		if(trim($field)=='count(*)' && in_array($key,array('order','limit','offset'))) continue;
        		switch($key){
        			case 'order':
        			case 'group':
        				$sql .= ' '.$key.' by '.$value;
        				break;
        			default:
        				$sql .= ' '.$key.' '.$value;
        				break;
        		}
        	}
        }
        
        return $sql;
    }
    
    /**
     * 
     * 方法不存在时调用DB_Sql类里的方法
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args){
    	$num = count($args);

    	if(!$args)  return $this->_db->$name(); 
    	if($num==1) return $this->_db->$name($args[0]);
    	if($num==2) return $this->_db->$name($args[0],$args[1]);
    }
    
    //simple functions
    public function formatTime($my_time,$time_type=''){
        if($time_type=='date') $my_time = strtotime($my_time);
    	
        $my_date = date('Ymd',$my_time);
        $now_date = date('Ymd');
        if($now_date == $my_date){
            $my_time = date('H:i',$my_time);
        }else if(date('Ymd',(strtotime($now_date)-86400)) == $my_date){
            $my_time = '昨天 '.date('H:i', $my_time);
        }else if(date('Ymd',(strtotime($now_date)+3600*24)) == $my_date){
            $my_time = '明天'.date('H:i', $my_time);
        }else if((date('Y')-1) >= date('Y',$my_time)){
            $my_time = date('Y-m-d',$my_time);
        }else{
            $my_time = date('m-d H:i',$my_time);
        }

        return $my_time;
    }
}