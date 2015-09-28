<?php /* Smarty version 3.1.27, created on 2015-09-17 17:39:16
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_details/partial/box_info/box_info.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:32344089755fa8a44f3ab74_15132987%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a199c5adecb9ca2ff0543e908a1f62561ca66b5b' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_details/partial/box_info/box_info.tpl',
      1 => 1442480859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32344089755fa8a44f3ab74_15132987',
  'variables' => 
  array (
    'data' => 0,
    'box' => 0,
    'item' => 0,
    'boxType' => 0,
    'j' => 0,
    'box_address_details' => 0,
    'utype' => 0,
    'boxStatus' => 0,
    'i' => 0,
    'status' => 0,
    'addressInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa8a45416865_36968215',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa8a45416865_36968215')) {
function content_55fa8a45416865_36968215 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '32344089755fa8a44f3ab74_15132987';
$_smarty_tpl->tpl_vars['status'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['orderStatus'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['utype'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['user']['usertype'], null, 0);?>

<?php $_smarty_tpl->tpl_vars['box'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['address_assign_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['box'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['address_assign_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_info']['product_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['addressInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_info']['address_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxType'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>

<?php $_smarty_tpl->tpl_vars['boxStatus'] = new Smarty_Variable(array('','待提箱','待产装','待运抵','已落箱，待运抵','已运抵','取消'), null, 0);?>

<div class="boxInfo-wrapper">
	<!-- <h3>箱/车/产装信息</h3> -->
	<table>
		<thead>
			<tr>
				<th width="8%">箱型</th>
				<th width="12%">箱号</th>
				<th width="12%">铅封号</th>
				<th width="10%">司机</th>
				<th width="10%">车牌号</th>
				<th width="32%">装箱详情</th>
				<th width="16%">操作状态</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($_smarty_tpl->tpl_vars['box']->value) > 0 && !empty($_smarty_tpl->tpl_vars['box']->value[0])) {?>
				<?php
$_from = $_smarty_tpl->tpl_vars['box']->value;
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
					<tr>
						<td><?php echo $_smarty_tpl->tpl_vars['boxType']->value[$_smarty_tpl->tpl_vars['item']->value['box_type']];?>
</td>
						<td><?php echo $_smarty_tpl->tpl_vars['item']->value['box_code'];?>
</td>
						<td><?php echo $_smarty_tpl->tpl_vars['item']->value['box_ensupe'];?>
</td>
						<td class="driver">
							<p><?php echo $_smarty_tpl->tpl_vars['item']->value['driver_info']['name'];?>
</p>
							<p><?php echo $_smarty_tpl->tpl_vars['item']->value['driver_info']['mobile'];?>
</p>
						</td>
						<td><?php echo $_smarty_tpl->tpl_vars['item']->value['driver_info']['car_number'];?>
</td>
						<td class="address-details">
							<?php if (isset($_smarty_tpl->tpl_vars['item']->value['address_info']) && !empty($_smarty_tpl->tpl_vars['item']->value['address_info']) && count($_smarty_tpl->tpl_vars['item']->value['address_info']) !== 0) {?>
								<?php
$_from = $_smarty_tpl->tpl_vars['item']->value['address_info'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['box_address_details'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['box_address_details']->_loop = false;
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['j']->value => $_smarty_tpl->tpl_vars['box_address_details']->value) {
$_smarty_tpl->tpl_vars['box_address_details']->_loop = true;
$foreach_box_address_details_Sav = $_smarty_tpl->tpl_vars['box_address_details'];
?>

									<ul class="box-complete <?php if ($_smarty_tpl->tpl_vars['j']->value+1 == count($_smarty_tpl->tpl_vars['item']->value['address_info'])) {?>last<?php }?>">
										<li class="clearfix">
											<div class="address-content">
												<p class="address" title="<?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['box_address_detail'];?>
"><?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['box_address_detail'];?>
</p>
												<p class="time" title="<?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['box_time'];?>
"><?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['box_time'];?>
</p>
											</div>
											<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1) {?>
												<?php if ($_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] == 2 || $_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] == 100) {?>
													<a href="javascript:;" class="complete" data-aid="<?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['assign_id'];?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'];?>
">产装完成</a>
												<?php } else { ?>
													<span class="complete" title="<?php echo $_smarty_tpl->tpl_vars['boxStatus']->value[$_smarty_tpl->tpl_vars['box_address_details']->value['assign_status']];?>
"><?php echo $_smarty_tpl->tpl_vars['boxStatus']->value[$_smarty_tpl->tpl_vars['box_address_details']->value['assign_status']];?>
</span>
												<?php }?>
											<?php } else { ?>
												<span class="complete" title="<?php if ($_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] < 0 && $_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] > 7) {
echo $_smarty_tpl->tpl_vars['boxStatus']->value[$_smarty_tpl->tpl_vars['box_address_details']->value['assign_status']];
} else { ?>司机已产装<?php }?>">
												<?php if ($_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] < 0 && $_smarty_tpl->tpl_vars['box_address_details']->value['assign_status'] > 7) {?>
													<?php echo $_smarty_tpl->tpl_vars['boxStatus']->value[$_smarty_tpl->tpl_vars['box_address_details']->value['assign_status']];?>

												<?php } else { ?>
													司机已产装
												<?php }?>
												</span>
											<?php }?>
										</li>
									</ul>
								<?php
$_smarty_tpl->tpl_vars['box_address_details'] = $foreach_box_address_details_Sav;
}
?>
							<?php }?>
						</td>
						<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1) {?>
							<td class="funcs control hidden">
								<?php if (empty($_smarty_tpl->tpl_vars['item']->value['box_code']) && empty($_smarty_tpl->tpl_vars['item']->value['box_ensupe'])) {?>
									<a href="javascript:;" class="add-ensupe" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
">添加箱号/铅封号</a>
								<?php } else { ?>
									<a href="javascript:;" class="edit-ensupe" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
">修改箱号/铅封号</a>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['item']->value['all_can_change']) {?>
									<?php if (count($_smarty_tpl->tpl_vars['item']->value['address_info']) == 0) {?>
										<a href="javascript:;" class="add-assign" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
">添加司机/装箱信息</a>
									<?php } else { ?>
										<a href="javascript:;" class="edit-assign" data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
">修改司机/装箱信息</a>
									<?php }?>
								<?php }?>
								<a href="<?php if ($_smarty_tpl->tpl_vars['item']->value['box_status'] > 2) {?>/carteam/order/download/product_address?boxid=<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];
} else { ?>javascript:;<?php }?>" class="print <?php if ($_smarty_tpl->tpl_vars['item']->value['box_status'] < 3) {?>disable<?php }?>">打印产装联系单</a>
							</td>
						<?php }?>
						<td class="funcs look <?php if ($_smarty_tpl->tpl_vars['utype']->value == 2) {?>hidden<?php }?>">
							<div class="status-box clearfix">
								<span><?php if ($_smarty_tpl->tpl_vars['item']->value['box_status'] != 100) {
echo $_smarty_tpl->tpl_vars['boxStatus']->value[$_smarty_tpl->tpl_vars['item']->value['box_status']];
} else { ?>司机产装完成<?php }?></span>
								<?php if ($_smarty_tpl->tpl_vars['item']->value['box_status'] > 1) {?><a href="/order/trace?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
#/<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
" target="_blank"><i class="icon-status-message" data-boxid="<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
"></i></a><?php }?>
							</div>
						</td>
					</tr>
				<?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
			<?php }?>
		</tbody>
	</table>
	<?php if ($_smarty_tpl->tpl_vars['status']->value < 3 && $_smarty_tpl->tpl_vars['status']->value != 8) {?>
		<div class="clearfix btn-wrap export_box_info">
			<a href="/freight/order/export_box_info?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
" target="_blank" id="download_box_number" class="right">导出箱号/铅封号</a>
		</div>
	<?php }?>
</div>
<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/order_details/partial/box_info/box_info').init(<?php echo json_encode($_smarty_tpl->tpl_vars['box']->value);?>
,'<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
', <?php echo json_encode($_smarty_tpl->tpl_vars['addressInfo']->value);?>
, '<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['carteam']['carTeam_id'];?>
');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>