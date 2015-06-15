<?php /* Smarty version 2.6.19, created on 2015-06-14 08:20:50
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/user_add_personal.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'D:\\wamp\\www\\mobile/templates/user_add_personal.html', 25, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>个人资料</title>
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
<body style="background-color:#F6F6F6;">
<div class="main">	
    <div class="personal_top" >
		<div class="personal_img">
		<img src="<?php if ($this->_tpl_vars['headPic']): ?><?php echo $this->_tpl_vars['headPic']; ?>
<?php else: ?>images/people.png<?php endif; ?>" style="border:none;" />
		</div>
		<p><?php if ($this->_tpl_vars['userRow']['type'] == 3): ?>客服-<?php endif; ?><?php echo ((is_array($_tmp=@$this->_tpl_vars['vo']['nickname'])) ? $this->_run_mod_handler('default', true, $_tmp, '匿名') : smarty_modifier_default($_tmp, '匿名')); ?>
</p>
	</div>
	<form action="" id="meminfo">
	<input type="hidden" name="num[id]" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
"/>
	<input type="hidden" id="picInput" name="str[pic]" value="<?php echo $this->_tpl_vars['vo']['pic']; ?>
" />
	<div class="tabs_info">
		<!-- <?php if ($this->_tpl_vars['userRow']['is_v'] == 1): ?><img class="vip" src="images/vip.gif"/><?php endif; ?> -->
		<div class="input_group">
			<input style="text-indent:4em;" type="text" name="str[nickname]" value="<?php echo $this->_tpl_vars['vo']['nickname']; ?>
" placeholder="请输入真实姓名，保证费用顺利结算" />
			<div class="title">姓名：</div>
		</div>
		<div class="input_group">
			<input style="text-indent:4em;" type="text" name="str[nicheng]" value="<?php echo $this->_tpl_vars['vo']['nicheng']; ?>
"/>
			<div class="title">昵称：</div>
		</div>
		<div class="input_group" style="margin-top:10px;">
			<input type="radio" name="num[sex]" value="1" <?php if ($this->_tpl_vars['vo']['sex'] == 1 || $this->_tpl_vars['vo']['sex'] == ''): ?>checked<?php endif; ?> ><label>男</label>
			<input type="radio" name="num[sex]" value="2" <?php if ($this->_tpl_vars['vo']['sex'] == 2): ?>checked<?php endif; ?> ><label>女</label>
		</div>
		<div style="margin-left:5px;margin-top:10px;margin-bottom:-10px;color:#ff7101;">现居住地：</div>
		<div>
			<select style="float:left;margin-right:1.5%;" name="num[pid]" class="provinceid">
				<option value="10">江苏省</option>
				<!-- <option value="">请选择省</option>
				<?php $_from = $this->_tpl_vars['parr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pv']):
?>
				<option value="<?php echo $this->_tpl_vars['pv']['ProvinceID']; ?>
" <?php if ($this->_tpl_vars['pv']['ProvinceID'] == $this->_tpl_vars['vo']['pid']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['pv']['ProvinceName']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?> -->
			</select>
			<select style="float:left;margin-right:1.5%;" name="num[cid]" class="cityid">
			<option value="78">苏州市</option>
			</select>
			<select style="width:33%;float:right;" name="num[did]" class="areaid">
				<option value="">请选择区</option>
				<?php $_from = $this->_tpl_vars['darr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dv']):
?>
				<option value="<?php echo $this->_tpl_vars['dv']['DistrictID']; ?>
" <?php if ($this->_tpl_vars['dv']['DistrictID'] == $this->_tpl_vars['vo']['did']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['dv']['DistrictName']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			<div class="clear"></div>		
		</div>
		<div class="input_group">
			<input type="text" name="str[address]" value="<?php echo $this->_tpl_vars['vo']['address']; ?>
" placeholder="请填写详细地址" />
		</div>
		<?php if ($this->_tpl_vars['userRow']['type'] == 1): ?>
		<div class="input_group">
			<input style="text-indent:7em;" type="text" name="str[cardnum]" value="<?php echo $this->_tpl_vars['vo']['cardnum']; ?>
" />
			<div class="title">身份证号码：</div>
		</div>
		<div class="input_group" id="cardPic">
			<img class="upload_pic" src="<?php if ($this->_tpl_vars['vo']['pic']): ?>/data/image_c/<?php echo $this->_tpl_vars['vo']['pic']; ?>
<?php else: ?>images/upload.gif<?php endif; ?>" />
		</div>
		<?php if ($this->_tpl_vars['vo']['recommend_phone'] == ''): ?>
		<!-- <div class="input_group">
			<input style="text-indent:9em;" type="text" name="recommend_phone" value=""/>
			<div class="title">推荐人手机账号：</div>
		</div> -->
		<?php endif; ?>
		<?php endif; ?>
	</div>
	</form>
	<a href="javascript:void(0);"><div class="register_end" style="width:96%;" onclick="save()">确定</div></a>	
</div><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- 上传照片 -->
<div style="visibility:hidden;">
	<iframe name='img-frame' id="img-frame" style="visibility:hidden;height:10px"></iframe>
	<form id="img-form" action="upload_img.php" encType="multipart/form-data" method="post" target="img-frame">
		<!-- <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
" /> -->
		<input type="file" name="imgfile" value="" id="img-file" onchange="changeValImg()"/>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#cardPic img").click(function(){
		$('#img-file').click();
	});
	$('#img-frame').load(function(){
        try{var html = $(this).contents().find('body').html();
    	}catch(e){ var html = window.frames["upload_frame"].document.body.innerText;}
    	try{
			var result = $.parseJSON(html);
			if(result.success==1){
				setTimeout(function(){
					$('#loading').hide();
				},1500)
				$(".upload_pic").attr('src',"/data/image_c/"+result.data.path);
				$("#picInput").val(result.data.path);
			}else{
				setTimeout(function(){
					$('#loading').hide();
				},1500)
				alert("照片太大或其他错误");
			}
    	}catch(e){}
    });
})
function changeValImg(){
	$('#loading').show();
	$('#img-form').submit();
}
function save(){
	if($('input[name="str[nickname]"]').val()==''){
		alert("请填写姓名");return;
	}
	if($('input[name="str[nicheng]"]').val()==''){
		alert("请填写昵称");return;
	}
	if($('.provinceid').val()=='' || $('.cityid').val()=='' || $('.areaid').val()==''){
		alert("请选择省市区");return;
	}
	if(isNumber($('input[name="str[address]"]').val())){
		alert("请填写正确的详细地址");return;
	}
	var card_len=parseInt($('input[name="str[cardnum]"]').val().length);
	if(card_len==15 || card_len==18){}else{
		alert("请填写正确的身份证号");return;
	}
	/*if($('input[name="str[pic]"]').val()==''){
		alert("请上传身份证正面照片");return;
	}*/
	$.post('index.php?m=user&a=user_add_personal',$('#meminfo').serialize(),function(res){
		if(jQuery.trim(res)){
			alert('保存成功');
			window.location.href='index.php?m=user&a=user_index';
		}else{
			alert('保存失败');
		}
	});
}
//验证手机号
function isMobil(s){  
	var patrn=/^1[0-9]{10}$/;  
	if (!patrn.exec(s)) return false
	return true
}
//验证数字
function isNumber(s){  
	var patrn=/^[0-9]*$/;  
	if (!patrn.exec(s)) return false
	return true
}
<!-- 城市级联开始 -->
function pselect(obj){
	$(".cityid option").remove();
	$(".areaid option").remove();
	$.ajax({
		url:"index.php?m=user&a=get_citys",
		type:"get",
		data:{pid:$(obj).val()},
		dataType:"json",
		success:function(data){
			var str="";
			str+="<option value=''>请选择</option>";
			for(var i=0;i<data.length;i++){
				str+="<option value=\""+data[i].CityID+"\">"+data[i].CityName+"</option>";
			}
			$(".cityid").append(str);
		}
	});
}
function cselect(obj){
	$(".areaid option").remove();
	$.ajax({
		url:"index.php?m=user&a=get_districts",
		type:"get",
		data:{cid:$(obj).val()},
		dataType:"json",
		success:function(dataa){
			var stra="";
			stra+="<option value=''>请选择</option>";
			for(var i=0;i<dataa.length;i++){
				stra+="<option value=\""+dataa[i].DistrictID+"\">"+dataa[i].DistrictName+"</option>";
			}
			$(".areaid").append(stra);
		}
	});
}
			
