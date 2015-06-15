//弹框的高度
function alert_tip(str){
	var alert_tip_info='<div class="alert_tip"><div class="alert_tiptop"><span>提示信息</span><a onclick=\'$(".alert_tip").hide();\'></a></div><div class="alert_tipinfo">'+str+'</div></div>';
	$('body').append(alert_tip_info);
	var scrollTopNum=parseInt(getScrollTop())+200;
	var scrollTop=scrollTopNum+"px";
	$('.alert_tip').css('top',scrollTop);
	$('.alert_tip').show();
	setTimeout('$(".alert_tip").fadeOut(500)',2000);
}
//获得滚动条高度
function getScrollTop(){
    var scrollTop=0;
    if(document.documentElement && document.documentElement.scrollTop){
        scrollTop=document.documentElement.scrollTop;
    }else if(document.body){
        scrollTop=document.body.scrollTop;
    }
    return scrollTop;
}