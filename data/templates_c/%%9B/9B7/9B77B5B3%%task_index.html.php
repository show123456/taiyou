<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:57
         compiled from D:%5Cwamp%5Cwww%5C/home/task/templates/task_index.html */ ?>
<style type="text/css">
	*{font-family:"Microsoft YaHei","黑体","宋体";}
	li{cursor:pointer;}
	.redactbtn{border-color:#bbb;}
	.enable,.sub-set-title ul li a{font-size:14px;}
	.serch_middle_div{height:35px;padding:5px;}
	.page-list{margin-top:15px;float: right;margin-right:20px;}
	.page-list a,.page-list span{margin-right:5px;}
	
	.sendBtn{padding:1px 2px;border:1px solid #4DA300;cursor:pointer;width:85px;
	background:none repeat scroll 0% 0% #53B000;border-radius:3px;color:#FFF;margin:0 auto}
	
	.kfTypeDiv span{cursor:pointer;}
	.kfSpan{color:#ccc;}
	.isKf{color:#428bca;}
</style>
<div class="main-content">
	<div class='inner'>
		<div class="manage-apply">
			<div class="apply-content">
				<div class="manage-menu">
					<div class='serch_middle_div'>
						<div>
							<button style="float:right;" onclick="javascript:location.href='task.php?a=taskadd'">发布兼职</button>
						</div>
						<div class="pull-right" style="margin-right:8px">
							<form method="get" action="">
								<input name="keywords" class="keywords" placeholder="输入标题" type="text" value="<?php echo $_GET['keywords']; ?>
">&nbsp;&nbsp;
								<input value="搜索" id="search" title="搜索" type="submit">
							</form>
						</div>
					</div>
					<div class="list-table">
						<div class="wrapper" style="width:100%;">
							<table style="table-layout:auto;" class="stable">
								<thead>
									<tr>
										<th class="enable">标题</th>
										<th class="enable">报名数</th>
										<th class="enable">浏览量</th>
										<th class="enable">是否关闭</th>
										<th class="enable">添加时间</th>
										<th class="enable">向兼职用户</th>
										<th class="enable">向报名用户</th>
										<th name="Operate" class="enable">操作</th>
									</tr>
								</thead>
								<tbody class='postTbody'>
									<!------------列表开始------------>
									<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
									<tr data-bc='#fff'>
										<td><div><?php if ($this->_tpl_vars['v']['uid'] == 1): ?>(平台)<?php else: ?><span style='color:red'>(企业)</span><?php endif; ?><?php echo $this->_tpl_vars['v']['title']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['countnum']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['see_num']; ?>
</div></td>
										<td><div class="kfTypeDiv">
											<span class="kfSpan <?php if ($this->_tpl_vars['v']['is_shut'] == 1): ?>isKf<?php endif; ?>" data-status='1' data-id='<?php echo $this->_tpl_vars['v']['id']; ?>
'>关闭</span> | 
											<span class="kfSpan <?php if ($this->_tpl_vars['v']['is_shut'] == 0): ?>isKf<?php endif; ?>" data-status='0' data-id='<?php echo $this->_tpl_vars['v']['id']; ?>
'>开放</span>
										</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['addtime']; ?>
</div></td>
										<td><div class='sendBtn' <?php if ($this->_tpl_vars['v']['sh_status'] == 0): ?>onclick="note_info('该职位还未通过审核','warn',event);"<?php endif; ?> >
										<a <?php if ($this->_tpl_vars['v']['sh_status'] == 1): ?>href="send_wx_msg.php?tid=<?php echo $this->_tpl_vars['v']['id']; ?>
" target="_blank"<?php endif; ?> style="color:#fff;">发送提醒消息</a>
										</div></td>
										<td style='padding:10px 0;'>
											<div class='sendBtn' style='width:60px;'><a style='color:#fff;' target='_blank' href="send_phone_msg.php?tid=<?php echo $this->_tpl_vars['v']['id']; ?>
">发送短信</a></div>
										</td>
										<td>
											<div class="btn-operate">
											<a href="bbs_manage.php?id=<?php echo $this->_tpl_vars['v']['id']; ?>
">查看评论</a> | 
											<a target='_blank' href="index.php?m=task_company&a=sign_mid&tid=<?php echo $this->_tpl_vars['v']['id']; ?>
">报名管理</a> | 
											<a href="task.php?a=sign&tid=<?php echo $this->_tpl_vars['v']['id']; ?>
">兼职人员</a> | 
											<a href="task.php?a=<?php if ($this->_tpl_vars['v']['uid'] == 1): ?>taskadd<?php else: ?>add<?php endif; ?>&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">修改</a> | 
											<a href="#" onclick="delRow(event,'<?php echo $this->_tpl_vars['v']['id']; ?>
')">删除</a>
											</div>
										</td>
									</tr>
									<?php endforeach; endif; unset($_from); ?>
									<!------------列表结束------------>
								</tbody>
							</table>
							<?php if ($this->_tpl_vars['page']): ?><div class="page-list" style="margin-top:15px;float: right;margin-right:20px;"><?php echo $this->_tpl_vars['page']; ?>
</div><?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
set_title_name('职位管理');
set_menu_cur('menu_41', 'cur omega');
function delRow(event,id){
	if(confirm("确定要删除此信息吗?")){
		$.ajax({
			url:"task.php",
			data:{id:id,a:'del'},
			type:"post",
			dataType:'json',
			async:false,
			success:function(res) {
				if(res){
					note_info('删除成功','success',event);
					setTimeout("window.location.reload()",300);
				}else{
					note_info('删除失败','warn',event);
				}
			}
		});
	}
}
function sendMsg(id,status,event){
	if(status=='0'){
		note_info('该职位还未通过审核','warn',event);return;
	}
	note_info('消息提醒发送中，请勿关闭页面','success',event);
	$.get('task.php?a=send_msg&tid='+id);
}

$(document).ready(function(){
	$('.kfTypeDiv span').click(function(){
		if(confirm('确定要执行此操作吗？')){
			var kf_type=$(this).attr('data-status');
			var open_act_id=$(this).attr('data-id');
			$.get('task.php',{a:'kf',id:open_act_id,type:kf_type},function(res){
				if(res){
					window.location.reload();
				}
			})
		}
	})
	
	$('.postTbody tr').click(
		function(){
			var backCol=$(this).attr('data-bc');
			if(backCol=='#fff'){
				$(this).attr('data-bc','#ddd');
				$(this).css({backgroundColor:'#ddd'});
			}else{
				$(this).attr('data-bc','#fff');
				$(this).css({backgroundColor:'#fff'});
			}
		}
	)
})
</script>