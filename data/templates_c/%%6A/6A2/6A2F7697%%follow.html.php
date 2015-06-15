<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:11
         compiled from D:%5Cwamp%5Cwww%5C/home/info/templates/follow.html */ ?>
<div class=" main-content">
    <div class="inner">
        <div class="alert alert-warning alert-dismissable fz12px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p>
            用户首次关注成功后收到的界面欢迎信息。可以写一些提醒用户的相关操作等。首次回复可以是以文字、图文、语音、音乐、视频、微主页等形式出现。
            </p>
        </div>

        <div>
            <form class="form-horizontal user-form" role="form" method="post" id="autoreplyform">
                <div class="form-group">
                    <label  class="col-sm-2 control-label">回复类型</label>
                    <div class="col-sm-4">
                        <select  class="form-control" id="is_keyword" name="is_keyword">
                            <option value="0">文本消息</option>
                            <option value="1" <?php if ($this->_tpl_vars['replyRow']['is_keyword'] == 1): ?>selected<?php endif; ?>>绑定关键词</option>
                        </select>
                    </div>
                </div>
                <div id="reply_content_div" class="form-group" <?php if ($this->_tpl_vars['replyRow']['is_keyword'] == 1): ?>style="display:none;"<?php endif; ?>>
                    <label class="col-sm-2 control-label">回复内容</label>
                    <div class="col-sm-10" style="width:50%;">
                        <textarea id="reply_content" class="form-control" name="reply_content" cols="30" rows="10"><?php echo $this->_tpl_vars['replyRow']['reply_content']; ?>
</textarea>
                    </div>
                </div>
                <div id="reply_keyword_div" class="form-group" <?php if ($this->_tpl_vars['replyRow']['is_keyword'] == 0): ?>style="display:none;"<?php endif; ?>>
                    <label class="col-sm-2 control-label">规则</label>
                    <div class="col-sm-10">
                        <input id="reply_keyword" name="reply_keyword" type="text" class="form-control input-large" value="<?php echo $this->_tpl_vars['replyRow']['reply_keyword']; ?>
"/>
                        <a href=""><span class="c-green"></span></a>
                        <p class="help-block">请先在对应板块设置好规则内容</p>
                    </div>
                </div>
				<div style="clear:both;"></div>
				<div class="form-group">
                    <label  class="col-sm-2 control-label">&nbsp;1</label>
                    <div class="col-sm-4">
                        <input type="radio" value="1" name="state"  <?php if ($this->_tpl_vars['replyRow']['state'] == 1): ?>checked<?php endif; ?>> <span class="c-green">开启</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="state" <?php if ($this->_tpl_vars['replyRow']['state'] == 2): ?>checked<?php endif; ?>> <span class="c-gray">关闭</span>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <div style="font-weight:bold;">
                            <input type="radio" value="1" name="state"  <?php if ($this->_tpl_vars['replyRow']['state'] == 1): ?>checked<?php endif; ?>> <span class="c-green">开启</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="state" <?php if ($this->_tpl_vars['replyRow']['state'] == 2): ?>checked<?php endif; ?>> <span class="c-gray">关闭</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
						<input type="hidden" name="save" value="1">
                        <button type="button" class="btn btn-large btn-success" onclick="saveform(event);">提交保存</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    set_title_name('首次关注设置');
    set_menu_cur('menu_11','cur');
	function saveform(evt){
		var reply_content=$("#reply_content").val();
		var reply_keyword=$("#reply_keyword").val();
		if(reply_content=='' && reply_keyword==''){
			note_info('请填写回复内容','warn',evt);
		}else{
			$.ajax({
				cache: true,
				type: "POST",
				url:"follow.php",
				data:$('#autoreplyform').serialize(),//formid
				async: true,
				error: function(request) {
					note_info('添加失败！','warn',evt);
				},
				success: function(data) {
				
					if(data=='success'){
						note_info('保存成功！','success',evt);
					}
				}
			});

		}
	}
    $(document).ready(function(){
    	$('#is_keyword').change(function(){
    		if($(this).val()==1){
    			$('#reply_content_div').hide();
    		    $('#reply_keyword_div').show();
    		}else{
    			$('#reply_keyword_div').hide();
    			$('#reply_content_div').show();
    			
    		}
    	});
    });
</script>