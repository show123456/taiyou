<?php /* Smarty version 2.6.19, created on 2015-06-12 14:21:50
         compiled from D:%5Cwamp%5Cwww%5C/home/public/templates/main_menu.html */ ?>
<div class="main-menu" style="min-height:800px;">
	<div class="user-info">
	   <img <?php if ($_SESSION['customer_photo']): ?>src="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/data/image_c/<?php echo $_SESSION['customer_photo']; ?>
" <?php else: ?>src="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/upload/customer/default.png" <?php endif; ?> class="avatar" id="customer_photo" style="margin:5px 0;width:60px;height:60px;" />
		<ul>
			<li><a href="javascript:void(0)" id="customer_group"><?php echo $_SESSION['suser']['name']; ?>
</a></li>
			<li><a href="javascript:void(0)">积分(<span id="customer_cent">0</span>)</a></li>
			<li class="omega"><a href="/home/suser/index.php?a=logout">退出</a></li>
		</ul>
	</div>
	<ul class="menu">
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-display"></i>基础设置</a>
			<ul style="display:none;">
				<!-- <li id="menu_11"><a href="/home/info/follow.php">首次关注设置</a></li>
				<li id="menu_12"><a href="/home/info/auto_reply.php">无匹配回复</a></li>
				<li id="menu_13"><a href="/home/info/menu.php">自定义菜单</a></li>
				<li id="menu_14"><a href="/home/info/index.php">关键词回复设置</a></li>
				<li id="menu_15"><a href="/home/info/bind.php">微信接口配置</a></li>
				<li id="menu_16"><a href="/home/info/auth.php">微信授权配置</a></li> -->
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-data"></i>数据分析</a>
			<ul style="display:none;">
				<!-- <li id="menu_21"><a href="/home/data/index.php">今日概况</a></li> -->
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-member"></i>用户管理</a>
			<ul style="display:none;">
				<li id="menu_31"><a href="/home/user/index.php?type=1">个人用户</a></li>
				<li id="menu_32"><a href="/home/user/index.php?type=2">企业用户</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-list"></i>职位管理</a>
			<ul style="display:none;">
				<li id="menu_41"><a href="/home/task/task.php">职位列表</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-display"></i>分享互动</a>
			<ul style="display:none;">
				<li id="menu_51"><a href="/home/pic/pic.php">互动管理</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-data"></i>积分设置</a>
			<ul style="display:none;">
				<li id="menu_61"><a href="/home/score/index.php?a=qd">签到积分</a></li>
				<li id="menu_62"><a href="/home/score/index.php?a=tjr">返利积分</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-member"></i>微信通道</a>
			<ul style="display:none;">
				<li id="menu_71"><a href="/home/msg/index.php">发送微信信息</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-list"></i>行业管理</a>
			<ul style="display:none;">
				<li id="menu_81"><a href="/home/industry/index.php">行业列表</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-display"></i>金额管理</a>
			<ul style="display:none;">
				<li id="menu_91"><a href="/home/tx/index.php">提现管理</a></li>
				<li id="menu_92"><a href="/home/tx/moneylog.php">金额日志</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-data"></i>评论管理</a>
			<ul style="display:none;">
				<li id="menu_10_1"><a href="/home/reply/talk.php?typenum=1">评论管理</a></li>
			</ul>
		</li>
		<li>
			<a href="javascript:void(0)" onclick="return false;"><i class="icons icons-member"></i>任务兼职</a>
			<ul style="display:none;">
				<li id="menu_11_1"><a href="<?php echo $this->_tpl_vars['WEB_DOMAIN']; ?>
/home/job/index.php">任务兼职管理</a></li>
			</ul>
		</li>
	</ul>
</div>