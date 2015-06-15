<?php
class Model_BbsPost extends Model_Common {
	protected $_name = 'bbs_post';
	
	public function add(){
		$data=array();
		if($_POST['id']) $data['id']=(int)$_POST['id'];
		$data['content']=str_inmysql($_POST['content']);
		$data['tid']=(int)$_POST['tid'];
		$data['uid']=(int)$_SESSION['customer_id'];
		$data['name']=str_inmysql($_SESSION['weixin_name']);
		$res=$this->upsert($data);
		return $res;
	}
	
	//帖子表回复数+1，更新最新回复
	public function replyUpdatePost($str,$id){
		return $this->query("update ".$this->_name." set replynum=replynum+1,newreply='".$str."' where id=".$id);
	}
	
	/**删除数据，并ajax返回
	  *接受的数据id：$arr['id']，用户id：$arr['uid']
	  *返回状态：$info['status']
	  *返回文本：$info['text']
	  *返回原始ID：$info['id']
	 */
	public function delRow($arr){
		$info['status']='success';
		$info['text']='删除成功';
		//判断权限
		if($this->checkRights($arr['uid'])){
			$id=(int)$arr['id'];
			$info['id']=$this->del($id);
			if(!$info['id']){
				$info['status']='warn';
				$info['text']='删除失败';
			}else{
				$info['id']=$arr['id'];
			}
		}else{
			$info['status']='warn';
			$info['text']='对不起，您无此权限';
		}
		echo json_encode($info);exit;
    }
}
?>
