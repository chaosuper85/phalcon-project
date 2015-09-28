<?php /* Smarty version 3.1.27, created on 2015-09-16 11:55:38
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/error/error.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:74651147755f8e83a2afd87_66075002%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7a320bf0132f1443215ab35d04dff1a7a4e6bfb' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/error/error.tpl',
      1 => 1442375474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '74651147755f8e83a2afd87_66075002',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f8e83a3151e2_53910744',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f8e83a3151e2_53910744')) {
function content_55f8e83a3151e2_53910744 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '74651147755f8e83a2afd87_66075002';
?>
<div id="error">
    <div class="wrapper">
        <div class="error-content clearfix">
            <div class="left err-img">
                <img src="/static/index/static/image/error.png" alt="error" class="error-img"/>
            </div>
            <div class="left err-info">
                <?php if ($_smarty_tpl->tpl_vars['data']->value && $_smarty_tpl->tpl_vars['data']->value['data'] && $_smarty_tpl->tpl_vars['data']->value['data']['error_msg']) {?>
                  <div class="error-info"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['error_msg'];?>
</div>
                <?php } else { ?>
                  <div class="error-info">您访问的页面找不到。</div>
                <?php }?>
                <div class="error-connection">如有疑问请联系客服电话<strong>400-969-6790</strong></div>
            </div>
        </div>
    </div>
</div><?php }
}
?>