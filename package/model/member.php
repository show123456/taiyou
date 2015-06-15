<?php

class Model_Member extends Model_Table {
	
	protected $_name = 'member';
	
	public function getStatus($status){
		return $status==0 ? '<span class="c-red">已取消':'<span class="c-green">正常</span>';
	}
	
	//由openid获取头像完整地址
	public function getPic($fromuser){
		$row=$this->fetchRow("select head_pic from sub_user where fromuser='{$fromuser}'");
		$memberRow=$this->fetchRow("select headimgurl from member where fromuser='{$fromuser}'");
		if($row['head_pic'])
			return "/data/image_c/".$row['head_pic'];
		else if($memberRow['headimgurl'])
			return $memberRow['headimgurl'];
		else
			return "images/men_icon.gif";
	}
}

