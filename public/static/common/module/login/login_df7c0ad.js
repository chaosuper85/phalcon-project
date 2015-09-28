[function(locals, filters, escape, rethrow) {
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
 buf.push('<div id="login-box">\n	<img src="', escape((__stack.lineno=2, '/static/common/static/image/logo_8633d69.png')), '" alt="56xdd.com" class="logo"/>\n	<dl>\n		<dd class="clearfix">\n			<input type="text" id="login-user" placeholder="请输入用户名/手机号" maxlength="12"/>\n			<span class="icon-user"></span>\n		</dd>\n		<dd class="clearfix">\n			<input type="password" id="login-pass" placeholder="请输入密码" maxlength="16"/>\n			<span class="icon-pass"></span>\n		</dd>\n		<dd id="login-captcha-wrap" class="clearfix hidden">\n			<input type="text" id="login-captcha" placeholder="验证码" maxlength="4"/>\n			<div class="captcha-wrap">\n			</div>\n		</dd>\n	</dl>\n	<div id="login-tip" class="invisible"></div>\n	<div class="func clearfix">\n		<input type="checkbox" id="login-keep" class="invisible" checked="checked"/>\n		<label for="login-keep" class="invisible">下次自动登录</label>\n		<a href="/index/findPwd">忘记密码</a>\n	</div>\n	<a href="javascript:;" id="login-submit">登 录</a>\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0]