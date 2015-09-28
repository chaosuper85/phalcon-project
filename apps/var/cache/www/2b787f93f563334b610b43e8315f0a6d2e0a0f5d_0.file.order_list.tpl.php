<?php /* Smarty version 3.1.27, created on 2015-09-17 16:20:32
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_list/order_list.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:178050370655fa77d0e72f22_23794230%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2b787f93f563334b610b43e8315f0a6d2e0a0f5d' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_list/order_list.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '178050370655fa77d0e72f22_23794230',
  'variables' => 
  array (
    'data' => 0,
    'status' => 0,
    'pageType' => 0,
    'usertype' => 0,
    'orders' => 0,
    'item' => 0,
    'box' => 0,
    'number' => 0,
    'boxType' => 0,
    'orderType' => 0,
    'REMARK_TXT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa77d163d721_16950951',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa77d163d721_16950951')) {
function content_55fa77d163d721_16950951 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '178050370655fa77d0e72f22_23794230';
$_smarty_tpl->tpl_vars['usertype'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['user']['usertype'], null, 0);?>

<?php $_smarty_tpl->tpl_vars['orders'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_list'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['status'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['orderStatus'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxType'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['orderType'] = new Smarty_Variable(array('全部','待确认','待分派','待提箱','待产装','待运抵','已运抵待评价','交易成功','交易关闭'), null, 0);?>

<?php $_smarty_tpl->tpl_vars['REMARK_TXT'] = new Smarty_Variable(array('','失望','不满','一般','满意','很满意'), null, 0);?>

<?php $_smarty_tpl->tpl_vars['pageType'] = new Smarty_Variable(array('unactive','unactive','unactive','unactive','unactive','unactive','unactive','unactive','unactive'), null, 0);?>
<?php $_smarty_tpl->createLocalArrayVariable('pageType', null, 0);
$_smarty_tpl->tpl_vars['pageType']->value[$_smarty_tpl->tpl_vars['status']->value] = 'active';?>

<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header_content/header_content.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('title'=>"订单列表",'class'=>"order-list"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header_content/header_content.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header_content/header_content.tpl", $_smarty_tpl->smarty);?>
<div id="order-list" class="user-content">
	<table class="nav">
		<tr class="nav">
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[0];?>
"><a href="/order/list">全部订单<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][0];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[1];?>
"><a href="/order/list?status=1">待确认<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][1];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[3];?>
"><a href="/order/list?status=3">待提箱<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][3];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[4];?>
"><a href="/order/list?status=4">待产装<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][4];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[5];?>
"><a href="/order/list?status=5">待运抵<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][5];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[6];?>
"><a href="/order/list?status=6">待评价<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][6];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[7];?>
"><a href="/order/list?status=7">交易成功<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][7];?>
)</span></a></td>
			<td class="cut"></td>
			<td class="<?php echo $_smarty_tpl->tpl_vars['pageType']->value[8];?>
"><a href="/order/list?status=8">交易关闭<span>(<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['total_count'][8];?>
)</span></a></td>
			<td class="cut"></td>
		</tr>
	</table>
	<form id="order_search" method="GET" action="/order/list" class="order-search-form">
		<input type="text" id="q" class="search-box" placeholder="<?php if ($_smarty_tpl->tpl_vars['usertype']->value == 1) {?>委托方<?php } else { ?>承运方<?php }?>" autocomplete="off" name="searchValue" aria-haspopup="true" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['searchValue'];?>
"></input>
		<div class="select-container"><div id="searchTypeSelector"></div></div>
		<input type="hidden" name="searchType" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['searchType'] || 1;?>
"/>
		<input type="submit" class="search-btn" value="搜索">
		<span class="search-icon"></span>
		<a href="javascript:;" class="clear-btn">×</a>
		<label class="hide-text" for="q"></label>
	</form>

	<table class="content">
		<thead>
			<tr>
				<th width="15%"><?php if ($_smarty_tpl->tpl_vars['usertype']->value == 1) {?>委托方<?php } else { ?>承运方<?php }?></th>
				<th width="15%">船信息</th>
				<th width="13%">箱型/箱量</th>
				<th width="15%">装货地</th>
				<th width="14%">装/卸货时间</th>
				<th width="13%">订单状态</th>
				<th width="15%">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($_smarty_tpl->tpl_vars['orders']->value) == 0) {?>
			<?php } else { ?>
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
					<tr class="item-head">
						<th colspan="7">
							<span class="left">提单号: <?php echo $_smarty_tpl->tpl_vars['item']->value['tidan_code'];?>
</span>
							<span class="left"><?php if ($_smarty_tpl->tpl_vars['item']->value['create_time'] != '0000-00-00 00:00:00') {?>成单时间: <?php echo $_smarty_tpl->tpl_vars['item']->value['create_time'];
}?></span>
							<span class="right">运单号: <?php echo $_smarty_tpl->tpl_vars['item']->value['yundan_code'];?>
</span>
						</th>
					</tr>
					<tr class="item-content">
						<td>
							<p class="top"><?php echo $_smarty_tpl->tpl_vars['item']->value['company_name'];?>
</p>
							<p class="bottom"><?php echo $_smarty_tpl->tpl_vars['item']->value['contactName'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['contactNumber'];?>
</p>
							<span class="icon-export"></span>  
						</td>
						<td>
							<p>
								<?php if ($_smarty_tpl->tpl_vars['item']->value['ship_info']['full_english_name']) {?>
									<?php echo $_smarty_tpl->tpl_vars['item']->value['ship_info']['full_english_name'];?>

								<?php } else { ?>
									<p>待完善</p>
								<?php }?>
							</p>
						</td>
						<td>
							<?php $_smarty_tpl->tpl_vars['number'] = new Smarty_Variable(0, null, 0);?>
							<?php
$_from = $_smarty_tpl->tpl_vars['item']->value['box_info'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['box'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['box']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['box']->value) {
$_smarty_tpl->tpl_vars['box']->_loop = true;
$foreach_box_Sav = $_smarty_tpl->tpl_vars['box'];
?>
								<?php if ($_smarty_tpl->tpl_vars['box']->value['box_number'] != 0) {?>
									<?php $_smarty_tpl->tpl_vars['number'] = new Smarty_Variable($_smarty_tpl->tpl_vars['number']->value+1, null, 0);?>
									<p><?php echo $_smarty_tpl->tpl_vars['box']->value['box_number'];?>
 * <span class="box-type"><?php echo $_smarty_tpl->tpl_vars['boxType']->value[$_smarty_tpl->tpl_vars['box']->value['box_type']];?>
</span></p>
								<?php }?>
							<?php
$_smarty_tpl->tpl_vars['box'] = $foreach_box_Sav;
}
?>
							<?php if ($_smarty_tpl->tpl_vars['number']->value == 0) {?>
								<p>待完善</p>
							<?php }?>
						</td>
						<td>
							<?php if (count($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'])) {?>
								<p><?php echo $_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['address'];?>
</p>
								<?php if (count($_smarty_tpl->tpl_vars['item']->value['product_adress_detail']) >= 2) {?>
									<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" class="blue" target="_blank">更多地址</a>
								<?php }?>
							<?php } else { ?>
								<p>待完善</p>
							<?php }?>
						</td>
						<td>
							<?php if (count($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'])) {?>
								<?php if (isset($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['supply_time']) && count($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['supply_time'])) {?>
									<p><?php echo $_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['supply_time'][0];?>
</p>
								<?php }?>
								<?php if (isset($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['supply_time']) && count($_smarty_tpl->tpl_vars['item']->value['product_adress_detail'][0]['supply_time']) >= 2) {?>
									<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" class="blue" target="_blank">更多时间</a>
								<?php }?>
							<?php } else { ?>
								<p>待完善</p>
							<?php }?>
						</td>
						<td>
							<p class="orange"><?php if ($_smarty_tpl->tpl_vars['item']->value['status'] != 2) {
echo $_smarty_tpl->tpl_vars['orderType']->value[$_smarty_tpl->tpl_vars['item']->value['status']];
} else { ?>待提箱<?php }?></p>
							<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" class="blue clearfix" target="_blank">订单详情</a>
							<?php if ($_smarty_tpl->tpl_vars['item']->value['status'] > 3 && $_smarty_tpl->tpl_vars['item']->value['status'] != 8) {?><a href="/order/trace?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" class="blue clearfix" target="_blank">物流详情</a><?php }?>
						</td>
						<td class="last">
							<?php if ($_smarty_tpl->tpl_vars['usertype']->value == 2) {?>
								<!-- 货代 -->
								<?php if ($_smarty_tpl->tpl_vars['item']->value['status'] == 3) {?>
									<a href="javascript:;" class="blue user-btn clearfix">下载箱号/铅封号</a>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 6) {?>
									<a href="javascript:;" class="blue user-btn btn_remark clearfix" data-odid="<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
">评价</a>
								<?php }?>
								
							<?php } else { ?>
								<!-- 车队 -->
								<?php if ($_smarty_tpl->tpl_vars['item']->value['status'] == 1) {?>
									<a href="/carteam/order/complete?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" target="_blank" class="blue clearfix">完善订单</a>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 2) {?>
									<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
&dispatch=1" target="_blank" class="blue clearfix">分配/调度</a>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 3) {?>
									<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
&dispatch=1" target="_blank" class="blue clearfix">分配/调度</a>
									<a href="/order/details?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" target="_blank" class="blue clearfix">产装确认</a>
									<a href="/carteam/order/product_address_list?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" target="_blank" class="blue clearfix">打印产装联系单</a>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 4) {?>
									<a href="/carteam/order/product_address_list?orderid=<?php echo $_smarty_tpl->tpl_vars['item']->value['orderid'];?>
" class="blue clearfix">打印产装联系单</a>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 5) {?>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 6) {?>
								<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['status'] == 7) {?>
									<?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent']) {?>
										<ul class="star-wrapper clearfix" title="<?php echo $_smarty_tpl->tpl_vars['REMARK_TXT']->value[$_smarty_tpl->tpl_vars['item']->value['order_total_percent']];?>
">
											<li <?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent'] > 0) {?>class="on"<?php }?>><span></span></li>
											<li <?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent'] > 1) {?>class="on"<?php }?>><span></span></li>
											<li <?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent'] > 2) {?>class="on"<?php }?>><span></span></li>
											<li <?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent'] > 3) {?>class="on"<?php }?>><span></span></li>
											<li <?php if ($_smarty_tpl->tpl_vars['item']->value['order_total_percent'] > 4) {?>class="on"<?php }?>><span></span></li>
										</ul>
									<?php }?>
								<?php }?>
							<?php }?>
						</td>
					</tr>
				<?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
			<?php }?>
		</tbody>
	</table>
	<?php if (count($_smarty_tpl->tpl_vars['orders']->value) == 0) {?>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['user']['usertype'] == 2) {?>
				<?php if ($_smarty_tpl->tpl_vars['data']->value['data']['search'] == 0 && $_smarty_tpl->tpl_vars['data']->value['data']['orderStatus'] == 0) {?>
					<div id="no_order_list_f" class="">
						<div>
							<p>暂无订单，请<a class="choose_order" href="/freight/order/choose" target="_blank">发起订单</a></p>
							<p class="tel-text"></p>
						</div>
					</div>
				<?php } else { ?>
					<div id="no_order_list_f" class="">
						<div>
							<p>未找到订单</p>
							<p class="tel-text"></p>
						</div>
					</div>
				<?php }?>
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['data']->value['data']['search'] == 0 && $_smarty_tpl->tpl_vars['data']->value['data']['orderStatus'] == 0) {?>
				<div id="no_order_list_c" class="">
					<div>
						<p>暂无订单，可邀请货代客户下单，或联系客服添加货代客户</p>
						<p class="tel-text">客服电话<a href="tel:400-8666">400-8666</a></p>
					</div>
				</div>
			<?php } else { ?>
					<div id="no_order_list_f" class="">
						<div>
							<p>未找到订单</p>
							<p class="tel-text"></p>
						</div>
					</div>
				<?php }?>
		<?php }?>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['data']->value['data']['total_page'] >= 2) {?>
		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("common/widget/pager/pager.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('total'=>$_smarty_tpl->tpl_vars['data']->value['data']['total_page']), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."common/widget/pager/pager.tpl".'"', E_USER_ERROR);}FISResource::load("common/widget/pager/pager.tpl", $_smarty_tpl->smarty);?>
	<?php }?>
</div>


<?php $fis_script_priority = 0;ob_start();?>
require('order/widget/view/order_list/order_list').init('<?php echo $_smarty_tpl->tpl_vars['usertype']->value;?>
', '<?php if (!empty($_smarty_tpl->tpl_vars['data']->value['data']['searchType'])) {
echo $_smarty_tpl->tpl_vars['data']->value['data']['searchType'];
}?>');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>