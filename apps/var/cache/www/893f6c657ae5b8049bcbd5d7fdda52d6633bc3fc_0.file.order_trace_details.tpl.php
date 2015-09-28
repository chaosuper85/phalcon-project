<?php /* Smarty version 3.1.27, created on 2015-09-16 15:47:51
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:39231099755f91ea7c4d104_89075469%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '893f6c657ae5b8049bcbd5d7fdda52d6633bc3fc' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl',
      1 => 1442387184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39231099755f91ea7c4d104_89075469',
  'variables' => 
  array (
    'data' => 0,
    'box_time_line' => 0,
    'item' => 0,
    'i' => 0,
    'j' => 0,
    'detail_length' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f91ea7d427c8_36008453',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f91ea7d427c8_36008453')) {
function content_55f91ea7d427c8_36008453 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '39231099755f91ea7c4d104_89075469';
$_smarty_tpl->tpl_vars['box_time_line'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['box_time_line'], null, 0);?>
<div id="trace_details_content">
	<div class="details">
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
				<?php $_smarty_tpl->tpl_vars['detail_length'] = new Smarty_Variable(count($_smarty_tpl->tpl_vars['item']->value['detail']), null, 0);?>
				<div class="box_detail hidden" data-num="<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
">
					<h3><?php echo $_smarty_tpl->tpl_vars['item']->value['detail_last']['content'];?>
</h3>
					<ul class="content">
						<?php
$_from = $_smarty_tpl->tpl_vars['item']->value['detail'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['content'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['content']->_loop = false;
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['j']->value => $_smarty_tpl->tpl_vars['content']->value) {
$_smarty_tpl->tpl_vars['content']->_loop = true;
$foreach_content_Sav = $_smarty_tpl->tpl_vars['content'];
?>
						<li class="<?php if ($_smarty_tpl->tpl_vars['j']->value == 0) {?>start<?php } elseif ($_smarty_tpl->tpl_vars['detail_length']->value == $_smarty_tpl->tpl_vars['j']->value+1) {?>end<?php } else { ?>process<?php }?> clearfix">
							<div class="timeline">
								<p><?php echo $_smarty_tpl->tpl_vars['content']->value['create_time'];?>
</p>
							</div>
							<div class="traceline">
								<div class="dot"></div>
								<?php if ($_smarty_tpl->tpl_vars['detail_length']->value != $_smarty_tpl->tpl_vars['j']->value+1 && $_smarty_tpl->tpl_vars['detail_length']->value !== 1) {?><div class="line"></div><?php }?>
							</div>
							<div class="info">
								<p><?php echo $_smarty_tpl->tpl_vars['content']->value['content'];?>
</p>
							</div>
						</li>
						<?php
$_smarty_tpl->tpl_vars['content'] = $foreach_content_Sav;
}
?>
					</ul>
				</div>
			<?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
		<?php }?>
		<div class="box_address">
			<i class="icon-mark"></i>
			<label>当前位置：</label>
			<span></span>
		</div>
		<!-- 物流地图开始 -->
		<div id="trace_map"></div>
		<!-- 物流地图结束 -->
	</div>
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=d27d23298c924c68b45ee1f9ef2eddb7"><?php echo '</script'; ?>
>
<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/order_trace/templete/order_trace_details/order_trace_details').init(<?php echo json_encode($_smarty_tpl->tpl_vars['box_time_line']->value);?>
);
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>