<?php
class SubDailyAction extends CommonAction{
    public function index(){
        $this->assign('list',D($this->moduleName)->getPager(array(),10,'plan_date desc'));
        $this->assign('now_date',date('Y-m-d'));
        $this->display();
    }
	
	public function update_date(){
		$data['id']=I('get.id');
		$data['send_date']=date('Y-m-d H:i:s');
		M('SubDaily')->save($data);
	}
}