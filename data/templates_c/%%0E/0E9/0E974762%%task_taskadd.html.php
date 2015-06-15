<?php /* Smarty version 2.6.19, created on 2015-06-12 22:23:27
         compiled from D:%5Cwamp%5Cwww%5C/home/task/templates/task_taskadd.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'D:\\wamp\\www\\/home/task/templates/task_taskadd.html', 137, false),)), $this); ?>
<link rel="stylesheet" type="text/css" href="../public/css/sub-manage.css" media="all" />
<!-- 日期插件开始 -->
<link rel="stylesheet" type="text/css" href="../public/css/calendar.css" />
<script type='text/javascript' src='../public/js/calendar.js'></script>
<!-- 日期插件结束 -->
<style type="text/css">
	*{font-family:"Microsoft YaHei","黑体","宋体";}
	li{cursor:pointer;}
	.cc_title_h4{border:1px solid #C9E4C3;height:35px;background-color:#E8FFE2;padding-left:25px;border-radius:4px;}
	.fg-upload span{color:#777;height:34px;line-height:34px}
	#ssqDiv select{width:auto;margin-right:10px;}
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
									<input type="hidden" name='a' value='taskadd'/>
									<input type="hidden" name="num[id]" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
" />
									<h4 class='cc_title_h4'>平台发布兼职</h4>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>职位标题：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="str[title]" value="<?php echo $this->_tpl_vars['vo']['title']; ?>
" type="text">
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>薪资：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="num[money]" value="<?php echo $this->_tpl_vars['vo']['money']; ?>
" type="text"><b style="height:34px;line-height:34px">&nbsp;元</b>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>结算方式：</h4>
								</div>
								<div class="shop_sort_set">
									<select name="num[pay_type]" class="shop_sort_text">
										<option value="1" <?php if ($this->_tpl_vars['vo']['pay_type'] == 1): ?>selected<?php endif; ?> >现金日结</option>
										<option value="2" <?php if ($this->_tpl_vars['vo']['pay_type'] == 2): ?>selected<?php endif; ?> >转账日结</option>
									</select>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>性别要求：</h4>
								</div>
								<div class="shop_sort_set">
									<select name="num[sex]" class="shop_sort_text">
										<option value="3" <?php if ($this->_tpl_vars['vo']['sex'] == 3): ?>selected<?php endif; ?> >男女不限</option>
										<option value="1" <?php if ($this->_tpl_vars['vo']['sex'] == 1): ?>selected<?php endif; ?> >男</option>
										<option value="2" <?php if ($this->_tpl_vars['vo']['sex'] == 2): ?>selected<?php endif; ?> >女</option>
									</select>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>报名截止日期</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" id="start_time" onclick="return showCalendar('start_time','y-mm-dd');" name="str[start_time]" value="<?php echo $this->_tpl_vars['vo']['start_time']; ?>
" type="text">&nbsp;&nbsp;
									<select class="shop_sort_text" style="width:100px;" name="str[start_hour]">
										<?php $_from = $this->_tpl_vars['hourArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
										<option value="<?php echo $this->_tpl_vars['v']; ?>
" <?php if (( ! $this->_tpl_vars['vo']['start_hour'] && $this->_tpl_vars['v'] == '12:00' ) || $this->_tpl_vars['v'] == $this->_tpl_vars['vo']['start_hour']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['v']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									</select>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>工作时间：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" id="work_date" onclick="return showCalendar('work_date','y-mm-dd');" name="work_date" value="<?php echo $this->_tpl_vars['vo']['work_date']; ?>
" type="text">
									
									<select class="shop_sort_text" style="width:100px;" name="work_hour">
									<?php $_from = $this->_tpl_vars['hourArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
									<option value="<?php echo $this->_tpl_vars['v']; ?>
" <?php if ($this->_tpl_vars['v'] == $this->_tpl_vars['vo']['work_hour']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['v']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
									<span class="shop_sort_text" style="width:10px;border:0;">-</span>
									<select class="shop_sort_text" style="width:100px;" name="work_hour_end">
									<?php $_from = $this->_tpl_vars['hourArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
									<option value="<?php echo $this->_tpl_vars['v']; ?>
" <?php if (( ! $this->_tpl_vars['vo']['work_hour_end'] && $this->_tpl_vars['v'] == '12:00' ) || $this->_tpl_vars['v'] == $this->_tpl_vars['vo']['work_hour_end']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['v']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>省市区：</h4>
								</div>
								<div class="shop_sort_set" id="ssqDiv">									
									<select name="num[pid]" class="shop_sort_text provinceid" onchange="pselect(this)">
										<option value="">请选择省</option>
										<?php $_from = $this->_tpl_vars['parr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pv']):
?>
										<option value="<?php echo $this->_tpl_vars['pv']['ProvinceID']; ?>
" <?php if ($this->_tpl_vars['pv']['ProvinceID'] == $this->_tpl_vars['vo']['pid']): ?>selected<?php endif; ?> ><?php echo $this->_tpl_vars['pv']['ProvinceName']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									</select>
									<select name="num[cid]" class="shop_sort_text cityid" onchange="cselect(this)">
									</select>
									<select name="num[did]" class="shop_sort_text areaid">
									</select>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>工作地点：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="str[address]" value="<?php echo $this->_tpl_vars['vo']['address']; ?>
" type="text">
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>坐标位置：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="maplocation" value="<?php if ($this->_tpl_vars['vo']['longitude']): ?><?php echo $this->_tpl_vars['vo']['longitude']; ?>
,<?php echo $this->_tpl_vars['vo']['latitude']; ?>
<?php endif; ?>" type="text">&nbsp;&nbsp;
									<b style="height:34px;line-height:34px">
									<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">查询坐标，请点这里</a></b>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>申请职位费</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="str[sq_fee]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['vo']['sq_fee'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" type="number" />
									<b style="height:34px;line-height:34px">&nbsp;元（默认0代表无需在线支付）</b>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>招聘人数：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="num[num]" value="<?php echo $this->_tpl_vars['vo']['num']; ?>
" type="number">
									<b style="height:34px;line-height:34px">&nbsp;人</b>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>集合地点：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="str[jihe_address]" value="<?php echo $this->_tpl_vars['vo']['jihe_address']; ?>
" type="text">
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>集合时间：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" id="jihe_date" onclick="return showCalendar('jihe_date','y-mm-dd');" name="jihe_date" value="<?php echo $this->_tpl_vars['vo']['jihe_date']; ?>
" type="text">
									<input class="shop_sort_text" name="jihe_hour" value="<?php echo $this->_tpl_vars['vo']['jihe_hour']; ?>
" type="text">
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>企业名称：</h4>
								</div>
								<div class="shop_sort_set">
									<input class="shop_sort_text" name="str[company_name]" value="<?php echo $this->_tpl_vars['vo']['company_name']; ?>
" type="text">
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>职位要求：</h4>
								</div>
								<div class="shop_sort_set">
									<textarea name="str[yaoqiu]" class="shop_sort_text" style="width:400px;height:150px;"><?php echo $this->_tpl_vars['vo']['yaoqiu']; ?>
</textarea>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>工作内容：</h4>
								</div>
								<div class="shop_sort_set">
									<textarea name="str[introduce]" class="shop_sort_text" style="width:400px;height:150px;"><?php echo $this->_tpl_vars['vo']['introduce']; ?>
</textarea>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>注意事项：</h4>
								</div>
								<div class="shop_sort_set">
									<textarea name="str[attention]" class="shop_sort_text" style="width:400px;height:150px;"><?php echo $this->_tpl_vars['vo']['attention']; ?>
</textarea>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>是否紧急：</h4>
								</div>
								<div class="shop_sort_set" style="margin:10px;color:#4E5359;">
									<input type="radio" name="info[is_recommend]" id="optionsRadios2" value="0" <?php if ($this->_tpl_vars['vo']['is_recommend'] == 0): ?>checked<?php endif; ?> > 
									<label for="optionsRadios2">否&nbsp;&nbsp;</label>
									<input type="radio" name="info[is_recommend]" id="optionsRadios1" value="1" <?php if ($this->_tpl_vars['vo']['is_recommend'] == 1): ?>checked<?php endif; ?> > <label for="optionsRadios1">是</label>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>审核通过：</h4>
								</div>
								<div class="shop_sort_set" style="margin:10px;color:#4E5359;">
									<input type="radio" name="info[sh_status]" id="sh_status2" value="0" <?php if ($this->_tpl_vars['vo']['sh_status'] == 0): ?>checked<?php endif; ?> > 
									<label for="sh_status2">否&nbsp;&nbsp;</label>
									<input type="radio" name="info[sh_status]" id="sh_status1" value="1" <?php if ($this->_tpl_vars['vo']['sh_status'] == 1): ?>checked<?php endif; ?> > <label for="sh_status1">是</label>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4>是否关闭：</h4>
								</div>
								<div class="shop_sort_set" style="margin:10px;color:#4E5359;">
									<input type="radio" name="info[is_shut]" id="is_shut2" value="0" <?php if ($this->_tpl_vars['vo']['is_shut'] == 0): ?>checked<?php endif; ?> > 
									<label for="is_shut2">否&nbsp;&nbsp;</label>
									<input type="radio" name="info[is_shut]" id="is_shut1" value="1" <?php if ($this->_tpl_vars['vo']['is_shut'] == 1): ?>checked<?php endif; ?> > <label for="is_shut1">是</label>
								</div>
							</div>
							<div class="shop_sort">
								<div class="shop_sort_title">
									<h4></h4>
								</div>
								<div class="shop_sort_set">
									<button onclick="save(event,this);return false" type='button' class="btngreen30" id="adddo" subFromFlag="0">
										完成
									</button>&nbsp;&nbsp;&nbsp;
									<button onclick="javascript:window.history.go(-1);" class="btngray30" type='button' id="adddo2">返回</button>
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
set_title_name('职位管理');
set_menu_cur('menu_41', 'cur omega');
function save(event){
	if($("input[name='maplocation']").val()==''){
		alert('请填写坐标位置');return;
	}
	$.ajax({
		url:"task.php",
		data:$('#memberform').serializeArray(),
		type:"post",
		dataType:'json',
		async :false,
		success:function(res) {
			if(res){
				note_info('保存成功','success',event);
				setTimeout("window.location.href='task.php'",300);
			}else{
				note_info('保存失败','warn',event);
			}
		}
	});
}
<!-- 城市级联开始 -->
function pselect(obj){
	$(".cityid option").remove();
	$(".areaid option").remove();
	$.ajax({
		url:"task.php?a=get_citys",
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
		url:"task.php?a=get_districts",
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
if('<?php echo $this->_tpl_vars['vo']['id']; ?>
'){
	var pid='<?php echo $this->_tpl_vars['vo']['pid']; ?>
';
	var cid='<?php echo $this->_tpl_vars['vo']['cid']; ?>
';
	var did='<?php echo $this->_tpl_vars['vo']['did']; ?>
';
	$.ajax({
		url:"task.php?a=get_citys",
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
		url:"task.php?a=get_districts",
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