<?php /* Smarty version 3.1.27, created on 2015-09-17 16:20:33
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/header_content/header_content.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:156911445355fa77d164b9d5_63290003%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f2c9cd932b3b84a5f4877252d462f517e67d5ffe' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/header_content/header_content.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156911445355fa77d164b9d5_63290003',
  'variables' => 
  array (
    'class' => 0,
    'title' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa77d1680515_78699247',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa77d1680515_78699247')) {
function content_55fa77d1680515_78699247 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '156911445355fa77d164b9d5_63290003';
?>
<h2 id="header-content">
	<span class=<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
></span>
	<span class="header-title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</span>
	<?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 1) {?>
	    <span class="tipInfo">请完善公司信息，认证通过后可进行交易操作</span>
	<?php }?>
</h2><?php }
}
?>