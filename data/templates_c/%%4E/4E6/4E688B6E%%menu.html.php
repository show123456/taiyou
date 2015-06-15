<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:19
         compiled from D:%5Cwamp%5Cwww%5C/home/info/templates/menu.html */ ?>
<div class=" main-content">
    <div class="inner">
        <div class="alert alert-danger alert-dismissable fz12px alertfz">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p>1. 您的公众平台帐号类型必须为<span class="c-red">订阅号并已通过微信认证</span>或<span class="c-red">服务号</span>。</p>
            <p>2. 在微信公众平台申请接口使用的AppId和AppSecret，然后在<a href="auth.php"><span class="c-green">【微信授权配置】</span></a>中设置。</p>
            <p>3. 最多创建3个一级菜单，每个一级菜单下最多可以创建5个二级菜单，菜单最多支持两层。</p>
            <p>4. 拖动树形菜单可以对菜单重排序，但最终只有“发布菜单”后才会生效，公众平台限制了每天的发布次数，请勿频繁操作。</p>
            <p>5. 微信公众平台规定，菜单发布24小时后生效。您也可先取消关注，再重新关注即可马上看到菜单。</p>
        </div>

        <div class="diy-menu">
            <h3>底部菜单管理</h3>
			
			
            <div class="preview">
			
            </div>
			
			
            <div class="opt">
                <div class="null">
                    点击左侧[<span class="c-green">+</span>]号添加微信底部菜单
                </div>
				
				<!-- maxiaolei -->
				<div class="inner" style="display:none;">
					<p class="c-gray fz12px">
					请选择订阅者点击菜单后，公众号做出相应动作     
					</p>

					<form class="form-horizontal user-form form-menu" id="menu_f" role="form">
						<div class="form-group">
							<label class="col-sm-3 control-label">菜单名称</label>
							<div class="col-sm-6">
								<input type="text" name="m_name" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">消息类型</label>
							<div class="col-sm-6">
								<select id="uniqueSelect" name="m_type" class="form-control">
									<option value="click" class='m_click' onclick="sm_type(0)">关键词绑定</option>
									<option value="view" class='m_view' onclick="sm_type(1)">链接地址</option>
								</select>
							</div>
						</div>
						<div class="form-group m_key1" >
							<label class="col-sm-3 control-label" id='unqi'>绑定规则</label>
							<div class="col-sm-9">
								<input id="m_keys0" type="text" name="m_key" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<span class="pull-right">
									<a href="">
										<span class="c-red fz12px">关键词管理</span>
									</a>
								</span>
								<input type="hidden" name="act" value="">
								<input type="hidden" name="m_id" value="">
								<input type="hidden" name="parent_id" value=0>
								<input type="hidden" name="menu_order" value=0>
								<button type='button' onclick="save(event);return false" class="btn btn-success">添加</button>
							</div>
						</div>

					</form>
				</div>
				
				<!-- maxiaolei -->
            </div>
			
			
            <a class="btn btn-large btn-success" onclick="pub_menu(event);return false;" href="">发布菜单</a>
        </div>


    </div>
</div>
<script>
    set_title_name('自定义菜单');
    set_menu_cur('menu_13','cur');

<!-- maxiaolei -->
function pub_menu(event){
	$.ajax({
			type: "POST",
			url:"/home/info/menu_ajax.php",
			data:{
				'act':'pub_menu'
			},
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(res) {
				if(res=='success'){
					note_info('发布成功！','success',event);
				}else{
					note_info(res,'warn',event);
				}
			}
		})
}

