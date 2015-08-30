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
	
    public function out(){
		$odModel=M('ApplistHptShopOdetail');
		$where['is_pay']=1;
		if(I('get.start_date')){
			$where['_string']=" pay_time > '".I('get.start_date')." 00:00:00' and pay_time < '".I('get.end_date')." 23:59:59' ";
		}
		$list=D($this->moduleName)->where($where)->select();$tt=0;
		foreach($list as $ok=>$ov){
			//底价支出
			$out_money=0;
			$od_arr=$odModel->where(array('oid'=>$ov['id']))->select();
			foreach($od_arr as $odv){
				$out_money+=$odv['ori_price']*$odv['num'];
			}
			$list[$ok]['out']=$out_money+9;
		}//var_dump($list);
        $this->assign('list',$list);
        $this->display();
    }
	
	public function update_date(){
		$data['id']=I('get.id');
		$data['send_date']=date('Y-m-d H:i:s');
		M('SubDaily')->save($data);
	}
}