<?php /* Smarty version 2.6.19, created on 2015-06-12 22:25:37
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/task_detail.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'D:\\wamp\\www\\mobile/templates/task_detail.html', 36, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>职位详情</title>
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
.discuss .w_discuss{float:right;margin-right:4%;color:#666;font-weight:normal;}
.discuss .w_discuss span{color:#01b4ec;margin-right:3px;}
.discuss .discuss_btn{width:100px;height:35px;line-height:35px;color:#fff;border-radius: 5px;background-color: #01B4EC; text-align:center;float:right;margin-bottom:5px;margin-right:2%;}
.register_end{height:35px;line-height:35px;font-size:16px;width:90%;}

.add_edit_div{background-color:#01B4EC;width:100%;height:25px;line-height:25px;font-size:14px;color:#fff;}
.add_edit_div a{display:block;width:48%;text-align:center;color:#fff;height:25px;line-height:25px;}

#atName{font-size:14px;color:#888;padding-left:5px;display:none;}
</style>
<body style="background-color:#eeeeee;">
<div class="main">  
	<div class="job_detail">
		<div class="item_list">
			<div class="list_title">
				<div class="job_title"><?php echo $this->_tpl_vars['vo']['title']; ?>
</div>
				<div class="num">已有<span style="color:red;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['signNum'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</span>人报名</div>
				<div class="clear"></div>
			</div>
			<ul>
				<li><span class="fa fa-clock-o"></span> <?php echo $this->_tpl_vars['vo']['fb_str']; ?>
</li>
				<li>浏览量：<span style="color:#ff9c00;"><?php echo $this->_tpl_vars['vo']['see_num']; ?>
</span> 次</li>
				<?php if ($this->_tpl_vars['userRow']['id'] == $this->_tpl_vars['vo']['uid']): ?><li class="open" onclick="osClick(this)" data-status="<?php echo $this->_tpl_vars['vo']['is_shut']; ?>
">
					<img src="images/<?php if ($this->_tpl_vars['vo']['is_shut'] == 1): ?>shut<?php else: ?>open<?php endif; ?>.png">
				</li><?php endif; ?>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
	<?php if ($this->_tpl_vars['userRow']['id'] == 1): ?>
	<div class='add_edit_div'>
		<a href="index.php?m=task&a=add&id=<?php echo $this->_tpl_vars['vo']['id']; ?>
" style='float:left;'>修改职位</a> | 
		<a href="index.php?m=task&a=add&id=<?php echo $this->_tpl_vars['vo']['id']; ?>
&zaifa=1" style='float:right;'>再发一个</a>
	</div>
	<?php endif; ?>
	<div class="job_info">
		<div class="info_list">
			<p style="color:red;">工资待遇：</p><span><?php echo $this->_tpl_vars['vo']['money']; ?>
元</span>
			<div class="clear_3"></div>
		</div>
		<div class="info_list">
			<p style="color:red;">结算方式：</p>
			<span><?php if ($this->_tpl_vars['vo']['pay_type'] == 1): ?>现金日结<?php else: ?>转账日结<?php endif; ?></span>
			<div class="clear_3"></div>
		</div>
		<div class="info_list">
			<p style="color:red;">性别要求：</p>
			<span><?php if ($this->_tpl_vars['vo']['sex'] == 1): ?>男<?php elseif ($this->_tpl_vars['vo']['sex'] == 2): ?>女<?php else: ?>男女不限<?php endif; ?></span>
			<div class="clear_3"></div>
		</div>
		<div class="info_list">
			<p>工作时间：</p>
			<span><?php echo $this->_tpl_vars['vo']['work_time']; ?>
</span>
			<div class="clear_3"></div>			
		</div>
		<div class="info_list">
			<p>工作地点：</p>
			<span><?php echo $this->_tpl_vars['vo']['address']; ?>
</span>
			<div class="clear_3"></div>			
		</div>
		<div class="info_list">
			<p>招聘人数：</p>
			<span><?php echo $this->_tpl_vars['vo']['num']; ?>
人</span>
			<div class="clear_3"></div>			
		</div>
		<div class="info_list">
			<p style="color:red;">报名费用：</p>
			<span><?php echo $this->_tpl_vars['vo']['sq_fee']; ?>
元</span>
			<div class="clear_3"></div>			
		</div>
		<div class="info_list">
			<p>集合地点：</p>
			<span><?php echo $this->_tpl_vars['vo']['jihe_address']; ?>
</span>
			<div class="clear_3"></div>			
		</div>	
		<div class="info_list">
			<p>集合时间：</p>
			<span><?php echo $this->_tpl_vars['vo']['jihe_time']; ?>
</span>
			<div class="clear_3"></div>			
		</div>			
	</div>
	<div class="job_info">
		<div class="title">职位要求：</div>
		<div class="info_list" style="color:#666;text-indent:2em;">
			<?php echo $this->_tpl_vars['vo']['yaoqiu']; ?>

		</div>
	</div>
	<div class="job_info">
		<div class="title">工作内容：</div>
		<div class="info_list" style="color:#666;text-indent:2em;">
			<?php echo $this->_tpl_vars['vo']['introduce']; ?>

		</div>
	</div>
	<div class="job_info">
		<div class="title" style="color:red;">注意事项：</div>
		<div class="info_list" style="color:#666;text-indent:2em;">
			<?php echo $this->_tpl_vars['vo']['attention']; ?>

		</div>
	</div>
	<?php if ($this->_tpl_vars['vo']['company_name']): ?>
	<div class="job_info">
		<div class="title"><?php echo $this->_tpl_vars['vo']['company_name']; ?>
</div>
	</div>
	<?php else: ?>
	<div class="job_info">
		<div class="title"><?php echo $this->_tpl_vars['uRow']['nickname']; ?>
</div>
		<div class="info_list" style="text-indent:2em;color:#666;"><?php echo $this->_tpl_vars['uRow']['introduce']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['nosession']): ?>
		<div class="register_end" onclick="noSesCli()">申请职位</div>
	<?php elseif ($this->_tpl_vars['nowanshan'] && $this->_tpl_vars['userRow']['type'] == 1): ?>
		<div class="register_end" onclick="noWanCli()">申请职位</div>
	<?php elseif ($this->_tpl_vars['userRow']['type'] == 1): ?>
		<?php if ($this->_tpl_vars['vo']['sign_status'] == 1): ?>
		<div class="register_end" onclick="cancelSign('<?php echo $this->_tpl_vars['vo']['sign_row_valid']; ?>
')">取消报名</div>
		<?php elseif ($this->_tpl_vars['vo']['is_shut'] == 1): ?>
		<div class="register_end">职位已关闭</div>
		<?php elseif ($this->_tpl_vars['out_date'] == 1): ?>
		<div class="register_end">职位已过期</div>
		<?php elseif ($this->_tpl_vars['out_num'] == 1): ?>
		<div class="register_end">报名人数已满</div>
		<?php else: ?>
		<div class="register_end" id="doSign">申请职位</div>
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['userRow']['type'] == 2 && $this->_tpl_vars['userRow']['id'] == $this->_tpl_vars['vo']['uid']): ?>
		<div class="register_end" onclick="window.location.href='index.php?m=task_company&a=sign_mid&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
'">报名管理</div>
	<?php elseif ($this->_tpl_vars['userRow']['type'] == 3): ?>
		<div class="register_end" id="addSign">追加报名</div>
	<?php endif; ?>
	<div class="discuss">
		<div class="title">已报名人员<span style="color:#888;font-weight:normal;">（<?php echo ((is_array($_tmp=@$this->_tpl_vars['signNum'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
人）</span></div>
		<div class="discuss_list">
		<?php $_from = $this->_tpl_vars['signList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<div class="user_icon" style="width:40px;height:60px;border:0;">
				<img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['head_pic'])) ? $this->_run_mod_handler('default', true, $_tmp, 'images/men_icon.gif') : smarty_modifier_default($_tmp, 'images/men_icon.gif')); ?>
" style="width:40px;height:40px;"/>
				<span style="font-size:12px;px;display:inline-block;width:100%;text-align:center;"><?php echo $this->_tpl_vars['v']['nicheng']; ?>
</span>
			</div>
		<?php endforeach; endif; unset($_from); ?>
		<?php if (! $this->_tpl_vars['nosession']): ?>
			<div class="user_icon" onclick='morePic(this)' data-page='1' style="width:40px;height:60px;line-height:60px;border:0;font-size:14px;color:#01B4EC;">
				更多>
			</div>
		<?php endif; ?>
		<div style="clear:both;"></div>
		</div>
	</div>
	<div style="clear:both;"></div>
	<div class="discuss">
		<div class="title">评论
			<div class="w_discuss" id="replyOs"><span class="fa fa-pencil-square" ></span><b style="font-weight:normal;">我要评论</b></div>
		</div>
		<div class="discuss_list" id="reply_box" style="display:none;">
			<span id='atName' data-maninfo=''>@匿名</span>
			<textarea style="width:96%;margin:10px 2%;" placeholder="评论内容" id="reply_content"></textarea>	
			<div class="discuss_btn" onclick="doReply()">评论</div>
			<div class="clear"></div>
		</div>
		<?php if ($this->_tpl_vars['replyList']): ?>
		<?php $_from = $this->_tpl_vars['replyList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<div class="discuss_list" id="discuss_list<?php echo $this->_tpl_vars['v']['id']; ?>
">
			<div class="user_icon"><img src="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['head_pic'])) ? $this->_run_mod_handler('default', true, $_tmp, 'images/men_icon.gif') : smarty_modifier_default($_tmp, 'images/men_icon.gif')); ?>
"/></div>
			<div class="discuss_info">
				<div class="user_title">
					<p><?php echo $this->_tpl_vars['v']['name']; ?>
<?php if ($this->_tpl_vars['v']['tomanname']): ?> @ <?php echo $this->_tpl_vars['v']['tomanname']; ?>
<?php endif; ?></p>
					<?php if ($this->_tpl_vars['userRow']['id'] == 1): ?>
						<span style="color:#01B4EC;" data-name="<?php echo $this->_tpl_vars['v']['name']; ?>
" onclick="replyMsg(this,'<?php echo $this->_tpl_vars['v']['uid']; ?>
-<?php echo $this->_tpl_vars['v']['name']; ?>
')">回复</span>
						<span style="color:#01B4EC;margin-right:20px;" onclick="delMsg(this,'<?php echo $this->_tpl_vars['v']['id']; ?>
')">删除</span>
					<?php endif; ?>
					<!-- <span><?php echo $this->_tpl_vars['v']['addtime']; ?>
</span> -->
				<div class="clear"></div></div>
				<div class="info_detail"><?php echo $this->_tpl_vars['v']['content']; ?>
</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach; endif; unset($_from); ?>
		<div class="register_end" id="doReplyPage" data-page="1" style="height:35px;line-height:35px;font-size:16px;width:90%;margin:8px auto;">更多评论</div>
		<?php endif; ?>
		<div style="height:5px;"></div>
	</div>
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
$(document).ready(function(){
	$("#doSign").click(function(){
		//有申请费
		var sq_fee='<?php echo $this->_tpl_vars['vo']['sq_fee']; ?>
';
		if(parseInt(sq_fee)>0){
			if(confirm('此职位需先支付申请费'+sq_fee+'元，确定要申请吗？')){
			}else{
				return;
			}
		}
		//转账日结要有银行卡号
		if('<?php echo $this->_tpl_vars['vo']['pay_type']; ?>
'=='2' && '<?php echo $this->_tpl_vars['userRow']['bank_card']; ?>
'==''){
			if(confirm('此兼职为转账日结方式，须绑定银行卡方可报名')){
				window.location.href="index.php?m=user&a=jk";
			}
			return;
		}
		//性别要求不符
		if(('<?php echo $this->_tpl_vars['vo']['sex']; ?>
'=='1' && '<?php echo $this->_tpl_vars['userRow']['sex']; ?>
'=='2') || ('<?php echo $this->_tpl_vars['vo']['sex']; ?>
'=='2' && '<?php echo $this->_tpl_vars['userRow']['sex']; ?>
'=='1')){
			alert('性别不符');
			return;
		}
		//身份证照片未上传
		/*if('<?php echo $this->_tpl_vars['userRow']['pic']; ?>
'==''){
			if(confirm('身份证照片审核未通过，去上传')){
				window.location.href="index.php?m=user&a=user_add_personal";
			}
			return;
		}*/
		
		$.get('index.php?m=task&a=sign&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
',function(res){
			res=jQuery.trim(res);
			if(res=='rsym'){
				alert('报名人数已满');
			}else if(res=='ybm'){
				alert('您已申请过该职位');
			}else if(res=='chongfu'){
				alert('对不起，该工作日你报名了其它职位！');
			}else if(res=='yebz'){
				if(confirm('对不起，您的余额不足！')){
					window.location.href="index.php?m=user&a=jk";return;
				}else{
					return;
				}
			}else if(res=='suc'){
				alert('报名成功，等待管理员进一步确认！');
				window.location.href="index.php?m=sign_history&a=index";
			}else if(res=='err'){
				alert('申请失败！');
			}else if(res){
				//window.location.href="./goodspay/js_api_call.php?oid="+res;
			}
		})
	})
	//评论分页
	$('#doReplyPage').click(function(){
		var pageNum=parseInt($(this).attr('data-page'))+1;
		$(this).attr('data-page',pageNum);
		$.get('index.php?m=task&a=ajax_reply&p='+pageNum+'&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
',function(res){
			if(res){
				if(res=='err'){
					$('#doReplyPage').text('没有更多了:)');
				}else{
					var goodsStr='';
					$.each(res,function(i,item){
						goodsStr+='<div class="discuss_list" id="discuss_list'+item.id+'">';
						goodsStr+='<div class="user_icon"><img src="'+item.head_pic+'"/></div><div class="discuss_info"><div class="user_title">';
						goodsStr+='<p>'+item.name;
						if(item.tomanname){
							goodsStr+=' @ '+item.tomanname;
						}
						goodsStr+='</p>';
						if('<?php echo $this->_tpl_vars['userRow']['id']; ?>
'=='1'){
							goodsStr+='<span style="color:#01B4EC;" data-name="'+item.name+'" onclick=\'replyMsg(this,"';
							goodsStr+=item.uid+'-'+item.name;
							goodsStr+='")\'>回复</span><span style="color:#01B4EC;margin-right:20px;" onclick="delMsg(this,'+item.id+')">删除</span>';
						}
						goodsStr+='<div class="clear"></div></div><div class="info_detail">'+item.content+'</div></div><div class="clear"></div></div>';
					})
					$('#doReplyPage').before(goodsStr);
				}
			}
		},'json');
	});
	//开放关闭评论
	$("#replyOs").toggle(
		function(){
			$("#replyOs b").text('关闭');
			$("#reply_box").show();
		},
		function(){
			$("#replyOs b").text('我要评论');
			$("#reply_box").hide();
		}
	);
	//追加报名
	var bh = $(".main").height()*1.2; 
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
		$.get('index.php?m=task&a=sign_zj&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
&phone='+add_phone,function(res){
			res=jQuery.trim(res);
			if(res=='no'){
				alert('该账号不存在或信息不完善');
			}else if(res=='ybm'){
				alert('您已申请过该职位');
			}else if(res=='suc'){
				alert('追加成功');
			}else if(res=='err'){
				alert('申请失败！');
			}
		})
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
	$(".cancel").click(function(){
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
})
//发表评论
function doReply(){
	var reply_content=$('#reply_content').val();
	var reply_at=$('#atName').attr('data-maninfo');
	if(reply_content==''){
		alert('评论内容不能为空');return;
	}
	$.get('index.php?m=task&a=reply&tid='+'<?php echo $this->_tpl_vars['vo']['id']; ?>
'+'&content='+reply_content+'&at='+reply_at,function(res){
		res=jQuery.trim(res);
		if(res=='suc'){
			alert('评论成功！');
			$('#reply_content').val('');
			window.location.reload();
		}else if(res=='err'){
			alert('评论失败！');
		}
	})
}
//验证手机号
function isMobil(s){  
	var patrn=/^1[0-9]{10}$/;  
	if (!patrn.exec(s)) return false
	return true
}
//开放关闭职位
function osClick(obj){
	var is_shut=$(obj).attr('data-status');
	if(is_shut==1){
		$.get('index.php?m=task&a=open_shut&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
&is_shut=0',function(res){
			$(obj).attr('data-status','0');
			$(obj).children('img').attr('src','images/open.png');
		})
	}else{
		$.get('index.php?m=task&a=open_shut&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
&is_shut=1',function(res){
			$(obj).attr('data-status','1');
			$(obj).children('img').attr('src','images/shut.png');
		})
	}
}
//不登录申请职位
function noSesCli(){
	if(confirm("您还未登录，是否去登录？")){
		window.location.href="login.php";
	}
}
//未完善资料申请职位
function noWanCli(){
	if(confirm("您还未完善资料，去完善？")){
		window.location.href="index.php?m=user&a=user_add_personal";
	}
}
//取消报名
function cancelSign(sign_valid){
	if(sign_valid=='0'){
		$.get('index.php?m=task&a=cancel_sign&tid=<?php echo $this->_tpl_vars['vo']['id']; ?>
',function(res){
			res=jQuery.trim(res);
			if(res=='no'){
				alert('您已通过管理员审核确认，不能取消报名');
			}else{
				alert('取消成功');
				window.location.reload();
			}
		})
	}else{
		alert('您已通过管理员审核确认，不能取消报名');
	}
}
//查看更多报名头像
function morePic(obj){
	var pageNum=parseInt($(obj).attr('data-page'))+1;
	$(obj).attr('data-page',pageNum);
	$.get('index.php?m=task&a=detail_ajax&id=<?php echo $_GET['id']; ?>
&p='+pageNum,function(res){
		if(res){
			if(res=='err'){
				$(obj).text('');
			}else{
				var goodsStr='';
				$.each(res,function(i,item){
					goodsStr+='<div class="user_icon" style="width:40px;height:60px;border:0;"><img src="'+item.head_pic+'" style="width:40px;height:40px;"/><span style="font-size:12px;px;display:inline-block;width:100%;text-align:center;">'+item.nicheng+'</span></div>';
				})
				$(obj).before(goodsStr);
			}
		}
	},'json');
}
//管理员点击回复
function replyMsg(obj,maninfo){
	var manname=$(obj).attr('data-name');
	$('#atName').text('@'+manname).show();
	$('#atName').attr('data-maninfo',maninfo);
	$("#replyOs b").text('关闭');
	$("#reply_box").show();
}
//管理员删除回复
function delMsg(obj,id){
	if(confirm('确定要删除吗？')){
		$.get('index.php?m=task&a=del_reply&id='+id,function(){
			$('#discuss_list'+id).remove();
		})
	}
}
</script>
</body>
</html>