<?php /* Smarty version 2.6.19, created on 2015-06-12 00:14:39
         compiled from D:%5Cwamp%5Cwww%5C/home/public/templates/layout.html */ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/public/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/public/css/default.css" />
<style type="text/css">
	*,input,textarea,button{font-family:"Microsoft YaHei","微软雅黑",STXihei,"华文细黑",serif;}
</style>
<script type='text/javascript' src='<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/public/js/jquery-1.11.0.min.js'></script>
<script type='text/javascript' src='<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/public/js/common.js'></script>
<title>云凡客管理平台</title>
</head>
<body>
    <div class="main-container clearfix">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['ROOT_PATH'])."/home/public/templates/main_menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['ROOT_PATH'])."/home/".($this->_tpl_vars['_TPL_']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div class="clearfix"></div>
        <footer class="main-footer">
		Copyright &copy; 2013-2015 All Rights Reserved. <!-- 苏ICP备09098830号 Tel：0512-65965129 Fax:0512-65924988 Email:service@yunfanke.com -->
		</footer>
    </div>
	<script type='text/javascript'>
	(function(){
	    var menu = $('.main-menu'), content = $('.main-content');
	    menu.height() > content.height() ? (content.css({'min-height':menu.height() +'px'})) : (menu.css({'min-height':content.height() + 'px'}) )
	})();
	$(function(){
		$('.menu li').click(function(){
			$(this).parent().find('ul').css('display','none');
			$(this).find('ul').css('display','');
		});
	});
	</script>
</body>
</html>