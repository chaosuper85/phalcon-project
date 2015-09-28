<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:32
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/view/account_info/account_info.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:170927546955fa7758061ab1_29305077%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1bb3c22878800b0b9dcd905c59f1e94efbacc4ed' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/view/account_info/account_info.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170927546955fa7758061ab1_29305077',
  'variables' => 
  array (
    'data' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa7758124696_30992344',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa7758124696_30992344')) {
function content_55fa7758124696_30992344 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '170927546955fa7758061ab1_29305077';
$_smarty_tpl->tpl_vars['user'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['data']['personalInfo']['user'], null, 0);?>
<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header_content/header_content.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('title'=>"个人信息",'class'=>"person"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header_content/header_content.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header_content/header_content.tpl", $_smarty_tpl->smarty);?>
<div id="user_info">
    <div class="user-content">
        <dl>
            <dd class="clearfix">
                <div class="title">头像</div>
                <div class="content">
                    <div class="head-img-content">
                        <img id="head-img" src="/static/user/static/image/img1.png"/>
                    </div>
                    <div class="btn-wrap btn-head hidden">
                        <a href="javascript:;" class="user-btn">上传图片</a>
                        <input type="file" name="IMAGE" multiple="" id="upload-headimg" accept="image/*"/>
                        <div class="info-wrap">
                            <p>支持jpg.jpeg.bmp.gif格式照片</p>
                            <p>pdf扫描文件，大小不超过20MB</p>
                        </div>
                    </div>
                </div>
            </dd>
            <dd class="clearfix">
                <div class="title">用户名</div>
                <div class="content"><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</div>
            </dd>
            <dd class="clearfix">
                <div class="title">手机号</div>
                <div class="content"><?php echo $_smarty_tpl->tpl_vars['user']->value['mobile'];?>
</div>
            </dd>
            <dd class="clearfix">
                <div class="title">用户分组</div>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['group_name']) {?>
                <div class="content"><?php echo $_smarty_tpl->tpl_vars['user']->value['group_name'];?>
</div>
                <?php } else { ?>
                <div class="content none">暂无</div>
                <?php }?>
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title">真实姓名</div>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['real_name']) {?>
                <div id="name" class="content show"><?php echo $_smarty_tpl->tpl_vars['user']->value['real_name'];?>
</div>
                <?php } else { ?>
                <div id="name" class="content show none">暂无</div>
                <?php }?>
                <input type="text" id="input-name" class="input0 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title">联系方式</div>
                <div id="phone" class="content show"><?php echo $_smarty_tpl->tpl_vars['user']->value['contactNumber'];?>
</div>
                <input type="text" id="input-phone" class="input1 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title" >座机</div>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['telephone_number']) {?>
                <div id="telephone" class="content show"><?php echo $_smarty_tpl->tpl_vars['user']->value['telephone_number'];?>
</div>
                <?php } else { ?>
                <div id="telephone" class="content show none">暂无</div>
                <?php }?>
                <input type="text" id="input-telephone" class="input2 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="edit">
                <div class="content">
                     <div class="btn-wrap ">
                         <a href="javascript:;" id="edit-info" class="user-btn">编辑资料</a>
                         <a href="javascript:;" id="submit-info" class="user-btn submit-btn">确认提交</a>
                     </div>
                </div>
            </dd>
        </dl>
    </div>
</div>


<?php $fis_script_priority = 0;ob_start();?>
	require('user/widget/view/account_info/account_info').init();
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>