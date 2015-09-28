<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:52
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/product_address_list/product_address_list.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:89708849455fa776c3e7353_78601629%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81164e911dcf243986a4775ecac8855931423d60' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/product_address_list/product_address_list.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '89708849455fa776c3e7353_78601629',
  'variables' => 
  array (
    'data' => 0,
    'orders' => 0,
    'item' => 0,
    'box_type' => 0,
    'box_size_type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa776c4e6321_90331459',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa776c4e6321_90331459')) {
function content_55fa776c4e6321_90331459 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '89708849455fa776c3e7353_78601629';
$_smarty_tpl->tpl_vars['orders'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['assign_list'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['box_type'] = new Smarty_Variable(array('','开顶箱','框架箱','冷冻箱','挂衣箱'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['box_size_type'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>

<div id="product_address_list">
	<div class="clearfix header-content">交易订单>运单<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['orderInfo']['yundan_code'];?>
>打印产装联系单</div>
	<div class="clearfix load-list">
		<a href="/freight/order/export_box_info?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['orderInfo']['orderid'];?>
" target="_blank" class="btn-load user-btn">导出箱号/铅封号</a>
	</div>
	<div class="clearfix list-content">
		<table class="list-product">
			<thead>
				<tr>
					<th width="15%">箱型</th>
					<th width="20%">箱号</th>
					<th width="20%">铅封号</th>
					<th width="10%">司机</th>
					<th width="15%">车牌号</th>
					<th class="operate" width="20%">操作</th>
				</tr>
			</thead>
			<tbody>
                <?php
$_from = $_smarty_tpl->tpl_vars['orders']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                    <tr class="scope">
                        <td><?php echo $_smarty_tpl->tpl_vars['box_type']->value[$_smarty_tpl->tpl_vars['item']->value['box_type']];?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['box_size_type']->value[$_smarty_tpl->tpl_vars['item']->value['box_size_type']];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['box_code'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['box_ensupe'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['driver_name'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['item']->value['car_number'];?>
</td>
                        <td>
                            <a href="/carteam/order/product_address?orderboxid=<?php echo $_smarty_tpl->tpl_vars['item']->value['box_id'];?>
" target="_blank" class="print blue clearfix ">查看产装联系单</a>
                        </td>
                    </tr>
                <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
			</tbody>
		</table>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
require('order/widget/view/product_address_list/product_address_list').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>