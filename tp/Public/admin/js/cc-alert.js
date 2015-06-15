//弹框的高度
function note_info(str){
	var scrollTopNum=parseInt(getScrollTop())+160;
	var scrollTop=scrollTopNum+"px";
	$('.tip').css('top',scrollTop);
	$('.tip').fadeIn(200);
	$('.tipright cite').html(str);
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