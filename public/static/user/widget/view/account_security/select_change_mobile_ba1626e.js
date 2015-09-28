[function(locals, filters, escape, rethrow) {
escape = escape || function (html){
  return String(html)
    .replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;');
};
var __stack = { lineno: 1, input: "<div id=\"select-box\">\n    <img src=\"<%=__uri('../../../../common/static/image/logo.png')%>\" alt=\"56xdd.com\" class=\"logo\"/>\n    <dl>\n        <dd class=\"clearfix\">\n            <a href=\"/user/changeBind?pass\" id=\"select-pass\" class=\"user-btn\">通过输入密码进行修改<em>>></em></a>\n\n        </dd>\n        <dd class=\"clearfix\">\n            <a href=\"/user/changeBind?sms\" id=\"select-sms\" class=\"user-btn\">通过手机验证码进行修改<em>>></em></a>\n        </dd>\n    </dl>\n</div>", filename: "user/widget/view/account_security/select_change_mobile.ejs" };
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
 buf.push('<div id="select-box">\n    <img src="', escape((__stack.lineno=2, '/static/common/static/image/logo_8633d69.png')), '" alt="56xdd.com" class="logo"/>\n    <dl>\n        <dd class="clearfix">\n            <a href="/user/changeBind?pass" id="select-pass" class="user-btn">通过输入密码进行修改<em>>></em></a>\n\n        </dd>\n        <dd class="clearfix">\n            <a href="/user/changeBind?sms" id="select-sms" class="user-btn">通过手机验证码进行修改<em>>></em></a>\n        </dd>\n    </dl>\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0]