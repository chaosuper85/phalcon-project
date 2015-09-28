<?php /* Smarty version 3.1.27, created on 2015-09-17 16:18:36
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/view/account_company/company.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:151194148955fa775ce470c9_50439176%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '79f786035d4ea9d81a6d115e2c9f7a2b934b7ccd' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/user/widget/view/account_company/company.tpl',
      1 => 1442477713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '151194148955fa775ce470c9_50439176',
  'variables' => 
  array (
    'data' => 0,
    'enterpriseType' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55fa775d112465_04337594',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55fa775d112465_04337594')) {
function content_55fa775d112465_04337594 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '151194148955fa775ce470c9_50439176';
$_smarty_tpl->tpl_vars['enterpriseType'] = new Smarty_Variable(array('','车队用户','货代用户'), null, 0);?>

<?php $_smarty_tpl->tpl_vars['userType'] = new Smarty_Variable($_smarty_tpl->tpl_vars['data']->value['user']['usertype'], null, 0);?>
<?php if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}$_tpl_path=FISResource::getUri("user/widget/header_content/header_content.tpl",$_smarty_tpl->smarty);if(isset($_tpl_path)){echo $_smarty_tpl->getSubTemplate($_tpl_path, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, $_smarty_tpl->caching, $_smarty_tpl->cache_lifetime, array('title'=>"我的公司",'class'=>"company"), Smarty::SCOPE_LOCAL);}else{trigger_error('unable to locale resource "'."user/widget/header_content/header_content.tpl".'"', E_USER_ERROR);}FISResource::load("user/widget/header_content/header_content.tpl", $_smarty_tpl->smarty);?>
<div id="user-company" class="user-content">
	<div class="info">
        <?php if ($_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']) {?>
            <div class="status">
                <?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 2) {?>
                    <span class="red">
                    您的资料审核未通过，请联系客服400-969-6790
                    </span>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 3) {?>
                    您的资料正在审核，请耐心等待，如有问题请拔打客服热线400-969-6790
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 4) {?>
                    恭喜，您的资料已审核通过！可使用<a href="/order/list">陆运服务</a>
                <?php }?>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 3 || $_smarty_tpl->tpl_vars['data']->value['user']['status'] == 4) {?>
                <dl>
                    <dd class="clearfix">
                        <span class="title">企业全称</span>
                        <div class="content">
                            <span class="txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['enterpriseName'];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">企业类型</span>
                        <div class="content">
                            <span class="txt"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['type'];
$_tmp1=ob_get_clean();
echo $_smarty_tpl->tpl_vars['enterpriseType']->value[$_tmp1];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">所在城市</span>
                        <div class="content">
                            <span class="txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['cityName'];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">成立时间</span>
                        <div class="content">
                            <span class="txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['buildDate'];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">区号-座机-分机</span>
                        <div class="content">
                            <span class="txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['contactMobile'];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">营业执照号</span>
                        <div class="content">
                            <span class="txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['licenceNumber'];?>
</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">营业执照附件</span>
                        <div class="content">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['licencePic'];?>
" alt="图1" class="zhizhao license">
                        </div>
                    </dd>
                </dl>
            <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['data']->value['user']['status'] == 2) {?>
                    <dl>
                        <dd class="clearfix">
                            <span class="title">企业全称</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="enterpriseName" value=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['enterpriseName'];?>
 />
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">企业类型</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="type-selector">
                                    </div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">所在城市</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="address-selector" data-pid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['provinceId'];?>
 data-cid=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['cityCode'];?>
 data-aid="0" >

                                    </div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">成立时间</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="company_date_selectBox" data-time=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['buildDate'];?>
></div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">区号-座机-分机</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="contactMobile-city" class="input-short input-must" value=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['contactMobile_city'];?>
 >
                                    - <input type="text" id="contactMobile-number" class="input-long input-must" value=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['contactMobile_number'];?>
 >
                                    - <input type="text" id="contactMobile-fenji" class="input-short" value=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['contactMobile_fenji'];?>
 >

                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">营业执照号</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="licenseNumber" value=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['licenceNumber'];?>
 />
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">营业执照附件</span>
                            <div class="content">
                                <div class="edit-wrap upload-license">
                                    <a href="javascript:;" class="user-btn">上传图片</a>
                                    <input type="file" name="license" multiple="" id="upload-license" accept="image/*">
                                </div>
                                <span class="info-wrap">
                                    <p>支持图片/pdf格式文件</p>
                                    <p>大小不超过2MB</p>
                                </span>
                                    <div class="img-license-content ">
                                    <img src=<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['companyInfo']['enterprise']['licencePic'];?>
 alt="图1" class="license" id="img-license">
                                    <a href="javascript:;" id="img-clean" title="关闭" ></a>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                    </dl>
                <?php }?>
                <div class="user-btn-wrap">
                    <a href="javascript:;" class="user-btn" id="submitInfo">确认提交</a>
                </div>
            <?php }?>
         <?php } else { ?>
            <dl>
                <dd class="clearfix">
                    <span class="title">企业全称</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="enterpriseName"/>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">企业类型</span>
                    <div class="content">
                        <div class="edit-wrap">
                             <div id="type-selector">
                             </div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">所在城市</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <div id="address-selector">

                            </div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">成立时间</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <div id="company_date_selectBox" data-time=""></div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">区号-座机-分机</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="contactMobile-city" class="input-short input-must" />
                            - <input type="text" id="contactMobile-number" class="input-long input-must" />
                            - <input type="text" id="contactMobile-fenji" class="input-short" />

                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">营业执照号</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="licenseNumber"/>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">营业执照附件</span>
                    <div class="content">
                        <div class="edit-wrap upload-license">
                            <a href="javascript:;" class="user-btn">上传图片</a>
                            <input type="file" name="license" multiple="" id="upload-license" accept="image/*">
                        </div>
                        <span class="info-wrap">
                            <p>支持图片/pdf格式文件</p>
                            <p>大小不超过2MB</p>
                        </span>
                            <div class="img-license-content hidden">
                            <img src="" alt="图1" class="license" id="img-license">
                            <a href="javascript:;" id="img-clean" title="关闭" ></a>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
            </dl>
            <div class="user-btn-wrap">
                <a href="javascript:;" class="user-btn" id="submitInfo">确认提交</a>
            </div>
        <?php }?>
	</div>
</div>

<?php $fis_script_priority = 0;ob_start();?>
        console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
)
	require('user/widget/view/account_company/company').init(<?php echo $_smarty_tpl->tpl_vars['data']->value['user']['usertype'];?>
);
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>