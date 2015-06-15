<?php
class ApartmentroomAction extends CommonAction {
    public function index(){
		$aid=I('get.aid');
        $this->assign('list',D($this->moduleName)->where(array('aid'=>$aid))->select());
		$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
        $this->display();
    }
}