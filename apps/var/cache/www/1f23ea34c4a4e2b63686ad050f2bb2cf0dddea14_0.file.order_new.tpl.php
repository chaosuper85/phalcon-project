<?php /* Smarty version 3.1.27, created on 2015-09-16 15:50:18
         compiled from "/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_new/order_new.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:40265024455f91f3ae99bb8_50249315%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f23ea34c4a4e2b63686ad050f2bb2cf0dddea14' => 
    array (
      0 => '/Users/chaosuper/migrate_project/phpweb/apps/modules/www/views/order/widget/view/order_new/order_new.tpl',
      1 => 1442389720,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '40265024455f91f3ae99bb8_50249315',
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55f91f3af0e3e1_76584950',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55f91f3af0e3e1_76584950')) {
function content_55f91f3af0e3e1_76584950 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '40265024455f91f3ae99bb8_50249315';
?>
<div id="order-new">
    <div class="breadcrumb">
        <ol>
            <li><a href="/">发起订单</a></li>
            <li>></li>
            <li class="active">发起出口产装订单</li>
        </ol>
    </div>
	<div class="order-number clearfix">
		<span class="number-left"></span>
		<span class="number-right">运单号<?php echo $_smarty_tpl->tpl_vars['data']->value['data']['yudan_code'];?>
</span>
	</div>
	<div class="carteam_freight mod">
        <div class="title">
            <h3>承运方</h3>
        </div>
        <div class="content">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="carteam_name">
                        <span class="name">承运方</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <div id="carteam-selector"></div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>承运方必填</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="chanzhuang mod">
        <div class="title">
            <h3>产装联系单</h3>
        </div>
        <div class="content ">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="chanzhuang_name">
                        <span class="name">上传产装联系单</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content clearfix">
                     <div class="upload-show chanzhuang-show hidden clearfix">
                        <div class="show-box">
                            <span class="filename"></span>
                        </div>
                        <a href="javascript:;" id="chanzhuang-delete" class="upload-delete" title="删除">删除</a>
                    </div>
                    <div class="btn-wrap">
                        <a href="javascript:;" class="user-btn">选择文件上传</a>
                        <input type="file" name="chanzhuang" type="file" id="upload-chanzhuang" value="" accept=".doc,.docx,.pdf"/>
                    </div>
                    <div class="info-wrap">
                        <p>支持.doc.docx或.pdf格式文件,不超过2M</p>
                    </div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>产装联系单必须上传</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tixiang mod">
        <div class="title">
            <h3>提箱联系单</h3>
        </div>
        <div class="content ">
            <div class="item clearfix">
                <div class="item-name">
                    <label for="freight_name">
                        <span class="name">提单号</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <input type="text" value="" id="tidan_name" class="" name="tidan_name" />
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>提单号必填</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
            <div class="item clearfix">
                <div class="item-name">
                    <label for="tixiang_name">
                        <span class="name">上传提箱联系单</span>
                        <span class="icon-require">*</span>
                    </label>
                </div>
                <div class="item-content">
                    <div class="upload-show tixiang-show hidden clearfix">
                        <div class="show-box">
                            <span class="filename"></span>
                        </div>
                        <a href="javascript:;" id="tixiang-delete" class="upload-delete" title="删除">删除</a>
                    </div>
                    <div class="btn-wrap">
                        <a href="javascript:;" class="user-btn">选择文件上传</a>
                        <input type="file" name="tidan" type="file" id="upload-tixiang" value="" accept=".doc,.docx,.pdf"/>
                    </div>
                    <div class="info-wrap">
                        <p>支持.doc.docx或.pdf格式文件,不超过2M</p>
                    </div>
                </div>
                <div class="item-message clearfix">
                    <div class="error-message hidden"><i class="icon-warn"></i>产装联系单必须上传</div>
                    <div class="right-message hidden"><i class="icon-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <button class="order-creat">创建订单</button>
</div>

<?php $fis_script_priority = 0;ob_start();?>
        console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
)
        console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value['data']['carteamList']);?>
)
	require('order/widget/view/order_new/order_new').init(<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value['data']['carteamList']);?>
,<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value['data']['yudan_code']);?>
);
<?php $script=ob_get_clean();if($script!==false){if(!class_exists('FISResource', false)){require_once('/Users/chaosuper/migrate_project/phpweb/apps/library/Smarty/plugins/FISResource.class.php');}if(FISResource::$cp) {if (!in_array(FISResource::$cp, FISResource::$arrEmbeded)){FISResource::addScriptPool($script, $fis_script_priority);FISResource::$arrEmbeded[] = FISResource::$cp;}} else {FISResource::addScriptPool($script, $fis_script_priority);}}FISResource::$cp = null;
}
}
?>