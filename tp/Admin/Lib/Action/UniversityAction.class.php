<?php
class UniversityAction extends CommonAction {
	public function index(){
		$where=array();
		if(I('get.title')){
			$where['title']=array('like','%'.I('get.title').'%');
		}
		$this->assign('list',D($this->moduleName)->getPager($where));
		$this->display();
	}
}