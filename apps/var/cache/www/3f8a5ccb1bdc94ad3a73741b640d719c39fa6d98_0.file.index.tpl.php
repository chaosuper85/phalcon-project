<?php /* Smarty version 3.1.27, created on 2015-09-17 19:20:46
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/index/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:63612808455faa20e57c1c3_88920201%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f8a5ccb1bdc94ad3a73741b640d719c39fa6d98' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/index/index.tpl',
      1 => 1442487660,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63612808455faa20e57c1c3_88920201',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55faa20e5b07d8_07672137',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faa20e5b07d8_07672137')) {
function content_55faa20e5b07d8_07672137 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '63612808455faa20e57c1c3_88920201';
?>
<div class="banner-bg">
	<img src="/static/index/static/image/bg-banner.jpg"/>
</div>
<div id="home">
	<div class="banner-content clearfix">
		<div class="panel">
			<div class="panel-content">
				<?php if (empty($_smarty_tpl->tpl_vars['data']->value['user'])) {?>
					<h3><a href="/index/login">已有帐号？立即登录</a></h3>
					<a href="/user/register?type=freight_agent" class="btn agent">
						<span></span>
						货代注册
					</a>
					<a href="/user/register?type=carteam" class="btn carteam">
						<span></span>
						车队注册
					</a>
				<?php } else { ?>
					已登录
				<?php }?>
			</div>
			<div class="panel-bg"></div>
		</div>
	</div>
	<div class="content">
		<div class="wrapper">
			<div class="notice">
				<p>箱典典<span>让集装箱物流更简单</span></p>
			</div>
			<div class="picture-notice">
				<div class="picture-content clearfix">
					<div class="picture-order"></div>
					<p class="order">一键派单 <span>全程掌控</span></p>
				</div>
				<div class="picture-content clearfix">
					<div class="picture-trace"></div>
					<p class="trace">一键派单 <span>全程掌控</span></p>
				</div>
				<div class="picture-content clearfix">
					<div class="picture-optimize"></div>
					<p class="optimize">一键派单 <span>全程掌控</span></p>
				</div>

		<!-- 		<ul>
					<li>
						<div class="picture-order"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
					<li>
						<div class="picture-trace"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
					<li>
						<div class="picture-optimize"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
				</ul> -->
			</div>
		</div>
		<div class="bottom-banner">
			<div class="bottom-content">
				<div class="left-content clearfix"></div>
				<div class="center-content clearfix">
					<div class="xdd-logo">
						<div class="logo"></div>
						<p>箱典典</p>
					</div>
					<div class="app-notice">
						<p class="title">司机版APP</p>
						<p>随时给您的司机派单</p>
						<p>事实追踪货物的运输情况</p>
					</div>
				</div>
				<div class="right-content clearfix">
					<p>扫一扫，即可下载</p>
					<div class="quickmark"></div>
					<a href="javascript:;">Android下载</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
	require('index/widget/view/index/index').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>