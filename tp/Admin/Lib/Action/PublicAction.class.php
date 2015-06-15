<?php
class PublicAction extends CommonAction {
	public function top(){
		$userRes=getSuserInfo();
		$this->assign('suserSession',$userRes);
		$this->display();
	}
	public function left(){
		$this->display();
	}
	public function swich(){
		$this->display();
	}
	public function bottom(){
		$this->display();
	}
}