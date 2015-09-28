<?php /* Smarty version 3.1.27, created on 2015-09-16 15:47:51
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/order_trace.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:150487418055f91ea782c8c1_39332077%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cda17e1652ab4b0edb3738e3d31e0386d063ebc7' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_trace/order_trace.tpl',
      1 => 1442387184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '150487418055f91ea782c8c1_39332077',
  'variables' => 
  array (
    'data' => 0,
    'order_time_line' => 0,
    'IS_Ok' => 0,
    'tidan' => 0,
    'freight' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f91ea7abb555_95141926',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f91ea7abb555_95141926')) {
function content_55f91ea7abb555_95141926 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '150487418055f91ea782c8c1_39332077';
$_smarty_tpl->tpl_vars['order_time_line'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_time_line'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['tidan'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['tidan'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['freight'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['freight'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['IS_Ok'] = new Smarty_Variable(1, null, 0);?>
<div id="order_trace">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><a href="/order/list">交易订单</a></li>
			<li>></li>
			<li class="active">物流详情</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 物流流程开始 -->
	<div class="trace_procedure clearfix">
		<ul>
			<li class="step_1 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[1]) && $_smarty_tpl->tpl_vars['order_time_line']->value[1]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<dl>
					<dt>配车</dt>
					<dd><?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[1])) {
echo $_smarty_tpl->tpl_vars['order_time_line']->value[1]['create_time'];
}?></dd>
				</dl>
			</li>
			<li class="line_1 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[2]) && $_smarty_tpl->tpl_vars['order_time_line']->value[2]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<div class="line"></div>
			</li>
			<li class="step_2 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[2]) && $_smarty_tpl->tpl_vars['order_time_line']->value[2]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<dl>
					<dt>提箱</dt>
					<dd><?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[2])) {
echo $_smarty_tpl->tpl_vars['order_time_line']->value[2]['create_time'];
}?></dd>
				</dl>
			</li>
			<li class="line_2 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[3]) && $_smarty_tpl->tpl_vars['order_time_line']->value[3]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<div class="line"></div>
			</li>
			<li class="step_3 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[3]) && $_smarty_tpl->tpl_vars['order_time_line']->value[3]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<dl>
					<dt>产装</dt>
					<dd><?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[3])) {
echo $_smarty_tpl->tpl_vars['order_time_line']->value[3]['create_time'];
}?></dd>
				</dl>
			</li>
			<li class="line_3 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[4]) && $_smarty_tpl->tpl_vars['order_time_line']->value[4]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<div class="line"></div>
			</li>
			<li class="step_4 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[4]) && $_smarty_tpl->tpl_vars['order_time_line']->value[4]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<dl>
					<dt>落箱</dt>
					<dd><?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[4])) {
echo $_smarty_tpl->tpl_vars['order_time_line']->value[4]['create_time'];
}?></dd>
				</dl>
			</li>
			<li class="line_4 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[5]) && $_smarty_tpl->tpl_vars['order_time_line']->value[5]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<div class="line"></div>
			</li>
			<li class="step_5 <?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[5]) && $_smarty_tpl->tpl_vars['order_time_line']->value[5]['ok'] === $_smarty_tpl->tpl_vars['IS_Ok']->value) {?>active<?php }?>">
				<dl>
					<dt>运抵</dt>
					<dd><?php if (isset($_smarty_tpl->tpl_vars['order_time_line']->value[5])) {
echo $_smarty_tpl->tpl_vars['order_time_line']->value[5]['create_time'];
}?></dd>
				</dl>
			</li>
		</ul>
	</div>
	<!-- 物流流程结束 -->
	<!-- 提单信息开始 -->
	<div class="trace_info clearfix">
		<div class="num left">
			<label>提单号：</label>
			<span><?php echo $_smarty_tpl->tpl_vars['tidan']->value;?>
</span>
		</div>
		<div class="company right">
			<label>承运方：</label>
			<span><?php echo $_smarty_tpl->tpl_vars['freight']->value['name'];?>
</span>
			<a href="#/id=<?php echo $_smarty_tpl->tpl_vars['freight']->value['id'];?>
"><i class="icon-msg"></i></a>
			<a href="tel:<?php echo $_smarty_tpl->tpl_vars['freight']->value['contactNumber'];?>
"><i class="icon-phone"></i></a>
		</div>
	</div>
	<!-- 提单信息结束 -->
	<!-- 物流详情开始 -->
	<div class="trace_details clearfix">
	
		<!-- 侧边栏开始 -->
		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl".'"', E_USER_ERROR);}FISResource::load("order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl", $_smarty_tpl->smarty);?>
		<!-- 侧边栏结束 -->

		<!-- 物流详情开始 -->
		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl".'"', E_USER_ERROR);}FISResource::load("order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl", $_smarty_tpl->smarty);?>
		<!-- 物流详情结束 -->
	</div>
	<!-- 物流详情结束 -->
</div>


<?php $fis_script_priority = 0;ob_start();?>
	
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>
























<?php }
}
?>