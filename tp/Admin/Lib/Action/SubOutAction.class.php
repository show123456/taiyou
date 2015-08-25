<?php
class SubOutAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		$where=array();
		//搜索条件
		if(I('get.is_pay')){
			I('get.is_pay')=='1' ? $where['is_pay']=1 : $where['is_pay']=0;
		}
		$condition=array();
		if($_GET['keywords']) $condition[]="nickname = '".I('get.keywords')."'";
		if($_GET['username']) $condition[]="username = '".I('get.username')."'";
		if($condition){
			$condition_str=implode(' and ',$condition);
			$uArr=$userModel->where($condition_str)->select();
			if($uArr){
				foreach($uArr as $v){
					$idRow[]=$v['id'];
				}
				$where['uid']=array('in',$idRow);
			}else{
				$where['uid']=0;
			}
		}
		if(I('get.start_date')){
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$list['data'][$key]['bank_name']=$userRow['bank_name'];
			$list['data'][$key]['bank_card']=$userRow['bank_card'];
		}
		$this->assign('list',$list);
		//总额
		$sum_row=array();
		if(I('get.start_date')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$this->assign('sum_row',$sum_row);
		}
		$this->display();
	}
	
	//总览
	public function all(){
		$userModel=M('SubUser');
		$where=array();
		//搜索条件
		if(I('get.start_date')){
			$where=" pay_time > '".I('get.start_date')." 00:00:00' and pay_time < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$list['data'][$key]['bank_name']=$userRow['bank_name'];
			$list['data'][$key]['bank_card']=$userRow['bank_card'];
		}
		$this->assign('list',$list);
		//总额
		$sum_row=array();
		if(I('get.start_date')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$this->assign('sum_row',$sum_row);
		}
		$this->display();
	}
	
	//更改支付状态
	public function kf(){
		$data['is_pay']=(int)$_GET['is_pay'];
		$data['id']=(int)$_GET['id'];
		$data['pay_time']=date('Y-m-d H:i:s');
		$res=D($this->moduleName)->save($data);
		if($res && $data['is_pay']==1){
			$row=D($this->moduleName)->find($data['id']);
			$userRow=M('SubUser')->find($row['uid']);
			$fromuser=$userRow['fromuser'];
			$content="您好，尾号为".substr($userRow['bank_card'],-4)."的银行账户，您提现的".$row['money']."元钱，将于两小时内到账，请注意查收！若未到账，请确认是否为本人银行卡，并核实银行卡号和开户行。";
			D('CustomerConfig')->sendCustomerMsg($content,$fromuser);
		}
		echo $res;die();
	}
	
	//数据导出
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '手机号')
					->setCellValue('B2', '银行名')
					->setCellValue('C2', '金额(元)')
					->setCellValue('D2', '银行卡')
					->setCellValue('E2', '姓名')
					->setCellValue('F2', '支付状态')
					->setCellValue('G2', '时间')
					->setCellValue('H2', '开户行');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);

		//数据库操作
		$model=D($this->moduleName);
		$userModel=M('SubUser');
		$current_time=time();
		
		$where_str="(addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59')";
		if(I('get.is_pay')){
			I('get.is_pay')=='1' ? $where_str.=" and is_pay=1 " : $where_str.=" and is_pay=0 ";
		}
		$title=I('get.start_date').'至'.I('get.end_date').'总额';
		
		$listArr=$model->where($where_str)->select();
		$sum_row=$model->field("sum(money) as sum_money")->where($where_str)->find();
		if(empty($listArr)) die('无数据');
		$title.=$sum_row['sum_money'].'元';
		
		foreach($listArr as $key=>$value){
			$uRow=$userModel->find($value['uid']);
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			$listArr[$key]['bank_name']=$uRow['bank_name'];
			$listArr[$key]['contact_name']=$uRow['contact_name'];
			$listArr[$key]['bank_card']=str_replace(array(' ','.','。'),'',$uRow['bank_card']);
			$value['is_pay']==1 ? $listArr[$key]['pay_status']='已支付' : $listArr[$key]['pay_status']='未支付';
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);
		foreach($listArr as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['username'])
										  ->setCellValue('B' . $lj, $lv['bank_name'])
										  ->setCellValue('C' . $lj, $lv['money'])
										  ->setCellValueExplicit('D' . $lj, $lv['bank_card'])
										  ->setCellValue('E' . $lj, $lv['nickname'])
										  ->setCellValue('F' . $lj, $lv['pay_status'])
										  ->setCellValue('G' . $lj, $lv['addtime'])
										  ->setCellValue('H' . $lj, $lv['contact_name']);
		}
											
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$title.'提现.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}