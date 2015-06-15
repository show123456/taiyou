$(document).ready(function(){
$(".tab01").click(function(){
    $(".manage_basic").css({'display':'block'});
	$(".manage_recommend").css({'display':'none'});
	$(".manage_set").css({'display':'none'});
	$(".tab03").css({"background-color":"#F8F8F8"});
	$(".tab01").removeClass("grey_bg");
	$(".tab01").addClass("blue_bg");
	$(".tab01").css({"background-color":"#F8F8F8"});
	$(".tab02").removeClass("blue_bg");
	$(".tab02").addClass("grey_bg");
	$(".tab02").css({"background-color":"#F8F8F8"});
	$(".manage_content .top_menu").css({"background-color":"#F8F8F8"});
	$(".tab03>span").css({"background":"url(images/blue_dot.png) no-repeat center","color":"#fff"});
  });
    $(".tab02").click(function(){
    $(".manage_basic").css({'display':'none'});
	$(".tab01").removeClass("blue_bg");
	$(".tab01").addClass("grey_bg");
	$(".tab01").css({"background-color":"#61CEF7"});
	$(".tab03").css({"background-color":"#F8F8F8"});
	$(".tab02").removeClass("grey_bg");
	$(".tab02").addClass("blue_bg");
	$(".tab02").css({"background-color":"#F8F8F8"});
	$(".manage_recommend").css({'display':'block'});
	$(".manage_set").css({'display':'none'});
	$(".manage_content .top_menu").css({"background-color":"#F8F8F8"});
	$(".tab03>span").css({"background":"url(images/blue_dot.png) no-repeat center","color":"#fff"});
  });
    $(".tab03").click(function(){
    $(".manage_basic").css({'display':'none'});
	$(".manage_recommend").css({'display':'none'});
	$(".manage_set").css({'display':'block'});
	$(".video_info").css({'display':'none'});
	$(".tab03").css({"background-color":"#61CEF7"});
	$(".tab01").css({"background-color":"#F8F8F8"});
	$(".tab02").css({"background-color":"#61CEF7"});
	$(".tab01").removeClass("blue_bg");
	$(".tab01").addClass("grey_bg");
	$(".tab02").removeClass("blue_bg");
	$(".tab02").addClass("grey_bg");
	$(".tab03>span").css({"background":"url(images/white_dot.png) no-repeat center","color":"#61CEF7"});
	$(".manage_content .top_menu").css({"background-color":"#61CEF7"});
  });
  $(".video_class").click(function(){
    $(".class_info").css({'display':'none'});
	$(".video_info").css({'display':'block'});
  });
    $(".public_class").click(function(){
    $(".class_info").css({'display':'block'});
	$(".video_info").css({'display':'none'});
  });
      $(".inner_class").click(function(){
    $(".class_info").css({'display':'block'});
	$(".video_info").css({'display':'none'});
  });
 });