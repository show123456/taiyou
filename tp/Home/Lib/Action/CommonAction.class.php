<?php
//通用Action类
class CommonAction extends Action{
    public $moduleName = '';
	
    public function _initialize(){
        header("Content-Type: text/html; charset=utf-8");
        $this->moduleName = MODULE_NAME;//当前控制器Action名
		$this->assign('moduleName',$this->moduleName);
		$this->assign('actionName',ACTION_NAME);
    }
    
    public function index(){
        $this->assign('list',D($this->moduleName)->getPager());
        $this->display();
    }
    
    public function add(){
        if(IS_POST){
            $res = D($this->moduleName)->saveData(I('post.'));
            $resText = $res ? '保存成功':'保存失败';
			
			if($res){
				$this->success($resText,I('post.lastURL'));
			}else{
				$this->error($resText);
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
        $res = D($this->moduleName)->delData($id);
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
    }
}