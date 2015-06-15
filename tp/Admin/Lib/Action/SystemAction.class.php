<?php
class SystemAction extends CommonAction{
	//联系方式
	public function contact(){
		$memberInfo=getSuserInfo();
		$this->assign('model',M("Suser")->find($memberInfo['id']));
		$this->display();
    }
}