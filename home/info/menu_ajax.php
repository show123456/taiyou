<?php
include_once("../../includes/config.inc.php");
include_once("../../includes/login_check.php");
function post_http($url,$postType,$data = null){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)){
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}

$BottomMenu = new Model_BottomMenu();
$id = (int)$_POST['mid'];
$c_id = (int)$_SESSION['customer_id'];

$data=array();
$data['customer_id']=(int)$_SESSION['customer_id'];
$data['menu_name']=str_inmysql($_POST['m_name']);
$data['menu_type']=str_inmysql($_POST['m_type']);
$data['menu_key']=str_inmysql($_POST['m_key']);
$data['menu_order']=(int)($_POST['menu_order']);
$data['id']=(int)($_POST['m_id']);
$data['parent_id'] = (int)$_POST['parent_id'];
if($_POST['act']=="createMenu"){
	pub();
}elseif($_POST['act']=="createMenuDo"){
	if($_POST['m_id']=='0'){
		unset($data['id']);
	}
	if($_POST['m_id']=='999999999'){
		unset($data['id']);
		$p =  $BottomMenu->fetchRow("select id from bottom_menu where menu_order='{$data[menu_order]}' and customer_id='{$data[customer_id]}' and parent_id='0'");
		$data['parent_id'] =$p['id'];
	}
	$result = $BottomMenu->insert($data);
	
}elseif($_POST['act']=="dele"){
	$c_id = (int)$_SESSION['customer_id'];
	$id = (int)$_POST['m_id'];
	$where = "customer_id=$c_id  and id=$id";
	$BottomMenu->delete($where);
	
}elseif($_POST['act']=="select"){
	$id=(int)$_POST['m_id'];
	$row = $BottomMenu->fetchRow("select * from bottom_menu where customer_id='$c_id' and id='$id'");
	$res = json_encode($row);
	header('Content-Type: application/json');
	echo $res;
}elseif($_POST['act']=="update"){
	$result = $BottomMenu->query("update bottom_menu set parent_id={$data[parent_id]},menu_name='{$data[menu_name]}',menu_type='{$data[menu_type]}',menu_key='{$data['menu_key']}',menu_order='{$data['menu_order']}' where id={$_POST['m_id']} and customer_id={$data[customer_id]}");
}elseif($_POST['act']=="pub_menu"){
	//向微信提交菜单信息
	$db_obj = new Model_CustomerConfig();
	$sql = "select c_value from customer_config where customer_id='$c_id' and c_type='appid' limit 1";
    $db_obj->query($sql);
	
    if ($db_obj->next_record()) {
        $c_value    = $db_obj->f('c_value');
        $appid_info = explode(',', $c_value);
        $appid      = $appid_info['0'];
        $secret     = $appid_info['1'];
        $url        = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        $get_token  = post_http($url, 'GET', '');
        //$get_token  = file_get_contents($url);
        if (strstr($get_token, 'errmsg')) {
            die('无效的Appld');
        } else if (strstr($get_token, 'access_token')) {
            $token_array  = json_decode($get_token, true);
            $access_token = $token_array['access_token'];
        } else {
            die('微信无应答，请稍候再试！Error2');
        }
    } else {
        die('请先做授权设置!!！');
    }
    $menu_body = '';
    $sql       = "select * from bottom_menu  where customer_id='$c_id' and parent_id='0' order by id ";
    $db_obj->query($sql);
    while ($db_obj->next_record()) {
        $i++;
        $menu[$i]['id']        = $db_obj->f('id');
        $menu[$i]['menu_name'] = $db_obj->f('menu_name');
        $menu[$i]['menu_type'] = $db_obj->f('menu_type');
        $menu[$i]['menu_key']  = $db_obj->f('menu_key');
    }
    $big_menu_num = count($menu);
    for ($i = 1; $i <= $big_menu_num; $i++) {
        $parent_id  = $menu[$i]['id'];
        $menu_name  = $menu[$i]['menu_name'];
        $menu_key   = $menu[$i]['menu_key'];
        $menu_type  = $menu[$i]['menu_type'];
        $smenu_body = '';
        $sql        = "select * from bottom_menu where customer_id='$c_id' and parent_id='$parent_id' order by id desc ";
        $db_obj->query($sql);
        while ($db_obj->next_record()) {
            $smenu_name = $db_obj->f('menu_name');
            $smenu_key  = $db_obj->f('menu_key');
            $smenu_type = $db_obj->f('menu_type');
            if ($smenu_type == 'click') {
                $smenu_body.="{
					  \"type\":\"$menu_type\",
					  \"name\":\"$smenu_name\",
					  \"key\":\"$smenu_key\"
				  },";
            } else {
                $smenu_body.="{
					  \"type\":\"$smenu_type\",
					  \"name\":\"$smenu_name\",
					  \"url\":\"$smenu_key\"
				  },";
            }
        }
        if ($smenu_body != '') {
            $smenu_body = rtrim($smenu_body, ',');
            $key        = "\"sub_button\":[
					$smenu_body]";
        } else {
            if ($menu_type == 'click') {
                $key = "\"key\":\"$menu_key\"";
            } else {
                $key = "\"url\":\"$menu_key\"";
            }
        }
        $menu_body.="{
				  \"type\":\"$menu_type\",
				  \"name\":\"$menu_name\",
				  $key
			  },";
    }
    $menu_body     = rtrim($menu_body, ',');
    $post_menu     = "{
			 \"button\":[
			 $menu_body]
		 }";
		 

    //echo $post_menu;
    $url           = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
    $create_result = post_http($url, 'POST', $post_menu);
    $result_array  = json_decode($create_result, true);
    $errcode       = $result_array['errcode'];
    $errmsg        = $result_array['errmsg']; 
    if ($errmsg == 'ok') {
        echo 'success';
    } else {
        echo '错误码：' . $errcode . '，错误提示：' . $errmsg;
    }
}

