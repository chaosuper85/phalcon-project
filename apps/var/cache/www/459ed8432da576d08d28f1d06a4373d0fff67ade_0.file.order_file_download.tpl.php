<?php /* Smarty version 3.1.27, created on 2015-09-17 17:39:16
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/com/order_file_download/order_file_download.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:237340555fa8a44ef81d1_46870616%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '459ed8432da576d08d28f1d06a4373d0fff67ade' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/com/order_file_download/order_file_download.tpl',
      1 => 1442480859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '237340555fa8a44ef81d1_46870616',
  'variables' => 
  array (
    'data' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa8a44f310f5_43377289',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa8a44f310f5_43377289')) {
function content_55fa8a44f310f5_43377289 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '237340555fa8a44ef81d1_46870616';
$_smarty_tpl->tpl_vars['file'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['order_files'], null, 0);?>
<!-- 下载文件开始 -->
<div class="clearfix order-files">
	<div class="title">
		<h3>下单文件</h3>
	</div>
	<div class="files-content">
		<ul>
			<li>
				<a id="download-one" href="<?php echo $_smarty_tpl->tpl_vars['file']->value['tixiang'];?>
">查看提箱单</a>
			</li>
			<li>
				<a id="download-two" href="<?php echo $_smarty_tpl->tpl_vars['file']->value['chanzhuang'];?>
">查看产装联系单</a>
			</li>
		</ul>
		<a href="javascript:;" id="download-all">全部下载</a>
	</div>
</div>
<!-- 下载文件结束 -->
<?php $fis_script_priority = 0;ob_start();?>

	require('order/widget/com/order_file_download/order_file_download').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>