<?php /* Smarty version 3.1.27, created on 2015-09-17 17:39:16
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_details/order_details.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:28498852755fa8a44a94471_99118526%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '126ecf646a4f80aa6a2da0e56f7fd0757abfb486' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_details/order_details.tpl',
      1 => 1442480859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28498852755fa8a44a94471_99118526',
  'variables' => 
  array (
    'data' => 0,
    'status' => 0,
    'orderType' => 0,
    'utype' => 0,
    'orderInfo' => 0,
    'box' => 0,
    'boxType' => 0,
    'i' => 0,
    'address' => 0,
    'date' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa8a44eede69_60219690',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa8a44eede69_60219690')) {
function content_55fa8a44eede69_60219690 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '28498852755fa8a44a94471_99118526';
?>

<?php $_smarty_tpl->tpl_vars['status'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['orderStatus'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['utype'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['user']['usertype'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['orderInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['boxType'] = new Smarty_Variable(array('','20GP','40GP','40HQ'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['addressInfo'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['address_assign_info'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['orderType'] = new Smarty_Variable(array('','待确认','待提箱','待装货','待运抵','已落箱','已运抵待评价','交易成功','交易关闭'), null, 0);?>

<div id="order_details">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><h3>陆运服务</h3></li>
			<li><a href="/order/list?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
">订单列表></a></li>
			<li><a href="/order/list">交易订单></a></li>
			<li class="active">运单<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['yundan'];?>
</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 订单信息开始 -->
	<!-- <div class="clearfix order-number">
		<span class="number-left">提单号: <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['tidan'];?>
</span>
		<span class="number-right">运单号<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['yundan'];?>
</span>
	</div> -->
	<!-- 订单信息结束 -->
	<div class="order-title">
		<span class="status"><?php echo $_smarty_tpl->tpl_vars['orderType']->value[$_smarty_tpl->tpl_vars['status']->value];?>
</span>
		<span class="num-tidan">提单号: <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['tidan'];?>
</span>
		<div class="right">
			<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1) {?>
				<p class="name">委托方 : <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['freight']['name'];?>
</p>
				<p class="contact">联系人 : <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['freight']['contactName'];?>
<span><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['freight']['contactNumber'];?>
</span></p>
			<?php } else { ?>
				<p class="name">承运方 : <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['carteam']['name'];?>
</p>
				<p class="contact">联系人 : <?php echo $_smarty_tpl->tpl_vars['data']->value['data']['carteam']['contactName'];?>
<span><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['carteam']['contactNumber'];?>
</span></p>
			<?php }?>
		</div>
	</div>
	<div class="order-funcs clearfix">
		<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1 && ($_smarty_tpl->tpl_vars['status']->value < 6)) {?>
			<a href="javascript:;" id="quit_order" class="order-btn style2">退载</a>
			<a href="/carteam/order/reConstruct_msg?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
" id="reconstruct_orderid" class="order-btn style2">退载重建</a>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['status']->value == 4 || $_smarty_tpl->tpl_vars['status']->value == 5 || $_smarty_tpl->tpl_vars['status']->value == 6) {?>
			<a href="/carteam/order/product_address_list?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
" class="order-btn style1">打印产装联系单</a>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1 && ($_smarty_tpl->tpl_vars['status']->value == 2 || $_smarty_tpl->tpl_vars['status']->value == 3 || $_smarty_tpl->tpl_vars['status']->value == 4 || $_smarty_tpl->tpl_vars['status']->value == 5)) {?>
			<a href="javascript:;" class="order-btn style1" id="btn_dispatch">分配/调度</a>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1 && $_smarty_tpl->tpl_vars['status']->value == 1) {?>
			<a href="/carteam/order/complete?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
" class="order-btn style1">完善订单</a>
		<?php }?>
	</div>
	<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("order/widget/com/order_file_download/order_file_download.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."order/widget/com/order_file_download/order_file_download.tpl".'"', E_USER_ERROR);}FISResource::load("order/widget/com/order_file_download/order_file_download.tpl", $_smarty_tpl->smarty);?>
	<div class="order-wrap dispatch-info <?php if ($_smarty_tpl->tpl_vars['utype']->value == 2 && $_smarty_tpl->tpl_vars['status']->value == 1) {?>hidden<?php }?>">
		<h3 class="second-title">调度信息</h3>
		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("order/widget/view/order_details/partial/box_info/box_info.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."order/widget/view/order_details/partial/box_info/box_info.tpl".'"', E_USER_ERROR);}FISResource::load("order/widget/view/order_details/partial/box_info/box_info.tpl", $_smarty_tpl->smarty);?>
	</div>

	<div class="order-wrap order-info">
		<h3 class="second-title">船务信息<span class="edit-records right" data-orderid="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
"><a href="/order/records?orderid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
" target="_blank">修改纪录</a></span></h3>
		<div class="order-info-wrap clearfix">
			<ul>
				<li><label>起运港</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['dock_city_code'];?>
</span></li>
				<li><label>船公司</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['ship_company_name'];?>
</span></li>
				<li><label>提单号</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['tidan_code'];?>
</span></li>
			</ul>
			<ul>
				<li><label>船名/航次</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['ship_name'];?>
/<?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['ship_ticket'];?>
</span></li>
				<li><label>船期备注</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['ship_ticket_desc'];?>
</span></li>
				<li><label>运抵堆场</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['ship_info']['yard_name'];?>
</span></li>
			</ul>
		</div>
		<h3 class="second-title">货物信息</h3>
		<div class="order-info-wrap clearfix">
			<ul>
				<li><label>货品名称</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['product_info']['product_name'];?>
</span></li>
				<li><label>箱体类型</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['product_info']['product_box_type'];?>
</span></li>
			</ul>
			<ul>
				<li class="box-type clearfix"><label>箱型箱量</label>
					<?php
$_from = $_smarty_tpl->tpl_vars['orderInfo']->value['product_info']['box_type_number'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['box'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['box']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['box']->value) {
$_smarty_tpl->tpl_vars['box']->_loop = true;
$foreach_box_Sav = $_smarty_tpl->tpl_vars['box'];
?>
						<?php if (1 || $_smarty_tpl->tpl_vars['box']->value['number'] != 0) {?>
							<div><span><?php echo $_smarty_tpl->tpl_vars['boxType']->value[$_smarty_tpl->tpl_vars['box']->value['type']];?>
</span> * <?php echo $_smarty_tpl->tpl_vars['box']->value['number'];?>
</div>
						<?php }?>
					<?php
$_smarty_tpl->tpl_vars['box'] = $foreach_box_Sav;
}
?>
				</li>
				<li><label>货物重量</label><span><?php echo $_smarty_tpl->tpl_vars['orderInfo']->value['product_info']['product_weight'];?>
 kg</span></li>
			</ul>
		</div>
		<h3 class="second-title">产装信息
			<?php if ($_smarty_tpl->tpl_vars['utype']->value == 1 && $_smarty_tpl->tpl_vars['orderType']->value > 1 && $_smarty_tpl->tpl_vars['orderType']->value != 8) {?>
				<div class="btn-wrap right">
					<a href="javascript:;" id="modify_productInfo">修改产装信息</a>
				</div>
			<?php }?>
		</h3>
		<div class="order-info-wrap clearfix">
			<?php if (count($_smarty_tpl->tpl_vars['orderInfo']->value['address_info']) != 0) {?>
				<?php
$_from = $_smarty_tpl->tpl_vars['orderInfo']->value['address_info'];
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
					<ul>
						<h4 class="title">产装信息-<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
</h4>
						<li><label>装箱地址</label><span><?php echo $_smarty_tpl->tpl_vars['address']->value['box_address_detail'];?>
</span></li>
						<li><label>联系人</label><span><?php echo $_smarty_tpl->tpl_vars['address']->value['contactName'];?>
</span></li>
						<li><label>联系电话</label><span><?php echo $_smarty_tpl->tpl_vars['address']->value['contactNumber'];?>
</span></li>
						<li>
							<label>装箱日期</label>
							<div class="time">
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
									<p><?php echo $_smarty_tpl->tpl_vars['date']->value['product_supply_time'];?>
</p>
								<?php
$_smarty_tpl->tpl_vars['date'] = $foreach_date_Sav;
}
?>
							</div>
						</li>
					</ul>
					<?php if ($_smarty_tpl->tpl_vars['i']->value%2 == 1) {?>
						<div class="clear"></div>
					<?php }?>
				<?php
$_smarty_tpl->tpl_vars['address'] = $foreach_address_Sav;
}
?>
			<?php }?>
			
			
		</div>
	</div>
	
</div>


<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/order_details/order_details').init(<?php echo json_encode($_smarty_tpl->tpl_vars['orderInfo']->value['address_info']);?>
, '<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
', '<?php echo $_smarty_tpl->tpl_vars['data']->value['dispatch'];?>
');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>

<?php }
}
?>