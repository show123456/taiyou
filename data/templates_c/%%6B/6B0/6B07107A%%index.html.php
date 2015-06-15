<?php /* Smarty version 2.6.19, created on 2015-06-15 13:47:12
         compiled from D:%5Cwamp%5Cwww%5C/home/job/templates/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'D:\\wamp\\www\\/home/job/templates/index.html', 39, false),)), $this); ?>
<style type="text/css">
.redactbtn{border-color:#bbb;}
.enable,.sub-set-title ul li a{font-size:14px;}
.serch_middle_div{height:35px;padding:5px;}
.page-list{margin-top:15px;float: right;margin-right:20px;}
.page-list a,.page-list span{margin-right:5px;}
</style>
<div class="main-content"><div class='inner'><div class="manage-apply">
<div class="apply-content"><div class="manage-menu">
	<div class='serch_middle_div'>
		<div>
			<button style="float:right;" onclick="javascript:location.href='<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/job/index.php?a=add'">发布任务</button>
		</div>
		<div class="pull-right" style="margin-right:8px">
			<form method="get">
				<input name="keywords" class="keywords" placeholder="输入标题" type="text" value="<?php echo $_GET['keywords']; ?>
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
						<th name="Theme" class="hover enable">标题</th>
						<th name="Theme" class="hover enable">金额</th>
						<th name="Theme" class="hover enable">领取列表</th>
						<th name="Theme" class="hover enable">取消列表</th>
						<th name="Theme" class="hover enable">提报列表</th>
						<th name="Theme" class="hover enable">添加时间</th>
						<th name="Operate" class="hover enable">操作</th>
					</tr>
				</thead>
				<tbody class='postTbody'>
					<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
					<tr>
						<td><div><?php echo $this->_tpl_vars['v']['title']; ?>
</div></td>
						<td><div><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['fee'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
(元)</div></td>
						<td><div><a href="index.php?a=sign&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">查看</a></div></td>
						<td><div><a href="index.php?a=sign_cancel&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">查看</a></div></td>
						<td><div><a href="index.php?a=submit&id=<?php echo $this->_tpl_vars['v']['id']; ?>
">查看</a></div></td>
						<td><div><?php echo $this->_tpl_vars['v']['addtime']; ?>
</div></td>
						<td>
							<div class="btn-operate">
							<a href="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/job/index.php?a=add&id=<?php echo $this->_tpl_vars['v']['id']; ?>
" class="redactbtn">修改</a> | 
							<a href="#" onclick="delRow(event,'<?php echo $this->_tpl_vars['v']['id']; ?>
')" class="redactbtn">删除</a>
							</div>
						</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
			<?php if ($this->_tpl_vars['page']): ?><div class="page-list"><?php echo $this->_tpl_vars['page']; ?>
</div><?php endif; ?>
		</div>
	</div>
</div></div></div></div></div>
<script type="text/javascript">
set_menu_cur('menu_11_1', 'cur');
function delRow(event,id){
	if(confirm("确定要删除此信息吗?")){
		$.ajax({
			url:"<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/job/index.php",
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