function pub(){
	$c_id = (int)$_SESSION['customer_id'];
	$BottomMenu= new Model_BottomMenu();
	$parentMenu = $BottomMenu->fetchAll("select * from bottom_menu where customer_id='$c_id' and parent_id='0' order by menu_order asc");

//如果菜单信息大于1条
		if(count($parentMenu)>0){
			foreach($parentMenu as $v){
				//将menu_order作为下标
				$final_Menu[$v['menu_order']]=$v;
				//查询 顶级菜单下的 2级菜单，并跟随父级菜单的下标
				$childMenu[$v['menu_order']] = $BottomMenu->fetchAll("select * from bottom_menu where customer_id='$c_id' and parent_id='{$v[id]}' order by id desc");
			}
			
			//第一分组
			if($final_Menu[1]){
				$langstr .="<div class='col c1' m_nu='1'>";
				if(count($childMenu[1])<5){
					$langstr .="<a href='#' menu_order_id='1' m_id='999999999'>+</a>";
				}
				foreach($childMenu[1] as $v){
					$langstr .="<a class='alpha'  menu_order_id='1' m_id={$v[id]} href='#'>{$v[menu_name]}</a>";
				}
				$langstr.="<a href='#' m_id={$final_Menu[1][id]}  menu_order_id='1' class='alpha'>{$final_Menu[1]['menu_name']}</a>";
				$langstr.='</div>';
			}else{
				$langstr="<div class='col c1'  m_nu='1'><a menu_order_id='1' m_id='0' href='#'>+</a></div>";
			}
			//第二分组
			if($final_Menu[2]){
				$langstr .="<div class='col c2' m_nu='2'>";
				if(count($childMenu[2])<5){
					$langstr .="<a href='#' menu_order_id='2'  m_id='999999999'>+</a>";
				}
				foreach($childMenu[2] as $v){
					$langstr .="<a class='alpha'  menu_order_id='2' m_id={$v[id]} href='#'>{$v[menu_name]}</a>";
				}
				$langstr.="<a href='#' class='alpha'  menu_order_id='2' m_id={$final_Menu[2][id]} >{$final_Menu[2]['menu_name']}</a>";
				$langstr.='</div>';
			}else{
				$langstr.="<div class='col c2' m_nu='2'><a  menu_order_id='2' m_id='0' href='#'>+</a></div>";
			}
		
			//第三分组
			if($final_Menu[3]){
				$langstr .="<div class='col c3' m_nu='3'>";
				if(count($childMenu[3])<5){
					$langstr .="<a href='#' menu_order_id='3'  m_id='999999999'>+</a>";
				}
				foreach($childMenu[3] as $v){
					$langstr .="<a class='alpha'  menu_order_id='3' m_id={$v[id]} href='#'>{$v[menu_name]}</a>";
				}
				$langstr.="<a href='#' class='alpha'  menu_order_id='3' m_id={$final_Menu[3][id]}>{$final_Menu[3]['menu_name']}</a>";
				$langstr.='</div>';
			}else{
				$langstr.="<div class='col c3' m_nu='3'><a  menu_order_id='3' m_id='0' href='#'>+</a></div>";
			}
			
			
			
			
		
			echo $langstr;
		}else{

			$langstr="<div class='col c1' m_nu='1'><a m_id='0' menu_order_id='1' href='#'>+</a></div><div class='col c2' m_nu='2'><a   menu_order_id='2'  m_id='0'  href='#'>+</a></div><div class='col c3' m_nu='3'><a m_id='0' menu_order_id='3' href='#'>+</a></div>";
			
			echo $langstr;
		}
}