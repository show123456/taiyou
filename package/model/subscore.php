<?php
class Model_SubScore extends Model_Subtable{
	//推荐人返利值
	public function tjr(){
		$row=$this->where("name='tjr'")->dataRow();
		return $row['score'];
	}
	
	//签到返还积分
	public function qd(){
		$row=$this->where("name='qd'")->dataRow();
		return $row['score'];
	}
}