<?php /* Smarty version 2.6.19, created on 2015-06-12 00:25:02
         compiled from D:%5Cwamp%5Cwww%5C/home/user/templates/cz.html */ ?>
<link rel="stylesheet" type="text/css" href="../public/css/sub-manage.css" media="all" />
<style type="text/css">
.cc_title_h4{border:1px solid #C9E4C3;height:35px;background-color:#E8FFE2;padding-left:25px;border-radius:4px;}
.fg-upload span{color:#777;height:34px;line-height:34px}
.input_span_extra{height:35px;line-height:35px;}
.shop_sort .shop_sort_title{width:12%;}
</style>
<div class="main-content" style="min-height:1000px;height:auto;"><div class='inner'>
<div class="manage-apply"><div class="apply-content"><div class="manage-menu">
<div class="msg_editer"><div class="sub-list"><div class="shop_sort_box">
	<form id="memberform">
		<input type="hidden" name='a' value='cz'/>
		<input type="hidden" name="uid" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
" />
		<h4 class='cc_title_h4'>充值</h4>
		<div class="shop_sort">
			<div class="shop_sort_title">
				<h4>姓名：</h4>
			</div>
			<div class="shop_sort_set">
				<span class='input_span_extra'><?php echo $this->_tpl_vars['vo']['nickname']; ?>
</span>
			</div>
		</div>
		<div class="shop_sort">
			<div class="shop_sort_title">
				<h4>性别：</h4>
			</div>
			<div class="shop_sort_set">
				<span class='input_span_extra'><?php if ($this->_tpl_vars['vo']['sex'] == 1): ?>男<?php else: ?>女<?php endif; ?></span>
			</div>
		</div>
		<div class="shop_sort">
			<div class="shop_sort_title">
				<h4>电话：</h4>
			</div>
			<div class="shop_sort_set">
				<span class='input_span_extra'><?php echo $this->_tpl_vars['vo']['username']; ?>
</span>
			</div>
		</div>
		<div class="shop_sort">
			<div class="shop_sort_title">
				<h4>充值金额：</h4>
			</div>
			<div class="shop_sort_set">
				<input class="shop_sort_text" name="fee" value="" type="text"><span class='input_span_extra'>&nbsp;元</span>
			</div>
		</div>
		<div class="shop_sort">
			<div class="shop_sort_title">
				<h4>备注：</h4>
			</div>
			<div class="shop_sort_set">
				<input class="shop_sort_text" name="desc" value="" type="text">
			</div>
		</div>
		<div class="shop_sort" style="margin-top:10px;">
			<div class="shop_sort_title">
				<h4></h4>
			</div>
			<div class="shop_sort_set" style="margin-bottom:10px;">
				<button onclick="save(event);return false" type='button' class="btngreen30" id="adddo">
					完成
				</button>&nbsp;&nbsp;
				<button onclick="window.history.go(-1)" class="btngray30" type='button' id="adddo2">返回</button>
			</div>
			<div class="clr"></div>
		</div>
	</form>
</div></div></div></div></div></div></div></div>
<script type="text/javascript">
set_menu_cur('menu_3_1', 'cur');
function save(event){
	if(!isNumber($('input[name="fee"]').val())){
		note_info("充值金额必须为正整数",'warn',event);return;
	}
	$.ajax({
		url:"index.php",
		data:$('#memberform').serializeArray(),
		type:"post",
		dataType:'json',
		async :false,
		success:function(res) {
			if(res=='suc'){
				note_info('充值成功','success',event);
				setTimeout("window.history.go(-1)",300);
			}else{
				note_info('充值失败','warn',event);
			}
		}
	});
}
//验证为正整数
function isNumber(s){  
	var patrn=/^[1-9][0-9]*$/;  
	if (!patrn.exec(s)) return false
	return true
}
</script>