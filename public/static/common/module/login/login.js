define('common/module/login/login', function(require, exports, module) {

/*
 *  注册弹出窗
 */

var popup = require('common/module/popup/popup.js');
var util = require('common/module/util.js');
var tpl = [function(locals, filters, escape, rethrow) {
escape = escape || function (html){
  return String(html)
    .replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;');
};
var __stack = { lineno: 1, input: "<div id=\"login-box\">\n\t<img src=\"<%=__uri('../../static/image/logo.png')%>\" alt=\"56xdd.com\" class=\"logo\"/>\n\t<dl>\n\t\t<dd class=\"clearfix\">\n\t\t\t<input type=\"text\" id=\"login-user\" placeholder=\"请输入用户名/手机号\" maxlength=\"12\"/>\n\t\t\t<span class=\"icon-user\"></span>\n\t\t</dd>\n\t\t<dd class=\"clearfix\">\n\t\t\t<input type=\"password\" id=\"login-pass\" placeholder=\"请输入密码\" maxlength=\"16\"/>\n\t\t\t<span class=\"icon-pass\"></span>\n\t\t</dd>\n\t\t<dd id=\"login-captcha-wrap\" class=\"clearfix hidden\">\n\t\t\t<input type=\"text\" id=\"login-captcha\" placeholder=\"验证码\" maxlength=\"4\"/>\n\t\t\t<div class=\"captcha-wrap\">\n\t\t\t</div>\n\t\t</dd>\n\t</dl>\n\t<div id=\"login-tip\" class=\"invisible\"></div>\n\t<div class=\"func clearfix\">\n\t\t<input type=\"checkbox\" id=\"login-keep\" class=\"invisible\" checked=\"checked\"/>\n\t\t<label for=\"login-keep\" class=\"invisible\">下次自动登录</label>\n\t\t<a href=\"/index/findPwd\">忘记密码</a>\n\t</div>\n\t<a href=\"javascript:;\" id=\"login-submit\">登 录</a>\n</div>", filename: "common/module/login/login.ejs" };
function rethrow(err, str, filename, lineno){
  var lines = str.split('\n')
    , start = Math.max(lineno - 3, 0)
    , end = Math.min(lines.length, lineno + 3);

  // Error context
  var context = lines.slice(start, end).map(function(line, i){
    var curr = i + start + 1;
    return (curr == lineno ? ' >> ' : '    ')
      + curr
      + '| '
      + line;
  }).join('\n');

  // Alter exception message
  err.path = filename;
  err.message = (filename || 'ejs') + ':'
    + lineno + '\n'
    + context + '\n\n'
    + err.message;
  
  throw err;
}
try {
var buf = [];
with (locals || {}) { (function(){ 
 buf.push('<div id="login-box">\n	<img src="', escape((__stack.lineno=2, '/static/common/static/image/logo.png')), '" alt="56xdd.com" class="logo"/>\n	<dl>\n		<dd class="clearfix">\n			<input type="text" id="login-user" placeholder="请输入用户名/手机号" maxlength="12"/>\n			<span class="icon-user"></span>\n		</dd>\n		<dd class="clearfix">\n			<input type="password" id="login-pass" placeholder="请输入密码" maxlength="16"/>\n			<span class="icon-pass"></span>\n		</dd>\n		<dd id="login-captcha-wrap" class="clearfix hidden">\n			<input type="text" id="login-captcha" placeholder="验证码" maxlength="4"/>\n			<div class="captcha-wrap">\n			</div>\n		</dd>\n	</dl>\n	<div id="login-tip" class="invisible"></div>\n	<div class="func clearfix">\n		<input type="checkbox" id="login-keep" class="invisible" checked="checked"/>\n		<label for="login-keep" class="invisible">下次自动登录</label>\n		<a href="/index/findPwd">忘记密码</a>\n	</div>\n	<a href="javascript:;" id="login-submit">登 录</a>\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0];

var login = function(){

	function _login(){
	    bind.call(this);
	}

	_login.prototype = new popup({
	    title :false,
	    height:340, // 显示验证码时为400
	    width :330,
	    tpl:tpl
	});

	_login.prototype.constructor = _login;

	_login.prototype.setHeight = function(h){
		this.pop.height(h);
	}

	var submit = $('#login-submit'),
		account = $('#login-user'),
		password = $('#login-pass'),
		tip = $('#login-tip'),
		errorType = '';

	function bind(){

		submit.click(function(){
			
			var _account = checkAccount();
			if(!_account)return;

			var _password = checkPass();
			if(!_password)return;

			submit.html('正在登录...');
			XDD.Request({
		        url : '/index/do_login',
		        data: {
		        	username : _account,
		        	password : _password
		        },
		        success : function(result){
		            if(result.error_code == 0){
		            	location.href = "/";
		            }else{
						showError(result.error_msg);
		            }
		            submit.html('登 录');
		        },
		        error : function(){
		            submit.html('登 录');
		        }

		    })

		});

		password.keydown(function(event){
			if(event.keyCode==13){
				submit.click();
			}
		});

	}


	function checkAccount(){
		var _account = account.val();

		if(_account.length == 0){
			showError('请输入用户名或手机号',account,'account');return;
		}
		if(!util.isUname(_account) && !util.isMobilePhone(_account)){
			showError('请输入正确的用户名或手机号',account,'account');return;
		}

		if(errorType == 'account'){
			hideError();
		}

		return _account;
	}

	function checkPass(){
		var _pass = password.val();
		if(_pass.length == 0){
			showError('请输入密码',password,'password');return;
		}
		if(_pass.length < 6){
			showError('密码不少于6位',password,'password');return;
		}
		if(errorType == 'password'){
			hideError();
		}

		return _pass;
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
		obj.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
		   .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40)
		   .animate({marginLeft:"0px"},40).focus();
	}

	
	return new _login();
}



module.exports = login;

});
