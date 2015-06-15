//内容区栏目名称
function set_title_name(name){
	$("#title_name").html(name);
}
//菜单选中状态
function set_menu_cur(menuid,addclass){
	menuArr=menuid.split("_");
	if(menuArr.length==3){
		var i=parseInt(menuArr[1]);
	}else{
		var i = parseInt(menuid.substring(5,6));
	}
	$('.menu li').find('ul').hide();
	$('.menu li').find('ul').eq(i-1).show();
	$("#"+menuid).attr("class",addclass);
}
/***********
 * js 截断
 * @example:  <a href='xxx' limit="2">xxxx</a> 会将xxxx截断成xx
 ***********/
function cut_str(str,lim, tip){
	var pos = 0;
	var len = 0;
	var limited = '';
	while(pos < str.length && len < lim){
		limited += str.charAt(pos);
		pos		+= 1;
		len		+= (str.charCodeAt(pos)>255)?2:1;
	}
	return limited + ( (pos < str.length)?tip:'');
}
$(document).ready(function(){
	$('a[limit],span[limit]').each(function(n,i){
		var tag = $(i);
		var tip = typeof(tag.attr('tip'))=='undefined' ? '...' : tag.attr('tip');
		tag.text(cut_str(tag.text(), tag.attr('limit'), tip));
	});
	$('textarea').keyup(function(){
        var counter=$(this).parent().find('.max-count');
        if (counter.length){
            var limit=parseInt(counter.attr('max'));
            current_count = Math.floor(limit-$(this).val().replace(/[^\x00-\xff]/ig,"xx").length/2);///[\u4e00-\u9fa5]/g
            counter.text(current_count.toString());
        };
    });
    $('textarea').change(function(){ $('textarea').keyup(); });
    $('textarea').keyup();
});
/***********
 * 提示框
 * @example:  成功：note_info('xxx','success',evt);失败：note_info('xxx','warn',evt);
 ***********/
function note_info(info, info_type, evt) {
	$("#op_result").remove();
    var iHeight = parseInt(document.documentElement.scrollHeight);
    var _event = evt ? evt : evt.event;
    //var divx;
    //var divy;
    $('body').append("<div class='noticeui noticeui-"+info_type+"' id='op_result'><p>"+info+"</p></div>");
    //var scrollHeight = document.documentElement.scrollTop - parseInt('60');
	var scrollHeight = getScrollTop() - parseInt('60');
    document.getElementById('op_result').style.top = _event.clientY + scrollHeight + 'px';
    document.getElementById('op_result').style.left = _event.clientX + 'px';
    $("#op_result").animate({opacity: 'show'}, 'slow');

    setTimeout("$('#op_result').animate({opacity:'hide'},'slow');", 3000);
	setTimeout("$('#op_result').remove();",3100);
}
function note_info_time(info, info_type, evt, t) {
	$("#op_result").remove();
    var iHeight = parseInt(document.documentElement.scrollHeight);
    var _event = evt ? evt : evt.event;
    //var divx;
    //var divy;
    $('body').append("<div class='noticeui noticeui-"+info_type+"' id='op_result'><p>"+info+"</p></div>");
    //var scrollHeight = document.documentElement.scrollTop - parseInt('60');
	var scrollHeight = getScrollTop() - parseInt('60');
    document.getElementById('op_result').style.top = _event.clientY + scrollHeight + 'px';
    document.getElementById('op_result').style.left = _event.clientX + 'px';
    $("#op_result").animate({opacity: 'show'}, 'slow');

    setTimeout("$('#op_result').animate({opacity:'hide'},'slow');", t);
	setTimeout("$('#op_result').remove();",t+100);
}
function getScrollTop(){
    var scrollTop=0;
    if(document.documentElement && document.documentElement.scrollTop){
        scrollTop=document.documentElement.scrollTop;
    }
    else if(document.body){
        scrollTop=document.body.scrollTop;
    }
    return scrollTop;
}
	
	//调用表情
	//需要 输入框class=‘expres_text’
	//需要在调出处 加onclick="$(this).next().toggle(get_expression());return false"
	//需要在  调用处的下一行 加<div name="face_box"  class="emoticons_frame" id='xianshiqu' style="display: none;></div> 作为同辈下一节点
	
var response=false;
function get_expression(){
	if(response==true){
		$(this).show();
	}else{
		$('#xianshiqu').load("/home/member/templates/expression.html",function(responseText,textStatus){
			response = true;
			$(this).css('display','block');
			$('a img').on('click',function(){
				$("#xianshiqu").hide();
				$('.expres_text').val($('.expres_text').val()+'['+$(this).parent().attr('title')+']');
			})
		});  
	}
}

