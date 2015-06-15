<?php /* Smarty version 2.6.19, created on 2015-06-12 14:19:59
         compiled from D:%5Cwamp%5Cwww%5C/home/info/templates/bind.html */ ?>
<div class=" main-content">
    <div class="inner">
        <div class="alert alert-success alert-dismissable fz12px alertfc">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p>
            1. 复制接口配置信息。登陆公众帐号，进入高级功能 -&gt; 开发模式 -&gt; 填写相应的URL和Token进行验证。
            </p>
        </div>

        <div class="infor">

            <p><strong>                    绑定微信公众平台，绑定信息：</strong></p>
            <form class="form-horizontal user-form form-api" role="form">
                <div class="form-group">
                    <label class="col-sm-4 control-label">接口URL</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control input-large" value="http://www.yunfanke.com/api/"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">接口Token</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control input-large" value="<?php echo $this->_tpl_vars['token']; ?>
" />
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>
<script>
    set_title_name('微信接口配置');
    set_menu_cur('menu_15','cur');
</script>