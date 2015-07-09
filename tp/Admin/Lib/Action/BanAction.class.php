<?php
class BanAction extends CommonAction{
    public function index(){
		$userModel=M('SubUser');
		$where=array();
		if(I('get.username')) $where['username']=I('get.username');
		if(I('get.cardnum')) $where['cardnum']=I('get.cardnum');
		//搜索姓名
		if(I('get.nickname')){
			$uArr=$userModel->where(array('nickname'=>I('get.nickname')))->select();
			if($uArr){
				foreach($uArr as $v){
					$usernameRow[]=$v['username'];
				}
				$where['username']=array('in',$usernameRow);
			}else{
				$where['id']=0;
			}
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $k=>$v){
			//匹配用户信息
			$condition="id>0";
			if($v['username']) $condition.=" and username='".$v['username']."'";
			if($v['cardnum']) $condition.=" and cardnum='".$v['cardnum']."'";
			$userRow=$userModel->where($condition)->find();
			$list['data'][$k]['nickname']=$userRow['nickname'];
			$list['data'][$k]['sex']=$userRow['sex'];
		}
        $this->assign('list',$list);
		$this->assign('ban_type',get_ban_type());
        $this->display();
    }
	
	public function add(){
		if(IS_POST){
			$data=I('post.');
			//如果该用户在禁闭期内
			if(!$data['info']['id']){
				if(!$data['info']['username']) $where="cardnum='".$data['info']['cardnum']."'";
				if(!$data['info']['cardnum']) $where="username='".$data['info']['username']."'";
				if($data['info']['username'] && $data['info']['cardnum'])
					$where="(username='".$data['info']['username']."' or cardnum='".$data['info']['cardnum']."')";
				$where.=" and end_time > '".date('Y-m-d')."'";
				$row=D($this->moduleName)->where($where)->find();
				if($row){
					$this->error('该手机号或身份证号仍在禁闭期内');die;
				}
			}
			if(!$data['info']['id']) $data['info']['start_time']=date('Y-m-d');
			$res=D($this->moduleName)->saveData($data);
			if($res){
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$id=I('get.id');
            if($id){
                $this->assign('vo',D($this->moduleName)->find($id));
            }
			if(I('get.uid')) $this->assign('uRow',M('SubUser')->find(I('get.uid')));
			$this->assign('ban_type',get_ban_type());
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
		}
	}
	
	//报名有效却未报到人
	public function lists(){
		$signModel=D(SubSign);
		$userModel=M('SubUser');
		$taskModel=M('SubTask');
		$model=M('Ban');
		$where=array();
		if(I('get.date')){
			$where="task_date = '".I('get.date')."'";
		}else{
			$where="task_date = '".date('Y-m-d')."'";
		}
		$where.=" and is_valid=1 and is_qd=2";
		$list=$signModel->getPager($where);
		foreach($list['data'] as $key=>$value){
			//用户信息
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$userRow['sex']==1 ? $list['data'][$key]['sex']='男' : $list['data'][$key]['sex']='女';
			//职位信息
			$taskRow=$taskModel->find($value['tid']);
			$list['data'][$key]['title']=$taskRow['title'];
			//查询此人是否在禁闭期内
			$banRow=$model->where("username='".$userRow['username']."' and end_time > '".date('Y-m-d')."'")->find();
			if($banRow) unset($list['data'][$key]);
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	//自动匹配用户信息
	public function match(){
		$username=I('post.username');
		$cardnum=I('post.cardnum');
		if($username) $where['username']=$username;
		if($cardnum) $where['cardnum']=$cardnum;
		$row=M('SubUser')->where($where)->find();
		if($row){
			$row['sex']==1 ? $sex='男' : $sex='女';
			echo $row['nickname'].'-'.$sex;
		}
		die;
		
	}
}