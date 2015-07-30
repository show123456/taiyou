<?php
class SubUserAction extends CommonAction{
	//角色模块-用户列表
	public function index(){
		$extModel=D('SubUserExt');
		$districtModel=D('SubDistrict');
		$group_row=get_group();
		$where=array();
		
		//管理人员列表
		if(I('get.manage')){
			$where_id_arr=array();
			$ext_arr=$extModel->select();
			if($ext_arr){
				foreach($ext_arr as $ek=>$ev){
					$where_id_arr[]=$ev['uid'];
				}
				$where['id']=array('in',$where_id_arr);
			}else{
				$where['id']=0;
			}
		}
		
		$where['pid']=array('neq',0);
		if($_GET['username']) $where['username']=I('get.username');
		if($_GET['nickname']) $where['nickname']=I('get.nickname');
		if($_GET['group_id']){
			$where_id_arr=array();
			$ext_arr=$extModel->where(array('group_id'=>I('get.group_id')))->select();
			if($ext_arr){
				foreach($ext_arr as $ek=>$ev){
					$where_id_arr[]=$ev['uid'];
				}
				$where['id']=array('in',$where_id_arr);
			}else{
				$where['id']=0;
			}
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $k=>$v){
			$row=$extModel->where(array('uid'=>$v['id']))->find();
			if($row){
				$list['data'][$k]['group_id']=$row['group_id'];
				$row['area']=trim($row['area'],',');
				$districtArr=$districtModel->where(array('id'=>array('in',$row['area'])))->select();
				$dnameArr=array();
				foreach($districtArr as $key=>$value){
					$dnameArr[]=$value['name'];
				}
				$list['data'][$k]['dname']=implode(',',$dnameArr);
			}else{
				$list['data'][$k]['group_id']=1;
			}
		}
		
		$this->assign('list',$list);
		$this->assign('group_row',$group_row);
		$this->display();
	}
	
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
		
		//本日平台活跃量
		$now_date=date('Y-m-d');//$now_date='2015-05-21';
		$arr=M('Message')->field('id,fromuser')->where("left(create_date,10) = '".$now_date."'")->group('fromuser')->select();
		$countnum=count($arr);
		$this->assign('countnum',$countnum);
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
			$vo=D($this->moduleName)->find(I('get.id'));
			$this->assign('vo',$vo);
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
			$this->display();
		}
	}
	
	//修改角色
	public function add_group(){
		$extModel=D('SubUserExt');
		if(IS_POST){
			$data=I('post.');
			if(!$data['area']) $data['area']=array(2865);
			$data['info']['area']=','.implode(',',$data['area']).',';
			//是否存在数据
			$row=$extModel->where(array('uid'=>$data['info']['uid']))->find();
			if($row){
				$data['info']['id']=$row['id'];
			}
			$res=$extModel->saveData($data);			
			if($res){
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$vo=D($this->moduleName)->find(I('get.id'));
			$this->assign('vo',$vo);
			//角色信息
			$dArr=array();
			$ext_row=$extModel->where(array('uid'=>$vo['id']))->find();
			$this->assign('ext_row',$ext_row);
			if($ext_row['area']){
				$dStr=trim($ext_row['area'],',');
				$dArr=explode(',',$dStr);
			}
			$this->assign('dArr',$dArr);
			
			$this->assign('group_row',get_group());
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
			//苏州下所有区
			$this->assign('districtArr',D('SubDistrict')->where('pid=78')->order('id asc')->select());
			$this->display();
		}
	}
	
	public function del_group(){
		$uid=I('get.uid');
		M('SubUserExt')->where(array('uid'=>$uid))->delete(); 
		$this->success('删除成功');
	}
}