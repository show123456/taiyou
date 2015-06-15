<?php
class SubMoneyLogAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		$typeArr=get_money_type();//金额变动方式数组
		
		$where=array();
		if(I('get.keyword')){
			$where['name']=array('like','%'.I('get.keyword').'%');
		}
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$list['data'][$key]['type']=$typeArr[$value['type']];
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	//转账日结
	public function zzrj(){
		$userModel=M('SubUser');
		$where=array();
		$where['type']=array('in',array(0,3));//转账日结
		if(I('get.keyword')){
			$uidArr=array();
			$user_where_res=$userModel->field('id,nickname')->where(array('nickname'=>I('get.keyword')))->select();
			if($user_where_res){				
				foreach($user_where_res as $k=>$v){
					$uidArr[]=$v['id'];
				}
				$where['uid']=array('in',$uidArr);
			}else{
				$where['uid']=0;
			}
		}
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$list['data'][$key]['type']=$typeArr[$value['type']];
		}
		$this->assign('list',$list);
		$this->display();
	}
}