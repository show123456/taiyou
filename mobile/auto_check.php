<?php
	/**
	 *每天21:00和23:58自动将明天工作的报名人员审核通过--windows定时任务
	 */
	  
	$date=date('Y-m-d',time()+24*3600);
	//$date='2015-07-29';
	$link = @mysql_connect('localhost','root','yunke123') or die('数据库连接失败！原因：'.mysql_error());
	mysql_set_charset("utf8");
	mysql_select_db('taiyou',$link);
	$sql = "update sub_sign set is_valid=1 where task_date='".$date."' and is_valid=0";
	mysql_query($sql,$link);