<?php
class SubAgentAction extends CommonAction{
    public function index(){
        $this->assign('list',D($this->moduleName)->getPager(array(),10,'id asc'));
        $this->display();
    }
		
	//更改审核状态
	public function kf(){
		$data['sh_status']=(int)$_GET['sh_status'];
		$data['id']=(int)$_GET['id'];
		$res=D($this->moduleName)->save($data);
		echo $res;die();
	}
		
	//更改变号
	public function num_change(){
		$data['num']=$_GET['num'];
		$row=D($this->moduleName)->where("num='".$data['num']."'")->find();
		if($row) die('cf');
		$data['id']=(int)$_GET['id'];
		$res=D($this->moduleName)->save($data);
		$res ? die('suc') : die('err');
	}
}