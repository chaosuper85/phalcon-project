define("index/widget/view/reg/reg",function(e,s,r){function t(){S.FORM.code.blur(function(){var e=i();e&&(S.TIPS.code.html("正在检查邀请码..."),a("code",e,function(s){0==s.error_code?(b("code",e,"邀请码可用"),S.BTNS.submit.removeClass("disable")):(f("code","请联系箱典典官方客服400-969-6790获取邀请码"),S.BTNS.submit.addClass("disable"))}))}),S.FORM.type.find(".radio-box").click(function(){var e=$(this);if(!e.hasClass("active")){e.addClass("active"),e.siblings(".radio-box").removeClass("active");var s=e.attr("type");S.FORM.type.attr("type",s)}}),S.FORM.username.blur(function(){var e=n();e&&(S.TIPS.username.html("正在检查用户名可用性..."),c("username",e,function(s){0==s.error_code?b("username",e,"用户名可用"):f("username","用户名已被注册（或不可用）")}))}),S.FORM.mobile.blur(function(){var e=o();e&&(S.TIPS.mobile.html("正在检查手机号可用性..."),c("mobile",e,function(s){0==s.error_code?b("mobile",e,"手机号可用"):f("mobile","手机号已被注册（或不可用）")}))}),S.FORM.mobile.keyup(function(e){var s=e.keyCode;37!=s&&38!=s&&39!=s&&40!=s&&S.FORM.mobile.val(S.FORM.mobile.val().replace(/\D/gi,""))}),S.BTNS.sms.click(function(){if(!S.BTNS.sms.hasClass("disable"))if(g.mobile)S.BTNS.sms.html("发送中..."),d(T.mobile);else{var e=o();e?(S.BTNS.sms.html("发送中..."),c("mobile",e,function(s){0==s.error_code?d(e):(S.BTNS.sms.html("发送短信"),f("mobile","手机号已被注册（或不可用）"),v("mobile"))})):v("mobile")}}),S.FORM.sms.blur(function(){m()}),S.FORM.pass.blur(function(){l()}),S.FORM.repass.blur(function(){u()}),S.BTNS.submit.click(function(){if(!g.code||S.BTNS.submit.hasClass("disable"))return f("code","请联系箱典典官方客服400-969-6790获取邀请码"),void _alert("请联系客服电话400-969-6790获取邀请码");var e=S.FORM.type.attr("type");if("freight_agent"!=e&&"carteam"!=e)return void f("type","请选择用户类型");b("type",e);var s={type:e},r=n(1);if(!r)return void v("username");s.userName=r;var t=o(1);if(!t)return void v("mobile");s.mobile=t;var i=m(1);if(!i)return void v("sms");s.smsCode=i;var a=l(1),c=u(1);return a?c?(s.pwd=c,S.FORM.agreement[0].checked?(S.BTNS.submit.html("正在注册..."),S.BTNS.submit.attr("disabled","disabled"),void XDD.Request({url:"/user/do_register",data:s,success:function(e){0==e.error_code?location.href="/account/enterpriseInfo":(_alert(e.error_msg),S.BTNS.submit.html("立即注册"),S.BTNS.submit.removeAttr("disabled"))},error:function(){S.BTNS.submit.html("立即注册"),S.BTNS.submit.removeAttr("disabled")}})):void _alert("请阅读并同意服务条款")):void v("repass"):void v("pass")})}function i(e){var s=S.FORM.code,r=s.val();return r?r:e?(v("code"),f("code","请联系箱典典官方客服400-969-6790获取邀请码"),!1):!1}function a(e,s,r){g[e]=!1,T[e]="",XDD.Request({type:"POST",url:"/user/checkregister",data:{register_code:s},success:function(e){"function"==typeof r&&r(e)}},!0)}function n(e){var s=S.FORM.username,r=s.val().replace(/\s{1,}/g,"").toLowerCase();return s.val(r),r?r.length<4?(f("username","用户名不少于4位"),!1):r.length>12?(f("username","用户名不多于12位"),!1):p.isUname(r)?r:(f("username","4-12位字母或数字，首字符不能为数字"),!1):e?(f("username","请输入用户名"),!1):!1}function o(e){var s=S.FORM.mobile,r=s.val();return r?11!==r.length?(f("mobile","请输入11位手机号"),!1):p.isMobilePhone(r)?r:(f("mobile","请输入正确的手机号"),!1):e?(f("mobile","请输入手机号"),!1):!1}function m(e){var s=S.FORM.sms,r=s.val();return r.length?4!==r.length?(f("sms","请输入4位短信验证码"),!1):p.isNumber(r)?(b("sms",r),r):(f("sms","短信验证码为4位数字"),!1):e?(f("sms","请输入短信验证码"),!1):!1}function l(e){var s=S.FORM.pass,r=s.val(),t=S.FORM.repass.val();return r.length?r.length<6||r.length>12?(f("pass","密码为6-16位"),!1):p.isPassword(r)?(t==r&&b("repass",t),b("pass",r),r):(f("pass","密码必须包含数字和字母"),!1):e?(f("pass","请输入密码"),!1):!1}function u(e){var s=S.FORM.repass,r=s.val(),t=S.FORM.pass.val();return r.length?r!==t?(f("repass","两次输入密码不一致"),!1):(b("repass",r),r):e?(f("repass","请再次输入密码"),!1):!1}function c(e,s,r){g[e]=!1,T[e]="",XDD.Request({type:"GET",url:"/index/checkExist",data:{mobileOrName:s},success:function(e){"function"==typeof r&&r(e)}})}function d(e){XDD.Request({type:"GET",url:"/user/sendSms",data:{mobile:e,smsType:"REGISTER"},success:function(e){if(0==e.error_code){_alert("已向您的手机发送了短信，请注意查收"),S.BTNS.sms.addClass("disable");var s=60,r=function(){1==s?(S.BTNS.sms.html("重发短信"),S.BTNS.sms.removeClass("disable"),clearTimeout(t)):(s--,S.BTNS.sms.html("重发("+s+")"),setTimeout(r,1e3))},t=setTimeout(r,1e3)}}})}function b(e,s,r){var t=r?r:h[e];S.TIPS[e].removeClass("red"),S.TIPS[e].html(t),S.PASS_MARKS[e].removeClass("invisible"),"pass"!=e&&(g[e]=!0,T[e]=s)}function f(e,s){S.TIPS[e].html(s),S.TIPS[e].addClass("red"),S.PASS_MARKS[e].addClass("invisible"),g[e]=!1,T[e]=""}function v(e){S.FORM[e].animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}var p=e("common/module/util.js"),S={FORM:{code:$("#reg-code"),type:$("#reg-type"),username:$("#reg-username"),mobile:$("#reg-mobile"),sms:$("#reg-sms"),pass:$("#reg-pass"),repass:$("#reg-repass"),agreement:$("#reg-agree")},TIPS:{},PASS_MARKS:{},BTNS:{sms:$("#get-sms"),submit:$("#reg-submit")}},g={},T={};for(var R in S.FORM)S.TIPS[R]=S.FORM[R].siblings(".tip"),S.PASS_MARKS[R]=S.FORM[R].siblings(".right-mark"),g[R]=!1;{var h={code:"请联系箱典典官方客服400-969-6790获取邀请码",type:"请选择用户类型",username:"4-12位字母或数字，首字符不能为数字",mobile:"可用于登录系统，找回密码",sms:"请查收手机短信，并填写短信中的验证码",pass:"6～16位字符，区分大小写",repass:"请再次输入密码"};$(".reg-content input")}r.exports={init:function(){t()}}});