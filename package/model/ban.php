<?php
class Model_Ban extends Model_Subtable{
	//判断是否无登录权限
	public function no_login_rights($userRow){
		$arr=array();
		if($userRow){
			if($userRow['cardnum']){
				$ban_where_str="type=1 and (username='".$userRow['username']."' or cardnum='".$userRow['cardnum']."')";
			}else{
				$ban_where_str="type=1 and username='".$userRow['username']."'";
			}
			$ban_row=$this->where($ban_where_str)->order('id desc')->dataRow();//查询最近的禁闭记录
			if($ban_row){
				$ban_end_time=strtotime($ban_row['end_time'].' 00:00:00')-time();
				if($ban_end_time > 0 || ($ban_row['end_time'] && strtotime($ban_row['end_time'].' 00:00:00')=='')){
					return $ban_end_time;
				}
			}
		}
		return false;
	}
	
	//判断是否无发言或报名权限，2禁言，3禁报名
	public function no_rights($userRow,$type=2){
		$arr=array();
		if($userRow){
			if($userRow['cardnum']){
				$ban_where_str="(type='{$type}' or type='4') and (username='".$userRow['username']."' or cardnum='".$userRow['cardnum']."')";
			}else{
				$ban_where_str="(type='{$type}' or type='4') and username='".$userRow['username']."'";
			}
			$ban_row=$this->where($ban_where_str)->order('id desc')->dataRow();
			if($ban_row){
				$ban_end_time=strtotime($ban_row['end_time'].' 00:00:00')-time();
				if($ban_end_time > 0 || ($ban_row['end_time'] && strtotime($ban_row['end_time'].' 00:00:00')=='')){
					return true;
				}
			}
		}
		return false;
	}
}