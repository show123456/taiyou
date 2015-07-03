<?php
class SubTaskAction extends CommonAction{
	//现金日结对应的任务列表
	public function xj_index(){
		$signModel=M('SubSign');
		$start_date=I('get.start_date');
		$end_date=I('get.end_date');
		if($start_date==$end_date){
			$where_str_task="pay_type=1 and left(work_time,10) = '".$start_date."'";
		}else{
			$where_str_task="pay_type=1 and left(work_time,10) >= '".$start_date."' and left(work_time,10) <= '".$end_date."'";
		}
		
		$list=D('SubTask')->getPager($where_str_task,10,'id asc');
		foreach($list['data'] as $k=>$v){
			//报名有效人数
			$valid_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_valid'=>1))->find();
			$list['data'][$k]['valid_num']=(int)$valid_row['c'];
			//允许签到人数
			$qd_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_qd'=>1))->find();
			$list['data'][$k]['qd_num']=(int)$qd_row['c'];
			//允许结算人数
			$js_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_js'=>1))->find();
			$list['data'][$k]['js_num']=(int)$js_row['c'];
			//需备用金额
			if($v['is_kh']==1){
				$list['data'][$k]['spare_money']=0;
			}else{
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$valid_row['c'];
				if($qd_row['c']) $list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$qd_row['c'];
				if($js_row['c']) $list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$js_row['c'];
			}
		}
		$this->assign('list',$list);
		$this->display('index');
	}
	
	//转账日结对应的任务列表
	public function zz_index(){
		$signModel=M('SubSign');
		I('get.start_date') ? $start_date=I('get.start_date') : $start_date=date('Y-m-d');
		I('get.end_date') ? $end_date=I('get.end_date') : $end_date=$start_date;
		if($start_date==$end_date){
			$where_str_task="pay_type=2 and left(work_time,10) = '".$start_date."'";
		}else{
			$where_str_task="pay_type=2 and left(work_time,10) >= '".$start_date."' and left(work_time,10) <= '".$end_date."'";
		}
		
		$list=D('SubTask')->getPager($where_str_task,10,'id asc');
		foreach($list['data'] as $k=>$v){
			//报名有效人数
			$valid_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_valid'=>1))->find();
			$list['data'][$k]['valid_num']=(int)$valid_row['c'];
			//允许签到人数
			$qd_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_qd'=>1))->find();
			$list['data'][$k]['qd_num']=(int)$qd_row['c'];
			//允许结算人数
			$js_row=$signModel->field('count(id) as c')->where(array('tid'=>$v['id'],'is_js'=>1))->find();
			$list['data'][$k]['js_num']=(int)$js_row['c'];
			//需备用金额
			if($v['is_kh']==1){
				$list['data'][$k]['spare_money']=0;
			}else{
				$list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$valid_row['c'];
				if($qd_row['c']) $list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$qd_row['c'];
				if($js_row['c']) $list['data'][$k]['spare_money']=intval($list['data'][$k]['money'])*$js_row['c'];
			}
		}
		$this->assign('list',$list);
		$this->display('index');
	}
	
	//查看名单
	public function see(){
		$userModel=M('SubUser');
		$tid=I('get.tid');
		$listArr=M('SubSign')->where(" tid='{$tid}' and is_valid=1")->select();
		if($listArr){
			foreach($listArr as $key=>$value){
				$listArr[$key]['xuhao']=$key+1;
				$uRow=$userModel->find($value['uid']);
				$listArr[$key]['nickname']=$uRow['nickname'];
				$listArr[$key]['username']=$uRow['username'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			}
		}
		$this->assign('list',$listArr);
		$this->display('see');
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '姓名')
					->setCellValue('B2', '性别')
					->setCellValue('C2', '联系电话')
					->setCellValue('D2', '身份证号');

		//数据库操作
		$model=D($this->moduleName);
		$userModel=M('SubUser');
		$tid=I('get.tid');
		$taskRow=$model->find($tid);
		
		$listArr=M('SubSign')->where(" tid='{$tid}' and is_valid=1")->select();
		if($listArr){
			foreach($listArr as $key=>$value){
				$uRow=$userModel->find($value['uid']);
				$listArr[$key]['nickname']=$uRow['nickname'];
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['cardnum']=$uRow['cardnum'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $taskRow['title']);
		foreach($listArr as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['nickname'])
										  ->setCellValue('B' . $lj, $lv['sex'])
										  ->setCellValue('C' . $lj, $lv['username'])
										  ->setCellValueExplicit('D' . $lj, $lv['cardnum']);
		}
											
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$taskRow['title'].'.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header ('Cache-Control: cache, must-revalidate');
		header ('Pragma: public');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}