<?php
//通用model类
class CommonModel extends Model {
	public $pkName = 'id';//主键名称
	
	//保存数据
	public function saveData($data){
        $data = $data['info'];
        $id = $data[$this->pkName];
        if(!$id){//添加
            return $this->add($data);
        }else{//修改
            return $this->save($data);
        }
    }
	
	//根据id删除
	public function delData($id){
		return $this->where(array($this->pkName=>$id))->delete();
    }
	
	//分页
	public function getPager($where=array(),$pageSize=10,$order='id desc'){
		if(!$_GET['p']) $_GET['p'] = 1;
        $list = $this->where($where)->order($order)->page($_GET['p'].','.$pageSize)->select();
        $count = $this->where($where)->count();
        import('ORG.Util.Page');
		$Page = new Page($count,$pageSize);
		$Page->parameter = I('get.');
        return array(
            'data' => $list,
            'page' => $Page->show(),
        );
    }
}