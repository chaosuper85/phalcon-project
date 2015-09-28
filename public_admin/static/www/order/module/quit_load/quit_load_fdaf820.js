define('www/order/module/quit_load/quit_load', function(require, exports, module) {

/*
 *确认退载弹窗
 */
var popup = require('www/common/module/popup/popup.js');
var tpl = [function(_template_object) {
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

}][0];


var confirmQuit = function(){

	function _confirmQuit(){
		bind.call(this);
	}

	_confirmQuit.prototype = new popup({
		title:false,
		height:280,
		width:330,
		tpl:tpl
	}); 
	_confirmQuit.prototype.constructor = _confirmQuit;

	_confirmQuit.prototype.setHeight = function(h){
		this.pop.height(h);
	}

	var submit = $("#confirm_submit"),
		password = $("#quit_confirm"),
		captcha = $("#quit_captcha"),
		tip = $("#quit_tip"),
		errorType = '';
  
	function bind(){
		submit.click(function(){
			var pwd = checkPass();
			if(!pwd) return;
 
			XDD.Request({
				url:'/index/do_login',
				data:{
					password:pwd
				},
				success:function(result){
					console.log(result);
					if(result.error_code == 0){
						location.href = "/order/details";
					}else{	
						showError(result.error_msg);
					}
				} 
			}) 
		});
	}
    
	function checkPass(){
		var pwd = password.val();
		if(pwd.length = 0){
			showError('请输入密码',password,'password');return;
		}
		if(pwd.length < 6){
			showError('密码不少于6位',password,'password');return;
		}
		if(errorType == 'password'){
			hideError();
		}
		return pwd;
	}

	var timer = null;

	function showError(msg,obj,type){

		errorType = type;

		tip.html(msg);
		tip.removeClass('invisible');
		obj && shake(obj);
		clearTimeout(timer);
		timer = setTimeout(function(){
			hideError();
		},5000)
	}

	function hideError(){
		tip.addClass('invisible');
	}

	function shake(obj){
		obj.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"}, 40)
		.animate({marginLeft:"-2px"}, 40).animate({marginLeft:"1px"}, 40)
		.animate({marginLeft:"0px"}, 40).focus();
	}

	return new _confirmQuit();
}
  
 
  
module.exports = confirmQuit;

});
