<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:36
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/nav_user/nav_user.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:204308747655fa775cde5af1_07217690%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '557ce5296e31e3fd387ecf61e1873a0430a93d1d' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/nav_user/nav_user.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '204308747655fa775cde5af1_07217690',
  'variables' => 
  array (
    'page' => 0,
    'active' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa775ce3f889_23578763',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa775ce3f889_23578763')) {
function content_55fa775ce3f889_23578763 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '204308747655fa775cde5af1_07217690';
$_smarty_tpl->tpl_vars['active'] = new Smarty_Variable(array('','','',''), null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['page']->value)) {?>
	<?php $_smarty_tpl->createLocalArrayVariable('active', null, 0);
$_smarty_tpl->tpl_vars['active']->value[$_smarty_tpl->tpl_vars['page']->value] = 'active';?>
<?php }?>

<ul id="user-nav">
	<li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[0];?>
">
		<a href="/account/personalInfo">
			<span class="nav-icon person"></span>
			个人信息
		</a>
	</li>
	<li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[1];?>
">
		<a href="/account/enterpriseInfo">
			<span class="nav-icon company"></span>
			公司信息
		</a>
	</li>
	<li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[2];?>
">
		<a href="/account/accountSecurity">
			<span class="nav-icon account"></span>
			账户安全
		</a>
	</li>
</ul><?php }
}
?>