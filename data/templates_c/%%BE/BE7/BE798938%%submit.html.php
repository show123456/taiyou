<?php /* Smarty version 2.6.19, created on 2015-06-15 13:47:16
         compiled from D:%5Cwamp%5Cwww%5C/home/job/templates/submit.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'D:\\wamp\\www\\/home/job/templates/submit.html', 58, false),)), $this); ?>
<style type="text/css">
.redactbtn{border-color:#bbb;}
.enable,.sub-set-title ul li a{font-size:14px;}
.serch_middle_div{height:35px;padding:5px;}
.page-list{margin-top:15px;float: right;margin-right:20px;}
.page-list a,.page-list span{margin-right:5px;}

.replyDiv{position:fixed;z-index:1999;width:1200px;padding:1px;top:0;display:none;}
.replyBox{border:1px solid #BCE1B4;width:250px;height:140px;margin:0 auto;background-color:#fff;border-radius:5px;}
.replyBox h3{background-color:#5CB85C;color:#fff;margin:0;font-size:16px;height:30px;line-height:30px;}
.replyBox div{margin:10px;}
.replyBox textarea{width:230px;height:60px;border-radius:5px;}
.replyBox input[type='button']{padding:3px 10px;font-family:"Microsoft YaHei","黑体","宋体";}
.replyBox b{display:block;float:right;cursor:pointer;}
</style>
<div class="main-content"><div class='inner'><div class="manage-apply">
<div class="apply-content"><div class="manage-menu">
<div class='serch_middle_div'>
	<span style='float:left;line-height:35px;font-weight:bold;'>数据：<?php echo $this->_tpl_vars['countnum']; ?>
条</span>
	<div class="pull-right" style="margin-right:8px">
		<form method="get" action="index.php">
			<input type="hidden" name="a" value="submit" />
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>
" />
			<input name="phone" class="keywords" placeholder="输入司机电话" type="text" value="<?php echo $_GET['phone']; ?>
">&nbsp;&nbsp;
			<input name="keywords" class="keywords" placeholder="输入司机姓名" type="text" value="<?php echo $_GET['keywords']; ?>
">&nbsp;&nbsp;
			<input value="搜索" id="search" class="" title="搜索" type="submit" />
			<a style="background-color:#eaeaea;color:#333;border:1px solid #999;padding:2px 5px;" href="exceldemo.php?id=<?php echo $_GET['id']; ?>
" target='_blank'>导出到EXCEL</a>
		</form>
	</div>
</div>
<div class="list-table">
	<div class="wrapper" style="width:100%;">
		<table style="table-layout:auto;" class="stable">
			<thead>
				<tr>
					<th name="Theme" class="hover enable">提交人姓名/性别/电话</th>
					<th name="Theme" class="hover enable">司机姓名</th>
					<th name="Theme" class="hover enable">手机号码</th>
					<th name="Theme" class="hover enable">车牌号</th>
					<th name="Theme" class="hover enable">驾照类型</th>
					<th name="Theme" class="hover enable">驾驶证</th>
					<th name="Theme" class="hover enable">车辆照片</th>
					<th name="Theme" class="hover enable">提交时间</th>
					<!-- <th name="Theme" class="hover enable">操作</th> -->
				</tr>
			</thead>
			<tbody class='postTbody'>
				<!------------列表开始------------>
				<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				<tr>
					<td><div><?php echo $this->_tpl_vars['v']['uname']; ?>
 / <?php echo $this->_tpl_vars['v']['usex']; ?>
 / <?php echo $this->_tpl_vars['v']['uphone']; ?>
 / <a href="javascript:void(0);" onclick="sendMsg(this,<?php echo $this->_tpl_vars['v']['uid']; ?>
)" data-name="<?php echo $this->_tpl_vars['v']['uname']; ?>
">发消息</a></div></td>
					<td><div><?php echo $this->_tpl_vars['v']['name']; ?>
</div></td>
					<td><div><?php echo $this->_tpl_vars['v']['phone']; ?>
</div></td>
					<td><div><?php echo $this->_tpl_vars['v']['carnum']; ?>
</div></td>
					<td><div><?php echo $this->_tpl_vars['v']['license']; ?>
</div></td>
					<td><div><?php if ($this->_tpl_vars['v']['is_jsz'] == 1): ?><span style="color:#62ab62">有</span><?php else: ?>无<?php endif; ?></div></td>
					<td><div><?php if ($this->_tpl_vars['v']['is_clz'] == 1): ?><span style="color:#62ab62">有</span><?php else: ?>无<?php endif; ?></div></td>
					<td><div><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['addtime'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 10, '') : smarty_modifier_truncate($_tmp, 10, '')); ?>
</div></td>
					<!-- <td>
						<div class="btn-operate">
						<a href="javascript:void(0);" onclick="delRow(event,'<?php echo $this->_tpl_vars['v']['id']; ?>
')" class="redactbtn">删除</a>
						</div>
					</td> -->
				</tr>
				<?php endforeach; endif; unset($_from); ?>
				<!------------列表结束------------>
			</tbody>
		</table>
		<?php if ($this->_tpl_vars['page']): ?><div class="page-list" style="margin-top:15px;float: right;margin-right:20px;"><?php echo $this->_tpl_vars['page']; ?>
</div><?php endif; ?>
	</div>
</div>
</div></div></div></div></div>
<!-- 回复框 -->
<div class="replyDiv">
	<div class='replyBox'>
		<h3>
			&nbsp;回复<span>陈冰</span>
			<b onclick="$('.replyDiv').hide();">×&nbsp;</b>
		</h3>
		<div>
			<input type="hidden" name="talk_id" id="talk_id" value="" />
			<textarea name="" id="replyContent"></textarea><br />
			<input type="button" class="btn btn-large btn-success" onclick="submitMsg()" value="发送" />
		</div>
	</div>
</div>
<script type="text/javascript">
set_title_name('任务提报');
set_menu_cur('menu_11_1', 'cur omega');
function delRow(event,id){
	if(confirm("确定要删除此信息吗?")){
		$.ajax({
			url:"<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/job/index.php",
			data:{id:id,a:'delsign'},
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
//对单个用户发消息
function sendMsg(obj,id){
	var manname=$(obj).attr('data-name');
	$('.replyBox span').text(manname);
	$('#talk_id').val(id);
	$('.replyDiv').slideDown();
}
function submitMsg(){
	var replyContent=$('#replyContent').val();
	if(replyContent==''){
		alert('消息不能为空');return;
	}
	var talk_id=$('#talk_id').val();
	$.post('index.php',{a:'send_msg',id:talk_id,content:replyContent},function(){
		alert('发送成功');
		$('.replyDiv').slideUp();
		$('#replyContent').val('');
	});
}
</script>