//修改时加载城市和区域信息
if(0 && '<?php echo $this->_tpl_vars['vo']['id']; ?>
'){
	var pid='<?php echo $this->_tpl_vars['vo']['pid']; ?>
';
	var cid='<?php echo $this->_tpl_vars['vo']['cid']; ?>
';
	var did='<?php echo $this->_tpl_vars['vo']['did']; ?>
';
	$.ajax({
		url:"index.php?m=user&a=get_citys",
		type:"get",
		data:{pid:pid},
		dataType:"json",
		success:function(data){
			var str="";
			for(var i=0;i<data.length;i++){
				if(data[i].CityID==cid){
					str+="<option value=\""+data[i].CityID+"\" selected >"+data[i].CityName+"</option>";
				}else{
					str+="<option value=\""+data[i].CityID+"\">"+data[i].CityName+"</option>";
				}
			}
			$(".cityid").append(str);
		}
	});

	$.ajax({
		url:"index.php?m=user&a=get_districts",
		type:"get",
		data:{cid:cid},
		dataType:"json",
		success:function(dataa){
			var str="";
			for(var i=0;i<dataa.length;i++){
				if(dataa[i].DistrictID==did){
					str+="<option value=\""+dataa[i].DistrictID+"\" selected >"+dataa[i].DistrictName+"</option>";
				}else{
					str+="<option value=\""+dataa[i].DistrictID+"\">"+dataa[i].DistrictName+"</option>";
				}
			}
			$(".areaid").append(str);
		}
	})
}
<!-- 城市级联结束 -->
</script>
</body>
</html>