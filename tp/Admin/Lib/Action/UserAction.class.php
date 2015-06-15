<?php
class UserAction extends CommonAction {
	//讲师审核列表
	public function index(){
		$where['identity']='1';//类别为1是讲师
		//如果是经纪人登录
		$suserInfo=getSuserInfo();
		if($suserInfo['type']==2){
			$arr=M('UserMap')->where(array('suid'=>$suserInfo['id']))->select();//查询出与该经纪人绑定的讲师
			$userIdRow=array();
			if($arr){
				foreach($arr as $v){
					$userIdRow[]=$v['uid'];
				}
			}
			$where['id']=array('in',$userIdRow);
		}
		$this->assign('list',D($this->moduleName)->getPager($where,10,'addtime desc'));
		$this->display();
	}
	
	//开通或关闭讲师
	public function changeState(){
		if(IS_AJAX){
			$data['id']=I('get.id');
			$data['state_show']=I('get.state_show');
			echo D($this->moduleName)->save($data);
		}
	}
	
	//查看
	public function see($id){
		$user=M('User')->find($id);
		session('user_info',$user);
		echo "<script>window.location.href='index.php?m=Ubase&a=add'</script>";
	}
	
	//讲师资源
	public function res(){
		$this->assign('moduleName',MODULE_NAME);
		$this->assign('actionName',ACTION_NAME);
		$where['identity']='1';//类别为1是讲师
		//如果是经纪人登录
		$suserInfo=getSuserInfo();
		if($suserInfo['type']==2){
			$arr=M('UserMap')->where(array('suid'=>$suserInfo['id']))->select();//查询出与该经纪人绑定的讲师
			$userIdRow=array();
			if($arr){
				foreach($arr as $v){
					$userIdRow[]=$v['uid'];
				}
			}
			$where['id']=array('in',$userIdRow);
		}
		//行业、领域
		$tradeRow=$this->arrToRow(M('Trade')->select(),'trade_name');
		$fieldRow=$this->arrToRow(M('Field')->select(),'field_name');
		//搜索条件
		if($_GET['name']) $where['name']=array('like',"%".I('get.name')."%");
		
		$list=D($this->moduleName)->getPager($where,5,'addtime desc');
		$ubaseModel=M('Ubase');
		$uextraModel=M('Uextra');
		$provinceModel=M('Province');
		$cityModel=M('City');
		foreach($list['data'] as $k=>$v){
			$ubaseRow=$ubaseModel->field('province_id,city_id')->where(array('uid'=>$v['id']))->find();
			$pRow=$provinceModel->field('name')->find($ubaseRow['province_id']);
			$cRow=$cityModel->field('name')->find($ubaseRow['city_id']);
			$uextraRow=$uextraModel->field('good_area,good_job')->where(array('uid'=>$v['id']))->find();
			$list['data'][$k]['province_name']=$pRow['name'];//省份名
			$list['data'][$k]['city_name']=$cRow['name'];//城市名
			$list['data'][$k]['trade_str']=$this->idToName($uextraRow['good_job'],$tradeRow);//行业
			$list['data'][$k]['field_str']=$this->idToName($uextraRow['good_area'],$fieldRow);//领域
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	//二维数组转一维
	private function arrToRow($arr,$name){
		foreach($arr as $v){
			$row[$v['id']]=$v[$name];
		}
		return $row;
	}
	
	//把id串转化为名字串
	private function idToName($idStr,$row){
		$idArr=explode(',',$idStr);
		foreach($idArr as $v){
			$nameArr[]=$row[$v];
		}
		$nameStr=implode(',',$nameArr);
		return $nameStr;
	}
}