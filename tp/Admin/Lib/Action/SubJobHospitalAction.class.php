<?php
class SubJobHospitalAction extends CommonAction{
    public function index(){
		$week_arr=array(1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日');
		$list=D($this->moduleName)->getPager();
		foreach($list['data'] as $k=>$v){
			if(!is_null($v['lq_time'])){
				$arr=explode(',',$v['lq_time']);
				$week_row=array();
				foreach($arr as $val){
					$week_row[]=$week_arr[$val];
				}
				$list['data'][$k]['lq_week']=implode(',',$week_row);
			}
		}
        $this->assign('list',$list);
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