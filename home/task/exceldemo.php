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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '序号')
            ->setCellValue('B2', '姓名')
            ->setCellValue('C2', '手机号')
            ->setCellValue('D2', '身份证')
            ->setCellValue('E2', '薪酬(元)')
            ->setCellValue('F2', '报名状态')
            ->setCellValue('G2', '签到状态')
            ->setCellValue('H2', '是否结算');
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
$link=@mysql_connect($_WGT['DB']['Host'],$_WGT['DB']['User'],$_WGT['DB']['Password'])or die('连接失败，原因：'.mysql_error());
mysql_set_charset('utf8');
mysql_select_db($_WGT['DB']['Database'],$link);//数据库名

//数据列表
$model=D('sub_task');
$signModel=D('sub_sign');
//职位标题
$vo=$model->field('id,title')->where("id=".$_GET['tid'])->dataRow();

$listArr=$signModel->where("tid=".$_GET['tid'])->order('distance asc')->dataArr();
if(!$listArr) die('无数据');
$userModel=D('sub_user');
foreach($listArr as $key=>$value){
	//序号
	$listArr[$key]['xuhao']=$key+1;
	
	$uRow=$userModel->find($value['uid']);
	$listArr[$key]['username']=$uRow['username'];
	$listArr[$key]['nickname']=$uRow['nickname'];
	$listArr[$key]['cardnum']=$uRow['cardnum'];
	if($value['is_valid']==0)
		$listArr[$key]['is_valid']='未审核';
	else if($value['is_valid']==1)
		$listArr[$key]['is_valid']='有效报名';
	else
		$listArr[$key]['is_valid']='无效报名';
	if($value['is_qd']==0)
		$listArr[$key]['is_qd']='未审核';
	else if($value['is_qd']==1)
		$listArr[$key]['is_qd']='已签到';
	else
		$listArr[$key]['is_qd']='未签到';
	if($value['is_js']==0)
		$listArr[$key]['is_js']='未审核';
	else if($value['is_js']==1)
		$listArr[$key]['is_js']='允许结算';
	else
		$listArr[$key]['is_js']='拒绝结算';
}
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $vo['title'].'报名列表');
foreach($listArr as $lk=>$lv){
	$lj=$lk+3;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $lj, $lv['xuhao'])
	                              ->setCellValue('B' . $lj, $lv['nickname'])
	                              ->setCellValue('C' . $lj, $lv['username'])
	                              ->setCellValue('D' . $lj, $lv['cardnum'].' ')
	                              ->setCellValue('E' . $lj, $lv['fact_money'])
	                              ->setCellValue('F' . $lj, $lv['is_valid'])
	                              ->setCellValue('G' . $lj, $lv['is_qd'])
	                              ->setCellValue('H' . $lj, $lv['is_js']);
}
									
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('报名列表');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$vo['title'].'报名列表.xls"');
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