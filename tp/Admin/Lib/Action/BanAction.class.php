<?php
class BanAction extends CommonAction{
    public function index(){
		$userModel=M('SubUser');
		$where=array();
		if(I('get.username')) $where['username']=I('get.username');
		if(I('get.cardnum')) $where['cardnum']=I('get.cardnum');
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
			/* //查重
			if(!$data['info']['id']){
				if(!$data['info']['username']) $where="cardnum='".$data['info']['cardnum']."'";
				if(!$data['info']['cardnum']) $where="username='".$data['info']['username']."'";
				if($data['info']['username'] && $data['info']['cardnum'])
					$where="username='".$data['info']['username']."' or cardnum='".$data['info']['cardnum']."'";
				$row=D($this->moduleName)->where($where)->find();
				if($row){
					$this->error('该手机号或身份证号已在禁闭列表');die;
				}
			} */
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
			$this->assign('ban_type',get_ban_type());
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
		}
	}
}