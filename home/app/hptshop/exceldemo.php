<?php
/** Error reporting */
//error_reporting(E_ALL);
ini_set('display_errors', FALSE);
//ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../../tx/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

// Add some data
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(50);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '订单号')
            ->setCellValue('B2', '产品条码')
            ->setCellValue('C2', '订单状态')
            ->setCellValue('D2', '买家姓名')
            ->setCellValue('E2', '商品名称')
            ->setCellValue('F2', '商品单价')
            ->setCellValue('G2', '商品数量')
            ->setCellValue('H2', '商品总价')
            ->setCellValue('I2', '收货人姓名')
            ->setCellValue('J2', '收货人电话')
            ->setCellValue('K2', '收货地址');

//数据库操作
include_once("../../../includes/config.inc.php");
$model=D('applist_hpt_shop_order');
$detailModel=D('applist_hpt_shop_odetail');
$goodsModel=D('applist_hpt_shop_goods');
$oid=$_GET['oid'];
$row = $model->where("id='{$oid}'")->dataRow();
$listArr = $detailModel->where("oid='{$oid}'")->dataArr();

if($row['is_lb']==1){
	$lb='（大礼包）';
}
if($row['is_pay']==1){
	$row['is_pay']='已支付';
}else{
	$row['is_pay']='未支付';
}
if($row['status']==0){
	$row['status']='，未发货';
}elseif($row['status']==1){
	$row['status']='，已发货';
}
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','总额：'.$row['money'].'元（含运费'.$row['yunfei'].'元）'.$lb);
foreach($listArr as $lk=>$lv){
	$goodsRow=$goodsModel->find($lv['gid']);
	$lj=$lk+3;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $row['id'])
	                              ->setCellValue('B' . $lj, $goodsRow['one_code'].' ')
	                              ->setCellValue('C' . $lj, $row['is_pay'].$row['status'])
	                              ->setCellValue('D' . $lj, $row['name'])
	                              ->setCellValue('E' . $lj, $lv['name'])
	                              ->setCellValue('F' . $lj, $lv['price'])
	                              ->setCellValue('G' . $lj, $lv['num'])
	                              ->setCellValue('H' . $lj, $lv['money'])
	                              ->setCellValue('I' . $lj, $row['name'])
	                              ->setCellValue('J' . $lj, $row['tel'].' ')
	                              ->setCellValue('K' . $lj, $row['address']);
}
									
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('订单');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="orderID'.$row['id'].'.xls"');
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