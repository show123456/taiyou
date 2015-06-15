<?php
/** Error reporting */
//error_reporting(E_ALL);
ini_set('display_errors', FALSE);
//ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '姓名')
            ->setCellValue('B2', '手机号')
            ->setCellValue('C2', '银行卡')
            ->setCellValue('D2', '金额(元)');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);

//数据库操作
include_once("../../includes/config.inc.php");
$link=@mysql_connect($_WGT['DB']['Host'],$_WGT['DB']['User'],$_WGT['DB']['Password'])or die('连接失败，原因：'.mysql_error());
mysql_set_charset('utf8');
mysql_select_db($_WGT['DB']['Database'],$link);//数据库名

//数据列表
$model=D('sub_out');
$userModel=D('sub_user');
$listArr = $model->where('is_out=0')->dataArr();
if(!$listArr) die('无未导出的数据');
foreach($listArr as $v){
	$idRow[]=$v['id'];
}
$sql="update sub_out set is_out=1 where id in (".implode(',',$idRow).") ";
$model->query($sql);

foreach($listArr as $key=>$value){
	$userRow=$userModel->find($value['uid']);
	$listArr[$key]['nickname']=$userRow['nickname'];
	$listArr[$key]['username']=$userRow['username'];
	$listArr[$key]['bank_name']=$userRow['bank_name'];
	$listArr[$key]['bank_card']=$userRow['bank_card'];
}

$current_time=time();
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', date('Y-m-d H:i',$current_time).'提现列表');
foreach($listArr as $lk=>$lv){
	$lj=$lk+3;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['nickname'])
	                              ->setCellValue('B' . $lj, $lv['username'])
	                              ->setCellValue('C' . $lj, $lv['bank_name'].'-'.$lv['bank_card'])
	                              ->setCellValue('D' . $lj, $lv['money']);
}
									
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('提现列表');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.date('Y-m-d',$current_time).'提现列表.xls"');
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