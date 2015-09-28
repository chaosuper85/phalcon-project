<?php /* Smarty version 3.1.27, created on 2015-09-17 16:20:32
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/nav_service/nav_service.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:51376133255fa77d0da1789_21704178%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67b3653c0be49cfdf2cbcc880d59fa4093df719a' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/nav_service/nav_service.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51376133255fa77d0da1789_21704178',
  'variables' => 
  array (
    'data' => 0,
    'page' => 0,
    'active' => 0,
    'user_info' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa77d0e6c103_94555800',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa77d0e6c103_94555800')) {
function content_55fa77d0e6c103_94555800 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '51376133255fa77d0da1789_21704178';
$_smarty_tpl->tpl_vars['active'] = new Smarty_Variable(array('','','',''), null, 0);?>
<?php $_smarty_tpl->tpl_vars['user_info'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['user'], null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['page']->value)) {?>
	<?php $_smarty_tpl->createLocalArrayVariable('active', null, 0);
$_smarty_tpl->tpl_vars['active']->value[$_smarty_tpl->tpl_vars['page']->value] = 'active';?>
<?php }?>

<ul id="user-nav">
	<li class="title">订单管理</li>
		<li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[1];?>
">
			<a href="/order/list">
				<span class="nav-icon order-list"></span>
				交易订单
			</a>
		</li>
		<?php if (!empty($_smarty_tpl->tpl_vars['user_info']->value) && $_smarty_tpl->tpl_vars['user_info']->value['usertype'] == 2) {?>
			<li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[0];?>
">
				<a href="/freight/order/choose" target="_blank">
					<span class="nav-icon new"></span>
					发起订单
				</a>
			</li>
		<?php }?>
        <?php if (!empty($_smarty_tpl->tpl_vars['user_info']->value) && $_smarty_tpl->tpl_vars['user_info']->value['usertype'] == 1) {?>
            <li class="item <?php echo $_smarty_tpl->tpl_vars['active']->value[0];?>
">
                <a href="/carteam/order/car_manage">
                    <span class="nav-icon car"></span>
                    车辆管理
                </a>
            </li>
        <?php }?>
</ul><?php }
}
?>