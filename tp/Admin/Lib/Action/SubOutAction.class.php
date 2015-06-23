<?php
class SubOutAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		$where=array();
		//搜索条件
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
	
	//更改支付状态
	public function kf(){
		$data[is_pay]=(int)$_GET['is_pay'];
		$data[id]=(int)$_GET['id'];
		$res=D($this->moduleName)->save($data);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '姓名')
					->setCellValue('B2', '手机号')
					->setCellValue('C2', '银行卡')
					->setCellValue('D2', '金额(元)')
					->setCellValue('E2', '支付状态')
					->setCellValue('F2', '时间');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);

		//数据库操作
		$model=D($this->moduleName);
		$userModel=M('SubUser');
		$current_time=time();
		
		$where_str="(addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59')";
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
			$listArr[$key]['bank_card']=$uRow['bank_card'];
			$value['is_pay']==1 ? $listArr[$key]['pay_status']='已支付' : $listArr[$key]['pay_status']='未支付';
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);
		foreach($listArr as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['nickname'])
										  ->setCellValue('B' . $lj, $lv['username'])
										  ->setCellValue('C' . $lj, $lv['bank_name'].'-'.$lv['bank_card'])
										  ->setCellValue('D' . $lj, $lv['money'])
										  ->setCellValue('E' . $lj, $lv['pay_status'])
										  ->setCellValue('F' . $lj, $lv['addtime']);
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