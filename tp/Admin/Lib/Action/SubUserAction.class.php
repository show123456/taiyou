<?php
class SubUserAction extends CommonAction{
	public function person_index(){
		$where=array();
		$where['type']=array('in',array(1,3));//个人用户或客服
		$where['pid']=array('neq',0);
		if($_GET['keywords']) $where['nickname']=I('get.keywords');
		if($_GET['nicheng']) $where['nicheng']=I('get.nicheng');
		if($_GET['username']){
			$where['username']=I('get.username');
		}else{
			$where['username']=array('neq','');
		}
		
		$list=D($this->moduleName)->getPager($where);
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add(){
		$id=I('get.id');
		$vo=D($this->moduleName)->find($id);
		//所属省市区
		$prow=M('SProvince')->where(array('ProvinceID'=>$vo['pid']))->find();
		$crow=M('SCity')->where('CityId="'.$vo['cid'].'"')->find();
		$drow=M('SDistrict')->where('DistrictId="'.$vo['did'].'"')->find();
		$vo['province']=$prow['ProvinceName'];
		$vo['city']=$crow['CityName'];
		$vo['district']=$drow['DistrictName'];
		
		$this->assign('vo',$vo);
		$this->display();
	}
	
	public function cz(){
		if(IS_POST){
			$data=I('post.');
			$current_time=time();
			$data['info']['year']=date('Y',$current_time);
			$data['info']['month']=date('m',$current_time);
			$data['info']['day']=date('d',$current_time);
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
				
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$vo=D($this->moduleName)->find(I('get.id'));
			$this->assign('vo',$vo);
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
			$this->display();
		}
	}
}