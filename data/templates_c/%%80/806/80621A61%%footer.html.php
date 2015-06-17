<?php /* Smarty version 2.6.19, created on 2015-06-17 13:57:23
         compiled from footer.html */ ?>
<style type="text/css">
.bottom_bar { position: fixed; z-index: 99999; bottom: 0; left: 0; right: 0;}
.bottom_menu { width: 100%;  height: 25px; margin:0; padding:0; background-color:#333;}
.bottom_menu .select{color:#40C7F0;}
.bottom_menu div{width:32%;float:left;text-align:center;color:#fff;font-size: 12px;height: 25px;line-height: 25px;}
</style>
<div style="height:45px;"></div>
<div class="bottom_bar">
    <div class="bottom_menu" style="height:18px;">
		<div onclick="window.location.href='index.php?m=pic&a=index'" <?php if ($_GET['m'] == 'pic'): ?>class="select"<?php endif; ?> style="height:18px;">
			<i class="fa fa-picture-o"></i>
		</div>
      <div onclick="window.location.href='index.php?m=task&a=index'" style="height:18px;border-left:1px solid #4b4b4b;border-right:1px solid #4b4b4b;" <?php if ($_GET['m'] == 'task'): ?>class="select"<?php endif; ?> >
	  <i class="fa fa-list-ul"></i>
	  </div>
      <div onclick="window.location.href='index.php?m=user&a=user_index'" style="height:18px;" <?php if ($_GET['m'] == 'user' || $_GET['m'] == 'task_company' || $_GET['m'] == 'sign_history'): ?>class="select"<?php endif; ?> ><i class="fa fa-user"></i></div>
    </div>
	  <div style="clear:both;"></div>
    <div class="bottom_menu">
      <div onclick="window.location.href='index.php?m=pic&a=index'" <?php if ($_GET['m'] == 'pic'): ?>class="select"<?php endif; ?> >图吧</div>
      <div onclick="window.location.href='index.php?m=task&a=index'" style="border-left:1px solid #4b4b4b;border-right:1px solid #4b4b4b;" <?php if ($_GET['m'] == 'task'): ?>class="select"<?php endif; ?> >职位列表</div>
      <div onclick="window.location.href='index.php?m=user&a=user_index'" <?php if ($_GET['m'] == 'user' || $_GET['m'] == 'task_company' || $_GET['m'] == 'sign_history'): ?>class="select"<?php endif; ?> >个人中心</div>
    </div>
</div>
<div id="loading" style="position:fixed;z-index:29999;display:none;width:100%;height:100px;top:0;background:#fff url(images/loading.gif) no-repeat center center;background-size:40px 40px;opacity:0.8;"></div>
<div id="alert_div" style="position:fixed;z-index:299;display:none;width:100%;height:20px;top:0;font-size:16px;font-weight:bold;letter-spacing:2px;color:#fff;background-color:#01B4EC;text-align:center;padding-top:8px;padding-bottom:8px;"></div>
<script type="text/javascript">
$(function(){
	var bodyHeight = document.documentElement.clientHeight;
	$('#loading').css({height:bodyHeight+'px'});
})
function alert_tip(msg){
	$('#alert_div').text(msg);
	$('#alert_div').fadeIn();
	$('#alert_div').fadeOut(3000);
}
</script>