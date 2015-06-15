<?php /* Smarty version 2.6.19, created on 2015-06-12 00:24:02
         compiled from D:%5Cwamp%5Cwww%5C/home/tx/templates/moneylog.html */ ?>
<style type="text/css">
	li{cursor:pointer;}
	.redactbtn{border-color:#bbb;}
	.enable,.sub-set-title ul li a{font-size:14px;}
	.serch_middle_div{height:35px;padding:5px;}
	.page-list{margin-top:15px;float: right;margin-right:20px;}
	.page-list a,.page-list span{margin-right:5px;}
	#out_btn{color:#428bca;font-weight:bold;text-decoration:none;}
</style>
<div class="main-content">
	<div class='inner'>
		<div class="manage-apply">
			<div class="apply-content">
				<div class="manage-menu">
					<!-- <div class='serch_middle_div'>
						<div class="pull-right" style="margin-right:8px">
							<form method="get" action="">
								<input name="keywords" class="keywords" placeholder="输入姓名" type="text" value="<?php echo $_GET['keywords']; ?>
">&nbsp;&nbsp;
								<input value="搜索" id="search" class="" title="搜索" type="submit">
							</form>
						</div>
					</div> -->
					<div class="list-table">
						<div class="wrapper" style="width:100%;">
							<table style="table-layout:auto;" class="stable">
								<thead>
									<tr>
										<th name="Theme" class="hover enable">姓名</th>
										<th name="Theme" class="hover enable">手机号</th>
										<th name="Theme" class="hover enable">类别</th>
										<th name="Theme" class="hover enable">用户金额变动(元)</th>
										<th name="Theme" class="hover enable">变动时间</th>
									</tr>
								</thead>
								<tbody class='postTbody'>
									<!------------列表开始------------>
									<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
									<tr>
										<td><div><?php echo $this->_tpl_vars['v']['nickname']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['username']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['type']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['money']; ?>
</div></td>
										<td><div><?php echo $this->_tpl_vars['v']['addtime']; ?>
</div></td>
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
set_title_name('提现管理');
set_menu_cur('menu_92', 'cur omega');
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
</script>