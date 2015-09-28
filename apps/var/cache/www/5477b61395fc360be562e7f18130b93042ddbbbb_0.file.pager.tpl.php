<?php /* Smarty version 3.1.27, created on 2015-09-17 16:20:33
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/widget/pager/pager.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:3268780355fa77d168b5a2_75334754%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5477b61395fc360be562e7f18130b93042ddbbbb' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/widget/pager/pager.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3268780355fa77d168b5a2_75334754',
  'variables' => 
  array (
    'total' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa77d16a7030_60846630',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa77d16a7030_60846630')) {
function content_55fa77d16a7030_60846630 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '3268780355fa77d168b5a2_75334754';
?>

<div class="pager-wrap">
    <div class="pager"></div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
    require('common/widget/pager/pager.js').init('<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>