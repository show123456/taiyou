function myAjax(url,data,fun){
	//if(confirm(ask)==false){}
	if(data.indexOf("=")<1){data = $("#"+data).serialize();}
	$.ajax(
	{
		type: "POST",
		url:url,
		data:data,//$('#accorder').serialize(),
		async: false,
		dataType:'json',
		success: function(data){		
			fun(data) ;
		}
	});
}
/**
 * 确认对话框
 */
function confirm_dialog(msg,fn_y,fn_n){
	$.layer({
		title:'提示信息',
	    area: ['350px','auto'],
	    border: [0],
	    dialog: {
	        msg:  msg,
	        btns: 2,                    
	        type: -1,
	        btn: ['确定','取消'],
	        yes: fn_y,
	        no: fn_n
	    }
	});
	$('.xubox_main').css('height','144px');
	$('.xubox_botton').find('a').css('bottom','20px');
	$('.xubox_text').css({'padding-left':'0px','padding-right':'0px','width': '100%','text-align': 'center'});
}
/**
 * 提示对话框
 */
function prompt_dialog(msg,fn){
	$.layer({
		title:'提示信息',
	    area: ['350px','auto'],
	    border: [0],
	    dialog: {
	        msg: msg,
	        btns: 1,                    
	        type: -1,
	        btn: ['确定'],
	        yes:fn
	    }
	});
	$('.xubox_main').css('height','144px');
	$('.xubox_botton').find('a').css('bottom','20px');
	$('.xubox_text').css({'padding-left':'0px','padding-right':'0px','width': '100%','text-align': 'center'});
}
/*
 * 关闭弹出层
 */
function close_dialog(index){
	layer.close(index);
}
/**
 * 去左右空格
 */
function trim(s){  
	return s.replace(/(^\s*)|(\s*$)/, "");   
} 
/**
 * 日历：
 * id:触发该方法的元素id
 * is_showtime:true/false;是否显示时间
 */
function myCalender(id,is_showtime){
	if(typeof(is_showtime)=='undefined')is_showtime=true;
	format = is_showtime?"%Y-%m-%d %H:%M":"%Y-%m-%d";
	Calendar.setup({
        inputField     :    id,   // id of the input field
        ifFormat       :    format,       // format of the input field
        showsTime      :    is_showtime,
        timeFormat     :    "24"
    });
}
/**
 * 把时间字符串转为时间戳
 */
function getTimeNum(dateStr){
	 var newstr = dateStr.replace(/-|\./g,'/'); 
	    var date =  new Date(newstr); 
	    var ddd = date.getTime();
	    return parseInt(ddd);
}