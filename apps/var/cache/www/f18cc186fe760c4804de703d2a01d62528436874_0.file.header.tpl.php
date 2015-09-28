<?php /* Smarty version 3.1.27, created on 2015-09-17 17:39:16
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/header/header.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:76297492855fa8a449f3473_33281193%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f18cc186fe760c4804de703d2a01d62528436874' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/header/header.tpl',
      1 => 1442480859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '76297492855fa8a449f3473_33281193',
  'variables' => 
  array (
    'page' => 0,
    'data' => 0,
    'active' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa8a44a8a880_32198218',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa8a44a8a880_32198218')) {
function content_55fa8a44a8a880_32198218 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '76297492855fa8a449f3473_33281193';
$_smarty_tpl->tpl_vars['active'] = new Smarty_Variable(array('','','',''), null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['page']->value)) {?>
	<?php $_smarty_tpl->createLocalArrayVariable('active', null, 0);
$_smarty_tpl->tpl_vars['active']->value[$_smarty_tpl->tpl_vars['page']->value] = 'active';?>
<?php }?>

<div class="header-wrapper">
	<div id="user-header" class="wrapper-wide">
		<a href="/" class="logo">
			<img src="/static/user/static/image/logo_orange.png" alt="56xdd.com"/>
		</a>
		<div class="nav clearfix">
			<ul class="left">
				<?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 4) {?><li><a href="/order/list" class="<?php echo $_smarty_tpl->tpl_vars['active']->value[0];?>
">陆运服务</a></li><?php }?>
				<li><a href="/account/personalInfo" class="<?php echo $_smarty_tpl->tpl_vars['active']->value[2];?>
">帐号管理</a></li>
			</ul>
			<ul class="right">
				<li><a href="javascript:;" class="nav-help" title="使用帮助"><div class="icon"></div></a></li>
				<li><a href="javascript:;" class="nav-app" title="APP"><div class="icon"></div><span>APP</span></a></li>
				<li class="user-func">
					<a href="javascript:;" class="clearfix">
						<div class="avatar"></div>
						<span class="username"><?php echo $_smarty_tpl->tpl_vars['data']->value['user']['username'];?>
</span>
						<span class="arrow"></span>
						<div class="user-menu">
							<span id="ucenter" href="javascript:;">个人中心</span>
							<span id="logout" href="javascript:;">退出账户</span>
						</div>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
	require('user/widget/header/header').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;

}
}
?>