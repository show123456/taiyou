<?php
class ApartmentpicAction extends CommonAction{
    public function index(){
		$aid=I('get.aid');
        $this->assign('list',D($this->moduleName)->where(array('aid'=>$aid))->select());
		$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
        $this->display();
    }
	
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
}