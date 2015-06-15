<?php /* Smarty version 2.6.19, created on 2015-06-15 13:48:24
         compiled from D:%5Cwamp%5Cwww%5C/home/industry/templates/index.html */ ?>
<style type="text/css">
	li{cursor:pointer;}
	.redactbtn{border-color:#bbb;}
	.enable,.sub-set-title ul li a{font-size:14px;}
	.serch_middle_div{height:35px;padding:5px;}
	.page-list{margin-top:15px;float: right;margin-right:20px;}
	.page-list a,.page-list span{margin-right:5px;}
</style>
<div class="main-content">
	<div class='inner'>
		<div class="manage-apply">
			<div class="apply-content">
				<div class="manage-menu">
					<div class='serch_middle_div'>
						<div>
							<button style="float:right;" onclick="javascript:location.href='index.php?a=add'">添加行业</button>
						</div>
						<div class="pull-right" style="margin-right:8px">
							<form method="get" action="">
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
										<th name="Theme" class="hover enable">名称</th>
										<th name="Operate" class="hover enable">操作</th>
									</tr>
								</thead>
								<tbody class='postTbody'>
									<!------------列表开始------------>
									<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
									<tr>
										<td><div><?php echo $this->_tpl_vars['v']['name']; ?>
</div></td>
										<td>
											<div class="btn-operate">
											<a href="index.php?a=add&id=<?php echo $this->_tpl_vars['v']['id']; ?>
" class="redactbtn">修改</a> | 
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
set_title_name('行业管理 ');
set_menu_cur('menu_81', 'cur omega');
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