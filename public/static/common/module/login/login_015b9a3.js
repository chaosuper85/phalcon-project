define("common/module/login/login",function(require,exports,module){var popup=require("common/module/popup/popup.js"),util=require("common/module/util.js"),tpl=[function(locals,filters,escape,rethrow){function rethrow(n,i,e,a){var l=i.split("\n"),t=Math.max(a-3,0),o=Math.min(l.length,a+3),s=l.slice(t,o).map(function(n,i){var e=i+t+1;return(e==a?" >> ":"    ")+e+"| "+n}).join("\n");throw n.path=e,n.message=(e||"ejs")+":"+a+"\n"+s+"\n\n"+n.message,n}escape=escape||function(n){return String(n).replace(/&(?!#?[a-zA-Z0-9]+;)/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#39;").replace(/"/g,"&quot;")};var __stack={lineno:1,input:'<div id="login-box">\n	<img src="<%=__uri(\'../../static/image/logo.png\')%>" alt="56xdd.com" class="logo"/>\n	<dl>\n		<dd class="clearfix">\n			<input type="text" id="login-user" placeholder="请输入用户名/手机号" maxlength="12"/>\n			<span class="icon-user"></span>\n		</dd>\n		<dd class="clearfix">\n			<input type="password" id="login-pass" placeholder="请输入密码" maxlength="16"/>\n			<span class="icon-pass"></span>\n		</dd>\n		<dd id="login-captcha-wrap" class="clearfix hidden">\n			<input type="text" id="login-captcha" placeholder="验证码" maxlength="4"/>\n			<div class="captcha-wrap">\n			</div>\n		</dd>\n	</dl>\n	<div id="login-tip" class="invisible"></div>\n	<div class="func clearfix">\n		<input type="checkbox" id="login-keep" class="invisible" checked="checked"/>\n		<label for="login-keep" class="invisible">下次自动登录</label>\n		<a href="/index/findPwd">忘记密码</a>\n	</div>\n	<a href="javascript:;" id="login-submit">登 录</a>\n</div>',filename:"common/module/login/login.ejs"};try{var buf=[];with(locals||{})!function(){buf.push('<div id="login-box">\n	<img src="',escape((__stack.lineno=2,"/static/common/static/image/logo_8633d69.png")),'" alt="56xdd.com" class="logo"/>\n	<dl>\n		<dd class="clearfix">\n			<input type="text" id="login-user" placeholder="请输入用户名/手机号" maxlength="12"/>\n			<span class="icon-user"></span>\n		</dd>\n		<dd class="clearfix">\n			<input type="password" id="login-pass" placeholder="请输入密码" maxlength="16"/>\n			<span class="icon-pass"></span>\n		</dd>\n		<dd id="login-captcha-wrap" class="clearfix hidden">\n			<input type="text" id="login-captcha" placeholder="验证码" maxlength="4"/>\n			<div class="captcha-wrap">\n			</div>\n		</dd>\n	</dl>\n	<div id="login-tip" class="invisible"></div>\n	<div class="func clearfix">\n		<input type="checkbox" id="login-keep" class="invisible" checked="checked"/>\n		<label for="login-keep" class="invisible">下次自动登录</label>\n		<a href="/index/findPwd">忘记密码</a>\n	</div>\n	<a href="javascript:;" id="login-submit">登 录</a>\n</div>')}();return buf.join("")}catch(err){rethrow(err,__stack.input,__stack.filename,__stack.lineno)}}][0],login=function(){function n(){i.call(this)}function i(){s.click(function(){var n=e();if(n){var i=a();i&&(s.html("正在登录..."),s.attr("disabled","disabled"),XDD.Request({url:"/index/do_login",data:{username:n,password:i},success:function(n){0==n.error_code?location.href="/":(l(n.error_msg),s.html("登 录"))},error:function(){s.html("登 录"),s.removeAttr("disabled")}}))}}),d.keydown(function(n){13==n.keyCode&&s.click()}),c.keydown(function(n){13==n.keyCode&&s.click()})}function e(){var n=c.val();return 0==n.length?void l("请输入用户名或手机号",c,"account"):util.isUname(n)||util.isMobilePhone(n)?("account"==p&&t(),n):void l("请输入正确的用户名或手机号",c,"account")}function a(){var n=d.val();return 0==n.length?void l("请输入密码",d,"password"):n.length<6?void l("密码不少于6位",d,"password"):("password"==p&&t(),n)}function l(n,i,e){p=e,r.html(n),r.removeClass("invisible"),i&&o(i),clearTimeout(u),u=setTimeout(function(){t()},5e3)}function t(){r.addClass("invisible")}function o(n){n.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}n.prototype=new popup({title:!1,height:340,width:330,tpl:tpl}),n.prototype.constructor=n,n.prototype.setHeight=function(n){this.pop.height(n)};var s=$("#login-submit"),c=$("#login-user"),d=$("#login-pass"),r=$("#login-tip"),p="",u=null;return new n};module.exports=login});