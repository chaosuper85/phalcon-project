<?php /* Smarty version 3.1.27, created on 2015-09-16 15:47:51
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:114057286555f91ea7ac4031_43438701%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f7ff115ef6a09c212fa44645be9d8a91be2d308' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl',
      1 => 1442387184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '114057286555f91ea7ac4031_43438701',
  'variables' => 
  array (
    'data' => 0,
    'box_time_line' => 0,
    'i' => 0,
    'item' => 0,
    'BOX_STATUS' => 0,
    'BOX_TYPE' => 0,
    'BOX_SIZE_TYPE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f91ea7c41460_28328609',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f91ea7c41460_28328609')) {
function content_55f91ea7c41460_28328609 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '114057286555f91ea7ac4031_43438701';
$_smarty_tpl->tpl_vars['box_time_line'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['box_time_line'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['BOX_TYPE'] = new Smarty_Variable(array('','开顶箱','框架箱','冷冻箱','挂衣箱'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['BOX_SIZE_TYPE'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['BOX_STATUS'] = new Smarty_Variable(array('','待产装','待落箱','待运抵','已落箱，待运抵','已运抵','取消'), null, 0);?>
<!-- 侧边栏开始 -->
<div id="order_trace_sidebar">
	<ul class="clearfix">
		<?php if (count($_smarty_tpl->tpl_vars['box_time_line']->value) !== 0) {?>
			<?php
$_from = $_smarty_tpl->tpl_vars['box_time_line']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
				<li class="clearfix">
					<a href="#/<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
">
						<div class="sequenced"><?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
</div>
						<div class="status"><?php if ($_smarty_tpl->tpl_vars['item']->value['box_status'] != -1 && !empty($_smarty_tpl->tpl_vars['item']->value['box_status'])) {
echo $_smarty_tpl->tpl_vars['BOX_STATUS']->value[$_smarty_tpl->tpl_vars['item']->value['box_status']];
}?></div>
						<div class="info clearfix">
							<div class="box_type item">
								<label>箱型</label>
								<span><?php if ($_smarty_tpl->tpl_vars['item']->value['box_type'] && $_smarty_tpl->tpl_vars['item']->value['box_size_type']) {
echo $_smarty_tpl->tpl_vars['BOX_TYPE']->value[$_smarty_tpl->tpl_vars['item']->value['box_type']];?>
 <?php echo $_smarty_tpl->tpl_vars['BOX_SIZE_TYPE']->value[$_smarty_tpl->tpl_vars['item']->value['box_size_type']];
}?></span>
							</div>
							<div class="box_address item">
								<label>装箱地</label>
								<span title="<?php if (isset($_smarty_tpl->tpl_vars['item']->value['box_address'])) {
echo $_smarty_tpl->tpl_vars['item']->value['box_address'];
}?>"><?php if (isset($_smarty_tpl->tpl_vars['item']->value['box_address'])) {
echo $_smarty_tpl->tpl_vars['item']->value['box_address'];
}?></span>
							</div>
							<div class="box_driver item">
								<label>司机</label>
								<span><?php if (isset($_smarty_tpl->tpl_vars['item']->value['driver_name'])) {
echo $_smarty_tpl->tpl_vars['item']->value['driver_name'];
}?></span>
							</div>
							<div class="box_mobile item">
								<label>手机</label>
								<span><?php if (isset($_smarty_tpl->tpl_vars['item']->value['driver_mobile'])) {
echo $_smarty_tpl->tpl_vars['item']->value['driver_mobile'];
}?></span>
							</div>
						</div>
					</a>
				</li>
			<?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
		<?php }?>
	</ul>
</div>
<!-- 侧边栏结束 -->
<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>


















<?php }
}
?>