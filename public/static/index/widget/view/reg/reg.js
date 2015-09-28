define('index/widget/view/reg/reg', function(require, exports, module) {

/*
 * 注册页面
 * by kenny
 */

var util = require('common/module/util.js');
var DOM = {
	FORM : {
        code : $('#reg-code'),
		type : $('#reg-type'),
		username : $('#reg-username'),
		mobile : $('#reg-mobile'),
		sms : $('#reg-sms'),
		pass : $('#reg-pass'),
		repass : $('#reg-repass'),
		agreement : $('#reg-agree')
	},
	TIPS : {},
	PASS_MARKS : {},
	BTNS : {
		sms : $('#get-sms'),
		submit : $('#reg-submit')
	}
},
PASS = {},
PASS_DATA = {};

for(var i in DOM.FORM){
	DOM.TIPS[i] = DOM.FORM[i].siblings('.tip');
	DOM.PASS_MARKS[i] = DOM.FORM[i].siblings('.right-mark');
	PASS[i] = false;
}

var MESSAGE = {
	type : '请选择用户类型',
	username : '4-12位字母或数字，首字符不能为数字',
	mobile : '可用于登录系统，找回密码',
	sms : '请查收手机短信，并填写短信中的验证码',
	pass : '6～16位字符，区分大小写',
	repass : '请再次输入密码'
}

function bind(){
    var status = 1;//1cuo0zheng

    //邀请码
    DOM.FORM.code.blur(function(){
        var code = checkCode();
        if(code){
            DOM.TIPS["code"].html("正在检查邀请码...");
            checkCodeRight('code',code,function(res){
                if(res.error_code == 0){
                    checkPassed("code",code,"邀请码可用");
                }else{
                    showError("code","请联系箱典典官方客服400-969-6790获取邀请码");
                    shake('code');
                }
            })
        }
    });

	// 类型
	DOM.FORM.type.find('.radio-box').click(function(){
		var obj = $(this);
		if(obj.hasClass('active'))return;

		obj.addClass('active');
		obj.siblings('.radio-box').removeClass('active');
		var type = obj.attr('type');
		DOM.FORM.type.attr('type',type);

	})

	// 用户名
	DOM.FORM.username.blur(function(){
		var name = checkUser();
		if(name){
			DOM.TIPS["username"].html("正在检查用户名可用性...");
			checkExist('username',name,function(res){
				if(res.error_code == 0){
					checkPassed("username",name,"用户名可用");
				}else{
					showError("username","用户名已被注册（或不可用）");
				}
			})
		}
	})

	// 手机号
	DOM.FORM.mobile.blur(function(){
		var mobile = checkMobile();
		if(mobile){
			DOM.TIPS["mobile"].html("正在检查手机号可用性...");
			checkExist('mobile',mobile,function(res){
				if(res.error_code == 0){
					checkPassed("mobile",mobile,'手机号可用');
				}else{
					showError("mobile","手机号已被注册（或不可用）");

				}
			})
		}
	})

	DOM.FORM.mobile.keyup(function(event) {
        var key = event.keyCode;
        if(key == 37 || key == 38 || key == 39 || key == 40)return;
		DOM.FORM.mobile.val(DOM.FORM.mobile.val().replace(/\D/gi,""));
	})



	// 短信
	DOM.BTNS.sms.click(function(){
		if(DOM.BTNS.sms.hasClass('disable'))return;
		if(PASS.mobile){
			DOM.BTNS.sms.html('发送中...')
			getSMS(PASS_DATA.mobile);
		}else{
			var mobile = checkMobile();
			if(mobile){
				DOM.BTNS.sms.html('发送中...')
				checkExist('mobile',mobile,function(res){
					if(res.error_code == 0){
						getSMS(mobile);
					}else{
						DOM.BTNS.sms.html('发送短信')
						showError("mobile","手机号已被注册（或不可用）");
						shake('mobile');
					}
				})
			}else{
				shake('mobile');
			}
		}
	})
	DOM.FORM.sms.blur(function(){
		checkSMS();
	})

	// 密码
	DOM.FORM.pass.blur(function(){
		checkPass();
	})
	DOM.FORM.repass.blur(function(){
		checkRepass();
	})

	// 提交
	DOM.BTNS.submit.click(function(){
        var code = checkCode(1);
        if(code){
            DOM.TIPS["code"].html("正在检查邀请码...");
            checkCodeRight('code',code,function(res){
                if(res.error_code == 0){
                    status = 0;
                    checkPassed("code",code,"邀请码可用");
                    console.log('status=',status);
                }else{
                    status = 1;
                    showError("code","请联系箱典典官方客服400-969-6790获取邀请码");
                    shake('code');
                }
            })
        }
        if(status == 1){
           return;
        }

		var type = DOM.FORM.type.attr('type');
		if(type == "freight_agent" || type == "carteam"){
			checkPassed("type",type);
		}else{
			showError("type","请选择用户类型");
			return;
		}
		var data = {'type':type};

		var data_username = checkUser(1);
		if(data_username){
			data['userName'] = data_username;
		}else{
			shake('username');
			return;
		}

		var data_mobile = checkMobile(1);
		if(data_mobile){
			data['mobile'] = data_mobile;
		}else{
			shake('mobile');
			return;
		}

		var data_sms = checkSMS(1);
		if(data_sms){
			data['smsCode'] = data_sms;
		}else{
			shake('sms');
			return;
		}

		var pass = checkPass(1),
			repass = checkRepass(1);

		if(!pass){
			shake('pass');
			return;
		}
		
		if(repass){
			data['pwd'] = repass;
		}else{
			shake('repass');
			return;
		}

		// 服务条款
		if(!DOM.FORM.agreement[0].checked){
			_alert('请阅读并同意服务条款');
			return;
		}
		DOM.BTNS.submit.html('正在注册...')
		XDD.Request({
	        url : '/user/do_register',
	        data: data,
	        success : function(result){
	            if(result.error_code == 0){
	            	location.href = "/account/enterpriseInfo";
	            }else{
	            	_alert(result.error_msg);
	            	DOM.BTNS.submit.html('立即注册')
	            }
	        },
	        error : function(){
            	DOM.BTNS.submit.html('立即注册')
	        }
	    })

	})
}
function checkCode(isSubmit){
    var obj = DOM.FORM.code,
        data_code = obj.val();
    if(data_code){

    }else if(isSubmit){
        shake('code');
        showError("code","请联系箱典典官方客服400-969-6790获取邀请码");
        return false;
    }else{
        return false;
    }
    return data_code;
}

function checkCodeRight(obj,code,callback){
    PASS[obj] = false;
    PASS_DATA[obj] = '';
    XDD.Request({
        type: 'POST',
        url : '/user/checkregister',
        data: {
            register_code : code
        },
        success : function(result){
            typeof callback === 'function' && callback(result);
        }
    },true)
}

function checkUser(isSubmit){

	var obj = DOM.FORM.username,
		data_name = obj.val().replace(/\s{1,}/g,"").toLowerCase();
	
	obj.val(data_name);

	if(data_name){
		if(data_name.length < 4){
			showError("username","用户名不少于4位");
			return false;
		}

		if(data_name.length >12){
			showError("username","用户名不多于12位");
			return false;
		}

		if(!util.isUname(data_name)){
			showError("username","4-12位字母或数字，首字符不能为数字");
			return false;
		}
	}else if(isSubmit){
		showError("username","请输入用户名");
		return false;
	}else{
		return false;
	}
	return data_name;
}

function checkMobile(isSubmit){

	var obj = DOM.FORM.mobile,
		data_mobile = obj.val();

	if(data_mobile){
		if(data_mobile.length !== 11){
			showError("mobile","请输入11位手机号");
			return false;
		}
		if(!util.isMobilePhone(data_mobile)){
			showError("mobile","请输入正确的手机号");
			return false;
		}
	}else if(isSubmit){
		showError("mobile","请输入手机号");
		return false;
	}else{
		return false;
	}
	return data_mobile;
}

function checkSMS(isSubmit){

	var obj = DOM.FORM.sms,
		data_sms = obj.val();

	if(data_sms.length){
		if(data_sms.length !== 4){
			showError("sms","请输入4位短信验证码");
			return false;
		}
		if(!util.isNumber(data_sms)){
			showError("sms","短信验证码为4位数字");
			return false;
		}
	}else if(isSubmit){
		showError("sms","请输入短信验证码");
		return false;
	}else{
		return false;
	}
	checkPassed("sms",data_sms);
	return data_sms;
}

function checkPass(isSubmit){

	var obj = DOM.FORM.pass,
		data_pass = obj.val(),
		data_repass = DOM.FORM.repass.val();

	if(data_pass.length){
		if(data_pass.length < 6 || data_pass.length > 12){
			showError("pass","密码为6-16位");
			return false;
		}
		if(!util.isPassword(data_pass)){
			showError("pass","密码必须包含数字和字母");
			return false;
		}
	}else if(isSubmit){
		showError("pass","请输入密码");
		return false;
	}else{
		return false;
	}

	if(data_repass == data_pass){
		checkPassed("repass",data_repass);
	}

	checkPassed("pass",data_pass);
	return data_pass;
}

function checkRepass(isSubmit){

	var obj = DOM.FORM.repass,
		data_repass = obj.val(),
		data_pass = DOM.FORM.pass.val();
	if(data_repass.length){
		if(data_repass !== data_pass){
			showError("repass","两次输入密码不一致");
			return false;
		}
	}else if(isSubmit){
		showError("repass","请再次输入密码");
		return false;
	}else{
		return false;
	}

	checkPassed("repass",data_repass);
	return data_repass;
}

function checkExist(obj,mobileOrName,callback){
	PASS[obj] = false;
	PASS_DATA[obj] = '';
	XDD.Request({
        type: 'GET',
        url : '/index/checkExist',
        data: {
            mobileOrName : mobileOrName
        },
        success : function(result){
            typeof callback === 'function' && callback(result);
        }
    })
}

function getSMS(mobile){
	XDD.Request({
        type: 'GET',
        url : '/user/sendSms',
        data: {
            mobile : mobile,
            smsType : "REGISTER"
        },
        success : function(result){
            if(result.error_code == 0){
            	_alert('已向您的手机发送了短信，请注意查收')
            	DOM.BTNS.sms.addClass('disable');
            	var count = 60;
                var setcount = function() {
                    if(count == 1) {
                        DOM.BTNS.sms.html("重发短信");
                        DOM.BTNS.sms.removeClass('disable');
                        clearTimeout(timeout);
                    }
                    else {
                        count--;
                        DOM.BTNS.sms.html("重发(" + count + ")");
                        setTimeout(setcount, 1000);
                    }
                };
                var timeout = setTimeout(setcount, 1000);
            }else{
            	// on send sms fail
            }
        }
    })
}

function checkPassed(obj,value,message){
	var msg = message ? message : MESSAGE[obj];
	DOM.TIPS[obj].removeClass('red');
	DOM.TIPS[obj].html(msg);
	DOM.PASS_MARKS[obj].removeClass('invisible');
	if(obj == 'pass'){
		return;
	}
	PASS[obj] = true;
	PASS_DATA[obj] = value;
}

function showError(obj,txt){
	DOM.TIPS[obj].html(txt);
	DOM.TIPS[obj].addClass('red');
	DOM.PASS_MARKS[obj].addClass('invisible');
	PASS[obj] = false;
	PASS_DATA[obj] = '';
}

function shake(obj){ 
	DOM.FORM[obj].animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
	   .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40)
	   .animate({marginLeft:"0px"},40).focus();
}

module.exports = {
	init : function(){
		bind();
	}
}

});
