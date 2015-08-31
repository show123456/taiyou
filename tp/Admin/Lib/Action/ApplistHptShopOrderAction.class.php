<?php
class ApplistHptShopOrderAction extends CommonAction{
    public function index(){
		$where['is_pay']=1;
		$where['pay_type']=I('get.pay_type');
		if(I('get.start_date')){
			$where['_string']=" addtime > '".I('get.start_date')." 00:00:00' and addtime < '".I('get.end_date')." 23:59:59' ";
		}
        $this->assign('list',D($this->moduleName)->getPager($where,10));
        $this->assign('now_date',date('Y-m-d'));
        $this->display();
    }
	
    public function out(){
		$odModel=M('ApplistHptShopOdetail');
		$where['is_pay']=1;
		if(I('get.start_date')){
			$where['_string']=" pay_time > '".I('get.start_date')." 00:00:00' and pay_time < '".I('get.end_date')." 23:59:59' ";
		}
		$list=D($this->moduleName)->where($where)->select();$tt=0;
		foreach($list as $ok=>$ov){
			//底价支出
			$out_money=0;
			$od_arr=$odModel->where(array('oid'=>$ov['id']))->select();
			foreach($od_arr as $odv){
				$out_money+=$odv['ori_price']*$odv['num'];
			}
			$list[$ok]['out']=$out_money+9;
		}//var_dump($list);
        $this->assign('list',$list);
        $this->display();
    }
	
	public function update_date(){
		$data['id']=I('get.id');
		$data['send_date']=date('Y-m-d H:i:s');
		M('SubDaily')->save($data);
	}
	
