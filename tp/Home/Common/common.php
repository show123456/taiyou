<?php
//设置登录成功的人员信息保存到session
function setUserInfo($user,$key='user_info'){
    session($key,$user);
	if(I("post.remember")=='1'){
		$time=time()+60*60*24*30;	//15days
		setcookie("home_saveuid","",time()-3600);
		setcookie("home_saveuid",$_SESSION[$key]['id'],$time);
	}
}

//获取登录成功的人员信息
function getUserInfo($key='user_info'){
    return session($key);
}

//删除登录成功的人员信息
function delUserInfo($key='user_info'){
    session($key,null);
	setcookie("home_saveuid","",time()-3600);
}
//字符串入库前处理
function str_inmysql($str, $nohtml = '', $can_special = '') {
	if ($nohtml == '1') {
		$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	} else {
		$old_array = array('alert', '<script', 'onclick', 'javascript', 'onload', 'onerror', 'iframe', '<meta', '<input', '<textarea', 'onmouse');
		$new_array = array('&#097;lert', '&#60;script', '&#111;nclick', '&#106;avascript', '&#111;nload', '&#111;nerror', '&#105;frame', '&#60;meta', '&#60;input', '&#60;textarea', '&#111;nmouse');
		if ($can_special != '1') {
			$str = str_ireplace($old_array, $new_array, $str);
		}
		$str =mysql_escape_string($str);
	}
	return $str;
}
//去除html标签及空格换行
function deletehtml($str) {
	$str = strip_tags($str);
	$str = str_replace(array("\t", "\r\n", "\r", "\n", " "), "", $str);
	return $str;
}
//utf8编码字符串截取函数
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {
	if ($code == 'UTF-8') {
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);

		if (count($t_string[0]) - $start > $sublen)
			return join('', array_slice($t_string[0], $start, $sublen)) . "...";
		return join('', array_slice($t_string[0], $start, $sublen));
	} else {
		$start = $start * 2;
		$sublen = $sublen * 2;
		$strlen = strlen($string);
		$tmpstr = '';
		for ($i = 0; $i < $strlen; $i++) {
			if ($i >= $start && $i < ($start + $sublen)) {
				if (ord(substr($string, $i, 1)) > 129)
					$tmpstr .= substr($string, $i, 2);
				else
					$tmpstr .= substr($string, $i, 1);
			}
			if (ord(substr($string, $i, 1)) > 129)
				$i++;
		}
		if (strlen($tmpstr) < $strlen)
			$tmpstr .= "...";
		return $tmpstr;
	}
}
?>