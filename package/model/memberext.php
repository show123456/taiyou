<?php
class Model_MemberExt extends Model_Subtable{	
	//增加积分
	public function addScore($fromuser,$score){
		$this->query("update member_ext set score=score+($score) where fromuser='{$fromuser}'");
	}
}