<?php /* Smarty version 3.1.27, created on 2015-09-16 15:44:30
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_complete/order_complete.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:34071836955f91ddeb3cd53_75754073%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74d8d5594213c50398d80832ac828509b2076202' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_complete/order_complete.tpl',
      1 => 1442387184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '34071836955f91ddeb3cd53_75754073',
  'variables' => 
  array (
    'data' => 0,
    'yundan' => 0,
    'freight' => 0,
    'carteam' => 0,
    'tidan' => 0,
    'box_type_list' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f91ddec2c228_41296621',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f91ddec2c228_41296621')) {
function content_55f91ddec2c228_41296621 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '34071836955f91ddeb3cd53_75754073';
$_smarty_tpl->tpl_vars['freight'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['freight'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['carteam'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['carteam'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['yundan'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['yundan'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['tidan'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['tidan'], null, 0);?>
<?php $_smarty_tpl->tpl_vars['box_type_list'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['box_type_list'], null, 0);?>
<div id="order_complete">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><a href="/order/list">交易订单</a></li>
			<li>></li>
			<li class="active">完善订单</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 订单信息开始 -->
	<div class="clearfix order-number">
		<span class="number-left">日期：<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['create_date'];?>
</span>
		<span class="number-right">运单号<?php echo $_smarty_tpl->tpl_vars['yundan']->value;?>
</span>
	</div>
	<!-- 订单信息结束 -->
	<!-- 委托方/承运商开始 -->
	<div class="carteam_freight">
		<div class="title">
			<h3>委托方／承运方</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="freight_name">
						<span class="name">委托方</span>
					</label>
				</div>
				<div class="item-content">
					<input id="freight_id" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['freight']->value['id'];?>
"/>
					<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['freight']->value['name'];?>
" id="freight_name" name="freight_name" readonly="readonly" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>委托方必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="carteam_name">
						<span class="name">承运方</span>
					</label>
				</div>
				<div class="item-content">
					<input id="carteam_id" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['carteam']->value['id'];?>
"/>
					<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['carteam']->value['name'];?>
" id="carteam_name" name="carteam_name" readonly="readonly" class="" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>承运方必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 委托方/承运商结束 -->
	<!-- 下载文件开始 -->
	<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("order/widget/com/order_file_download/order_file_download.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."order/widget/com/order_file_download/order_file_download.tpl".'"', E_USER_ERROR);}FISResource::load("order/widget/com/order_file_download/order_file_download.tpl", $_smarty_tpl->smarty);?>
	<!-- 下载文件结束 -->
	<!-- 船期开始 -->
	<div class="ship_info">
		<div class="title">
			<h3>船期</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_company">
						<span class="name">船公司</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_company" name="ship_company" placeholder="请输入船公司"/>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船公司必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="tidan">
						<span class="name">提单号</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['tidan']->value;?>
" id="tidan" name="tidan" readonly="readonly" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>提单号必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_name">
						<span class="name">船名</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_name" name="ship_name" placeholder="请输入船名" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船名必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_num">
						<span class="name">船次</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_num" name="ship_num" placeholder="请输入船次" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船次必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_yard">
						<span class="name">指定堆场</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_yard" name="ship_yard" placeholder="请输入指定堆场" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>指定堆场必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_remark">
						<span class="name">备注</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_remark" name="ship_remark" placeholder="请输入备注" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>备注必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 船期结束 -->
	<!-- 货物信息开始 -->
	<div class="product_info">
		<div class="title">
			<h3>货物信息</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_type">
						<span class="name">货物箱型</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<div id="product_type" class="selectBox"></div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物箱型必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item box_type clearfix">
				<div class="item-name">
					<label>
						<span class="name">箱型箱量</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<p class="message-tip">此处箱型箱量请填写提箱单上给出的总箱量</p>
					<div class="box_type_text">
						<ul class="clearfix">
							<li><label for="product_20gp">20GP</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_20gp" id="product_20gp" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
							<li><label for="product_40gp">40GP</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_40gp" id="product_40gp" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
							<li><label for="product_40hg">40HQ</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_40hg" id="product_40hg" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>箱量总数不能为0或不能超过1000000</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_name">
						<span class="name">货物名称</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_name" name="product_name" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物名称必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_weight">
						<span class="name">货物重量</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_weight" name="product_weight" class="weight" />公斤
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物重量必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_remark">
						<span class="name">备注信息</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_remark" name="product_remark" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>备注信息必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 货物信息结束 -->
	<!-- 产装地址开始 -->
	<div class="address_info">
		<div class="title">
			<h3>产装地址</h3>
		</div>
		<div class="content">
			<dl class="address-item clearfix" data-item='1'>
				<dd class="address-title">
					<div class="address-name">
						<h4>产装地址<span class="num">1</span></h4>
					</div>
					<div class="address-tip">
						<p>请详细填写每个产装地址同时间进行产装的的具体箱型箱量</p>
					</div>
					<div class="address-del"></div>
				</dd>
				<dt class="item package_date clearfix">
					<div class="item-name">
						<label for="package_date">
							<span class="name">装箱时间</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<div class="package_date_selectBox data_1 clearfix" data-flag="1"></div>
						<a class="add-date" href="javaScript:">+增加其它装箱时间</a>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>装箱时间必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="package_location">
							<span class="name">装箱地</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<div class="package_location"></div>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>装箱地必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="package_address">
							<span class="name">详细地址</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" class="package_address" id="package_address" name="package_address" />
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>详细地址必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="linkman">
							<span class="name">工厂联系人</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" class="linkman" id="linkman" name="linkman" />
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>工厂联系人必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="contact">
							<span class="name">联系方式</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" id="contact" name="contact" class="contact"/>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>联系方式必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<div class="clearfix address-bottom-line"></div>
			</dl>
			<button class="add-address" type="button">+增加新的装箱地址</button>
		</div>
	</div>
	<!-- 产装地址结束 -->
	<button class="order-save">确认接单</button>
</div>


<?php $fis_script_priority = 0;ob_start();?>
	require('order/widget/view/order_complete/order_complete').init('<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['order_fregiht_id'];?>
',<?php echo json_encode($_smarty_tpl->tpl_vars['box_type_list']->value);?>
);
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>
























<?php }
}
?>