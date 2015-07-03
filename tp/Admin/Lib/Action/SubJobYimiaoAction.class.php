<?php
class SubJobYimiaoAction extends CommonAction{
	public function add(){
		if(IS_POST){
			if($_FILES['pic']['name']){
				$now_date=date('Ymd');
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();
				//$upload->maxSize  = 3145728;
				$upload->maxSize  = 6145728;
				$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
				$upload->savePath =  './Public/upload/admin/'.$now_date.'/';
				if(!$upload->upload()) {
					$this->error($upload->getErrorMsg());
				}else{
					// 上传成功 获取上传文件信息
					$info =  $upload->getUploadFileInfo();
					$picinfo=I('post.');
					$picinfo['info']['pic'] = $upload->savePath.$info[0]['savename']; ;
				}
				//删除原图
				$arr=I('post.info');
				if($arr['id']){
					$vo=D($this->moduleName)->find($arr['id']);
					@unlink($vo['pic']);
				}
				// 保存当前数据对象
				$res = D($this->moduleName)->saveData($picinfo);
				if($res){
					$this->success('保存成功',I('post.lastURL'));
				}else{
					$this->error('保存失败');
				}
			}else{
				$res = D($this->moduleName)->saveData(I('post.'));
				if($res){
					$this->success('保存成功',I('post.lastURL'));
				}else{
					$this->error('保存失败');
				}
			}
		}else{
			$id=I('get.id');
            if($id){
                $this->assign('vo',D($this->moduleName)->find($id));
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
	
	public function see(){
		$signModel=D('SubYimiaoSign');
		$userModel=M('SubUser');
		$hptModel=M('SubJobHospital');
		$where=array();
		if(I('get.date')) $where="left(addtime,10) = '".I('get.date')."'";
		$list=$signModel->getPager($where);
		foreach($list['data'] as $key=>$value){
			//用户信息
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$userRow['sex']==1 ? $list['data'][$key]['sex']='男' : $list['data'][$key]['sex']='女';
			//医院信息
			$hptRow=$hptModel->find($value['hpt_id']);
			$list['data'][$key]['hpt_name']=$hptRow['name'];
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	public function del_sign($id){
        $res = D('SubYimiaoSign')->delData($id);
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
    }
	
	public function add_sign(){
		if(IS_POST){
			$res = D('SubYimiaoSign')->saveData(I('post.'));
			if($res){
				$this->success('保存成功',I('post.lastURL'));
			}else{
				$this->error('保存失败');
			}
		}else{
			$id=I('get.id');
			$vo=D('SubYimiaoSign')->find($id);
			//医院名
			$row=M('SubJobHospital')->find($vo['hpt_id']);
			$vo['hpt_name']=$row['name'];
            $this->assign('vo',$vo);
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
		}
	}
}