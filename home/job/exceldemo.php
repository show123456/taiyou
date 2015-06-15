<?php
/** Error reporting */
//error_reporting(E_ALL);
ini_set('display_errors', FALSE);
//ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../task/Classes/PHPExcel.php';

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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '提交人姓名/性别/电话')
            ->setCellValue('B2', '司机姓名')
            ->setCellValue('C2', '手机号码')
            ->setCellValue('D2', '车牌号')
            ->setCellValue('E2', '驾照类型')
            ->setCellValue('F2', '驾驶证')
            ->setCellValue('G2', '车辆照片')
            ->setCellValue('H2', '提交时间');
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
include_once("../../includes/config.inc.php");
$submitModel=new Model_Subtable('sub_job_submit');
$id=(int)$_GET['id'];
$listArr = $submitModel->where(" jid='{$id}' ")->order('id desc')->dataArr();
if(!$listArr) die('无数据');

$userModel=new Model_Subtable('sub_user');
foreach($listArr as $key=>$value){
	$value['is_jsz']==1 ? $listArr[$key]['is_jsz']='有' : $listArr[$key]['is_jsz']='无';
	$value['is_clz']==1 ? $listArr[$key]['is_clz']='有' : $listArr[$key]['is_clz']='无';
	$userRow=$userModel->find($value['uid']);
	$listArr[$key]['uname']=$userRow['nickname'];
	$listArr[$key]['uphone']=$userRow['username'];
	$userRow['sex']==1 ? $listArr[$key]['usex']='男' : $listArr[$key]['usex']='女';
}
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '提报列表'.date('Y-m-d H:i:s'));
foreach($listArr as $lk=>$lv){
	$lj=$lk+3;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['uname'].'/'.$lv['usex'].'/'.$lv['uphone'])
	                              ->setCellValue('B' . $lj, $lv['name'])
	                              ->setCellValue('C' . $lj, $lv['phone'].' ')
	                              ->setCellValue('D' . $lj, $lv['carnum'])
	                              ->setCellValue('E' . $lj, $lv['license'])
	                              ->setCellValue('F' . $lj, $lv['is_jsz'])
	                              ->setCellValue('G' . $lj, $lv['is_clz'])
	                              ->setCellValue('H' . $lj, $lv['addtime']);
}
									
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('提报列表');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="提报列表'.date('Ymd').'.xls"');
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