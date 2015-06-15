<?php

class Model_Message extends Model_Table {
	
	protected $_name = 'message';
	
	public function is_reply($is_reply){
		return $is_reply ? '<input type="checkbox" checked  readonly/> 已回复</span>':'';
	}
}