//	调用弹框效果  给定事件	onclick = showDialogBox(event,'att',title);
//	evt 	必须 。 当前事件，
//	att 	必须 。 弹窗类型。  lianxiwomen || lbs ||  column ||xiangxi
//  fun     必须 。 保存按钮的回调函数 必须有括号
//	title 	可选 。 弹窗标题。
//  表单id 统一为 DialogBoxform
//  提交按钮 为	subDialogBox
function showDialogBox(evt,att,fun,title){
	 //setTimeout("$('#op_result').animate({opacity:'hide'},'slow');", 3000);
	 $('#DialogBox').remove();
	 var iHeight = parseInt(document.documentElement.scrollHeight);
	 var _events = evt ? evt : evt.event;
	 var scrollHeight = document.documentElement.scrollTop - parseInt('60');
	 switch(att){
		case 'lianxiwomen':
			strs = "联系号码：<input id='cent' name='lianxiwomen' class='textarea' value='0' type='text'><input type='hidden' name='types' value='lianxiwomen'>";
			tit  = "手机号设置"; 
			break;
		case 'lbs':
			strs = "地址名称：<input id='cent' name='lbs' class='textarea' value='0' type='text'><input type='hidden' name='types' value='lbs'>";
			tit = "LBS设置";		
			break;
		case 'column':
			strs = "栏目名称：<input id='cent' name='column' class='textarea' value='0' type='text'><input type='hidden' name='types' value='column'>";
			tit = "栏目设置";		
			break;
		case 'cate':
			strs = "<div style='line-height:35px;'>栏目名称：<input id='cent' name='cate' class='textarea' type='text'></div><div style='line-height:35px;'>分类排序：<input id='cent' name='order_num' class='textarea' type='text'></div><div style='line-height:35px; position:relative;'>上传图片：<span class='uppic'><input id='logo_file' name='cate_pic' class='uppic_file' type='text'></span></div><div style='line-height:35px;'>开放状态：<input type='radio' name='state' id='radone' value='1' /> 开放 <input id='radtwo' type='radio' name='state' value='2'/> 关闭 <input type='hidden' name='id' value=''></div>";
			tit = "分类设置";		
			break;
		case 'cate_2':
			strs = "<div style='line-height:35px;'>栏目名称：<input id='cent' name='cate' class='textarea' type='text'></div><div style='line-height:35px;'>分类排序：<input id='cent' name='order_num' class='textarea' type='text'></div><div style='line-height:35px;'>开放状态：<input type='radio' name='state' id='radone' value='1' /> 开放 <input id='radtwo' type='radio' name='state' value='2'/> 关闭 <input type='hidden' name='id' value=''></div>";
			tit = "分类设置";		
			break;
		case 'column_update':
			strs ="<div class='form-group'><span style='line-height:40px;'>栏目名称：</span><input id='cent' name='column_update' class='textarea' value='0' type='text' /><br><span id='oncespan' ><span style='line-height:40px;'>栏目logo：</span><input id='local-image' type='text' class='form-control textarea'  readonly='readonly' style='height:30px;width:187px;display:inline-block;'><a href='javascript:void(0);' class='btn-upload' style='margin-left:76px;margin-top:110px'><i class='icons icons-upload'></i>上传</a></span><input type='hidden' name='column_old' value='' /><input type='hidden' name='types' value='column' /><input type='hidden' name='pic' id='oncepic' /></div>";
			tit = "栏目设置";		
			break;
		case 'xiangxi':
			strs=title;
			title='';
			tit = "详细信息";
			break;
	 }
	title     ? tit   = title     :    tit = tit;
	$('body').append("<div id='DialogBox' style='margin: -161px 0px 0px -149px; position: absolute; z-index: 10000; border: medium none; padding: 0px; display: block;'><form id='DialogBoxform' name='DialogBoxform' method='post'><div style='display: block; margin: 0px;' class='Dialog-wrapper' id='DialogWrapper'><div class='Dialog-content'><h4 style='cursor: move;' class='Dialog-title' id='DialogTitle'><a href='#' title='关闭窗口' class='close-btn' id='closeBtn' onclick='hideDialogBox();return false'>×</a>"+tit+"</h4><div class='Dialog-text'><div class='dialog-box'><div style='margin-top:10px; line-height:30px;' class='editer-bt'>"+strs+"</div></div></div><div class='Dialog-footer'><button class='btn-normal' id='DialogNoBtn' onclick='hideDialogBox();return false'>取消</button><button class='btn-highlight' id='DialogYesBtn' type='button' id='subDialogBox' onclick='subDialogBox("+fun+");return false;'>保存</button></div></div></div></form></div>");
	
	document.getElementById('DialogBox').style.top = _events.clientY + scrollHeight + 150+ 'px';
    document.getElementById('DialogBox').style.left = _events.clientX + 'px';

}
	
//隐藏弹窗效果
function hideDialogBox(){
	$('#DialogBox').hide();
	return false;
}

//提交弹框内表单
function subDialogBox(fun){	
	fun;
	hideDialogBox();
}

//开启关闭 会员应用
//参数 appid（必须），ste（必须），event（可选）
//标签处 固定 id="appstate"
function upstate(appid,sta,event){
	if(sta==1){
		 state=2;
		 imageurl="url(/home/app/express/images/close.png)";
		 appinfo="关闭";
	}else{
		 state=1;
		 imageurl="url(/home/app/express/images/open.png)";
		 appinfo="开启";
	}
	$.ajax({
		type: "POST",
		url:"/home/app/index.php",
		data:{'act':'upstate','app_id':appid,'state_value':state},//$('#accorder').serialize(),
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(result) {		
			if(result=='success'){
				note_info('操作成功!','success',event);
				setTimeout("window.location.href=''",500);
			}else{
				alert(result)
			}
		}
	});
	$("#appstate").css("background-image",imageurl);
}