<?php /* Smarty version 2.6.19, created on 2015-06-17 14:18:33
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/user_index_company.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'D:\\wamp\\www\\mobile/templates/user_index_company.html', 25, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>企业个人中心</title>
<meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
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
</head>
<body>
<div class="main">	
    <div class="personal_top" id="cardPic">
		<div class="personal_img">
		<img src="<?php if ($this->_tpl_vars['headPic']): ?><?php echo $this->_tpl_vars['headPic']; ?>
<?php else: ?>images/people.png<?php endif; ?>" style="border:none;" />
		</div>
		<p><?php echo ((is_array($_tmp=@$this->_tpl_vars['userRow']['nickname'])) ? $this->_run_mod_handler('default', true, $_tmp, '匿名') : smarty_modifier_default($_tmp, '匿名')); ?>
</p>
	</div>
	<div class="personal_list">
		<ol>
			<a href="index.php?m=user&a=user_add_company">
			<li>
				<div class="list_info">企业资料</div>
				<div class="next"><span class="fa fa-chevron-right"></span></div>
			</li>
			</a>
			<a href="<?php if ($this->_tpl_vars['userRow']['pid']): ?>index.php?m=task&a=add<?php else: ?>javascript:if(confirm('请先完善资料'))window.location.href='index.php?m=user&a=user_add_company'<?php endif; ?>">
			<li>
				<div class="list_info">发布职位</div>
				<div class="next"><span class="fa fa-chevron-right"></span></div>
			</li>
			</a>
			<a href="index.php?m=task_company&a=subindex">
				<li>
					<div class="list_info">报名管理</div>
					<div class="next"><span class="fa fa-chevron-right"></span></div>
				</li>
			</a>
			<a href="index.php?m=task_company&a=index">
				<li>
					<div class="list_info">历史发布</div>
					<div class="next"><span class="fa fa-chevron-right"></span></div>
				</li>
			</a>
			<a href="editp.php">
			<li>
				<div class="list_info">修改密码</div>
				<div class="next"><span class="fa fa-chevron-right" ></span></div>
			</li>
			</a>
			<div class="register_end" onclick="logout()" style="height:35px;line-height:35px;font-size:16px;width:90%;background-color:#ff7101;">退出登录</div>
		</ol>
	</div>
	<div style='color:#888;text-align:center;font-size:12px;'>技术支持：微沟通</div>
</div><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- 上传照片 -->
<div style="visibility:hidden;">
	<iframe name='img-frame' id="img-frame" style="visibility:hidden;height:10px"></iframe>
	<form id="img-form" action="upload.php" encType="multipart/form-data" method="post" target="img-frame">
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['userRow']['id']; ?>
" />
		<input type="file" name="imgfile" value="" id="img-file" onchange="$('#img-form').submit();"/>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#cardPic img").click(function(){
		$('#img-file').click()
	});
	$('#img-frame').load(function(){
        try{var html = $(this).contents().find('body').html();
    	}catch(e){ var html = window.frames["upload_frame"].document.body.innerText;}
    	try{
    	    var result = $.parseJSON(html);
    	    if(result.success==1){
    	    	$("#cardPic img").attr('src',"/data/image_c/"+result.data.path);
    	    }else{
				alert("照片太大或其他错误");
			}
    	}catch(e){}
    });
})
function tixian(){
	if(confirm('确定要提现吗？')){
		$.post('index.php?m=user&a=tx',function(){
			window.location.href="index.php?m=user&a=user_index";
		})
	}
}
//退出
function logout(){
	if(confirm('确定要退出吗？')){
		$.get('index.php?m=user&a=logout',function(){
			window.location.href="login.php";
		})
	}
}
</script>
</body> 
</html>