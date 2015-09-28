<?php /* Smarty version 3.1.27, created on 2015-09-16 19:38:42
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/reg/reg.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:211954091755f954c26f4fc6_78747722%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c565883d5ce59179f6582a91714bc314ec09c723' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/reg/reg.tpl',
      1 => 1442400041,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '211954091755f954c26f4fc6_78747722',
  'variables' => 
  array (
    'data' => 0,
    'type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f954c2782ce4_16615363',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f954c2782ce4_16615363')) {
function content_55f954c2782ce4_16615363 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '211954091755f954c26f4fc6_78747722';
if ($_smarty_tpl->tpl_vars['data']->value['type'] == "carteam" || $_smarty_tpl->tpl_vars['data']->value['type'] == "freight_agent") {?>
	<?php $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['type'], null, 0);?>
<?php } else { ?>
	<?php $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable('', null, 0);?>
<?php }?>
<div id="reg">
	<div class="reg-header">
		<div class="wrapper">
			<h2>注册用户</h2>
		</div>
	</div>
	<div class="wrapper">
		<div class="reg-content clearfix">
			<div class="center">
				<dl>
                    <dd class="clearfix">
                        <span class="reg-title">邀请码</span>
                        <input type="text" id="reg-code" name="reg-code" maxlength="10" autocomplete="off"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip">请联系箱典典官方客服400-969-6790获取邀请码</div>
                    </dd>
					<dd class="clearfix">
						<span class="reg-title">用户类型</span>
						<div id="reg-type" class="clearfix" type="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
							<?php if ($_smarty_tpl->tpl_vars['type']->value == "freight_agent") {?>
								<div class="radio-box active" type="freight_agent">
							<?php } else { ?>
								<div class="radio-box" type="freight_agent">
							<?php }?>
								<span class="marker"></span>
								<span>货代用户</span>
							</div>
							
							<?php if ($_smarty_tpl->tpl_vars['type']->value == "carteam") {?>
								<div class="radio-box active" type="carteam">
							<?php } else { ?>
								<div class="radio-box" type="carteam">
							<?php }?>
								<span class="marker"></span>
								<span>车队用户</span>
							</div>
						</div>
						<div class="tip">请选择用户类型</div>
					</dd>
					<dd class="clearfix">
						<span class="reg-title">用户名</span>
						<input type="text" id="reg-username" name="reg-username" maxlength="12" autocomplete="off"/>
						<span class="right-mark invisible"></span>
						<div class="tip">4-12位字母或数字，首字符不能为数字</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">手机号</span>
						<input type="text" id="reg-mobile" name="reg-mobile" maxlength="11" autocomplete="off"/>
						<span class="right-mark invisible"></span>
						<div class="tip">可用于登录系统，找回密码</div>
					</dd>
					<dd class="sms clearfix"><span class="reg-title">短信验证码</span>
						<input type="text" id="reg-sms" name="reg-sms" maxlength="4" autocomplete="off"/>
						<span id="get-sms" class="unselectable">发送短信</span>
						<div class="tip">请查收手机短信，并填写短信中的验证码</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">密码</span>
						<input type="password" id="reg-pass" name="reg-pass" maxlength="12"/>
						<span class="right-mark invisible"></span>
						<div class="tip">6～12位数字和字母组合，区分大小写</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">确认密码</span>
						<input type="password" id="reg-repass" name="reg-repass" maxlength="12"/>
						<span class="right-mark invisible"></span>
						<div class="tip">请再次输入密码</div>
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="wrapper regbtn-wrap">
		<div class="agreement">
			<input type="checkbox" id="reg-agree">
            <label for="reg-agree">我已阅读并同意</label>
            <a href="/index/agreement" target="_blank">《箱典典网站服务条款》</a> 
		</div>
		<button id="reg-submit">立即注册</button>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
	require('index/widget/view/reg/reg').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;

}
}
?>