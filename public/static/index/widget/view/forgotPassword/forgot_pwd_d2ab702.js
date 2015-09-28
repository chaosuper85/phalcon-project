define("index/widget/view/forgotPassword/forgot_pwd",function(i,e,o){function t(){DOM={Module:{forgotPwd:$("#forgotPassword"),newPwd:$("#new_password"),finshPwd:$("#finsh_password"),regAccount:$(".sign-account")},FORM:{account:$("#pwd_account"),mobile:$("#pwd_msg"),newPwd:$("#new_pwd"),confirmPwd:$("#confirm_pwd")},Btn:{nextStep:$("#next_step"),confirm:$("#next_confirm"),gotoIndex:$("#goto_index"),msg:$("#btn_getMsg")},notice:{accountNotice:$(".xdd-mobile"),msgNotice:$(".xdd-msg"),newPwdNotice:$(".xdd-newpwd"),reConfirm:$(".xdd-repwd")}},n(),s(),c(),l(),$("input[name=changePwd]").focus(function(){var i=$(this);i.siblings(".tip").css("color","#999999"),i.siblings(".right-mark").addClass("invisible")}),DOM.Btn.nextStep.click(function(i){i.preventDefault(),n(),c();var e=DOM.FORM.account.val(),o=DOM.FORM.mobile.val();return""==e||null==e?(DOM.notice.accountNotice.html("请输入箱典典注册手机号码"),DOM.notice.accountNotice.css("color","#a94442"),!1):""==o||null==o?(DOM.notice.msgNotice.html("请输入四位数字的手机验证码"),DOM.notice.msgNotice.css("color","#a94442"),!1):void XDD.Request({type:"POST",url:"/user/validateSmsCode",data:{mobile:DOM.FORM.account.val(),code:DOM.FORM.mobile.val()},success:function(i){return 0!=i.error_code?(DOM.notice.msgNotice.html("验证码不正确"),DOM.notice.msgNotice.css("color","#a94442"),DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible"),DOM.Btn.nextStep.attr("disabled",!0),!1):(DOM.notice.msgNotice.html("验证码正确"),DOM.notice.msgNotice.siblings(".right-mark").removeClass("invisible"),DOM.Module.forgotPwd.hide(),DOM.Module.newPwd.show(),void 0)}})}),DOM.Btn.msg.click(function(){n(),XDD.Request({type:"GET",url:"/user/sendSms",data:{mobile:DOM.FORM.account.val(),smsType:"CHANGE_PWD"},success:function(i){if(0==i.error_code){_alert("已向您的手机发送了短信，请注意查收"),DOM.Btn.msg.attr("disabled",!0),count=60;var e=function(){1==count?(DOM.Btn.msg.html("重发短信"),DOM.Btn.msg.attr("disabled",!0),clearTimeout(o)):(count--,DOM.Btn.msg.html("("+count+")后可重新发送"),setTimeout(e,1e3))},o=setTimeout(e,1e3)}}})}),DOM.Btn.confirm.click(function(i){i.preventDefault();var e=DOM.FORM.newPwd.val();if(null==e||""==e)return DOM.notice.newPwdNotice.html("新密码不能为空"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1;if(e.length<6||e.length>15)return DOM.notice.newPwdNotice.html("密码字符长度应在6～15位"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1;if(!a.isPassword(e))return DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1;DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合"),DOM.notice.newPwdNotice.css("color","#999999"),DOM.notice.newPwdNotice.siblings(".right-mark").removeClass("invisible");var e=DOM.FORM.newPwd,o=DOM.FORM.confirmPwd;return e.val()!==o.val()?(DOM.notice.reConfirm.html("两次输入密码不一致"),DOM.notice.reConfirm.css("color","#a94442"),DOM.notice.reConfirm.siblings(".right-mark").addClass("invisible"),!1):(DOM.notice.reConfirm.siblings(".right-mark").removeClass("invisible"),XDD.Request({type:"POST",url:"/user/changePwd ",data:{newPwd:DOM.FORM.confirmPwd.val(),mobile:DOM.FORM.account.val()},success:function(i){0==i.error_code?(_alert(i.error_msg),DOM.Module.newPwd.hide(),DOM.Module.finshPwd.show()):_alert(i.error_msg)}}),void r(5))}),DOM.Btn.gotoIndex.click(function(i){i.preventDefault(),window.location.href="/index"})}function n(){DOM.FORM.account.blur(function(){var i=DOM.FORM.account.val();return 11!==i.length?(DOM.notice.accountNotice.html("请输入11位箱典典注册手机号码"),DOM.notice.accountNotice.css("color","#a94442"),DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible"),!1):a.isMobilePhone(i)?null==i||""==i?(DOM.notice.accountNotice.html("手机号码不能为空"),DOM.notice.accountNotice.css("color","#a94442"),DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible"),!1):(DOM.notice.accountNotice.html("箱点点注册手机号码"),DOM.notice.accountNotice.css("color","#999999"),void XDD.Request({type:"GET",url:"/index/checkMobileExist",data:{mobileOrName:DOM.FORM.account.val()},success:function(i){return 1!=i.error_code?(DOM.notice.accountNotice.html(i.error_msg),DOM.notice.accountNotice.css("color","#a94442"),DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible"),DOM.Btn.msg.attr("disabled",!0),!1):(DOM.notice.accountNotice.html("手机号正确"),void DOM.notice.accountNotice.siblings(".right-mark").removeClass("invisible"))}})):(DOM.notice.accountNotice.html("请输入11位箱典典注册手机号码"),DOM.notice.accountNotice.css("color","#a94442"),DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible"),!1)})}function c(){DOM.FORM.mobile.blur(function(){var i=DOM.FORM.mobile.val();return 4!==i.length?(DOM.notice.msgNotice.html("请输入四位数字的手机验证码"),DOM.notice.msgNotice.css("color","#a94442"),DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible"),!1):a.isNumber(i)?void DOM.notice.msgNotice.siblings(".right-mark").removeClass("invisible"):(DOM.notice.msgNotice.html("请输入四位数字的手机验证码"),DOM.notice.msgNotice.css("color","#a94442"),DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible"),!1)})}function s(){DOM.FORM.newPwd.blur(function(){var i=DOM.FORM.newPwd.val();return null==i||""==i?(DOM.notice.newPwdNotice.html("新密码不能为空"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1):i.length<6||i.length>15?(DOM.notice.newPwdNotice.html("密码字符长度应在6～15位"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1):a.isPassword(i)?(DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合"),DOM.notice.newPwdNotice.css("color","#999999"),DOM.notice.newPwdNotice.siblings(".right-mark").removeClass("invisible"),void 0):(DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合"),DOM.notice.newPwdNotice.css("color","#a94442"),DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible"),!1)})}function l(){DOM.FORM.confirmPwd.blur(function(){var i=DOM.FORM.newPwd,e=DOM.FORM.confirmPwd;return i.val()!==e.val()?(DOM.notice.reConfirm.html("两次输入密码不一致"),DOM.notice.reConfirm.css("color","#a94442"),DOM.notice.reConfirm.siblings(".right-mark").addClass("invisible"),!1):void DOM.notice.reConfirm.siblings(".right-mark").removeClass("invisible")})}function r(i){var e=DOM.FORM.account.val();DOM.Module.regAccount.html(e),DOM.Btn.gotoIndex.html(i+"秒跳转到首页"),0==i?window.location.href="/index":(i-=1,setTimeout(function(){r(i)},1e3))}var a=i("common/module/util.js");o.exports={init:function(){t()}}});