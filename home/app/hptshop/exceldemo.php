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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '商品名')
            ->setCellValue('B2', '价格')
            ->setCellValue('C2', '数量')
            ->setCellValue('D2', '小计');

//数据库操作
include_once("../../../includes/config.inc.php");
$model=D('applist_hpt_shop_order');
$detailModel=D('applist_hpt_shop_odetail');
$oid=$_GET['oid'];
$row = $model->where("id='{$oid}'")->dataRow();
$listArr = $detailModel->where("oid='{$oid}'")->dataArr();

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','订单号：'.$row['id'].'，联系人：'.$row['name'].'-'.$row['tel'].'-'.$row['address'].'，总额：'.$row['money'].'元（含运费'.$row['yunfei'].'元），下单时间：'.substr($row['addtime'],0,16).'，备注：'.$row['content']);
foreach($listArr as $lk=>$lv){
	$lj=$lk+3;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['name'])
	                              ->setCellValue('B' . $lj, $lv['price'])
	                              ->setCellValue('C' . $lj, $lv['num'])
	                              ->setCellValue('D' . $lj, $lv['money']);
}
									
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('订单');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="订单'.$row['id'].'.xls"');
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