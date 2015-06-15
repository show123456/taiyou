<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:05
         compiled from D:%5Cwamp%5Cwww%5C/home/info/templates/text_table.html */ ?>
<table class="table table-library">
    <thead>
        <tr>
            <th class="t1">关键词</th>
            <th class="t2">回复内容</th>
            <th class="t3">推送量</th>
            <th class="t4">状态</th>
            <th class="t5">操作 </th>
        </tr>
    </thead>
    
    <tbody>
        <tr>
            <td colspan="5">
                <div class="tag pull-left" id='div_p' style="float:left;width:500px">
				 <button class="btn btn-info btn-xs " onclick='window.location.href="/home/info/index.php?type=<?php echo $this->_tpl_vars['type']; ?>
"'>
				 全部分类
				</button>
				<?php if ($this->_tpl_vars['cates']): ?>
                <?php $_from = $this->_tpl_vars['cates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cate']):
?>
                    <button style='margin:2px' class="btn btn-info btn-xs " onclick='window.location.href="/home/info/index.php?cate_id=<?php echo $this->_tpl_vars['cate']['id']; ?>
&type=<?php echo $this->_tpl_vars['type']; ?>
"' id="cate<?php echo $this->_tpl_vars['cate']['id']; ?>
" cate_name="<?php echo $this->_tpl_vars['cate']['cate_name']; ?>
"><span><?php echo $this->_tpl_vars['cate']['cate_name']; ?>
</span>
					<?php if ($this->_tpl_vars['cate']['cate_num'] > 0): ?>(<?php echo $this->_tpl_vars['cate']['cate_num']; ?>
)<?php endif; ?>
					</button>
                <?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
				
                </div>
                <div class="pull-right">
					<div style="float:left;">
					
					
						<form action='/home/info/insert_cate.php' method='post' style="display:none;" id='f_m'>
							<input type='text' class="textarea" name='cate_name'>
							<input type='hidden' value="<?php echo $this->_tpl_vars['type']; ?>
" name='info_type'>
							
							<button type='button' class="btn btn-sm btn-success" onclick="save_cate(event)" >新增</button>
						</form>
						<form action='/home/info/insert_cate.php' method='post' style="display:none;" id='f_m_two'>
							<input type='hidden' value="" name='id'>
							<input type='text' class="textarea" name='cate_name_two'>
							<input type='hidden' value="<?php echo $this->_tpl_vars['type']; ?>
" name='info_type_two'>
							<button type='button' class="btn btn-sm btn-success" onclick="save_cate_two(event);return false" >保存</button>
						</form>
						
					</div>
					<?php if ($this->_tpl_vars['cate_id']): ?>
						<div class='cate_div'>
							<a href='#'  style='font-size:12px;' onclick='edit_cate("<?php echo $this->_tpl_vars['cate_id']; ?>
");return false'>编辑</a> | <a href='#'  style='font-size:12px;' onclick='del_cate("<?php echo $this->_tpl_vars['cate_id']; ?>
",event);return false'>删除</a>
						</div>
					<?php else: ?>
						<a href='#' id='a' onclick="insertCate();return false;" style="font-size:12px;">+添加分类</a></a>
					<?php endif; ?>
				</div>
            </td>
        </tr>
    <?php if ($this->_tpl_vars['infolist']): ?>

			<?php $_from = $this->_tpl_vars['infolist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<tr>
				<td><strong class="c-red"><?php echo $this->_tpl_vars['row']['keyword']; ?>
</strong></td>
				<td><?php echo $this->_tpl_vars['row']['info_intro']; ?>
</td>
				<td><?php echo $this->_tpl_vars['row']['push_num']; ?>
</td>
				<td><?php if ($this->_tpl_vars['row']['state'] == 1): ?><div style='color:#009A07'>开放中</div><?php else: ?>已关闭<?php endif; ?></td>
				<td>
					<a href="/home/info/<?php echo $this->_tpl_vars['type']; ?>
.php?id=<?php echo $this->_tpl_vars['row']['id']; ?>
&type=<?php echo $this->_tpl_vars['type']; ?>
" title="编辑"><i class="glyphicon glyphicon-edit"></i></a>
					<a href="/home/info/<?php echo $this->_tpl_vars['type']; ?>
.php?id=<?php echo $this->_tpl_vars['row']['id']; ?>
&act=del&type=<?php echo $this->_tpl_vars['type']; ?>
" title="删除"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
		

    <?php else: ?>
        <tr>
            <td colspan="5">
                <p class="c-gray wrap-null"> 暂无内容！点击右上角<span class="c-green">【+】号</span>新建内容吧！ </p>
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<script type="text/javascript">
//添加分类
function insertCate(){
	$('#a').css('display','none');
	$('input[name=cate_name]').val('');
	$('#f_m').show();
}
//保存分类
function save_cate(event){
	if($('input[name=cate_name]').val()==''){
		note_info('不能为空！','warn',event);
		return false;
	}
	$.ajax({
             type: "POST",
             url: "/home/info/insert_cate.php",
             data: $('#f_m').serialize(),//{username:$("#username").val(), content:$("#content").val()},
             
			 async: false,
             success: function(res){
				if(typeof(res)=='object'){
					note_info('保存成功！','success',event);			
					$('#div_p').append("<button class='btn btn-info btn-xs'style='margin:2px' onclick='window.location.href=\"/home/info/index.php?cate_id="+res.id+"&type=<?php echo $this->_tpl_vars['type']; ?>
\"'>"+res.cate_name+"</button>");
					$('#f_m').hide();
					$('#a').show();
				}
				if(res=='1'){
					note_info('此分类已存在','warn',event);
					return false;
				}
             }	

         });
};
//修改分类
function edit_cate(id){
	$('#f_m_two').show();
	$('.cate_div').hide();
	$('input[name=cate_name_two]').val($('#cate'+id).attr('cate_name'));
	$('input[name=id]').val(id);
}
//保存修改后的分类
function save_cate_two(ent){	
	$.ajax({
		 type: "POST",
		 url: "/home/info/insert_cate.php",
		 data: $('#f_m_two').serialize(),
		
		 async: false,
		 success: function(res){
			if(res=='success'){
			$('#cate'+$('input[name=id]').val()).html("<span>"+$('input[name=cate_name_two]').val()+"</span>");	
				note_info('修改成功','success',ent);
			$('#f_m_two').hide();
			$('.cate_div').show();
			}
		 }	

	 });
}
//删除分类
function del_cate(id,event){
	if(confirm('确定删除吗?')){
		$.ajax({
		 type: "POST",
		 url: "/home/info/insert_cate.php",
		 data: {
			'del_id':id
		 },	
		 async: false,
		 success: function(res){
			if(res=='success'){
				note_info('删除成功','success',event);
				$("#cate"+id).remove();
			}
		 }	

    });
	}	
}
</script>