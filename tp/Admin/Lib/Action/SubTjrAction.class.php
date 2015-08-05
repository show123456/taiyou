<?php
class SubTjrAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		I('get.date') ? $date=I('get.date') : $date=date('Y-m-d');
		$where['add_date']=$date;
		if(I('get.is_pay')==2){
			$where['is_pay']=0;
		}elseif(I('get.is_pay')==1){
			$where['is_pay']=1;
		}
		if(I('get.phone')){
			$urow=$userModel->where(array('username'=>I('get.phone')))->find();
			$where['tjr_uid']=$urow['id'];
		}
		
		$list=D($this->moduleName)->field('*,count(reg_uid) as reg_num')->where($where)->group('tjr_uid')->select();
		foreach($list as $k=>$v){
			$tjr_row=$userModel->find($v['tjr_uid']);
			$list[$k]['tjr_phone']=$tjr_row['username'];
			$list[$k]['tjr_name']=$tjr_row['nickname'];
		}
		$this->assign('list',$list);
		$this->assign('date',$date);
		$row=M('SubReg')->where(array('k'=>$date))->find();
		$this->assign('money',$row['v']);
		$userRes=getSuserInfo();
		$this->assign('suserSession',$userRes);
		$this->display();
	}
	
	public function cz(){
		if(IS_POST){
			$data=I('post.');
			$current_time=time();
			$res=D('Recharge')->saveData($data);			
			if($res){
				//用户金额增加
				M()->query("update sub_user set money = money + ".$data['info']['money']." where id='".$data['info']['uid']."' limit 1");
				//写金额日志
				$data1=array();
				$data1['type']=6;//系统充值
				$data1['uid']=$data['info']['uid'];
				$data1['money']=$data['info']['money'];
				$data1['desc']=$data['info']['desc'];
				M('SubMoneyLog')->add($data1);
				//更改该推荐人当天的支付状态
				M()->query("update sub_tjr set is_pay = 1 where tjr_uid='".$data['info']['uid']."' and add_date='".$data['add_date']."'");
				//发消息
				$userRow=M('SubUser')->find($data1['uid']);
				$fromuser=$userRow['fromuser'];
				$content="您好".$userRow['nickname']."，云客驿站为您充值".$data1['money']."元钱，请前往“个人金库”查看。备注：".$data1['desc'];
				D('CustomerConfig')->sendCustomerMsg($content,$fromuser);
				
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$vo=M('SubUser')->find(I('get.id'));
			$this->assign('vo',$vo);
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
			//单价
			$row=M('SubReg')->where(array('k'=>I('get.add_date')))->find();
			$this->assign('price',$row['v']);
			$this->display();
		}
	}
	
	public function set(){
		//推荐人金额保存
		$regModel=M('SubReg');
		$row=$regModel->where(array('k'=>I('post.k')))->find();
		$reg_data['k']=I('post.k');
		$reg_data['v']=I('post.v');
		if($row['id']){
			$reg_data['id']=$row['id'];
			$regModel->save($reg_data);
		}else{
			$regModel->add($reg_data);
		}
	}
	
	public function reg_index(){
		$userModel=M('SubUser');
		$list=D($this->moduleName)->where(array('add_date'=>I('get.add_date'),'tjr_uid'=>I('get.tjr_uid')))->select();
		foreach($list as $k=>$v){
			$reg_row=$userModel->find($v['reg_uid']);
			$list[$k]['reg_phone']=$reg_row['username'];
			$list[$k]['reg_name']=$reg_row['nickname'];
		}
		$this->assign('list',$list);
		$this->display();
	}
}