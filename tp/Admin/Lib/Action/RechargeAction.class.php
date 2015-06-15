<?php
class RechargeAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		$where=array();
		//搜索条件
		if($_GET['keywords']){//姓名
			$uArr=$userModel->where("nickname = '".I('get.keywords')."'")->select();
			if($uArr){
				foreach($uArr as $v){
					$idRow[]=$v['id'];
				}
				$where['uid']=array('in',$idRow);
			}else{
				$where['uid']=0;
			}
		}
		if($_GET['username']){//手机号
			$uArr=$userModel->where("username = '".I('get.username')."'")->select();
			if($uArr){
				foreach($uArr as $v){
					$idRow[]=$v['id'];
				}
				$where['uid']=array('in',$idRow);
			}else{
				$where['uid']=0;
			}
		}
		if(I('get.year')) $where['year']=I('get.year');
		if(I('get.month')) $where['month']=I('get.month');
		if(I('get.day')) $where['day']=I('get.day');
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
		}
		$this->assign('list',$list);
		//总计充值额
		$sum_row=array();
		if(I('get.keywords') || I('get.username') || I('get.year')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$this->assign('sum_row',$sum_row);
		}else{//默认显示今日、本月
			$current_time=time();
			$month_str="year='".date('Y',$current_time)."' and month='".date('m',$current_time)."'";
			$day_str=$month_str." and day='".date('d',$current_time)."'";
			$sum_month_row=D($this->moduleName)->field("sum(money) as sum_money")->where($month_str)->find();
			$sum_day_row=D($this->moduleName)->field("sum(money) as sum_money")->where($day_str)->find();
			$this->assign('sum_month_row',$sum_month_row);
			$this->assign('sum_day_row',$sum_day_row);
		}
		//年月日数组
		$yearArr=array();
		for($i=2014;$i<2026;$i++){
			$yearArr[]=$i;
		}
		$this->assign('yearArr',$yearArr);
		$monthArr=array();
		for($i=1;$i<13;$i++){
			if($i<10){
				$monthArr[]='0'.$i;
			}else{
				$monthArr[]=$i;
			}
		}
		$this->assign('monthArr',$monthArr);
		$dayArr=array();
		for($i=1;$i<32;$i++){
			if($i<10){
				$dayArr[]='0'.$i;
			}else{
				$dayArr[]=$i;
			}
		}
		$this->assign('dayArr',$dayArr);
		
		$this->display();
	}
	
	//充值操作在SubUser/cz函数里
	
	//数据导出
	public function daochu(){
		
	}
}