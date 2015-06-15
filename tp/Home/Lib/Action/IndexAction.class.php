<?php
class IndexAction extends CommonAction {
	public function index(){
		//$model=D('Class');
		//幻灯片
		$this->assign('slidelist',M('Slide')->order('ordernum desc')->select());
		//downtown
		$dtModel=M('Downtown');
		$this->assign('downtownlist',$dtModel->select());
		$this->assign('dt_home_list',$dtModel->where(array('is_home'=>'1'))->order('id asc')->select());
		//大学
		$this->assign('unilist',M('University')->select());
		
		$this->display();
	}
	
	public function lists(){
		$model=D('Class');
		//热门文章
		$this->assign('hotlist',$model->order("zan_num desc,id desc")->limit('4')->select());
		//类别
		$this->assign('sortArr',D('Sort')->select());
		//标签
		$this->assign('tagArr',D('Tag')->select());
		//列表
		//搜索条件
		if(I('get.title')) $condition[]=" title like '%".I('get.title')."%' ";
		if(I('get.tag')) $condition[]=" tag like '%,".I('get.tag').",%' ";
		if(I('get.sortid')) $condition[]=" sortid='".I('get.sortid')."'";
		if($condition) $where=implode('and',$condition);
		
		$this->assign('list',$model->getPager($where,3,'id desc'));
		$this->display();
	}
	
	public function full(){
		$model=D('Class');
		$vo=$model->find(I('get.id'));
		$idStr=I('get.id');
		//感兴趣的课程
		$enjoylist1=array();
		if($vo['relate_class']){
			$enjoylist1=$model->where("id in (".$vo['relate_class'].")")->order("id desc")->limit('5')->select();
			$idStr=I('get.id').','.$vo['relate_class'];
		}
		//所需补充的条数
		$enjoyNum=5-count($enjoylist1);
		if($enjoyNum > 0){
			$map['id']=array('not in',$idStr);
			$enjoylist2=$model->where($map)->order("id desc")->limit($enjoyNum)->select();
			if($enjoylist1){
				foreach($enjoylist1 as $k=>$v){
					$enjoylist[]=$v;
				}
			}
			if($enjoylist2){
				foreach($enjoylist2 as $k=>$v){
					$enjoylist[]=$v;
				}
			}
		}
		$this->assign('enjoylist',$enjoylist);
		$this->assign('vo',$vo);
		//步骤
		$this->assign('stepArr',D('Step')->where(array('cid'=>I('get.id')))->order('num asc')->select());
		$this->display();
	}
	
	public function faq(){
		$this->display();
	}
	
	public function contact(){
		$this->display();
	}
}