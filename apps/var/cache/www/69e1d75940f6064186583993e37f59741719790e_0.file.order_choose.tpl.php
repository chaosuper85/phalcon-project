<?php /* Smarty version 3.1.27, created on 2015-09-16 16:03:55
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_choose/order_choose.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:103113618455f9226b0468b1_52259813%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '69e1d75940f6064186583993e37f59741719790e' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_choose/order_choose.tpl',
      1 => 1442390601,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '103113618455f9226b0468b1_52259813',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f9226b07c101_89195700',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f9226b07c101_89195700')) {
function content_55f9226b07c101_89195700 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '103113618455f9226b0468b1_52259813';
if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header_content/header_content.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('title'=>"选择进出口",'class'=>"new"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header_content/header_content.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header_content/header_content.tpl", $_smarty_tpl->smarty);?>
<div id="order-choose">
    <div class="order-content">
        <div class="docks clearfix">
            <div class="title">起运城市：</div>
            <div id="docks-selector">
            </div>
        </div>
		<dl class="clearfix">
			<dd>
				<div class="content left">
					<a href="javascript:;" class="user-btn current" id="choose-out">出口</a>
				</div>
			</dd>
			<dd>
                <div class="content">
                    <a href="javascript:;" class="user-btn" id="choose-in">进口</a>
                </div>
            </dd>
        </dl>
        <div class="btn-wrap">
            <a href="/freight/order/new" class="user-btn">确定</a>
        </div>

    </div>
</div>


<?php $fis_script_priority = 0;ob_start();?>
        console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
)
	require('order/widget/view/order_choose/order_choose').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>