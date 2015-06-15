<?php
class ClassAction extends CommonAction {
	public function index(){
		//分类数组
		$sortArr=D('Sort')->select();
		foreach($sortArr as $k=>$v){
			$sortRow[$v['id']]=$v['name'];
		}
		$this->assign('sortRow',$sortRow);
		//标签数组
		$tagArr=D('Tag')->select();
		foreach($tagArr as $k=>$v){
			$tagRow[$v['id']]=$v['name'];
		}
		
		$where=array();
		if(I('get.keyword')){
			$where['title']=array('like','%'.I('get.keyword').'%');
		}
		if(I('get.sortid')){
			$where['sortid']=array('eq',I('get.sortid'));
		}
		$list=D($this->moduleName)->getPager($where);
		if($list['data']){
			//标签处理
			foreach($list['data'] as $key=>$val){
				if($val['tag']){
					$tagStrArr=array();
					$tagTempArr=explode(',',trim($val['tag'],','));
					foreach($tagTempArr as $tk=>$tv){
						$tagStrArr[]=$tagRow[$tv];
					}
					$list['data'][$key]['tagstr']=implode(',',$tagStrArr);
				}
			}
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			$data=I('post.');
			if($data['tag']) $data['info']['tag']=','.implode(',',$data['tag']).',';
			$res = D($this->moduleName)->saveData($data);
			if($data['info']['type']==1 && $data['pic']){
				foreach($data['pic'] as $k=>$v){
					$tempArr=explode('_',$k);
					$numArr[]=$tempArr[1];
				}
				if($numArr){
					$stepModel=D('Step');
					//修改时cid变化
					if($data['info']['id']) $res=$data['info']['id'];
					$stepModel->where(array('cid'=>$res))->delete();
						
					$numArr=array_unique($numArr);
					foreach($numArr as $key=>$val){
						$stepData=array();
						$stepData['info']['cid']=$res;
						$stepData['info']['num']=$val;
						$stepData['info']['pic']=$data['pic']['pic_'.$val];
						$stepData['info']['content']=$data['pic']['content_'.$val];
						$stepModel->saveData($stepData);
					}
				}
			}
			if($res){
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			//分类数组
			$this->assign('sortArr',D('Sort')->select());
			//标签数组
			$this->assign('tagArr',D('Tag')->select());
			
			$id=I('get.id');
            if($id){
				$vo=D($this->moduleName)->find($id);
				//已选标签
				if($vo['tag']){
					$tagStrArr=array();
					$tagStrArr=explode(',',trim($vo['tag'],','));
					$this->assign('tagStrArr',$tagStrArr);
				}
				//已上传步骤
				$this->assign('stepArr',D('Step')->where(array('cid'=>$id))->order('num asc')->select());

				$this->assign('vo',$vo);
            }
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
		}
	}
	
	public function del($id){
		//删除原图
		$vo=D($this->moduleName)->find($id);
		@unlink($vo['pic']);
        $res = D($this->moduleName)->delData($id);
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
    }
	
	//相关课程
	public function relate(){
		if(IS_POST){
			$data=I('post.');
			if($data['relate']){
				$data['info']['relate_class']=implode(',',$data['relate']);
				$res = D($this->moduleName)->saveData($data);
			}
			if($res){
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$model=D('Class');
			$row=$model->field("id,relate_class")->find(I('get.id'));
			if($row['relate_class']) $this->assign('relateArr',explode(',',$row['relate_class']));
			$this->assign('list',$model->field('id,title')->where("id !=".I('get.id'))->select());
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
		}
    }
}