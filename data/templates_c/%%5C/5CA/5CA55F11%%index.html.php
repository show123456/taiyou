<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:05
         compiled from D:%5Cwamp%5Cwww%5C/home/info/templates/index.html */ ?>
<div class=" main-content">
    <div class="inner">
        <ul class="tab clearfix">
            <li <?php if ($this->_tpl_vars['type'] == 'text'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=text">文字</a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'single'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=single">单图文</a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'multi'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=multi">多图文</a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'pic'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=pic"></a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'music'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=music"></a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'video'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=video"></a></li>
            <li <?php if ($this->_tpl_vars['type'] == 'lbs'): ?>class="cur"<?php endif; ?>><a href="/home/info/index.php?type=lbs"></a></li>
            <li class="search"><input type="search" class="form-control" placeholder="消息内容" /> <input class="icons icons-search" type="submit" /></li>
            <li class="omega">
               <div class="dropdown infotype">
                   <a id="drop6" role="button" data-toggle="dropdown" href="/home/info/<?php echo $this->_tpl_vars['type']; ?>
.php"><i class="icons icons-plus"></i></a>
                   <ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop6" style="margin:0px;">
                       <li><a href="/home/info/text.php?type=text">文字</a></li>
                       <li><a href="/home/info/single.php?type=single">单图文</a></li>
                       <li><a href="/home/info/multi.php?type=multi">多图文</a></li>
                       <li><a href="/home/info/music.php?type=music">音乐</a></li>
                       <li><a href="/home/info/video.php?type=video">视频</a></li>
                       <li><a href="/home/info/lbs.php?type=lbs">LBS</a></li>
                   </ul>
               </div>
            </li>
        </ul>
       <!--  <?php if ($this->_tpl_vars['type'] == 'text'): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['ROOT_PATH'])."/home/info/templates/text_table.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else: ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['ROOT_PATH'])."/home/info/templates/single_table.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
 -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['ROOT_PATH'])."/home/info/templates/".($this->_tpl_vars['typeyhtml']).".html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    set_title_name('关键词回复设置');
    set_menu_cur('menu_14','cur');
    $(function(){
    	$('.infotype').hover(
    	    function(){$('#menu3').show();},
    	    function(){$('#menu3').hide();}
    	);
    	$('a[title="删除"]').click(function(){
    		if(!confirm('确定删除吗?')) return false;
    		return true;
    	});
    });
</script>