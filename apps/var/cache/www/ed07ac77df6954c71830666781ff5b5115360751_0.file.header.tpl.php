<?php /* Smarty version 3.1.27, created on 2015-09-17 20:12:22
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/header/header.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:93222874555faae262ec859_29628181%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed07ac77df6954c71830666781ff5b5115360751' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/header/header.tpl',
      1 => 1442488890,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '93222874555faae262ec859_29628181',
  'variables' => 
  array (
    'page' => 0,
    'home_class' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55faae263585d1_93712316',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faae263585d1_93712316')) {
function content_55faae263585d1_93712316 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '93222874555faae262ec859_29628181';
$_smarty_tpl->tpl_vars['home_class'] = new Smarty_Variable('', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['page']->value) && $_smarty_tpl->tpl_vars['page']->value == 'home') {?>
	<?php $_smarty_tpl->tpl_vars['home_class'] = new Smarty_Variable("home", null, 0);?>
<?php }?>

<div class="header-second <?php echo $_smarty_tpl->tpl_vars['home_class']->value;?>
">
	<a href="/"><img src="/static/common/static/image/logo.png" alt="56xdd.com" class="logo"/></a>
	<div class="right">
		<?php if (empty($_smarty_tpl->tpl_vars['data']->value['user'])) {?>
			<a id="login-btn" href="javascript:;">登录</a>
			<a href="/user/register">注册</a>
		<?php } else { ?>
			<a href="/account/personalInfo"><?php echo $_smarty_tpl->tpl_vars['data']->value['user']['username'];?>
</a>
			<a href="javascript:;" id="logout">退出</a>
		<?php }?>
		<a class="header-app" href="javascript:;"><i></i>App下载</a>
		<a href="javascript:;" class="service-phone"><i></i>400-969-6790</a>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
	require('index/widget/header/header').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;

}
}
?>