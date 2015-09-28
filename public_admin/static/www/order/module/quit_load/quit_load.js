[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="quit_loading_box"><div class="logo"></div><dl><dd class="clearfix"><input type="password" id="quit_confirm" placeholder="请输入密码" maxlength="16"/><span class="icon-pass"></span></dd><dd id="quit_captcha_wrap" class="clearfix hidden"><input type="text" id="quit_captcha" placeholder="验证码" maxlength="4"/><div class="captcha-wrap"></div></dd></dl><div id="quit_tip" class="invisible"></div><a href="javascript:;" id="confirm_submit">确定</a></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]