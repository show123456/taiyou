<?php
class SubPhoneAction extends CommonAction{
    public function index(){
		$userModel=M('SubUser');
		if(I('get.is_important')){
			$where['is_important']=I('get.is_important');
		}
		if(I('get.title')){
			$where['title']=array('like',"%".I('get.title')."%");
		}
		I('get.date') ? $date=I('get.date') : $date=date('Y-m-d');
		$where['_string']=" left(addtime,10)='{$date}' ";
		
		//$list=D($this->moduleName)->field('*,count(id) as count_num')->where($where)->group('uid')->select();
		$list=D($this->moduleName)->where($where)->select();
		foreach($list as $k=>$v){
			$tjr_row=$userModel->find($v['uid']);
			$list[$k]['tjr_phone']=$tjr_row['username'];
			$list[$k]['tjr_name']=$tjr_row['nickname'];
			$list[$k]['xuhao']=$k+1;
		}
		$this->assign('list',$list);
		$this->assign('date',$date);
		$this->display();
    }
	
    public function add(){
        if(IS_POST){
			$data=I('post.');
			if(isset($data['lq_time'])) $data['info']['lq_time']=implode(',',$data['lq_time']);
            $res = D($this->moduleName)->saveData($data);
            $resText = $res ? '保存成功':'保存失败';
			
			if($res){
				$this->success($resText,I('post.lastURL'));
			}else{
				$this->error($resText);
			}
        }else{
			$lp_time_arr=array();
            $id=I('get.id');
            if($id){
				$vo=D($this->moduleName)->find($id);
				if(!is_null($vo['lq_time']))
					$lp_time_arr=explode(',',$vo['lq_time']);
                $this->assign('vo',$vo);
                $this->assign('lp_time_arr',$lp_time_arr);
            }
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }
}