<?php /* Smarty version 3.1.27, created on 2015-09-10 15:42:21
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/car_manage/car_manage.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:132059510255f1345d91db12_90839884%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ed49c1befacf299a48cdf059e4e21a6fad63699' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/car_manage/car_manage.tpl',
      1 => 1441868864,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '132059510255f1345d91db12_90839884',
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f1345d9ed256_86269188',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f1345d9ed256_86269188')) {
function content_55f1345d9ed256_86269188 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '132059510255f1345d91db12_90839884';
if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header_content/header_content.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('title'=>"车辆管理",'class'=>"car-manage"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header_content/header_content.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header_content/header_content.tpl", $_smarty_tpl->smarty);?>

<div id="car-manage">
    <table class="content">
        <thead>
            <tr>
                <th width="20%">司机姓名</th>
                <th width="25%">司机联系电话</th>
                <th width="25%">车牌号(牵引车)</th>
                <th width="30%">行驶证号</th>
            </tr>
        </thead>
        <tbody>
            <?php
$_from = $_smarty_tpl->tpl_vars['data']->value['data']['data']['result'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                <tr class="item-content">
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['driver_name'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['contactNumber'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['car_number'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['drive_number'];?>
</td>
                </tr>
            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
        </tbody>
    </table>
    	<?php if ($_smarty_tpl->tpl_vars['data']->value['data']['data']['pageCount'] >= 1) {?>
    		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("common/widget/pager/pager.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('total'=>$_smarty_tpl->tpl_vars['data']->value['data']['data']['pageCount']), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."common/widget/pager/pager.tpl".'"', E_USER_ERROR);}FISResource::load("common/widget/pager/pager.tpl", $_smarty_tpl->smarty);?>
    	<?php }?>
</div>

<?php $fis_script_priority = 0;ob_start();?>
        console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
)
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;

}
}
?>