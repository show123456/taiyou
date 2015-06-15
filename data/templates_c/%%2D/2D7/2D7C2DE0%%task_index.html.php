<?php /* Smarty version 2.6.19, created on 2015-06-12 22:22:41
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/task_index.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>兼职列表</title>
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
.no_sign{width:100%; background-color:#fffbe7;}
.no_sign .other_status{color:#ff6f28;}
.no_sign .special{background-color:#ff9c00;border:1px solid #dc8700;border-radius:3px;color:#fff;font-size:12px;font-weight:normal;padding:0 3px; margin-left:5px;}
.city{list-style-type:none;display:block; margin:0; padding:0; height:40px;background-color:#fff;width:100%;color:#666;border-top:1px solid #DDDDDD;}
.city li{list-style-type:none;display:block;width:32%;border-right:1px solid #DDDDDD; height:40px;float:left;line-height:40px;text-align:center;padding-right:1%;}
.city li:last-child{border-right:none;}
.city select{border:none;box-shadow:none;margin-top:0;height:33px;border-radius:0;width:100%;font-size:14px; background:url(images/down.gif) no-repeat right center;}
.city option{font-size:14px;}

.history .item_list ul{margin:0;}
.history .item_list .job_adress{padding-top:5px;}
.history .item_list ul li{height:35px;line-height:35px;}
.register_end{height:35px;line-height:35px;font-size:16px;width:96%;}
</style>
<body>
<div class="main">
	<form action="index.php" method="get" id="addrForm">
	<input type="hidden" name="m" value="task" />
	<input type="hidden" name="a" value="index" />
	<ul class="city">
		<li>
			<select name="pid" class="provinceid" onchange="pselect(this)">
				<?php $_from = $this->_tpl_vars['parr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pv']):
?>
				<?php if (( ! $_GET['pid'] && $this->_tpl_vars['pv']['ProvinceID'] == 10 ) || $this->_tpl_vars['pv']['ProvinceID'] == $_GET['pid']): ?><option value="<?php echo $this->_tpl_vars['pv']['ProvinceID']; ?>
" selected ><?php echo $this->_tpl_vars['pv']['ProvinceName']; ?>
</option><?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</li>
		<li>
			<select name="cid" class="cityid" onchange="cselect(this)">
			<?php $_from = $this->_tpl_vars['carr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cv']):
?>
				<?php if (( ! $_GET['cid'] && $this->_tpl_vars['cv']['CityID'] == 78 ) || $this->_tpl_vars['cv']['CityID'] == $_GET['cid']): ?><option value="<?php echo $this->_tpl_vars['cv']['CityID']; ?>
" selected ><?php echo $this->_tpl_vars['cv']['CityName']; ?>
</option><?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</li>
		<li>
			<select name="did" class="areaid" onchange="$('#addrForm').submit()">
			<option value="">请选择区</option>
			<?php $_from = $this->_tpl_vars['darr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cv']):
?>
				<option value="<?php echo $this->_tpl_vars['cv']['DistrictID']; ?>
" <?php if ($this->_tpl_vars['cv']['DistrictID'] == $_GET['did']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['cv']['DistrictName']; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</li>		
	</ul>
	</form>
	<div class="blank_015"></div>
	<div style="margin-top:-10px;margin-bottom:10px;"><img src="./images/job_index.jpg" style="width:100%;" onclick="window.location.href='index.php?m=job&a=index'" /></div>
	<div class="history" style="border-bottom:none;">
		<div class="no_sign">
		<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
			<div class="item_list" onclick="window.location.href='index.php?m=task&a=detail&id=<?php echo $this->_tpl_vars['v']['id']; ?>
'" style="position:relative;">
				<div class="list_title">
					<div class="job_title"><?php echo $this->_tpl_vars['v']['title']; ?>
<?php if ($this->_tpl_vars['v']['is_recommend'] == 1): ?><span class="special">急招</span><?php endif; ?></div>
					<div class="price"><?php echo $this->_tpl_vars['v']['money']; ?>
元 </div>
					<div class="clear"></div>
				</div>
				<div class="job_adress"><?php echo $this->_tpl_vars['v']['addr']; ?>

					<?php if ($this->_tpl_vars['nosession']): ?>
					<?php elseif ($this->_tpl_vars['v']['sign_status'] == 1): ?>
					<div style="float:right;color:#ff6f28;">已报名</div>
					<?php else: ?>
					<div style="float:right;">未报名</div>
					<?php endif; ?>
				</div>
				<ul>
					<li><?php echo $this->_tpl_vars['v']['nickname']; ?>
</li>
					<li class="other_status" style="color:#999;"><?php echo $this->_tpl_vars['v']['fb_str']; ?>
</li>
					<li class="other_status" style="color:#ff9c00;">已报名<?php echo $this->_tpl_vars['v']['countnum_bm']; ?>
人&nbsp;&nbsp;&nbsp;</li>
				</ul>
				<div class="clear"></div>
				<div style="position:absolute;width:35%;height:50%;top:40%;right:20%;">
					<?php if ($this->_tpl_vars['v']['gq_status'] == 1): ?>
					<img src="images/shut_status.png" style="height:100%;" />
					<?php elseif ($this->_tpl_vars['v']['is_shut'] == 1): ?>
					<img src="images/shut_status.png" style="height:100%;" />
					<?php elseif ($this->_tpl_vars['v']['man_status'] == 1): ?>
					<img src="images/man_status.png" style="height:100%;" />
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>
</div>
<div class="register_end" data-page="1">查看更多</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
$(document).ready(function(){
	//分页
	$('.register_end').click(function(){
		var pageNum=parseInt($(this).attr('data-page'))+1;
		$(this).attr('data-page',pageNum);
		$.get('index.php?m=task&a=index&p='+pageNum+'&did=<?php echo $_GET['did']; ?>
',function(res){
			if(res){
				if(res=='err'){
					$('.register_end').text('没有更多了');
				}else{
					var goodsStr='';
					$.each(res,function(i,item){
						goodsStr+='<div style="position:relative;" class="item_list" onclick=\'window.location.href="index.php?m=task&a=detail&id='+item.id+'"\'><div class="list_title" >';
						goodsStr+='<div class="job_title">'+item.title+'</div>';
						goodsStr+='<div class="price">'+item.money+'元 </div>';
						goodsStr+='<div class="clear"></div></div>';
						goodsStr+='<div class="job_adress">'+item.addr;
						if(item.sign_status==1){
							goodsStr+='<div style="float:right;color:#ff6f28;">已报名</div></div>';
						}else{
							goodsStr+='<div style="float:right;">未报名</div></div>';
						}
						goodsStr+='<ul><li>'+item.nickname+'</li><li class="other_status" style="color:#999;">'+item.fb_str+'</li><li class="other_status" style="color:#ff9c00;">已报名'+item.countnum_bm+'人&nbsp;&nbsp;&nbsp;</li></ul>';
						goodsStr+='<div class="clear"></div>';
						goodsStr+='<div style="position:absolute;width:35%;height:50%;top:40%;right:20%;">';
						if(item.gq_status==1){
							goodsStr+='<img src="images/shut_status.png" style="height:100%;" />';
						}else if(item.is_shut==1){
							goodsStr+='<img src="images/shut_status.png" style="height:100%;" />';
						}else if(item.man_status==1){
							goodsStr+='<img src="images/man_status.png" style="height:100%;" />';
						}
						goodsStr+='</div></div>';
					})
					$('.no_sign').append(goodsStr);
				}
			}
		},'json');
	})
})
/* 城市级联开始 */
/*function pselect(obj){
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
if('<?php echo $_GET['did']; ?>
'){
	var pid='<?php echo $_GET['pid']; ?>
';
	var cid='<?php echo $_GET['cid']; ?>
';
	var did='<?php echo $_GET['did']; ?>
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
			var str="<option value='clear'>清空条件</option>";
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
}*/
/* 城市级联结束 */
</script>
</body>
</html>