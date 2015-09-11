<?php
class SubAgentAction extends CommonAction{
    public function index(){
        $this->assign('list',D($this->moduleName)->getPager(array(),10,'id asc'));
        $this->display();
    }
	
	//推荐人
    public function tjr(){
		$userModel=M('SubUser');
		$current_time=time();
		$_GET['start_date'] ? $start_date=$_GET['start_date'] : $start_date=date('Y-m-d',$current_time);
		$_GET['end_date'] ? $end_date=$_GET['end_date'] : $end_date=date('Y-m-d',$current_time);
		$this->assign('start_date',$start_date);$this->assign('end_date',$end_date);
		
		if(!$_GET['num']){
			$where="my_num!=''";
		}else{
			$where="my_num='".$_GET['num']."'";
		}
		$where.=" and addtime >= '".$start_date." 00:00:00' and addtime <= '".$end_date." 23:59:59'";
		
		$list=$userModel->where($where)->limit('100')->select();
		foreach($list as $k=>$v){
			$row=$userModel->where(array('agent_num'=>$v['my_num']))->find();
			$list[$k]['tjr']=$row['nickname'].'-'.$row['username'];
		}
        $this->assign('list',$list);
        $this->assign('count_num',count($list));
        $this->display();
    }
	
	//代理商列表
	public function lists(){
		$userModel=M('SubUser');
		$_GET['num'] ? $where="agent_num='".$_GET['num']."'" : $where="agent_num != ''";
		$list=$userModel->where($where)->select();
        $this->assign('list',$list);
        $this->display();
	}
	
	//某个代理商下属用户
	public function lists_member(){
		$userModel=M('SubUser');
		$list=$userModel->where("my_num='".$_GET['num']."'")->select();
        $this->assign('list',$list);
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
	
    public function daochu(){
		ini_set('display_errors', FALSE);
		date_default_timezone_set('Europe/London');
		if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');
		require_once '../home/task/Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
		// Add some data
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '订单号')
					->setCellValue('B2', '金额')
					->setCellValue('C2', '收货人姓名')
					->setCellValue('D2', '收货人手机')
					->setCellValue('E2', '代理商编号')
					->setCellValue('F2', '代理商姓名')
					->setCellValue('G2', '代理商手机')
					->setCellValue('H2', '支付时间');
		//数据
		$orderModel=D('ApplistHptShopOrder');
		$userModel=M('SubUser');
		if(!$_GET['num']){
			$where="is_pay=1 and agent_num!=''";
		}else{
			$where="is_pay=1 and agent_num='".$_GET['num']."'";
		}
		$where.=" and pay_time >= '".$_GET['start_date']." 00:00:00' and pay_time <= '".$_GET['end_date']." 23:59:59'";
		
		$list=$orderModel->where($where)->select();
		$num=0;$money=0;
		foreach($list as $k=>$v){
			$userRow=$userModel->where(array('agent_num'=>$v['agent_num']))->find();
			$list[$k]['agent_name']=$userRow['nickname'];
			$list[$k]['agent_tel']=$userRow['username'];
			$num++;
			$money+=($v['money']-12);//每笔减12
		}
		//返额
		$ret=0.15;//返现比
		$return_money=round($money*$ret,2);//返现金额
		$tax_row=$this->count_tax($return_money);
		$tax=$tax_row[0];//税金
		$final_money=$tax_row[1];//实收金额
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "共{$num}单 ，总额￥{$money}(每单减12物流费) ，返现金额￥{$return_money} ，税金￥{$tax} ，实返现额￥{$final_money}");
		foreach($list as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['id'])
										  ->setCellValue('B' . $lj, $lv['money'])
										  ->setCellValue('C' . $lj, $lv['name'])
										  ->setCellValueExplicit('D' . $lj, $lv['tel'],PHPExcel_Cell_DataType::TYPE_STRING)
										  ->setCellValueExplicit('E' . $lj, $lv['agent_num'],PHPExcel_Cell_DataType::TYPE_STRING)
										  ->setCellValue('F' . $lj, $lv['agent_name'])
										  ->setCellValueExplicit('G' . $lj, $lv['agent_tel'],PHPExcel_Cell_DataType::TYPE_STRING)
										  ->setCellValue('H' . $lj, $lv['pay_time']);
		}
											
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="报表.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');exit;
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