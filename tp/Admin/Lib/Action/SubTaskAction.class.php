<?php
class SubTaskAction extends CommonAction{
	//现金日结对应的任务列表
	public function xj_index(){
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
	}
	
	//转账日结对应的任务列表
	public function zz_index(){
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
	}
	
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