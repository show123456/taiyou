<?php /* Smarty version 2.6.19, created on 2015-06-15 09:35:00
         compiled from D:%5Cwamp%5Cwww%5C/home/user/templates/detail.html */ ?>
<link rel="stylesheet" type="text/css" href="../public/css/sub-manage.css" media="all" />
<style type="text/css">
	*{font-family:"Microsoft YaHei","黑体","宋体";}
	li{cursor:pointer;}
	.cc_title_h4{border:1px solid #C9E4C3;height:35px;background-color:#E8FFE2;padding-left:25px;border-radius:4px;}
	.fg-upload span,b{color:#777;height:34px;line-height:34px}
	b{color:#777;height:40px;line-height:40px}
</style>
<div class="main-content" style="min-height:1000px;height:auto;">
	<div class='inner'>
		<div class="manage-apply">
			<div class="apply-content">
				<div class="manage-menu">
					<div class="msg_editer">
						<div class="sub-list">
							<div class="shop_sort_box">
								<form id="memberform">
									<input type="hidden" name='a' value='addv'/>
									<input type="hidden" name="num[id]" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
" />
									<h4 class='cc_title_h4'>会员详情</h4>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>手机账号：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['username']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>真是姓名</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['nickname']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>昵称：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['nicheng']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>籍贯：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['province']; ?>
<?php echo $this->_tpl_vars['vo']['city']; ?>
<?php echo $this->_tpl_vars['vo']['district']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>详细住址：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['address']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>身份证号：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['cardnum']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>身份证照：</h4>
										</div>
										<div>
										<?php if ($this->_tpl_vars['vo']['pic']): ?>
										<img src="../../data/image_c/<?php echo $this->_tpl_vars['vo']['pic']; ?>
" style="max-width:530px;" />
										<span class="btngreen30" onclick='delPic(this)'>删除</span>
										<?php endif; ?>
										</div>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>累计积分：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['score_all']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>银行名称：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['bank_name']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>银行账号：</h4>
										</div>
										<b><?php echo $this->_tpl_vars['vo']['bank_card']; ?>
</b>
									</div>
									<div class="shop_sort">
										<div class="shop_sort_title">
											<h4>是否加V：</h4>
										</div>
										<div class="shop_sort_set" style="margin:10px;color:#4E5359;">
											<input type="radio" name="info[is_v]" id="optionsRadios2" value="0" <?php if ($this->_tpl_vars['vo']['is_v'] == 0): ?>checked<?php endif; ?> > 
											<label for="optionsRadios2">否&nbsp;&nbsp;</label>
											<input type="radio" name="info[is_v]" id="optionsRadios1" value="1" <?php if ($this->_tpl_vars['vo']['is_v'] == 1): ?>checked<?php endif; ?> > <label for="optionsRadios1">是</label>
										</div>
									</div>
									<div class="shop_sort" style="margin-top:10px;">
										<div class="shop_sort_title">
											<h4></h4>
										</div>
										<div class="shop_sort_set">
											<button onclick="save(event);return false" type='button' class="btngreen30" id="adddo">
												保存
											</button>&nbsp;&nbsp;
											<button onclick="window.history.go(-1)" class="btngray30" type='button' id="adddo2">返回</button>
										</div>
										<div class="clr"></div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
set_title_name('用户管理');
<?php if ($this->_tpl_vars['vo']['type'] == 1): ?>
set_menu_cur('menu_31', 'cur');
<?php else: ?>
set_menu_cur('menu_32', 'cur omega');
<?php endif; ?>
function save(event){
	$.ajax({
		url:"index.php",
		data:$('#memberform').serializeArray(),
		type:"post",
		dataType:'json',
		async :false,
		success:function(res) {
			if(res){
				note_info('保存成功','success',event);
				setTimeout("window.location.href='index.php?type=1'",300);
			}else{
				note_info('保存失败','warn',event);
			}
		}
	});
}
//删除身份证照
function delPic(obj){
	if(confirm('确定删除，并同时向该用户发提醒消息吗？')){
		$.get('index.php?a=del_pic&id=<?php echo $this->_tpl_vars['vo']['id']; ?>
',function(res){
			res=jQuery.trim(res);
			if(res=='suc'){
				$(obj).prev().remove();
				$(obj).remove();
			}else if(res=='err'){
				note_info('删除失败','warn',event);
			}
		})
	}
}
</script>