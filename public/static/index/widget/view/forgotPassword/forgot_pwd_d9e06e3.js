define("index/widget/view/forgotPassword/forgot_pwd",function(s,e,i){function t(){var s=$("#getSms"),e=!1,i=!1,t=$("#submit_step1");d.FORM.mobile.on("keyup",function(){var s=$(this),e=s.val();e&&11==e.length&&n.isMobilePhone(e)?o(e):(d.TIPS.mobile.html(m.mobile),d.TIPS.mobile.css("color","#999"),p="")});var o=function(i){d.TIPS.mobile.html("正在检测手机号..."),XDD.Request({type:"GET",url:"/index/checkMobileExist",data:{mobileOrName:i},success:function(t){1==t.error_code?(p=i,r("mobile","手机号正确"),e||s.removeClass("disable")):(p="",l("mobile",t.error_msg))}})};s.on("click",function(){c()});var c=function(){s.hasClass("disable")||(s.html("正在发送短信...").addClass("disable"),i=!1,XDD.Request({type:"GET",url:"/user/sendSms",data:{mobile:p,smsType:"CHANGE_PWD"},success:function(t){if(0==t.error_code){_alert("已向您手机发送了短信验证码，请查收"),i=!0,e=!0;var a=60,o=function(){1==a?(s.html("重发短信"),s.removeClass("disable"),clearTimeout(l),e=!1):(a--,s.html("("+a+"s)后可重新发送"),setTimeout(o,1e3))},l=o()}else _alert(t.error_msg)}}))};d.FORM.sms.on("focus",function(){i?(d.TIPS.sms.html("请输入4位短信验证码"),d.TIPS.sms.css("color","#999")):l("sms","请先获取短信验证码")}),d.FORM.sms.on("keyup",function(){if(i){var s=$(this),e=s.val();e&&4==e.length&&n.isNumber(e)&&(t.removeClass("disable"),r("sms","短信验证码格式正确"))}}),d.FORM.sms.on("blur",function(){v()});var v=function(){if(i){var s=d.FORM.sms,e=s.val();return e&&4===e.length&&n.isNumber(e)?void r("sms","短信验证码格式正确"):void l("sms","短信验证码为4位纯数字")}};t.on("click",function(){if(!t.hasClass("disable")){if(!u.mobile)return void _alert("手机号不存在或手机格式不正确");if(!u.sms)return void _alert("未发送短信或验证码格式不正确");t.html("验证中..."),XDD.Request({type:"POST",url:"/user/validateSmsCode",data:{mobile:p,code:d.FORM.sms.val(),smsType:"CHANGE_PWD"},success:function(s){0==s.error_code?(d.PAGE.step1.hide(),d.PAGE.step2.show(),d.STEP.step1.removeClass("cur").addClass("finish"),d.STEP.step2.addClass("cur"),a()):_alert(s.error_msg),t.html("下一步")}})}})}function a(){var s=$("#submit_step2");d.FORM.pass.on("blur",function(){var s=$(this),e=s.val();return e?!n.isPassword(e)||e.length<6||e.length>15?void l("pass","密码必须包含数字和字母(6~15位)"):void r("pass","密码格式正确"):void l("pass","请输入密码")}),d.FORM.pass.on("keyup",function(){var e=$(this),i=e.val(),t=d.FORM.repass.val();t&&(i==t?(r("repass","两次密码一致"),n.isPassword(i)&&i.length>=6&&i.length<=15?s.removeClass("disable"):s.addClass("disable")):(l("repass","两次输入密码不一致"),s.addClass("disable")))}),d.FORM.repass.on("keyup",function(){var e=$(this),i=e.val(),t=d.FORM.pass.val();return t?void(i==t?(r("repass","两次密码一致"),n.isPassword(i)&&i.length>=6&&i.length<=15?s.removeClass("disable"):s.addClass("disable")):(l("repass","两次输入密码不一致"),s.addClass("disable"))):void l("repass","请先输入新密码")}),s.on("click",function(){var s=$(this);s.hasClass("disable")||(s.html("请稍候..."),XDD.Request({type:"POST",url:"/user/changePwd ",data:{newPwd:d.FORM.pass.val(),mobile:p},success:function(e){0==e.error_code?(d.PAGE.step2.hide(),d.PAGE.step3.show(),d.STEP.step2.removeClass("cur").addClass("finish"),d.STEP.step3.addClass("cur"),o()):_alert(e.error_msg),s.html("提 交")}}))})}function o(){var s=5,e=$("#submit_step3"),i=function(){0==s?(location.href="/index",clearTimeout(t)):(e.html(s+"秒自动回到首页"),s--,setTimeout(i,1e3))},t=i()}function l(s,e){d.TIPS[s].html(e),d.TIPS[s].css("color","red"),d.PASS_MARKS[s].addClass("invisible"),u[s]=!1}function r(s,e){d.TIPS[s].html(e),d.TIPS[s].css("color","#999"),d.PASS_MARKS[s].removeClass("invisible"),u[s]=!0}var n=s("common/module/util.js"),d={PAGE:{step1:$("#step1"),step2:$("#step2"),step3:$("#step3")},STEP:{step1:$(".step1"),step2:$(".step2"),step3:$(".step3")},FORM:{mobile:$("#data_mobile"),sms:$("#data_sms"),pass:$("#data_pass"),repass:$("#data_repass")},PASS_MARKS:{},TIPS:{}},m={},u={};for(var c in d.FORM)d.TIPS[c]=d.FORM[c].siblings(".tip"),d.PASS_MARKS[c]=d.FORM[c].siblings(".right-mark"),m[c]=d.TIPS[c].html(),u[c]=!1;var p;i.exports={init:function(){$("#forgot_pwd input").focus(function(){var s=$(this);s.siblings(".tip").css("color","#999"),s.siblings(".right-mark").addClass("invisible")}),t()}}});