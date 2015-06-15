<?php /* Smarty version 2.6.19, created on 2015-06-14 08:33:56
         compiled from D:%5Cwamp%5Cwww%5Cmobile/templates/sign_qd.html */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>签到管理</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="cleartype" content="on">
	<link rel="stylesheet" type="text/css" href="font/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/global.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css"/>
	<script src="js/jquery.min.js"></script>
    <style>
	.bm5041{width:100%;height: auto;overflow-x: hidden;}
	.bm5041_tit{font-size:1em;color:#990000;height:1.5em;line-height:1.5em;text-align: center;
	-webkit-margin-before:0.5em;-webkit-margin-after:0.5em;}
	.bm5041_con{width:98%;height: auto;overflow: hidden;margin:0 auto;text-align: center;}
	.bm5041_con_li{width: 100%;height: 1.8em;line-height: 1.8em;font-size: 0.9em;border-bottom: 1px solid #CDCDCD;margin-top:5px;margin-bottom:15px;padding-bottom:15px;height: 1.5em;
	line-height: 1.5em;font-size: 1em;text-align: center;color:#323232;}
	.bm5041_con_li div{float:left;}
	.bm5041_con_li_1{width:25%;margin-left:2%;}
	.bm5041_con_li_2{width:10%;}
	.bm5041_con_li_3{width:40%;}
	.bm5041_con_li_4{width:20%;}
	.bm5041_con_li_5{width:15%;}
	.bm5041_con_li_6{width:15%;margin-top:5px;height:1.5rem;line-height:1.5rem;}
	.refuceBtn{background-color:#aaa;}
	.bm5041_con_sub{border-radius: 5px;font-weight:bold;width:30%;  height: 2em;
	line-height: 2em;text-align: center;  border: 1px solid #009DD9;
	background: #009DD9;color:#fff;font-size: 1em;margin:1em auto;}
	.bm5041_con_li_6 img{width:100%;}
	.register_end{height:35px;line-height:35px;font-size:16px;width:90%;margin-top:5px;}
	.redColor{color:red;}
    </style>
</head>
<body>
<div class="bm5041">
    <p class="bm5041_tit">应到<?php echo $this->_tpl_vars['countnum']; ?>
人</p>
    <div class="bm5041_con">
        <form action="" id='meminfo'>
			<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
            <div class="bm5041_con_li <?php if ($this->_tpl_vars['v']['is_qd'] == '0'): ?>redColor<?php endif; ?>">
                <div class="bm5041_con_li_1"><?php echo $this->_tpl_vars['v']['xuhao']; ?>
-<?php echo $this->_tpl_vars['v']['nickname']; ?>
</div>
                <div class="bm5041_con_li_2"><?php echo $this->_tpl_vars['v']['sex']; ?>
</div>
                <div class="bm5041_con_li_3"><?php echo $this->_tpl_vars['v']['username']; ?>
</div>
				<div class="bm5041_con_li_6">
					<img onclick="refuse(this);" src="images/qd_<?php if ($this->_tpl_vars['v']['is_qd'] == '2'): ?>off<?php else: ?>on<?php endif; ?>.png" />
					<input type="hidden" name="info[qd_<?php echo $this->_tpl_vars['v']['id']; ?>
]" value='<?php echo $this->_tpl_vars['v']['is_qd']; ?>
' />
				</div>
            </div>
			<?php endforeach; endif; unset($_from); ?>
        </form>
    </div>
	<div class="register_end" data-page="1" onclick="seeMore(this)">查看更多</div>
	<div class="register_end" onclick="save();">确认</div>
	<div style="color:#777;text-align:center;"><span style='color:#323232'>黑色</span>代表已经确认过的，<span style='color:red'>红色</span>代表待确认的</div>
	<?php if ($this->_tpl_vars['userRow']['id'] == 1): ?>
	<div style="height:10px;"></div>
	<div class="register_end" id="addSign" style="height:35px;line-height:35px;font-size:16px;width:90%;">追加报名</div>
	<?php endif; ?>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--弹出层-->
<div id="fullbg" class="fullbg"></div>
<div class="box_pop">
	<input type="text" id="add_sign_phone" placeholder="请输入会员手机账号"/>
	<div class="db_btn">
		<div class="ensure">确定</div><div class="cancel">取消</div>
		<div class="clear"></div>
	</div>
</div>
<!--弹出层 end-->
<script type="text/javascript">
function save(){
	$.post('index.php?m=task_company&a=sign_qd',$('#meminfo').serialize(),function(res){
		res=jQuery.trim(res);
		if(res=='suc'){
			alert('确认成功');
			$('.bm5041_con_li').removeClass('redColor')
			//window.location.href='index.php?m=task_company&a=sign_mid&tid=<?php echo $_GET['tid']; ?>
';
		}else if(res=='err'){
			alert('确认失败');
		}
	});
}
//拒绝报名
function refuse(obj){
	if($(obj).next('input').val()=='2'){
		$(obj).next('input').val('1');
		$(obj).attr('src','images/qd_on.png');
	}else{
		$(obj).next('input').val('2');
		$(obj).attr('src','images/qd_off.png');
	}
}
//查看更多
function seeMore(obj){
	var pageNum=parseInt($(obj).attr('data-page'))+1;
	$(obj).attr('data-page',pageNum);
	$.get('index.php?m=task_company&a=sign_qd_ajax&tid=<?php echo $_GET['tid']; ?>
&p='+pageNum,function(res){
		if(res){
			if(res=='err'){
				$(obj).text('没有更多了:)');
			}else{
				var goodsStr='';
				$.each(res,function(i,item){
					if(item.is_qd==0){
						goodsStr+='<div class="bm5041_con_li redColor">';
					}else{
						goodsStr+='<div class="bm5041_con_li">';
					}
					goodsStr+='<div class="bm5041_con_li_1">'+item.xuhao+item.nickname+'</div><div class="bm5041_con_li_2">'+item.sex+'</div><div class="bm5041_con_li_3">'+item.username+'</div><div class="bm5041_con_li_6">';
					if(item.is_qd==2){
						goodsStr+='<img onclick="refuse(this);" src="images/qd_off.png" />';
					}else{
						goodsStr+='<img onclick="refuse(this);" src="images/qd_on.png" />';
					}
					goodsStr+='<input type="hidden" name="info[qd_'+item.id+']" value="'+item.is_qd+'" /></div></div>';
				})
				$('#meminfo').append(goodsStr);
			}
		}
	},'json');
}

$(function(){
	//追加报名
	var bh = $("body").height()*1.2; 
	var bw = $("body").width(); 
	$("#addSign").click(function(){
		$(".fullbg").css({height:bh,width:bw,display:"block"});
		$(".fullbg").show();
		$(".box_pop").show();
	});
	$(".ensure").click(function(){
		var add_phone=$('#add_sign_phone').val();
		if(!isMobil(add_phone)){
			alert("请填写正确的手机账号");return;
		}
		$.get('index.php?m=task&a=sign_zj&tid=<?php echo $_GET['tid']; ?>
&phone='+add_phone,function(res){
			res=jQuery.trim(res);
			if(res=='no'){
				alert('该账号不存在或信息不完善');
			}else if(res=='ybm'){
				alert('已申请过该职位');
			}else if(res=='chongfu'){
				alert('该工作日已报名了其它职位！');
			}else if(res=='suc'){
				alert('追加成功');
			}else if(res=='err'){
				alert('追加失败！');
			}
			$('#add_sign_phone').val('');
		})
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
	$(".cancel").click(function(){
		$(".fullbg").hide();
		$(".box_pop").hide(); 
	});
})
//验证手机号
function isMobil(s){  
	var patrn=/^1[0-9]{10}$/;  
	if (!patrn.exec(s)) return false
	return true
}
</script>
</body>
</html>