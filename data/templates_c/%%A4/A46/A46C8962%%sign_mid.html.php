<?php /* Smarty version 2.6.19, created on 2015-06-14 08:02:19
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/sign_mid.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>历史发布</title>
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
<meta content="no-cache" http-equiv="pragma">
<meta content="0" http-equiv="expires">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<link rel="stylesheet" type="text/css" href="font/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/global.css"/>
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<script src="js/jquery.min.js"></script>
<style type="text/css">
.job_adress{margin-top:8px;margin-bottom:-3px;;}
.history .item_list ul{margin-top:0;}
</style>
</head>
<body style="background-color:#eeeeee;">
<div class="main">
	<div style="height:10px;"></div>
	<div class="register_end" onclick="window.location.href='index.php?m=task_company&a=sign_index&tid=<?php echo $this->_tpl_vars['tid']; ?>
'" style="height:35px;line-height:35px;font-size:16px;width:90%;">报名管理</div>
	<div style="height:10px;"></div>
	<div class="register_end" onclick="window.location.href='index.php?m=task_company&a=sign_qd&tid=<?php echo $this->_tpl_vars['tid']; ?>
'" style="height:35px;line-height:35px;font-size:16px;width:90%;">签到管理</div>
	<div style="height:10px;"></div>
	<div class="register_end" onclick="window.location.href='index.php?m=task_company&a=sign_js&tid=<?php echo $this->_tpl_vars['tid']; ?>
'" style="height:35px;line-height:35px;font-size:16px;width:90%;">结算管理</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--弹出层-->
<div id="fullbg" class="fullbg"></div>
<div class="box_pop">
	<input type="text" id="add_sign_phone" placeholder="请输入会员手机账号"/>
	<div class="db_btn">
		<div class="ensure">确定</div><div class="cancel">取消</div>
		<div class="clear"></div>
	</div>
</div>
<!--弹出层 end-->
<script type="text/javascript">
$(function(){
	//追加报名
	var bh = $("body").height()*1.2; 
	var bw = $("body").width(); 
	$("#addSign").click(function(){
		$(".fullbg").css({height:bh,width:bw,display:"block"});
		$(".fullbg").show();
		$(".box_pop").show();
	});
	$(".ensure").click(function(){
		var add_phone=$('#add_sign_phone').val();
		if(!isMobil(add_phone)){
			alert("请填写正确的手机账号");return;
		}
		$.get('index.php?m=task&a=sign_zj&tid=<?php echo $this->_tpl_vars['tid']; ?>
&phone='+add_phone,function(res){
			res=jQuery.trim(res);
			if(res=='no'){
				alert('该账号不存在或信息不完善');
			}else if(res=='ybm'){
				alert('已申请过该职位');
			}else if(res=='chongfu'){
				alert('该工作日已报名了其它职位！');
			}else if(res=='suc'){
				alert('追加成功');
			}else if(res=='err'){
				alert('追加失败！');
			}
			$('#add_sign_phone').val('');
		})
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
	$(".cancel").click(function(){
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
})
//验证手机号
function isMobil(s){  
	var patrn=/^1[0-9]{10}$/;  
	if (!patrn.exec(s)) return false
	return true
}
</script>
</body>
</html>