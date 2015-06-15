<?php /* Smarty version 2.6.19, created on 2015-06-12 13:06:24
         compiled from D:%5Cwamp%5Cwww%5C/home/user/templates/index.html */ ?>
<style type="text/css">
	*{font-family:"Microsoft YaHei","黑体","宋体";}
	li{cursor:pointer;}
	.redactbtn{border-color:#bbb;}
	.enable,.sub-set-title ul li a{font-size:14px;}
	.serch_middle_div{height:35px;padding:5px;}
	.page-list{margin-top:15px;float: right;margin-right:20px;}
	.page-list a,.page-list span{margin-right:5px;}
	
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
						<?php if ($_GET['type'] == 1): ?>
						<span style='float:left;line-height:35px;font-weight:bold;'>个人用户人数：<?php echo $this->_tpl_vars['countnum']; ?>
</span>
						<?php else: ?>
						<span style='float:left;line-height:35px;font-weight:bold;'>企业用户人数：<?php echo $this->_tpl_vars['countnum']; ?>
</span>
						<?php endif; ?>
						<!-- <div>
							<button style="float:right;" onclick="javascript:location.href='/home/news/index.php?a=add'">发布资讯</button>
						</div> -->
						<div class="pull-right" style="margin-right:8px">
							<form method="get" action="">
								<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>
" />
								<input type="hidden" name="admin" value="<?php echo $_GET['admin']; ?>
" />
								<input name="nicheng" class="keywords" placeholder="输入昵称" type="text" value="<?php echo $_GET['nicheng']; ?>
">&nbsp;&nbsp;
								<input name="keywords" class="keywords" placeholder="输入名称" type="text" value="<?php echo $_GET['keywords']; ?>
">&nbsp;&nbsp;
								<input value="搜索" id="search" class="" title="搜索" type="submit">
							</form>
						</div>
					</div>
					<div class="list-table">
						<div class="wrapper" style="width:100%;">
							<table style="table-layout:auto;" class="stable">
								<thead>
									<tr>
										<?php if ($_GET['type'] == 1): ?>
										<th name="Theme" class="hover enable">头像</th>
										<th name="Theme" class="hover enable">手机账号</th>
										<th name="Theme" class="hover enable">真实姓名</th>
										<th name="Theme" class="hover enable">昵称</th>
										<th name="Theme" class="hover enable">性别</th>
										<th name="Theme" class="hover enable">设置为客服</th>
										<th name="Theme" class="hover enable">余额(元)</th>
										<?php if ($_GET['admin'] == 1): ?><th name="Theme" class="hover enable">充值</th><?php endif; ?>
										<?php else: ?>
										<th name="Theme" class="hover enable">手机账号</th>
										<th name="Theme" class="hover enable">公司名称</th>
										<th name="Theme" class="hover enable">联系人</th>
										<?php endif; ?>
										<th name="Operate" class="hover enable">操作</th>
									</tr>
								</thead>
								<tbody class='postTbody'>
									<!------------列表开始------------>
									<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
									<tr>
										<?php if ($_GET['type'] == 1): ?>
										<td><div>
											<?php if ($this->_tpl_vars['v']['headPic']): ?>
												<img src="<?php echo $this->_tpl_vars['v']['headPic']; ?>
" height="50" />
											<?php endif; ?>
										</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['username']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['nickname']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['nicheng']; ?>
</div></td>
										<td><div><?php if ($this->_tpl_vars['v']['sex'] == 1): ?>男<?php else: ?>女<?php endif; ?></div></td>
										<td><div class="kfTypeDiv">
											<span class="kfSpan <?php if ($this->_tpl_vars['v']['type'] == 3): ?>isKf<?php endif; ?>" data-status='3' data-id='<?php echo $this->_tpl_vars['v']['id']; ?>
'>是</span> | 
											<span class="kfSpan <?php if ($this->_tpl_vars['v']['type'] == 1): ?>isKf<?php endif; ?>" data-status='1' data-id='<?php echo $this->_tpl_vars['v']['id']; ?>
'>否</span>
										</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['money']; ?>
</div></td>
										<?php if ($_GET['admin'] == 1): ?><td><a href="index.php?a=cz&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">充值</a></td><?php endif; ?>
										<?php else: ?>
										<td><div><?php echo $this->_tpl_vars['v']['username']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['nickname']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['contact_name']; ?>
</div></td>
										<?php endif; ?>
										<td>
											<div class="btn-operate">
											<?php if ($_GET['type'] == 1): ?>
											<a href="index.php?a=detail&id=<?php echo $this->_tpl_vars['v']['id']; ?>
" class="redactbtn">详细信息</a> | 
											<?php else: ?>
											<a href="index.php?a=detail_company&id=<?php echo $this->_tpl_vars['v']['id']; ?>
" class="redactbtn">详细信息</a> | 
											<?php endif; ?>
											<a href="#" onclick="delRow(event,'<?php echo $this->_tpl_vars['v']['id']; ?>
')" class="redactbtn">删除</a>
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
set_title_name('用户管理');
<?php if ($_GET['type'] == 1): ?>
set_menu_cur('menu_31', 'cur');
<?php else: ?>
set_menu_cur('menu_32', 'cur omega');
<?php endif; ?>
function delRow(event,id){
	if(confirm("确定要删除此信息吗?")){
		$.ajax({
			url:"index.php",
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
$(document).ready(function(){
	$('.kfTypeDiv span').click(function(){
		if(confirm('确定要执行此操作吗？')){
			var kf_type=$(this).attr('data-status');
			var open_act_id=$(this).attr('data-id');
			$.get('index.php',{a:'kf',id:open_act_id,type:kf_type},function(res){
				if(res){
					window.location.reload();
				}
			})
		}
	})
})
</script>