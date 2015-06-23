<?php
class SubMoneyLogAction extends CommonAction{
	public function index(){
		$userModel=M('SubUser');
		$typeArr=get_money_type();//金额变动方式数组
		//搜索条件
		$where=array();
		if($_GET['uid']) $where['uid']=$_GET['uid'];
		if($_GET['type']){
			$_GET['type']=='3' ? $where['type']=array('in',array(0,3)) : $where['type']=$_GET['type'];
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
		
		if(I('get.start_date')){//日期
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			$list['data'][$key]['type']=$typeArr[$value['type']];
		}
		$this->assign('list',$list);
		//总计
		$model=D($this->moduleName);
		$sum_row=array();
		if(I('get.start_date')){
			$start_date=I('get.start_date');
			$end_date=I('get.end_date');
		}else{
			$start_date=date('Y-m-d');
			$end_date=$start_date;
		}
		$this->assign('start_date',$start_date);
		$this->assign('end_date',$end_date);
		//系统充值
		$where_str6=" type=6 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59' ";
		$row=$model->field("sum(money) as sum_money")->where($where_str6)->find();
		$sum_row['type6']=$row['sum_money'];
		//个人充值
		$where_str1=" type=1 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59' ";
		$row=$model->field("sum(money) as sum_money")->where($where_str1)->find();
		$sum_row['type1']=$row['sum_money'];
		//转账日结
		$where_str3=" (type=0 or type=3) and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59' ";
		$row=$model->field("sum(money) as sum_money")->where($where_str3)->find();
		$sum_row['type3']=$row['sum_money'];
		//支付报名费
		$where_str4=" type=4 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59' ";
		$row=$model->field("sum(money) as sum_money")->where($where_str4)->find();
		$sum_row['type4']=0-$row['sum_money'];
		//退还报名费
		$where_str5=" type=5 and addtime > '".$start_date." 00:00:00' and addtime < '".$end_date." 23:59:59' ";
		$row=$model->field("sum(money) as sum_money")->where($where_str5)->find();
		$sum_row['type5']=$row['sum_money'];
		$this->assign('sum_row',$sum_row);
		$this->display();
	}
	
	//转账日结
	public function zzrj(){
		$userModel=M('SubUser');
		$taskModel=M('SubTask');
		$where=array();
		$where['type']=array('in',array(0,3));//转账日结
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
		
		if(I('get.start_date')){//日期
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			//职位
			$taskRow=$taskModel->field('id,title,addtime')->find($value['desc']);
			if($taskRow) $list['data'][$key]['title']=cut_str(deletehtml($taskRow['title']),15).'-'.substr($taskRow['addtime'],0,10);
		}
		$this->assign('list',$list);
		//总计充值额
		$sum_row=array();
		if(I('get.start_date')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$this->assign('sum_row',$sum_row);
		}
		$this->display();
	}
	
	//支付申请费
	public function zfsqf(){
		$userModel=M('SubUser');
		$taskModel=M('SubTask');
		$where=array();
		$where['type']=4;//转账日结
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
		
		if(I('get.start_date')){//日期
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$list['data'][$key]['money']=0-$value['money'];
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			//职位
			$taskRow=$taskModel->field('id,title,addtime')->find($value['desc']);
			if($taskRow) $list['data'][$key]['title']=cut_str(deletehtml($taskRow['title']),15).'-'.substr($taskRow['addtime'],0,10);
		}
		$this->assign('list',$list);
		//总计充值额
		$sum_row=array();
		if(I('get.start_date')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$sum_row['sum_money']=0-$sum_row['sum_money'];
			$this->assign('sum_row',$sum_row);
		}
		$this->display();
	}
	
	//退还申请费
	public function thsqf(){
		$userModel=M('SubUser');
		$taskModel=M('SubTask');
		$where=array();
		$where['type']=5;//转账日结
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
		
		if(I('get.start_date')){//日期
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $key=>$value){
			$userRow=$userModel->find($value['uid']);
			$list['data'][$key]['nickname']=$userRow['nickname'];
			$list['data'][$key]['username']=$userRow['username'];
			//职位
			$taskRow=$taskModel->field('id,title,addtime')->find($value['desc']);
			if($taskRow) $list['data'][$key]['title']=cut_str(deletehtml($taskRow['title']),15).'-'.substr($taskRow['addtime'],0,10);
		}
		$this->assign('list',$list);
		//总计充值额
		$sum_row=array();
		if(I('get.start_date')){
			$sum_row=D($this->moduleName)->field("sum(money) as sum_money")->where($where)->find();
			$sum_row['search']=1;
			$sum_row['sum_money']=$sum_row['sum_money'];
			$this->assign('sum_row',$sum_row);
		}
		$this->display();
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '姓名')
					->setCellValue('B2', '手机号')
					->setCellValue('C2', '职位')
					->setCellValue('D2', '金额(元)')
					->setCellValue('E2', '时间');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

		//数据库操作
		$model=D($this->moduleName);
		$userModel=M('SubUser');
		$taskModel=M('SubTask');
		
		if(I('get.type')=='zzrj'){
			$type_str="type in (0,3) and ";
		}else if(I('get.type')=='zfsqf'){
			$type_str="type=4 and ";
		}else if(I('get.type')=='thsqf'){
			$type_str="type=5 and ";
		}
		
		$where_str=$type_str."(addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59')";
		$title=I('get.start_date').'至'.I('get.end_date').'总额';
		
		$listArr=$model->where($where_str)->select();
		$sum_row=$model->field("sum(money) as sum_money")->where($where_str)->find();
		if(empty($listArr)) die('无数据');
		$title.=$sum_row['sum_money'].'元';
		
		foreach($listArr as $key=>$value){
			if($value['money'] < 0) $listArr[$key]['money']=0-$value['money'];
			$uRow=$userModel->find($value['uid']);
			$listArr[$key]['username']=$uRow['username'];
			$listArr[$key]['nickname']=$uRow['nickname'];
			//职位
			$taskRow=$taskModel->field('id,title,addtime')->find($value['desc']);
			if($taskRow) $listArr[$key]['title']=cut_str(deletehtml($taskRow['title']),15).'-'.substr($taskRow['addtime'],0,10);
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);
		foreach($listArr as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['nickname'])
										  ->setCellValue('B' . $lj, $lv['username'])
										  ->setCellValue('C' . $lj, $lv['title'])
										  ->setCellValue('D' . $lj, $lv['money'])
										  ->setCellValue('E' . $lj, $lv['addtime']);
		}
											
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		if(I('get.type')=='zzrj'){
			$file_name='转账日结';
		}else if(I('get.type')=='zfsqf'){
			$file_name='支付申请费';
		}else if(I('get.type')=='thsqf'){
			$file_name='退还申请费';
		}
		header('Content-Disposition: attachment;filename="'.$title.$file_name.'.xls"');
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