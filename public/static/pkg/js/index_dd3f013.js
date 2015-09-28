;/*!/index/module/apps/apps.js*/
define("index/module/apps/apps",function(require,exports,module){var popup=require("common/module/popup/popup.js"),util=require("common/module/util.js"),tpl=[function(_template_object){var _template_fun_array=[],fn=function(__data__){var _template_varName="";for(var name in __data__)_template_varName+="var "+name+'=__data__["'+name+'"];';eval(_template_varName),_template_fun_array.push('<div id="pop_apps" class="clearfix"><div class="left"><img class="app-pic" src=',baidu.template._encodeHTML("/static/index/static/image/app-pic_9aeadd5.png"),' alt="箱典典司机端APP"></div><div class="right"><div class="app clearfix"><div class="title-wrap"><img src=',baidu.template._encodeHTML("/static/index/static/image/applogo_f7d772d.png"),' alt="箱典典司机端APP"></div><div class="description"><p class="title">箱典典司机端APP</p><p>随时给您的司机派单</p><p>实时追踪货物的运输情况</p></div></div><div class="download"><h4>扫一扫，即可下载</h4><div class="item"><div class="erweima"><img src=',baidu.template._encodeHTML("/static/index/static/image/erweima_1ca81b5.png"),' alt=""></div><a href="javascript:;">Android下载</a></div><div class="item"><div class="erweima">即将上线</div><a href="javascript:;" title="即将上线">iOS下载</a></div></div></div></div>'),_template_varName=null}(_template_object);return fn=null,_template_fun_array.join("")}][0],apps=function(){function a(){e.call(this)}function e(){}return a.prototype=new popup({title:!1,height:450,width:700,tpl:tpl}),a.prototype.constructor=a,a.prototype.setHeight=function(a){this.pop.height(a)},new a};module.exports=apps});
;/*!/index/widget/header/header.js*/
define("index/widget/header/header",function(n,o,e){function i(){$("#login-btn").click(function(){u.show()}),$("#logout").click(function(){XDD.Request({type:"GET",url:"/index/logout",success:function(n){0==n.error_code?location.href="/":_alert(n.error_msg)}})}),$("#apps-btn").click(function(){l.show()})}var c=n("common/module/login/login.js"),t=n("index/module/apps/apps.js"),u=c(),l=t();e.exports={init:function(){i()}}});
;/*!/index/widget/view/forgotPassword/forgot_pwd.js*/
define("index/widget/view/forgotPassword/forgot_pwd",function(s,e,i){function t(){var s=$("#getSms"),e=!1,i=!1,t=$("#submit_step1");d.FORM.mobile.on("keyup",function(){var s=$(this),e=s.val();e&&11==e.length&&n.isMobilePhone(e)?o(e):(d.TIPS.mobile.html(m.mobile),d.TIPS.mobile.css("color","#999"),p="")});var o=function(i){d.TIPS.mobile.html("正在检测手机号..."),XDD.Request({type:"GET",url:"/index/checkMobileExist",data:{mobileOrName:i},success:function(t){1==t.error_code?(p=i,r("mobile","手机号正确"),e||s.removeClass("disable")):(p="",l("mobile",t.error_msg))}})};s.on("click",function(){c()});var c=function(){s.hasClass("disable")||(s.html("正在发送短信...").addClass("disable"),i=!1,XDD.Request({type:"GET",url:"/user/sendSms",data:{mobile:p,smsType:"CHANGE_PWD"},success:function(t){if(0==t.error_code){_alert("已向您手机发送了短信验证码，请查收"),i=!0,e=!0;var a=60,o=function(){1==a?(s.html("重发短信"),s.removeClass("disable"),clearTimeout(l),e=!1):(a--,s.html("("+a+"s)后可重新发送"),setTimeout(o,1e3))},l=o()}else _alert(t.error_msg)}}))};d.FORM.sms.on("focus",function(){i?(d.TIPS.sms.html("请输入4位短信验证码"),d.TIPS.sms.css("color","#999")):l("sms","请先获取短信验证码")}),d.FORM.sms.on("keyup",function(){if(i){var s=$(this),e=s.val();e&&4==e.length&&n.isNumber(e)&&(t.removeClass("disable"),r("sms","短信验证码格式正确"))}}),d.FORM.sms.on("blur",function(){v()});var v=function(){if(i){var s=d.FORM.sms,e=s.val();return e&&4===e.length&&n.isNumber(e)?void r("sms","短信验证码格式正确"):void l("sms","短信验证码为4位纯数字")}};t.on("click",function(){if(!t.hasClass("disable")){if(!u.mobile)return void _alert("手机号不存在或手机格式不正确");if(!u.sms)return void _alert("未发送短信或验证码格式不正确");t.html("验证中..."),XDD.Request({type:"POST",url:"/user/validateSmsCode",data:{mobile:p,code:d.FORM.sms.val(),smsType:"CHANGE_PWD"},success:function(s){0==s.error_code?(d.PAGE.step1.hide(),d.PAGE.step2.show(),d.STEP.step1.removeClass("cur").addClass("finish"),d.STEP.step2.addClass("cur"),a()):_alert(s.error_msg),t.html("下一步")}})}})}function a(){var s=$("#submit_step2");d.FORM.pass.on("blur",function(){var s=$(this),e=s.val();return e?!n.isPassword(e)||e.length<6||e.length>15?void l("pass","密码必须包含数字和字母(6~15位)"):void r("pass","密码格式正确"):void l("pass","请输入密码")}),d.FORM.pass.on("keyup",function(){var e=$(this),i=e.val(),t=d.FORM.repass.val();t&&(i==t?(r("repass","两次密码一致"),n.isPassword(i)&&i.length>=6&&i.length<=15?s.removeClass("disable"):s.addClass("disable")):(l("repass","两次输入密码不一致"),s.addClass("disable")))}),d.FORM.repass.on("keyup",function(){var e=$(this),i=e.val(),t=d.FORM.pass.val();return t?void(i==t?(r("repass","两次密码一致"),n.isPassword(i)&&i.length>=6&&i.length<=15?s.removeClass("disable"):s.addClass("disable")):(l("repass","两次输入密码不一致"),s.addClass("disable"))):void l("repass","请先输入新密码")}),s.on("click",function(){var s=$(this);s.hasClass("disable")||(s.html("请稍候..."),XDD.Request({type:"POST",url:"/user/changePwd ",data:{newPwd:d.FORM.pass.val(),mobile:p},success:function(e){0==e.error_code?(d.PAGE.step2.hide(),d.PAGE.step3.show(),d.STEP.step2.removeClass("cur").addClass("finish"),d.STEP.step3.addClass("cur"),o()):_alert(e.error_msg),s.html("提 交")}}))})}function o(){var s=5,e=$("#submit_step3"),i=function(){0==s?(location.href="/index",clearTimeout(t)):(e.html(s+"秒自动回到首页"),s--,setTimeout(i,1e3))},t=i()}function l(s,e){d.TIPS[s].html(e),d.TIPS[s].css("color","red"),d.PASS_MARKS[s].addClass("invisible"),u[s]=!1}function r(s,e){d.TIPS[s].html(e),d.TIPS[s].css("color","#999"),d.PASS_MARKS[s].removeClass("invisible"),u[s]=!0}var n=s("common/module/util.js"),d={PAGE:{step1:$("#step1"),step2:$("#step2"),step3:$("#step3")},STEP:{step1:$(".step1"),step2:$(".step2"),step3:$(".step3")},FORM:{mobile:$("#data_mobile"),sms:$("#data_sms"),pass:$("#data_pass"),repass:$("#data_repass")},PASS_MARKS:{},TIPS:{}},m={},u={};for(var c in d.FORM)d.TIPS[c]=d.FORM[c].siblings(".tip"),d.PASS_MARKS[c]=d.FORM[c].siblings(".right-mark"),m[c]=d.TIPS[c].html(),u[c]=!1;var p;i.exports={init:function(){$("#forgot_pwd input").focus(function(){var s=$(this);s.siblings(".tip").css("color","#999"),s.siblings(".right-mark").addClass("invisible")}),t()}}});
;/*!/index/widget/view/login/login.js*/
define("index/widget/view/login/login",function(e,n,i){function a(e){var n=l.FORM.username.val(),i=m.isUname(n),a=m.isNumber(n),r=m.isMobilePhone(n),o=n.length;if(n){if(!i&&!r)return a&&11!=o?(t("username","用户名为4-12位字母或数字，首字符不能为数字"),!1):r||11!=o?(t("username","请输入手机号码或4-12位用户名"),!1):(t("username","请输入正确的手机号码"),!1);if(i||r)return s("username",n),n}else if(e)return t("username","请输入用户名或11位手机号码"),n}function r(e){var n=l.FORM.pass.val(),i=(m.isNull(n),n.length);return n?6>i?(t("pass","密码为6-16位"),!1):(s("pass",n),n):e?(t("pass","请输入密码"),!1):void 0}function s(e,n,i){var a=i?i:d[e];l.TIPS[e].removeClass("red"),l.TIPS[e].html(a),l.PASS_MARKS[e].removeClass("invisible"),"pass"!=e&&(c[e]=!0,g[e]=n)}function t(e,n){l.TIPS[e].html(n),l.TIPS[e].addClass("red"),l.PASS_MARKS[e].addClass("invisible"),c[e]=!1,g[e]=""}function o(e){l.FORM[e].animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}var m=e("common/module/util.js"),u="/account/personalInfo";i.exports={init:function(e){e&&(u=e)}};var l={FORM:{username:$("#pageLogin-username"),pass:$("#pageLogin-pass"),remember:$("#pageLogin-remember")},TIPS:{},PASS_MARKS:{}},c={},g={};for(var p in l.FORM)l.TIPS[p]=l.FORM[p].siblings(".tip"),l.PASS_MARKS[p]=l.FORM[p].siblings(".right-mark"),c[p]=!1;var d={username:"请输入用户名或11位手机号码",pass:"请输入密码",code:"请输入验证码"};$("html body").on("click",".wrapper .pageLogin-content input",function(){var e=$(this),n=e.next(".right-mark");e.focus(),n.addClass("invisible")}),$("#pageLogin-pass").on("click",function(){$(this).val("")}),$("html body").on("blur",".wrapper .pageLogin-content #pageLogin-username",function(){a()}),$("html body").on("blur",".wrapper .pageLogin-content #pageLogin-pass",function(){r()});var f=$("#pageLogin-submit");f.click(function(){var e=a(1),n=r(1);e&&n?(f.html("正在登录..."),f.attr("disabled","disabled"),XDD.Request({url:"/index/do_login",data:{username:e,password:n},success:function(e){0==e.error_code?location.href=u:(_alert(e.error_msg),f.html("登 录"),f.removeAttr("disabled"))},error:function(){f.html("登 录"),f.removeAttr("disabled")}})):e?n||o("pass"):o("username")}),$(document).keyup(function(e){13==e.keyCode&&f.trigger("click")}),$("#pageLogin-pass").keydown(function(e){13==e.keyCode&&f.click()})});
;/*!/index/widget/view/reg/reg.js*/
define("index/widget/view/reg/reg",function(e,s,r){function t(){S.FORM.code.blur(function(){var e=i();e&&(S.TIPS.code.html("正在检查邀请码..."),a("code",e,function(s){0==s.error_code?(b("code",e,"邀请码可用"),S.BTNS.submit.removeClass("disable")):(f("code","请联系箱典典官方客服400-969-6790获取邀请码"),S.BTNS.submit.addClass("disable"))}))}),S.FORM.type.find(".radio-box").click(function(){var e=$(this);if(!e.hasClass("active")){e.addClass("active"),e.siblings(".radio-box").removeClass("active");var s=e.attr("type");S.FORM.type.attr("type",s)}}),S.FORM.username.blur(function(){var e=n();e&&(S.TIPS.username.html("正在检查用户名可用性..."),c("username",e,function(s){0==s.error_code?b("username",e,"用户名可用"):f("username","用户名已被注册（或不可用）")}))}),S.FORM.mobile.blur(function(){var e=o();e&&(S.TIPS.mobile.html("正在检查手机号可用性..."),c("mobile",e,function(s){0==s.error_code?b("mobile",e,"手机号可用"):f("mobile","手机号已被注册（或不可用）")}))}),S.FORM.mobile.keyup(function(e){var s=e.keyCode;37!=s&&38!=s&&39!=s&&40!=s&&S.FORM.mobile.val(S.FORM.mobile.val().replace(/\D/gi,""))}),S.BTNS.sms.click(function(){if(!S.BTNS.sms.hasClass("disable"))if(g.mobile)S.BTNS.sms.html("发送中..."),d(T.mobile);else{var e=o();e?(S.BTNS.sms.html("发送中..."),c("mobile",e,function(s){0==s.error_code?d(e):(S.BTNS.sms.html("发送短信"),f("mobile","手机号已被注册（或不可用）"),v("mobile"))})):v("mobile")}}),S.FORM.sms.blur(function(){m()}),S.FORM.pass.blur(function(){l()}),S.FORM.repass.blur(function(){u()}),S.BTNS.submit.click(function(){if(!g.code||S.BTNS.submit.hasClass("disable"))return f("code","请联系箱典典官方客服400-969-6790获取邀请码"),void _alert("请联系客服电话400-969-6790获取邀请码");var e=S.FORM.type.attr("type");if("freight_agent"!=e&&"carteam"!=e)return void f("type","请选择用户类型");b("type",e);var s={type:e},r=n(1);if(!r)return void v("username");s.userName=r;var t=o(1);if(!t)return void v("mobile");s.mobile=t;var i=m(1);if(!i)return void v("sms");s.smsCode=i;var a=l(1),c=u(1);return a?c?(s.pwd=c,S.FORM.agreement[0].checked?(S.BTNS.submit.html("正在注册..."),S.BTNS.submit.attr("disabled","disabled"),void XDD.Request({url:"/user/do_register",data:s,success:function(e){0==e.error_code?location.href="/account/enterpriseInfo":(_alert(e.error_msg),S.BTNS.submit.html("立即注册"),S.BTNS.submit.removeAttr("disabled"))},error:function(){S.BTNS.submit.html("立即注册"),S.BTNS.submit.removeAttr("disabled")}})):void _alert("请阅读并同意服务条款")):void v("repass"):void v("pass")})}function i(e){var s=S.FORM.code,r=s.val();return r?r:e?(v("code"),f("code","请联系箱典典官方客服400-969-6790获取邀请码"),!1):!1}function a(e,s,r){g[e]=!1,T[e]="",XDD.Request({type:"POST",url:"/user/checkregister",data:{register_code:s},success:function(e){"function"==typeof r&&r(e)}},!0)}function n(e){var s=S.FORM.username,r=s.val().replace(/\s{1,}/g,"").toLowerCase();return s.val(r),r?r.length<4?(f("username","用户名不少于4位"),!1):r.length>12?(f("username","用户名不多于12位"),!1):p.isUname(r)?r:(f("username","4-12位字母或数字，首字符不能为数字"),!1):e?(f("username","请输入用户名"),!1):!1}function o(e){var s=S.FORM.mobile,r=s.val();return r?11!==r.length?(f("mobile","请输入11位手机号"),!1):p.isMobilePhone(r)?r:(f("mobile","请输入正确的手机号"),!1):e?(f("mobile","请输入手机号"),!1):!1}function m(e){var s=S.FORM.sms,r=s.val();return r.length?4!==r.length?(f("sms","请输入4位短信验证码"),!1):p.isNumber(r)?(b("sms",r),r):(f("sms","短信验证码为4位数字"),!1):e?(f("sms","请输入短信验证码"),!1):!1}function l(e){var s=S.FORM.pass,r=s.val(),t=S.FORM.repass.val();return r.length?r.length<6||r.length>12?(f("pass","密码为6-16位"),!1):p.isPassword(r)?(t==r&&b("repass",t),b("pass",r),r):(f("pass","密码必须包含数字和字母"),!1):e?(f("pass","请输入密码"),!1):!1}function u(e){var s=S.FORM.repass,r=s.val(),t=S.FORM.pass.val();return r.length?r!==t?(f("repass","两次输入密码不一致"),!1):(b("repass",r),r):e?(f("repass","请再次输入密码"),!1):!1}function c(e,s,r){g[e]=!1,T[e]="",XDD.Request({type:"GET",url:"/index/checkExist",data:{mobileOrName:s},success:function(e){"function"==typeof r&&r(e)}})}function d(e){XDD.Request({type:"GET",url:"/user/sendSms",data:{mobile:e,smsType:"REGISTER"},success:function(e){if(0==e.error_code){_alert("已向您的手机发送了短信，请注意查收"),S.BTNS.sms.addClass("disable");var s=60,r=function(){1==s?(S.BTNS.sms.html("重发短信"),S.BTNS.sms.removeClass("disable"),clearTimeout(t)):(s--,S.BTNS.sms.html("重发("+s+")"),setTimeout(r,1e3))},t=setTimeout(r,1e3)}}})}function b(e,s,r){var t=r?r:h[e];S.TIPS[e].removeClass("red"),S.TIPS[e].html(t),S.PASS_MARKS[e].removeClass("invisible"),"pass"!=e&&(g[e]=!0,T[e]=s)}function f(e,s){S.TIPS[e].html(s),S.TIPS[e].addClass("red"),S.PASS_MARKS[e].addClass("invisible"),g[e]=!1,T[e]=""}function v(e){S.FORM[e].animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}var p=e("common/module/util.js"),S={FORM:{code:$("#reg-code"),type:$("#reg-type"),username:$("#reg-username"),mobile:$("#reg-mobile"),sms:$("#reg-sms"),pass:$("#reg-pass"),repass:$("#reg-repass"),agreement:$("#reg-agree")},TIPS:{},PASS_MARKS:{},BTNS:{sms:$("#get-sms"),submit:$("#reg-submit")}},g={},T={};for(var R in S.FORM)S.TIPS[R]=S.FORM[R].siblings(".tip"),S.PASS_MARKS[R]=S.FORM[R].siblings(".right-mark"),g[R]=!1;{var h={code:"请联系箱典典官方客服400-969-6790获取邀请码",type:"请选择用户类型",username:"4-12位字母或数字，首字符不能为数字",mobile:"可用于登录系统，找回密码",sms:"请查收手机短信，并填写短信中的验证码",pass:"6～16位字符，区分大小写",repass:"请再次输入密码"};$(".reg-content input")}r.exports={init:function(){t()}}});