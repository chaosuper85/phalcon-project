<?php /* Smarty version 3.1.27, created on 2015-09-16 11:55:37
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/page/error.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:126230340855f8e839ed0f78_73359226%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f23cb61f077ecef6716dbc124f385f5d3abc9238' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/page/error.tpl',
      1 => 1442375474,
      2 => 'file',
    ),
    '7bc96a07c165f3578f05a4988f402159690db2f6' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/page/layout.tpl',
      1 => 1442375474,
      2 => 'file',
    ),
    '4eea4aa9149eb9eb428b73f84ed5a1e6aca3f6a8' => 
    array (
      0 => '4eea4aa9149eb9eb428b73f84ed5a1e6aca3f6a8',
      1 => 0,
      2 => 'string',
    ),
    'f136bbd02af59a66fc3ee189ebda6709e7bd7ed8' => 
    array (
      0 => 'f136bbd02af59a66fc3ee189ebda6709e7bd7ed8',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '126230340855f8e839ed0f78_73359226',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f8e83a1ac816_35530856',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f8e83a1ac816_35530856')) {
function content_55f8e83a1ac816_35530856 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '126230340855f8e839ed0f78_73359226';
?>
<!DOCTYPE html>
<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}FISResource::setFramework(FISResource::getUri("common/static/script/mod.js", $_smarty_tpl->smarty)); ?><html>
<head>
	<?php
$_smarty_tpl->properties['nocache_hash'] = '126230340855f8e839ed0f78_73359226';
?>

	<title>错误页面 - 箱典典</title>
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
$_smarty_tpl->properties['nocache_hash'] = '126230340855f8e839ed0f78_73359226';
?>

    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("index/widget/header/header.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."index/widget/header/header.tpl".'"', E_USER_ERROR);}FISResource::load("index/widget/header/header.tpl", $_smarty_tpl->smarty);?>
    <?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("index/widget/view/error/error.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array(), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."index/widget/view/error/error.tpl".'"', E_USER_ERROR);}FISResource::load("index/widget/view/error/error.tpl", $_smarty_tpl->smarty);?>


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