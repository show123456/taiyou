<?php
class ApplistHptShopOrderAction extends CommonAction{
    public function index(){
		$where['is_pay']=1;
		$where['pay_type']=I('get.pay_type');
		if(I('get.start_date')){
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
        $this->assign('list',D($this->moduleName)->getPager($where,10));
        $this->assign('now_date',date('Y-m-d'));
        $this->display();
    }
	
	public function update_date(){
		$data['id']=I('get.id');
		$data['send_date']=date('Y-m-d H:i:s');
		M('SubDaily')->save($data);
	}
}