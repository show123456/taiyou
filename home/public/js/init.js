//set page height
(function(){
    var menu = $('.main-menu'), content = $('.main-content');
    menu.height() > content.height() ? (content.css({height:menu.height() +'px'})) : (menu.css({height:content.height() + 'px'}) )
})()

