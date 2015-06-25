<?php
class SubTaskAction extends CommonAction{
	//现金日结对应的任务列表
	public function xj_index(){
		$signModel=M('SubSign');
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		if($start_date==$end_date){
			$where_str_task="sh_status=1 and pay_type=1 and left(work_time,10) = '".$start_date."'";
		}else{
			$where_str_task="sh_status=1 and pay_type=1 and left(work_time,10) >= '".$start_date."' and left(work_time,10) <= '".$end_date."'";
		}
		
		$list=D('SubTask')->getPager($where_str_task,10,'id asc');
		foreach($list['data'] as $k=>$v){
			//报名有效人数
			$valid_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_valid'=>1))->find();
			$list['data'][$k]['valid_num']=(int)$valid_row['c'];
			//允许结算人数
			$js_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_js'=>1))->find();
			$list['data'][$k]['js_num']=(int)$js_row['c'];
			//需备用金额
			if($valid_row['c']==0){
				$list['data'][$k]['spare_money']=0;
			}else if($js_row['c']==0){//计算报名有效备用金额
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$valid_row['c'];
			}else{//计算允许结算报名备用金额
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$js_row['c'];
			}
		}
		$this->assign('list',$list);
		$this->display('index');
	}
	
	/* public function xj_index(){
		$signModel=M('SubSign');
		//查询职位id
		$tid_row=array();
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		$logArr=M('SubMoneyLog')->where("type=7 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59'")->select();
		if($logArr){			
			foreach($logArr as $v){
				$tid_row[]=(int)$v['desc'];
			}
			$tid_row=array_unique($tid_row);
		}
	
		$list=D($this->moduleName)->getPager(array('id'=>array('in',$tid_row)));
		foreach($list['data'] as $k=>$v){
			//总数
			$countRow=$signModel->field('count(id) as c')->where(" tid='{$v['id']}'")->find();
			$list['data'][$k]['countnum']=$countRow['c'];
		}
		$this->assign('list',$list);
		$this->display('index');
	} */
	
	//转账日结对应的任务列表
	public function zz_index(){
		$signModel=M('SubSign');
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		if($start_date==$end_date){
			$where_str_task="sh_status=1 and pay_type=2 and left(work_time,10) = '".$start_date."'";
		}else{
			$where_str_task="sh_status=1 and pay_type=2 and left(work_time,10) >= '".$start_date."' and left(work_time,10) <= '".$end_date."'";
		}
		
		$list=D('SubTask')->getPager($where_str_task,10,'id asc');
		foreach($list['data'] as $k=>$v){
			//报名有效人数
			$valid_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_valid'=>1))->find();
			$list['data'][$k]['valid_num']=(int)$valid_row['c'];
			//允许结算人数
			$js_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_js'=>1))->find();
			$list['data'][$k]['js_num']=(int)$js_row['c'];
			//需备用金额
			if($valid_row['c']==0){
				$list['data'][$k]['spare_money']=0;
			}else if($js_row['c']==0){//计算报名有效备用金额
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$valid_row['c'];
			}else{//计算允许结算报名备用金额
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$js_row['c'];
			}
		}
		$this->assign('list',$list);
		$this->display('index');
	}
	
	/* public function zz_index(){
		$signModel=M('SubSign');
		//查询职位id
		$tid_row=array();
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		$logArr=M('SubMoneyLog')->where("type=3 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59'")->select();
		if($logArr){			
			foreach($logArr as $v){
				$tid_row[]=(int)$v['desc'];
			}
			$tid_row=array_unique($tid_row);
		}
		
		$list=D($this->moduleName)->getPager(array('id'=>array('in',$tid_row)));
		foreach($list['data'] as $k=>$v){
			//总数
			$countRow=$signModel->field('count(id) as c')->where(" tid='{$v['id']}'")->find();
			$list['data'][$k]['countnum']=$countRow['c'];
		}
		$this->assign('list',$list);
		$this->display('index');
	} */
	
	//查看名单
	public function see(){
		$userModel=M('SubUser');
		$tid=I('get.tid');
		$listArr=M('SubSign')->where(" tid='{$tid}'")->select();
		if($listArr){
			foreach($listArr as $key=>$value){
				$uRow=$userModel->find($value['uid']);
				$listArr[$key]['nickname']=$uRow['nickname'];
				$listArr[$key]['username']=$uRow['username'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			}
		}
		$this->assign('list',$listArr);
		$this->display('see');
	}
}