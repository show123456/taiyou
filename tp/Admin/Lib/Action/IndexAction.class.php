<?php
class IndexAction extends Action {
	public function index(){
		//判断是否登录
		$res=array();
		$res=getSuserInfo();
        if(empty($res)){
            $this->redirect('Index/login');
        }
		$this->display();
	}
	
	public function login(){
		$this->display();
	}
	
	//执行登陆
	public function dologin(){
		$arr=array();
		$username = I("post.username","","trim");
		$pass = I("post.pass","","trim");
		if(empty($username) || empty($pass)){
			$this->error("用户名或者密码不能为空，请重新输入！",U('Index/login'));
		}
		$pass=md5($pass);
		$arr=M('SubSuser')->where(array('name'=>$username,'pass'=>$pass))->find();
		if(empty($arr['name'])){
			$this->error("用户名或密码错误！",U('Index/login'));
		}else{
			setSuserInfo($arr);
			if($arr['type']==5){
				$this->success("登录成功！",'../home/app/hptshop/order.php');
			}else{
				$this->success("登录成功！",U('Index/index'));
			}
		}
	}
	
	//退出操作
	public function logout(){
        delSuserInfo();
        $this->success("退出成功！",U('Index/login'));
    }
	
	//执行注册***********弃用中
	public function doregister(){
		$username = I("post.username");
		$pass = I("post.pass");
		$phone = I("post.phone");
		if(empty($username) || empty($pass) || empty($phone)){
			$this->error("所填信息不能为空！",U('Index/register'));die;
		}
		$arr=M('User')->where(array('username'=>$username))->find();
		if($arr){
			$this->error("用户名重复！",U('Index/register'));die;
		}
		
		$data['username']=$username;
		$data['pass']=md5($pass);
		$data['phone']=$phone;
		$res=M('User')->data($data)->add();
		if($res){
			$this->success("注册成功！",U('Index/login'));
		}else{
			$this->success("注册失败！",U('Index/register'));
		}
	}
	
	//验证用户名是否重复***********弃用中
	public function checkName(){
		$name=I('post.name');
		$row=M('User')->where(array('username'=>$name))->find();
		if($row){
			echo json_encode('is');die;
		}else{
			echo json_encode('not');die;
		}
	}
	
	//修改密码***********弃用中
	public function editp(){
		if(IS_POST){
            $m=M("User");
			$userInfo=getSuserInfo();
			$id=$userInfo['id'];
			$res=$m->find($id);
			$p0=md5(I('post.pass0'));
			$p1=md5(I('post.pass1'));
			$p2=md5(I('post.pass2'));
			if($p0!=$res['pass']){
				$this->error("原密码输入错误！");
			}
			if($p2!=$p1){
				$this->error("两次密码输入不一致！");
			}
			$result=$m->where(array('id'=>$id))->setField("pass",$p1);
			if($result){
				$this->success("修改成功！",U('Index/editp'));
			}else{
				$this->error("修改失败！");
			}
        }else{
            $this->display();
        }
    }
}