<?php

class Model_Customer extends Model_Table {
	
	protected $_name = 'customer';
	
	public function findByEmail($email){
		$sql = "select * from {$this->_name} where email='{$email}'";
    	return $this->fetchRow($sql);
	}
	
    public function login($email, $user_pass){
    	global $_WGT;
    	if(!$email || !$user_pass) return array('success'=>0, 'content'=>1111);
    	
    	$row = $this->findByEmail($email);
    	if(!$row) return array('success'=>0, 'content'=>1112);
        if($row['user_pass'] != md5($user_pass)) return array('success'=>0, 'content'=>1112);
    	if($row['is_valid'] != 1) return array('success'=>0, 'content'=>1113);
    	
        $_SESSION['customer_id']     = $row['id'];
    	$_SESSION['email']           = $row['email'];
        $_SESSION['last_login_date'] = $row['last_login_date'];
        $_SESSION['last_login_ip']   = $row['last_login_ip'];
        $_SESSION['customer_id_photo']   = $row['photo'];
        $_SESSION['weixin_name']=$row['weixin_name'];
    	$sql = "update {$this->_name} set last_login_ip='{$_WGT['IP']}',last_login_date='".date('Y-m-d H:i:s',$_WGT['TIME'])."',login_num=login_num+1 where id='{$row['id']}'";
    	$this->query($sql);
    	return array('success'=>1);
    }
    
    
    public function register($data){
    	
    	$row = $this->findByEmail($data['email']);
	    if($row) return array('success'=>0, 'content'=>1102);
	
	    $result = $this->insert($data);
	    return array('success'=>$result ? 1 : 0);
    }
}

