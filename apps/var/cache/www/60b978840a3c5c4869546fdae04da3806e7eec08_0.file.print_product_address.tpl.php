<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:56
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/print_product_address/print_product_address.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:128036107655fa7770644f85_93704810%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60b978840a3c5c4869546fdae04da3806e7eec08' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/print_product_address/print_product_address.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '128036107655fa7770644f85_93704810',
  'variables' => 
  array (
    'data' => 0,
    'box' => 0,
    'shipInfo' => 0,
    'productInfo' => 0,
    'boxType' => 0,
    'boxName' => 0,
    'addressInfo' => 0,
    'i' => 0,
    'address' => 0,
    'date' => 0,
    'carteamInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa7770831963_23856966',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa7770831963_23856966')) {
function content_55fa7770831963_23856966 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '128036107655fa7770644f85_93704810';
$_smarty_tpl->tpl_vars['box'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['shipInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['ship_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['productInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['product_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['addressInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['address_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['carteamInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['carteam_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxType'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxName'] = new Smarty_Variable(array('','开顶箱','框架箱','冷冻箱','挂衣箱'), null, 0);?>

<div id="print_product_address">
	<!-- <?php echo print_r($_smarty_tpl->tpl_vars['data']->value);?>
 -->
	<div class="print-header">
		<h3>陆运服务</h3>
		<span> 交易订单> 运单<?php echo $_smarty_tpl->tpl_vars['box']->value['yundan_code'];?>
> 打印产装联系单 > 产装联系单详情</span>
	</div>
	<div class="download">
		<a href="/carteam/order/download/product_address?boxid=<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
" id="download_address" class="btn-download">下载产装单</a>
	</div>
	<div class="print-content">
		<div class="print-title">
			<h2><?php echo $_smarty_tpl->tpl_vars['box']->value['carrier_company_name'];?>
</h2>
			<p>装货联系单</p>
		</div>
		<div class="print-date">
			<div class="left">
				<label>日期：</label>
				<p><?php echo $_smarty_tpl->tpl_vars['box']->value['create_time'];?>
</p>
			</div>
			<div class="right">
				<label>运单号：</label>
				<p><?php echo $_smarty_tpl->tpl_vars['box']->value['yundan_code'];?>
</p>
			</div>
		</div>
		<div class="print-table">
			<table>
				<tbody>
					<tr class="tr-title">
						<td colspan="4"><h3>船信息</h3></td>
					</tr>
					<tr>
						<td class="title">起运港：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['dock_city_code'];?>
</td>
						<td class="title">船名/航次:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['ship_name'];?>
</td>
					</tr>
					<tr>
						<td class="title">船公司：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['ship_company_name'];?>
</td>
						<td class="title">船期:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['ship_ticket'];?>
</td>
					</tr>
					<tr>
						<td class="title">提单号：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['tidan_code'];?>
</td>
						<td class="title">运抵堆场:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['shipInfo']->value['yard_name'];?>
</td>
					</tr>
					<tr class="tr-title">
						<td colspan="4"><h3>货物信息</h3></td>
					</tr>
					<tr>
						<td class="title">货品名称：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['productInfo']->value['product_name'];?>
</td>
						<td class="title">箱型:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['boxType']->value[$_smarty_tpl->tpl_vars['productInfo']->value['box_type_number']['box_size_type']];?>
  <span><?php echo $_smarty_tpl->tpl_vars['boxName']->value[$_smarty_tpl->tpl_vars['productInfo']->value['box_type_number']['product_box_type']];?>
</span></td>
					</tr>
					<tr>
						<td class="title">箱体类型：</td>
						<td>普通货箱</td>
						<td class="title">货物重量:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['productInfo']->value['product_weight'];?>
</td>
					</tr>
					<?php
$_from = $_smarty_tpl->tpl_vars['addressInfo']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['address'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['address']->_loop = false;
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['address']->value) {
$_smarty_tpl->tpl_vars['address']->_loop = true;
$foreach_address_Sav = $_smarty_tpl->tpl_vars['address'];
?>
					<tr class="tr-title">
						<td colspan="4"><h3>装货信息-<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
</h3></td>
					</tr>
					<tr>
						<td class="title">装箱日期：</td>
						<td class="date">
							<?php
$_from = $_smarty_tpl->tpl_vars['address']->value['box_date'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['date']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['date']->value) {
$_smarty_tpl->tpl_vars['date']->_loop = true;
$foreach_date_Sav = $_smarty_tpl->tpl_vars['date'];
?>
								<p><?php echo $_smarty_tpl->tpl_vars['date']->value;?>
</p>
							<?php
$_smarty_tpl->tpl_vars['date'] = $foreach_date_Sav;
}
?>
						</td>
						<td class="title">到厂时间:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">装箱地址：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['address']->value['box_address'];?>
</td>
						<td class="title">签字:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">联系人：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['address']->value['contactName'];?>
</td>
						<td class="title">离厂时间:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">联系方式：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['address']->value['contactNumber'];?>
</td>
						<td class="title">签字:</td>
						<td></td>
					</tr>
					<?php
$_smarty_tpl->tpl_vars['address'] = $foreach_address_Sav;
}
?>
					<tr class="tr-title">
						<td colspan="4"><h3>调度信息</h3></td>
					</tr>
					<tr>
						<td class="title">司机姓名：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['carteamInfo']->value['name'];?>
</td>
						<td class="title">车牌号:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['carteamInfo']->value['car_number'];?>
</td>
					</tr>
					<tr>
						<td class="title">箱号：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['carteamInfo']->value['box_number'];?>
</td>
						<td class="title">铅封号:</td>
						<td><?php echo $_smarty_tpl->tpl_vars['box']->value['box_ensupe'];?>
</td>
					</tr>
					<tr>
						<td class="title">箱型：</td>
						<td><?php echo $_smarty_tpl->tpl_vars['boxType']->value[$_smarty_tpl->tpl_vars['carteamInfo']->value['box_type']];?>
</td>
						<td class="title"></td>
						<td></td>
					</tr>
					<tr class="tr-title">
						<td colspan="4"><h3>备注：</h3></td>
					</tr>
					<tr>
						<td colspan="4" class="notice">
							<p></p>
							<p></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="bottom-print">
			<a href="/carteam/order/download/product_address?boxid=<?php echo $_smarty_tpl->tpl_vars['box']->value['id'];?>
" id="print_address" class="btn-download">打印产装单</a>
		</div>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/print_product_address/print_product_address').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>









<?php }
}
?>