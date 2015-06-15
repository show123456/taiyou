<?php /* Smarty version 2.6.19, created on 2015-06-12 22:25:51
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/sign_history.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>历史报名</title>
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
<style type="text/css">
.no_sign{width:100%; background-color:#fffbe7;}
.no_sign .other_status{color:#ff6f28;}
.no_sign .special{background-color:#ff9c00;border:1px solid #dc8700;border-radius:3px;color:#fff;font-size:12px;font-weight:normal;padding:0 3px; margin-left:5px;}
.city{list-style-type:none;display:block; margin:0; padding:0; height:40px;background-color:#fff;width:100%;color:#666;border-top:1px solid #DDDDDD;}
.city li{list-style-type:none;display:block;width:32%;border-right:1px solid #DDDDDD; height:40px;float:left;line-height:40px;text-align:center;padding-right:1%;}
.city li:last-child{border-right:none;}
.city select{border:none;box-shadow:none;margin-top:0;height:33px;border-radius:0;width:100%;font-size:14px; background:url(images/down.gif) no-repeat right center;}
.city option{font-size:14px;}

.history .item_list ul{margin:0;}
.history .item_list .job_adress{padding-top:5px;}
.history .item_list ul li{height:35px;line-height:35px;}
.register_end{height:35px;line-height:35px;font-size:16px;width:96%;}
</style>
<body>
<div class="main">
	<div class="history" style="border-bottom:none;">
		<div>
		<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<div class="item_list" onclick="window.location.href='index.php?m=task&a=detail&id=<?php echo $this->_tpl_vars['v']['id']; ?>
'">
				<div class="list_title">
					<div class="job_title"><?php echo $this->_tpl_vars['v']['title']; ?>
<!-- <span class="special">特别推荐</span> --></div>
					<div class="price"><?php echo $this->_tpl_vars['v']['money']; ?>
元 </div>
					<div class="clear"></div>
				</div>
				<div class="job_adress"><?php echo $this->_tpl_vars['v']['addr']; ?>

					<?php if ($this->_tpl_vars['v']['is_valid'] == 0): ?>
					<div style="float:right;">审核中</div>
					<?php elseif ($this->_tpl_vars['v']['is_valid'] == 1): ?>
					<div style="float:right;color:#ff6f28;">已通过</div>
					<?php else: ?>
					<div style="float:right;">未通过</div>
					<?php endif; ?>
				</div>
				<ul>
					<li><?php echo $this->_tpl_vars['v']['nickname']; ?>
</li>
					<li class="other_status" style="color:#999;"><?php echo $this->_tpl_vars['v']['start_time']; ?>
</li>
				</ul>
				<div class="clear"></div>
			</div>
		<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>
</div>
<div class="register_end" data-page="1">查看更多</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function(){
	//分页
	$('.register_end').click(function(){
		var pageNum=parseInt($(this).attr('data-page'))+1;
		$(this).attr('data-page',pageNum);
		$.get('index.php?m=sign_history&a=index&p='+pageNum+'&did=<?php echo $_GET['did']; ?>
',function(res){
			if(res){
				if(res=='err'){
					$('.register_end').text('没有更多了');
				}else{
					var goodsStr='';
					$.each(res,function(i,item){
						goodsStr+='<div class="item_list"><div class="list_title" onclick=\'window.location.href="index.php?m=task&a=detail&id='+item.id+'"\'>';
						goodsStr+='<div class="job_title">'+item.title+'</div>';
						goodsStr+='<div class="price">'+item.money+'元 </div>';
						goodsStr+='<div class="clear"></div></div>';
						goodsStr+='<div class="job_adress">'+item.addr;
						if(item.is_valid==0){
							goodsStr+='<div style="float:right;">审核中</div>';
						}else if(item.is_valid==1){
							goodsStr+='<div style="float:right;color:#ff6f28;">已通过</div>';
						}else{
							goodsStr+='<div style="float:right;">未通过</div>';
						}
						goodsStr+='<ul><li>'+item.nickname+'</li><li class="other_status" style="color:#999;">'+item.start_time+'</li></ul>';
						goodsStr+='<div class="clear"></div></div>';
					})
					$('.history').append(goodsStr);
				}
			}
		},'json');
	})
})
</script>
</body>
</html>