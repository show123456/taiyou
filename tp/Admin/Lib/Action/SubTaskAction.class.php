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
	
	//任务分拨
	public function daily(){
		I('get.start_date') ? $start_date=I('get.start_date') : $start_date=date('Y-m-d');
		I('get.end_date') ? $end_date=I('get.end_date') : $end_date=$start_date;
		if($start_date==$end_date){
			$where_str_task="left(work_time,10) = '".$start_date."'";
		}else{
			$where_str_task="left(work_time,10) >= '".$start_date."' and left(work_time,10) <= '".$end_date."'";
		}
		
		$list=D('SubTask')->getPager($where_str_task,10,'id asc');
		//foreach($list['data'] as $k=>$v){
			
		//}
		$this->assign('list',$list);
		$this->display();
	}
	
	//任务分拨
	public function detail(){
		$userModel=M('SubUser');
		$tid=I('get.tid');
		$list=M('SubAssign')->where(array('tid'=>$tid))->select();
		foreach($list as $k=>$v){
			$dudao_row=$userModel->find($v['dudao_uid']);
			$list[$k]['dudao_name']=$dudao_row['nickname'];
			$list[$k]['xuhao']=$k+1;
			if($v['zd']){
				$zd_name_arr=array();
				$zd_arr=explode(',',$v['zd']);
				$zd_array=$userModel->where(array('id'=>array('in',$zd_arr)))->select();
				foreach($zd_array as $zv){
					$zd_name_arr[]=$zv['nickname'];
				}
				$list[$k]['zd_name_str']=implode(',',$zd_name_arr);
			}
		}
		$this->assign('list',$list);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', '序号')
					->setCellValue('B3', '姓名')
					->setCellValue('C3', '性别')
					->setCellValue('D3', '联系电话')
					->setCellValue('E3', '身份证号')
					->setCellValue('F3', '人员类别')
					->setCellValue('G3', '结算方式')
					->setCellValue('H3', '薪资');

		//数据库操作
		$model=D($this->moduleName);
		$userModel=M('SubUser');
		$tid=I('get.tid');
		$taskRow=$model->find($tid);
		$taskRow['pay_type']==1 ? $taskRow['pay_type_name']='现金日结' : $taskRow['pay_type_name']='转账日结';
		
		$listArr=M('SubSign')->where(" tid='{$tid}' and is_valid=1")->select();
		if($listArr){
			foreach($listArr as $key=>$value){
				$uRow=$userModel->find($value['uid']);
				$listArr[$key]['nickname']=$uRow['nickname'];
				$listArr[$key]['username']=$uRow['username'];
				$listArr[$key]['cardnum']=$uRow['cardnum'];
				$uRow['sex']==1 ? $listArr[$key]['sex']='男' : $listArr[$key]['sex']='女';
				$uRow['is_v']==1 ? $listArr[$key]['type']='加v会员' : $listArr[$key]['type']='普通会员';
				$listArr[$key]['xuhao']=$key+1;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $taskRow['title']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '兼职日期'.substr($taskRow['work_time'],0,10));
		foreach($listArr as $lk=>$lv){
			$lj=$lk+4;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['xuhao'])
										  ->setCellValue('B' . $lj, $lv['nickname'])
										  ->setCellValue('C' . $lj, $lv['sex'])
										  ->setCellValue('D' . $lj, $lv['username'])
										  ->setCellValueExplicit('E' . $lj, $lv['cardnum'])
										  ->setCellValue('F' . $lj, $lv['type'])
										  ->setCellValue('G' . $lj, $taskRow['pay_type_name'])
										  ->setCellValue('H' . $lj, $lv['fact_money']);
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
	
	//任务分拨导出
	public function daily_daochu(){
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', '客户名称')
					->setCellValue('B3', '人数')
					->setCellValue('C3', '工作')
					->setCellValue('D3', '结算方式')
					->setCellValue('E3', '集合地点')
					->setCellValue('F3', '工作时间')
					->setCellValue('G3', '工资')
					->setCellValue('H3', '备注');

		//数据库操作
		$model=D($this->moduleName);
		$assignModel=M('SubAssign');
		if(I('get.start_date')){
			$start_date=I('get.start_date');
			$end_date=I('get.end_date');
		}else{
			$start_date=$end_date=date('Y-m-d');
		}
		$list=$model->where("left(work_time,10) >= '{$start_date}' and left(work_time,10) >= '{$end_date}'")->select();
		foreach($list as $k=>$v){
			$v['pay_type']==1 ? $list[$k]['pay_type_name']='现金日结' : $list[$k]['pay_type_name']='转账日结';
			/* $assign_arr=$assignModel->where(array('tid'=>$tid))->select();
			foreach($list as $k=>$v){
				$dudao_row=$userModel->find($v['dudao_uid']);
				$list[$k]['dudao_name']=$dudao_row['nickname'];
				$list[$k]['xuhao']=$k+1;
				if($v['zd']){
					$zd_name_arr=array();
					$zd_arr=explode(',',$v['zd']);
					$zd_array=$userModel->where(array('id'=>array('in',$zd_arr)))->select();
					foreach($zd_array as $zv){
						$zd_name_arr[]=$zv['nickname'];
					}
					$list[$k]['zd_name_str']=implode(',',$zd_name_arr);
				}
			} */
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '任务分拨');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $start_date.'~'.$start_date);
		foreach($list as $lk=>$lv){
			$lj=$lk+4;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['company_name'])
										  ->setCellValue('B' . $lj, $lv['num'])
										  ->setCellValue('C' . $lj, $lv['title'])
										  ->setCellValue('D' . $lj, $lv['pay_type_name'])
										  ->setCellValueExplicit('E' . $lj, $lv['jihe_address'])
										  ->setCellValue('F' . $lj, substr($lv['work_time'],0,10))
										  ->setCellValue('G' . $lj, $lv['money'])
										  ->setCellValue('H' . $lj, '无');
		}
											
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="任务分拨.xls"');
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