$(function(){
	createMenu();
	//移入移出事件 浮动层

})
function createMenu(){
	$.ajax({
			type: "POST",
			url:"/home/info/menu_ajax.php",
			data:{
				'act':'createMenu'
			},// 你的formid
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(result){
				
				$('.preview').html(result);
				$('.col a').hover(function(){
					old_val = $(this).html();
					$(this).html("<span class='popup'><i class='glyphicon glyphicon-pencil' onclick='editMenu("+$(this).attr('m_id')+","+$(this).attr('menu_order_id')+");return false' title='编辑'></i><i onclick='dele("+$(this).attr('m_id')+",event);return false' class='glyphicon glyphicon-trash' title='删除'></i></span>");
				},function(){
					$(this).html(old_val);
				});
			}
	})
}
	//获取菜单信息
	//参数 菜单id
	function getMenuInfo(id){
		
	}
	

	//编辑菜单
	//参数 菜单mid  位置id 
	function editMenu(mid,menu_order_id){
		$('.null').css('display','none');
		$('.inner').css('display','block');
		$('input[name=menu_order]').val(menu_order_id);
		if(mid=='999999999'){
			$('input[name=m_name]').val('');
			$('input[name=m_type]').val('');
			$('input[name=m_key]').val('');
			$('input[name=m_id]').val(mid);
			return false;
		}else if(mid=='0'){
			$('input[name=m_name]').val('');
			$('input[name=m_type]').val('');
			$('input[name=m_key]').val('');
			$('input[name=m_id]').val('0');
			return false;
		}
	
		$.ajax({
                type: "POST",
                url:"/home/info/menu_ajax.php",
                data:{
					'm_id':mid,
					'act':'select'
				},
                async: false,
                error: function(request) {
                    alert("Connection error");
                },
                success: function(data) {
					if(data.menu_type=='click'){
						$('#uniqueSelect').val('click');
						$('.m_click').click();
					}else{
						$('.m_view').click();
						$('#uniqueSelect').val('view');
					}
					$('input[name=m_name]').val(data.menu_name);
					$('input[name=m_type]').val(data.menu_type);
					$('input[name=m_key]').val(data.menu_key);
					$('input[name=m_id]').val(data.id);
					$('input[name=parent_id]').val(data.parent_id);
				}
			})
	
	}
	
	//提交表单
	function save(event){

		if($('input[name=m_name]').val()=='' || $('input[name=m_name]').val().length>5){
		
			//alert($('input[name=m_name]').val())
			note_info('请填写正确的菜单名称','warn',event);
			return false;
		}
		

	
		var mid = $('input[name=m_id]').val();
		if(mid=='999999999' || mid=='0'){
			$('input[name=act]').val('createMenuDo');
			
		}else{
			$('input[name=act]').val('update');
		}
		var m_r = $('input[name=menu_order]').val();
		var p_i = $('input[name=parent_id]').val();
		
		if(m_r=='' && p_i==''){
			note_info('请选择操作菜单','warn',event);
			return false;
		}
		
		$.ajax({
                type: "POST",
                url:"/home/info/menu_ajax.php",
                data:$('#menu_f').serialize(),
                async: false,
                error: function(request) {
                    alert("Connection error");
                },
                success: function(result) {		
					note_info('保存成功！','success',event);
					$('input[name=m_name]').val('');
					$('input[name=act]').val('');
					$('input[name=mid]').val('');
					$('input[name=parent_id]').val('');
					$('input[name=m_name]').val('');
					$('input[name=m_key]').val('');
					$('input[name=m_type]').val('');
					$('input[name=m_keys0]').val('');
					$('input[name=menu_order]').val('');
				createMenu();
				}
			})
		return false;
	};
	
	function dele(mid,event){
		if(mid=='999999999' || mid=='0'){
			note_info('不能删除空菜单！','warn',event);
			return false;
		}
	

		if(confirm("确定要删除数据吗")){
			
			$.ajax({
                type: "POST",
                url:"/home/info/menu_ajax.php",
                data:{
					'm_id':mid,
					'act':'dele'
				},
                async: false,
                error: function(request) {
                    alert("Connection error");
                },
                success: function(res) {
					note_info('删除成功！','success',event);	
					createMenu();
				}
			})
		}
	}
	//note_info('保存成功！','success',evt);
	//note_info('添加失败！','warn',evt);
	function sm_type(a){
		if(a=='1'){
			$('#unqi').html('URL地址');
		}else{
			$('#unqi').html('绑定规则');
		}
	}
	
<!-- maxiaolei -->
</script>