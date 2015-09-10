<?php
class SubAgentAction extends CommonAction{
    public function index(){
        $this->assign('list',D($this->moduleName)->getPager(array(),10,'id asc'));
        $this->display();
    }
	
    public function all(){
		$orderModel=D('ApplistHptShopOrder');
		$userModel=M('SubUser');
		$current_time=time();
		$_GET['start_date'] ? $start_date=$_GET['start_date'] : $start_date=date('Y-m-d',$current_time);
		$_GET['end_date'] ? $end_date=$_GET['end_date'] : $end_date=date('Y-m-d',$current_time);
		$this->assign('start_date',$start_date);$this->assign('end_date',$end_date);
		
		if(!$_GET['num']){
			$where="is_pay=1 and agent_num!=''";
		}else{
			$where="is_pay=1 and agent_num='".$_GET['num']."'";
		}
		$where.=" and pay_time >= '".$start_date." 00:00:00' and pay_time <= '".$end_date." 23:59:59'";
		$list=$orderModel->getPager($where,10,'id asc');
		foreach($list['data'] as $k=>$v){
			$userRow=$userModel->where(array('agent_num'=>$v['agent_num']))->find();
			$list['data'][$k]['agent_name']=$userRow['nickname'];
			$list['data'][$k]['agent_tel']=$userRow['username'];
		}
		$this->assign('list',$list);
		//统计
		$row=$orderModel->field("id,money,sum(money) as sum_money,count(id) as num")->where($where)->find();
		$this->assign('num',$row['num']);
		$this->assign('money',$row['sum_money']-$row['num']*12);
		//返额
		$ret=0.15;//返现比
		$return_money=round(($row['sum_money']-$row['num']*12)*$ret,2);//每笔减12
		$tax_row=$this->count_tax($return_money);
		$this->assign('return_money',$return_money);//返现金额
		$this->assign('tax',$tax_row[0]);//税金
		$this->assign('final_money',$tax_row[1]);//实收金额
		$this->display();
    }
	
	//折扣设置
	public function discount_add(){
		if(IS_POST){
			$data['id']=$_POST['id'];
			$data['v']=$_POST['discount'];
            $res = M('sub_kv')->save($data);
            $resText = $res ? '保存成功':'已保存';
			
			if($res){
				$this->success($resText);
			}else{
				$this->error($resText);
			}
        }else{
			$this->assign('vo',M('sub_kv')->where(array('k'=>'discount'))->find());
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
	}
	
	//更改审核状态
	public function kf(){
		$data['sh_status']=(int)$_GET['sh_status'];
		$data['id']=(int)$_GET['id'];
		$res=D($this->moduleName)->save($data);
		echo $res;die();
	}
	
	//更改变号
	public function num_change(){
		$data['num']=$_GET['num'];
		$row=D($this->moduleName)->where("num='".$data['num']."'")->find();
		if($row) die('cf');
		$data['id']=(int)$_GET['id'];
		$res=D($this->moduleName)->save($data);
		$res ? die('suc') : die('err');
	}
	
	//输入工资，返回税金和实际工资组成的数组
	public function count_tax($mon=0){
		$mon_mid=3500;
		$cha=$mon-$mon_mid;
		if($cha<=0){
			$fee=0;
			$final_money=$mon;
		}elseif($cha<=1500){
			$fee=$cha*0.03;
			$final_money=$mon-$fee;
		}elseif($cha<=4500){
			$fee=$cha*0.1-105;
			$final_money=$mon-$fee;
		}elseif($cha<=9000){
			$fee=$cha*0.2-555;
			$final_money=$mon-$fee;
		}elseif($cha<=35000){
			$fee=$cha*0.25-1005;
			$final_money=$mon-$fee;
		}elseif($cha<=55000){
			$fee=$cha*0.3-2755;
			$final_money=$mon-$fee;
		}elseif($cha<=80000){
			$fee=$cha*0.35-5505;
			$final_money=$mon-$fee;
		}else{
			$fee=$cha*0.45-13505;
			$final_money=$mon-$fee;
		}
		$final=array($fee,round($final_money,2));
		return $final;
	}
}