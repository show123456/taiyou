<?php
class SubPhoneAction extends CommonAction{
    public function index(){
		$userModel=M('SubUser');
		if(I('get.is_important')){
			$where['is_important']=I('get.is_important');
		}
		if(I('get.title')){
			$where['title']=array('like',"%".I('get.title')."%");
		}
		if(I('get.username')){
			$uRow=$userModel->where(array('username'=>I('get.username')))->find();
			$where['uid']=$uRow['id'];
		}
		if(I('get.start_date')){
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
		
		$list=D($this->moduleName)->getPager($where);
		foreach($list['data'] as $k=>$v){
			$tjr_row=$userModel->find($v['uid']);
			$list['data'][$k]['tjr_phone']=$tjr_row['username'];
			$list['data'][$k]['tjr_name']=$tjr_row['nickname'];
			$list['data'][$k]['xuhao']=$k+1;
		}
		$this->assign('list',$list);
		$this->display();
    }
	
    public function add(){
        if(IS_POST){
			$data=I('post.');
			if(isset($data['lq_time'])) $data['info']['lq_time']=implode(',',$data['lq_time']);
            $res = D($this->moduleName)->saveData($data);
            $resText = $res ? '保存成功':'保存失败';
			
			if($res){
				$this->success($resText,I('post.lastURL'));
			}else{
				$this->error($resText);
			}
        }else{
			$lp_time_arr=array();
            $id=I('get.id');
            if($id){
				$vo=D($this->moduleName)->find($id);
				if(!is_null($vo['lq_time']))
					$lp_time_arr=explode(',',$vo['lq_time']);
                $this->assign('vo',$vo);
                $this->assign('lp_time_arr',$lp_time_arr);
            }
			$this->assign('lastURL',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '序号')
					->setCellValue('B2', '手机号')
					->setCellValue('C2', '姓名')
					->setCellValue('D2', '性别')
					->setCellValue('E2', '客户级别')
					->setCellValue('F2', '提报人')
					->setCellValue('G2', '职位')
					->setCellValue('H2', '提报时间');
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
		if(I('get.is_important')){
			$where['is_important']=I('get.is_important');
			I('get.is_important')==1 ? $title='重要客户' : $title='普通客户';
		}
		if(I('get.title')){
			$where['title']=array('like',"%".I('get.title')."%");
			$title.='，'.I('get.title');
		}
		if(I('get.username')){
			$uRow=$userModel->where(array('username'=>I('get.username')))->find();
			$where['uid']=$uRow['id'];
			$title.='，'.I('get.username');
		}
		if(I('get.start_date')){
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
			$title.='，'.I('get.start_date').'~'.I('get.end_date');
		}
		
		$list=D($this->moduleName)->where($where)->select();
		foreach($list as $k=>$v){
			$v['sex']==1 ? $list[$k]['sex']='男' : $list[$k]['sex']='女';
			$v['is_important']==1 ? $list[$k]['is_important']='重要' : $list[$k]['is_important']='普通';
			$tjr_row=$userModel->find($v['uid']);
			$list[$k]['tjr_phone']=$tjr_row['username'];
			$list[$k]['tjr_name']=$tjr_row['nickname'];
			$list[$k]['xuhao']=$k+1;
		}
		$listArr=$list;
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '电话提报:'.$title);
		foreach($listArr as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['xuhao'])
										  ->setCellValue('B' . $lj, $lv['phone'])
										  ->setCellValue('C' . $lj, $lv['name'])
										  ->setCellValue('D' . $lj, $lv['sex'])
										  ->setCellValue('E' . $lj, $lv['is_important'])
										  ->setCellValue('F' . $lj, $lv['tjr_name'].'-'.$lv['tjr_phone'])
										  ->setCellValue('G' . $lj, $lv['title'].'-'.$lv['work_date'])
										  ->setCellValue('H' . $lj, $lv['addtime']);
		}
											
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('统计数据');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="电话提报.xls"');
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