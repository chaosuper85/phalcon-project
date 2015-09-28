define('index/widget/view/forgotPassword/forgot_pwd', function(require, exports, module) {

var util = require('common/module/util.js');

function bind(){
	DOM = {
		Module:	{
			forgotPwd:$("#forgotPassword"),
			newPwd:$("#new_password"),
			finshPwd:$("#finsh_password"),
			regAccount:$(".sign-account")
		},
		FORM:{
			account:$("#pwd_account"),
			mobile:$("#pwd_msg"),
			newPwd:$("#new_pwd"),
			confirmPwd:$("#confirm_pwd")
		},
		Btn:{
			nextStep:$("#next_step"),
			confirm:$("#next_confirm"),
			gotoIndex:$("#goto_index"),
			msg:$("#btn_getMsg")
		},
		notice:{
			accountNotice:$(".xdd-mobile"),
			msgNotice:$(".xdd-msg"),
			newPwdNotice:$(".xdd-newpwd"),
			reConfirm:$(".xdd-repwd")
		}
	}
	checkMobile();
	checkNewPwd();
	checkMsg();
	confirmPwd();

	$('input[name=changePwd]').focus(function(){
		var obj = $(this);
		obj.siblings(".tip").css("color","#999999");
		obj.siblings(".right-mark").addClass("invisible");
	});
	//找回密码第一步
	DOM.Btn.nextStep.click(function(e){
		e.preventDefault();
		checkMobile();
		checkMsg();

		var formAccount = DOM.FORM.account.val();
		var formMobile = DOM.FORM.mobile.val();
		if(formAccount=="" || formAccount==null){
			DOM.notice.accountNotice.html("请输入箱典典注册手机号码");
			DOM.notice.accountNotice.css("color","#a94442");
			return false;
		}
		if(formMobile=="" || formMobile==null){
			DOM.notice.msgNotice.html("请输入四位数字的手机验证码");
			DOM.notice.msgNotice.css("color","#a94442");
			return false;
		}

		XDD.Request({
			type:'POST',
			url:'/user/validateSmsCode',
			data:{
				mobile:DOM.FORM.account.val(),
				code:DOM.FORM.mobile.val()
			},
			success:function(result){
				if(result.error_code==0){
					DOM.notice.msgNotice.html("验证码正确");
					DOM.notice.msgNotice.siblings(".right-mark").removeClass("invisible");
					DOM.Module.forgotPwd.hide();
					DOM.Module.newPwd.show();
				}
				else{
					DOM.notice.msgNotice.html("验证码不正确");
					DOM.notice.msgNotice.css("color","#a94442");
					DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible");
					DOM.Btn.nextStep.attr("disabled",true);
					return false;
				}
			}
		});


	});
	//发送短信验证码
	DOM.Btn.msg.click(function(){
		checkMobile();
		XDD.Request({
			type:'GET',
			url:'/user/sendSms',
			data:{
				mobile:DOM.FORM.account.val(),
				smsType:"CHANGE_PWD"
			},
			success:function(result){
				if(result.error_code==0){
					_alert('已向您的手机发送了短信，请注意查收');
					DOM.Btn.msg.attr("disabled",true);
					count=60;
					var setcount = function(){
						if(count==1){
							DOM.Btn.msg.html("重发短信");
							DOM.Btn.msg.attr("disabled",true);
							clearTimeout(timeout);
						}
						else{
							count--;
							DOM.Btn.msg.html("(" + count + ")后可重新发送");
							setTimeout(setcount,1000);
						}
					};
					var timeout = setTimeout(setcount,1000);
				}
				else{

				}
			}
		});
	});

	//找回密码第二步
	DOM.Btn.confirm.click(function(e){
		e.preventDefault();
		//checkNewPwd();

		var pwd = DOM.FORM.newPwd.val();
		if(pwd==null || pwd==""){
			DOM.notice.newPwdNotice.html("新密码不能为空");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(pwd.length<6 || pwd.length>15){
			DOM.notice.newPwdNotice.html("密码字符长度应在6～15位");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(!util.isPassword(pwd)){
			DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合");
			DOM.notice.newPwdNotice.css("color","#999999");
			DOM.notice.newPwdNotice.siblings(".right-mark").removeClass("invisible");
		}

		var pwd = DOM.FORM.newPwd;
		var confirm = DOM.FORM.confirmPwd;
		if(pwd.val()!==confirm.val()){
			DOM.notice.reConfirm.html("两次输入密码不一致");
			DOM.notice.reConfirm.css("color","#a94442");
			DOM.notice.reConfirm.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.reConfirm.siblings(".right-mark").removeClass("invisible");
		}

		XDD.Request({
			type:'POST',
			url:'/user/changePwd ',
			data:{
				newPwd:DOM.FORM.confirmPwd.val(),
				mobile:DOM.FORM.account.val()
			},
			success:function(result){
				if(result.error_code==0){
					_alert(result.error_msg);
					DOM.Module.newPwd.hide();
					DOM.Module.finshPwd.show();
				}
				else{
					_alert(result.error_msg);
				}
			}
		});



		countDown(5);

	});

	//找回密码完成
	DOM.Btn.gotoIndex.click(function(e){
		e.preventDefault();
		window.location.href="/index";
	});



}
//核对注册手机号码
function checkMobile(){
	DOM.FORM.account.blur(function(){
		var xdd_account = DOM.FORM.account.val();
		if(xdd_account.length!==11){
			DOM.notice.accountNotice.html("请输入11位箱典典注册手机号码");
			DOM.notice.accountNotice.css("color","#a94442");
			DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(!util.isMobilePhone(xdd_account)){
			DOM.notice.accountNotice.html("请输入11位箱典典注册手机号码");
			DOM.notice.accountNotice.css("color","#a94442");
			DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(xdd_account==null || xdd_account==""){
			DOM.notice.accountNotice.html("手机号码不能为空");
			DOM.notice.accountNotice.css("color","#a94442");
			DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.accountNotice.html("箱点点注册手机号码");
			DOM.notice.accountNotice.css("color","#999999");
		}

		XDD.Request({
			type:'GET',
			url:'/index/checkMobileExist',
			data:{
				mobileOrName:DOM.FORM.account.val()
			},
			success:function(result){
				if(result.error_code==1){
					DOM.notice.accountNotice.html("手机号正确");
					DOM.notice.accountNotice.siblings(".right-mark").removeClass("invisible");
				}
				else{
					DOM.notice.accountNotice.html(result.error_msg);
					DOM.notice.accountNotice.css("color","#a94442");
					DOM.notice.accountNotice.siblings(".right-mark").addClass("invisible");
					DOM.Btn.msg.attr("disabled",true);
					return false;
				}
			}
		});
	});
}
//核对手机验证码
function checkMsg(){
	DOM.FORM.mobile.blur(function(){
		var msgVerify = DOM.FORM.mobile.val();
		if(msgVerify.length!==4){
			DOM.notice.msgNotice.html("请输入四位数字的手机验证码");
			DOM.notice.msgNotice.css("color","#a94442");
			DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(!util.isNumber(msgVerify)){
			DOM.notice.msgNotice.html("请输入四位数字的手机验证码");
			DOM.notice.msgNotice.css("color","#a94442");
			DOM.notice.msgNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.msgNotice.siblings(".right-mark").removeClass("invisible");
		}


	});
}

//核对密码要求
function checkNewPwd(){
	DOM.FORM.newPwd.blur(function(){
		var pwd = DOM.FORM.newPwd.val();
		if(pwd==null || pwd==""){
			DOM.notice.newPwdNotice.html("新密码不能为空");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(pwd.length<6 || pwd.length>15){
			DOM.notice.newPwdNotice.html("密码字符长度应在6～15位");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		if(!util.isPassword(pwd)){
			DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合");
			DOM.notice.newPwdNotice.css("color","#a94442");
			DOM.notice.newPwdNotice.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.newPwdNotice.html("密码必须是数字和字母的组合");
			DOM.notice.newPwdNotice.css("color","#999999");
			DOM.notice.newPwdNotice.siblings(".right-mark").removeClass("invisible");
		}
	});
}
//确认两次输入密码一致
function confirmPwd(){
	DOM.FORM.confirmPwd.blur(function(){
		var pwd = DOM.FORM.newPwd;
		var confirm = DOM.FORM.confirmPwd;
		if(pwd.val()!==confirm.val()){
			DOM.notice.reConfirm.html("两次输入密码不一致");
			DOM.notice.reConfirm.css("color","#a94442");
			DOM.notice.reConfirm.siblings(".right-mark").addClass("invisible");
			return false;
		}
		else{
			DOM.notice.reConfirm.siblings(".right-mark").removeClass("invisible");
		}
	});
}
//5s跳转到首页
function countDown(count){
	var regMobile = DOM.FORM.account.val();
	DOM.Module.regAccount.html(regMobile);

	DOM.Btn.gotoIndex.html(count+"秒跳转到首页");
	if(count == 0){
		window.location.href="/index";
	} 
	else{
		//count=5;
		count-=1;
		setTimeout(function(){
			countDown(count);
		},1000);
	}
}







module.exports = {
	init : function(){
		bind();
	}
}

});