	public function daochu_page(){
		for($i=0;$i<24;$i++){
			if($i<10){
				$arr[]='0'.$i.':00';
			}else{
				$arr[]=$i.':00';
			}
		}
        $this->assign('timearr',$arr);
		
		$odModel=M('ApplistHptShopOdetail');
		I('get.start_date') ? $start_date=I('get.start_date') : $start_date=date('Y-m-d');
		I('get.start_time') ? $start_time=I('get.start_time').':00' : $start_time='00:00:00';
		I('get.end_date') ? $end_date=I('get.end_date') : $end_date=$start_date;
		I('get.end_time') ? $end_time=I('get.end_time').':59' : $end_time='00:00:59';
		$where="is_pay=1 and pay_time > '".$start_date.' '.$start_time."' and pay_time < '".$end_date.' '.$end_time."'";
		
		$this->assign('start_date',$start_date);
		$this->assign('end_date',$end_date);
		$list=D($this->moduleName)->where($where)->order('id asc')->select();
		
		$goodsModel=M('ApplistHptShopGoods');
		$info=array();$list_k=0;
		$total_shouru=0;//总收入
		$total_zhichu=0;//总支出
		$total_shouru_yue=0;//余额收入
		$total_shouru_wx=0;//微支付收入
		foreach($list as $key=>$value){
			$zc=0;//支出
			$od_arr=$odModel->where(array('oid'=>$value['id']))->select();
			foreach($od_arr as $k=>$v){
				//订单信息
				$info[$list_k]['oid']=$value['id'];//订单号
				$info[$list_k]['uname']=$value['name'];
				$info[$list_k]['utel']=$value['tel'];
				$info[$list_k]['pay_time']=$value['pay_time'];
				//详情信息
				$info[$list_k]['gone_code']=$v['one_code'];
				$info[$list_k]['gname']=$v['name'];
				$info[$list_k]['gori_price']=$v['ori_price'];
				$info[$list_k]['gfact_price']=$v['price'];
				$info[$list_k]['gnum']=$v['num'];
				$info[$list_k]['gori']=$v['ori_price']*$v['num'];
				$info[$list_k]['gfact']=$v['fact_price']*$v['num'];
				$zc+=$v['ori_price']*$v['num'];
				
				if(($k+1)==$value['num']){
					$info[$list_k]['is_last']=1;//为订单最后一行
					$info[$list_k]['money']=$value['money'];
					$info[$list_k]['pay_type']=$value['pay_type'];
					$value['pay_type']==1 ? $info[$list_k]['pay_type_name']='余额支付' : $info[$list_k]['pay_type_name']='微信支付';
					$info[$list_k]['yunfei']=12;
					$info[$list_k]['bz']=0;//包装费
					$info[$list_k]['zc']+=$zc+12;
				}
				
				$list_k++;
			}
			if($value['pay_type']==1){
				$total_shouru_yue+=$value['money'];
			}else{
				$total_shouru_wx+=$value['money'];
			}
			$total_shouru+=$value['money'];
			$index_k=$list_k-1;
			$total_zhichu+=$info[$index_k]['zc'];
		}
		//dump($info);
		$this->assign('info',$info);
		$this->assign('total_shouru_yue',$total_shouru_yue);
		$this->assign('total_shouru_wx',$total_shouru_wx);
		$this->assign('total_shouru',$total_shouru);
		$this->assign('total_zhichu',$total_zhichu);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', '订单号')
					->setCellValue('B2', '条形码')
					->setCellValue('C2', '商品名')
					->setCellValue('D2', '底价')
					->setCellValue('E2', '售价')
					->setCellValue('F2', '数量')
					->setCellValue('G2', '运费')
					->setCellValue('H2', '包装费')
					->setCellValue('I2', '支付方式')
					->setCellValue('J2', '收入')
					->setCellValue('K2', '支出')
					->setCellValue('L2', '支付时间');

		//数据库操作
		$model=D($this->moduleName);
		$odModel=M('ApplistHptShopOdetail');
		
		I('get.start_time') ? $start_time=I('get.start_time').':00' : $start_time='00:00:00';
		I('get.end_time') ? $end_time=I('get.end_time').':59' : $end_time='00:00:59';
		$where="is_pay=1 and pay_time > '".I('get.start_date').' '.$start_time."' and pay_time < '".I('get.end_date').' '.$end_time."'";
		$list=D($this->moduleName)->where($where)->order('id asc')->select();
		
		$goodsModel=M('ApplistHptShopGoods');
		$info=array();$list_k=0;
		$total_shouru=0;//总收入
		$total_zhichu=0;//总支出
		$total_shouru_yue=0;//余额收入
		$total_shouru_wx=0;//微支付收入
		foreach($list as $key=>$value){
			$zc=0;//支出
			$od_arr=$odModel->where(array('oid'=>$value['id']))->select();
			foreach($od_arr as $k=>$v){
				//订单信息
				$info[$list_k]['oid']=$value['id'];//订单号
				$info[$list_k]['uname']=$value['name'];
				$info[$list_k]['utel']=$value['tel'];
				$info[$list_k]['pay_time']=$value['pay_time'];
				//详情信息
				$info[$list_k]['gone_code']=$v['one_code'];
				$info[$list_k]['gname']=$v['name'];
				$info[$list_k]['gori_price']=$v['ori_price'];
				$info[$list_k]['gfact_price']=$v['price'];
				$info[$list_k]['gnum']=$v['num'];
				$info[$list_k]['gori']=$v['ori_price']*$v['num'];
				$info[$list_k]['gfact']=$v['fact_price']*$v['num'];
				$zc+=$v['ori_price']*$v['num'];
				
				if(($k+1)==$value['num']){
					$info[$list_k]['is_last']=1;//为订单最后一行
					$info[$list_k]['money']=$value['money'];
					$info[$list_k]['yunfei']=12;
					$info[$list_k]['bz']=0;//包装费
					$info[$list_k]['zc']+=$zc+12;
					$value['pay_type']==1 ? $info[$list_k]['pay_type_name']='余额支付' : $info[$list_k]['pay_type_name']='微信支付';
				}
				
				$list_k++;
			}
			if($value['pay_type']==1){
				$total_shouru_yue+=$value['money'];
			}else{
				$total_shouru_wx+=$value['money'];
			}
			$total_shouru+=$value['money'];
			$index_k=$list_k-1;
			$total_zhichu+=$info[$index_k]['zc'];
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '总计：余额收入￥'.$total_shouru_yue.'，微信收入￥'.$total_shouru_wx.'总收入￥'.$total_shouru.'，支出￥'.$total_zhichu);
		foreach($info as $lk=>$lv){
			$lj=$lk+3;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['oid'])
										  ->setCellValue('B' . $lj, $lv['gone_code'])
										  ->setCellValue('C' . $lj, $lv['gname'])
										  ->setCellValue('D' . $lj, $lv['gori_price'])
										  ->setCellValue('E' . $lj, $lv['gfact_price'])
										  ->setCellValue('F' . $lj, $lv['gnum'])
										  ->setCellValue('G' . $lj, $lv['yunfei'])
										  ->setCellValue('H' . $lj, $lv['bz'])
										  ->setCellValue('I' . $lj, $lv['pay_type_name'])
										  ->setCellValue('J' . $lj, $lv['money'])
										  ->setCellValue('K' . $lj, $lv['zc'])
										  ->setCellValue('L' . $lj, $lv['pay_time']);
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
}