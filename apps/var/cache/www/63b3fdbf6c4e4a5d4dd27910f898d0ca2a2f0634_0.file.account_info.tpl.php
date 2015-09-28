<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:31
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/page/account_info.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:175302868555fa7757d34977_65065189%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63b3fdbf6c4e4a5d4dd27910f898d0ca2a2f0634' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/page/account_info.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
    '7bc96a07c165f3578f05a4988f402159690db2f6' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/page/layout.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
    'd71d8aee355163f9206e7e3be5ac497c3873ad48' => 
    array (
      0 => 'd71d8aee355163f9206e7e3be5ac497c3873ad48',
      1 => 0,
      2 => 'string',
    ),
    '5141e176746d75b7a8a0b9f04f001ec58983439d' => 
    array (
      0 => '5141e176746d75b7a8a0b9f04f001ec58983439d',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '175302868555fa7757d34977_65065189',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa7757e9ef37_68640802',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa7757e9ef37_68640802')) {
function content_55fa7757e9ef37_68640802 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '175302868555fa7757d34977_65065189';
?>
<!DOCTYPE html>
<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::setFramework(FISResource::getUri("common/static/script/mod.js", $_smarty_tpl->smarty)); ?><html>
<head>
	<?php
$_smarty_tpl->properties['nocache_hash'] = '175302868555fa7757d34977_65065189';
?>

	<title>个人信息 - 帐号管理</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>

    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::load("common/static/style/base.less",$_smarty_tpl->smarty, false);?>
    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::load("common/static/script/jquery.js",$_smarty_tpl->smarty, false);?>
    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::load("common/static/script/jquery.json.js",$_smarty_tpl->smarty, false);?>
    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::load("common/static/script/boot.js",$_smarty_tpl->smarty, false);?>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="/static/common/static/favicon.ico"/>
    
    <!--[FIS_CSS_LINKS_HOOK]-->
</head>
<body>
    <?php
$_smarty_tpl->properties['nocache_hash'] = '175302868555fa7757d34977_65065189';
?>

    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header/header.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('page'=>"2"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header/header.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header/header.tpl", $_smarty_tpl->smarty);?>
    <div class="wrapper-wide clearfix">
    	<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/nav_user/nav_user.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('page'=>"0"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/nav_user/nav_user.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/nav_user/nav_user.tpl", $_smarty_tpl->smarty);?>
    	<div class="wrapper right">
    		<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/view/account_info/account_info.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/view/account_info/account_info.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/view/account_info/account_info.tpl", $_smarty_tpl->smarty);?>
    	</div>
    </div>
    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("common/widget/footer/footer.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."common/widget/footer/footer.tpl".'"', E_USER_ERROR);}FISResource::load("common/widget/footer/footer.tpl", $_smarty_tpl->smarty);?>

    <!--[FIS_JS_SCRIPT_HOOK]-->
    <?php if (isset($_smarty_tpl->tpl_vars['data']->value)) {?>
        <?php $fis_script_priority = 0;ob_start();?>
            console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
)
        <?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>
    <?php }?>
</body>
<?php $_smarty_tpl->registerFilter('output', array('FISResource', 'renderResponse'));?></html><?php }
}
?>