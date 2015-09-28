<?php /* Smarty version 3.1.27, created on 2015-09-17 20:12:22
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/widget/footer/footer.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:165965386855faae263921f6_73758706%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '80df21d16170ef3dd5dcd763fb28edb3dc92c787' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/common/widget/footer/footer.tpl',
      1 => 1442488890,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '165965386855faae263921f6_73758706',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55faae263b1189_63651510',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faae263b1189_63651510')) {
function content_55faae263b1189_63651510 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '165965386855faae263921f6_73758706';
?>
<div id="footer" class="invisible">
	<div class="clearfix wrapper-wide">
		<div class="left">
			<div class="nav-top">
				<img class="logo" src="/static/common/static/image/logo_blue.png" alt="56xdd.com"/>
				<ul class="clearfix">
					<li><i class="btn_4"></i><a href="/index/agreement">服务条款</a></li>
					<li><i class="btn_2"></i><a href="javasctipt:;">新手指南</a></li>
					<li><i class="btn_3"></i><a href="javasctipt:;">常见问题</a></li>
					<li><i class="btn_1"></i><a href="javasctipt:;">帮助中心</a></li>
				</ul>
			</div>
			<div class="nav-bottom">
				<ul class="clearfix">
					<li><a href="javasctipt:;">关于我们</a></li>
					<li class="cut"></li>
					<li><a href="javasctipt:;">联系我们</a></li>
					<li class="cut"></li>
					<li><a href="javasctipt:;">加入我们</a></li>
					<li class="cut"></li>
					<li><a href="javasctipt:;">商务合作</a></li>
				</ul>
				<p>Copyright ©2015. 箱典典网络科技有限公司. 京ICP备15031160号</p>
			</div>
		</div>

		<div class="right">
			<p>服务时间: 9:00-21:00</p>
			<img src="/static/common/static/image/400.png" alt="400电话"/>
		</div>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
    require('common/widget/footer/footer');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;?>

<?php }
}
?>