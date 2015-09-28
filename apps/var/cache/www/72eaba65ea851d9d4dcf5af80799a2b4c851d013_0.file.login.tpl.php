<?php /* Smarty version 3.1.27, created on 2015-09-17 20:12:22
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/login/login.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:145894734455faae263642e2_67491986%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '72eaba65ea851d9d4dcf5af80799a2b4c851d013' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/index/widget/view/login/login.tpl',
      1 => 1442488890,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '145894734455faae263642e2_67491986',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55faae26385094_53369204',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55faae26385094_53369204')) {
function content_55faae26385094_53369204 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '145894734455faae263642e2_67491986';
?>
<div id="login">
    <div class="login-header">
        <div class="wrapper">
            <h2>
                登录
            </h2>
        </div>
    </div>
    <div class="wrapper">
        <div class="pageLogin-content ">
            <div class="center">
                <dl>
                    <dd class="clearfix">
                        <span class="pageLogin-title">
                            用户名/手机号
                        </span>
                        <input type="text" id="pageLogin-username" name="pageLogin-username" maxlength="11"/>
                        <span class="right-mark invisible">
                        </span>
                        <div class="tip">
                            请输入用户名或11位手机号码
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="pageLogin-title">
                            密码
                        </span>
                        <input type="password" id="pageLogin-pass" name="pageLogin-pass" maxlength="16"/>
                        <span class="right-mark invisible">
                        </span>
                        <div class="tip">
                            请输入密码
                        </div>
                    </dd>
                    <dd class="clearfix none">
                        <span class="pageLogin-title">
                            验证码
                        </span>
                        <input type="text" id="pageLogin-code" name="pageLogin-code" maxlength="4"/>
                        <div class="code">
                        </div>
                        <span class="right-mark invisible code-mark">
                        </span>
                        <div class="tip">
                            请输入验证码
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="wrapper regbtn-wrap">
        <div class="remember clearfix">
            <!-- <input type="checkbox" id="pageLogin-remember">
            <label for="pageLogin-remember">
                自动登录
            </label> -->
            <a href="/index/findPassword">
                忘记密码
            </a>
        </div>
        <div class="btn clearfix">
            <span class="pageLogin-title invisible">
            </span>
            <a href="javascript:;" id="pageLogin-submit">
                登录
            </a>
        </div>
    </div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
    require('index/widget/view/login/login').init('<?php echo $_smarty_tpl->tpl_vars['data']->value['from'];?>
');
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>