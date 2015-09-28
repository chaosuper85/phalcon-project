define('index/widget/view/login/login', function(require, exports, module) {

/**
 * Created by wll on 15/7/27.
 */
var util = require('common/module/util.js'),
    from_url = "/account/personalInfo";


module.exports = {
    init : function(from){
        if(from){
            from_url = from;
        }
    }
}

var DOM = {
        FORM : {
            username : $('#pageLogin-username'),
            pass : $('#pageLogin-pass'),
            remember : $('#pageLogin-remember')
        },
        TIPS : {},
        PASS_MARKS : {},
        BTNS : {
            submit : $('#pageLogin-submit')
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
    username : '请输入用户名或11位手机号码',
    pass : '请输入密码',
    code : '请输入验证码'
}

//点击input消除正确提示
$('html body').on('click','.wrapper .pageLogin-content input',function(){
    var _this = $(this),
        success = _this.next('.right-mark');
        _this.focus();
        success.addClass('invisible');
});

//点击清除密码
$('#pageLogin-pass').on('click',function(){
   $(this).val('');
});

//检查用户
function checkLoginUser(isSubmit){
    var username = DOM.FORM.username.val(),
        right = util.isUname(username),
        number = util.isNumber(username),
        mobile = util.isMobilePhone(username),
        length = username.length;
        
    if(username) {
        if (!right && !mobile) {
            if (number && length != 11) {
                showError('username', '用户名为4-12位字母或数字，首字符不能为数字');
                return false;
            }
            if (!mobile && length == 11) {
                showError('username', '请输入正确的手机号码');
                return false;
            }
            else {
                showError('username', '请输入手机号码或4-12位用户名');
                return false;
            }
        } else if (right || mobile) {
            checkPassed('username', username);
            return username;
        }
    }else if(isSubmit){
        showError("username", "请输入用户名或11位手机号码");
        return username;
    }
}

//检查密码
function checkLoginPass(isSubmit){
    var pass = DOM.FORM.pass.val(),
        kong = util.isNull(pass),
        length = pass.length;

    if(pass) {
        if (length < 6) {
                showError('pass', '密码为6-16位');
                return false;
        } else {
                checkPassed('pass', pass);
                return pass;
            }
    }else if(isSubmit){
        showError('pass', '请输入密码');
        return false;
    }

}

//用户名、手机号验证
$('html body').on('blur','.wrapper .pageLogin-content #pageLogin-username',function(){
    checkLoginUser();
});

 //密码验证
$('html body').on('blur','.wrapper .pageLogin-content #pageLogin-pass',function() {
    checkLoginPass();
});

//登录
var $submit = $('#pageLogin-submit');

$submit.click(function() {
    var _username  =  checkLoginUser(1);
    var _pass = checkLoginPass(1);

    if(_username&&_pass){
        
        $submit.html('正在登录...')

        XDD.Request({
            url : '/index/do_login',
            data: {
                username : _username,
                password : _pass
            },
            success : function(result){
                if(result.error_code == 0){
                    location.href = from_url;
                }else{
                    _alert(result.error_msg);
                }
                $submit.html('登 录')
            },
            error : function(){
                $submit.html('登 录')
            }
        })
    }else if(!_username){
        shake('username');
    }else if(!_pass){
        shake('pass');
    }
});

$(document).keyup(function(event){
    if(event.keyCode ==13){
        $submit.trigger("click");
    }
});

// 回车登录
$('#pageLogin-pass').keydown(function(event){
    if(event.keyCode==13){
        $('#pageLogin-submit').click();
    }
});

/*以下为reg.js中的方法*/
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

//错误提示
function showError(obj,txt){
    DOM.TIPS[obj].html(txt);
    DOM.TIPS[obj].addClass('red');
    DOM.PASS_MARKS[obj].addClass('invisible');
    PASS[obj] = false;
    PASS_DATA[obj] = '';
}

//晃动
function shake(obj){
    DOM.FORM[obj].animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
        .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40)
        .animate({marginLeft:"0px"},40).focus();
}



});
