define('user/widget/view/account_security/change_phone', function(require, exports, module) {

/**
 * Created by wll on 15/8/10.
 */
var util = require('common/module/util.js');

function bind(){
    //获取url参数
    var _url = window.location.href,
        _id = _url.split('?')[1];
/*第一步*/
    stepOne();
    //根据url获取step_one显示
    function stepOne(){
        if(_id == 'pass'){
            $('#step-one-pass').show();
        }else if(_id == 'sms'){
            $('#step-one-sms').show();
        }
    }

    //点击下一步
    $('.next-step-one').on('click',function(){
        if(_id == 'pass') {
            var _pass = checkPass(1);
            if(_pass){
                XDD.Request({
                    url: '/user/validatePwd',
                    data: {
                        pwd: _pass
                    },
                    success: function (result) {
                        console.log(result);
                        if (result.error_code == 0) {
                            stepTwo();

                        } else {
                            _alert(result.error_msg)
                        }
                    }
            })
        }
        }else if(_id == 'sms') {
            var _sms = checkSms(1);
            if(_sms){
                XDD.Request({
                    //todo 去掉type,修改页面变量
                    url: '/user/validateSmsCode',
                    type:'GET',
                    data: {
                        code: _sms
                    },
                    success: function (result) {
                        console.log(result);
                        if (result.error_code == 0) {
                            stepTwo();

                        } else {
                            _alert(result.error_msg)
                        }
                    }
                })
            }
        }
    });


    //检查密码
    function checkPass(isSubmit){
        var _pass = $('#step-one-password').val(),
            _right = util.isPassword(_pass);
        if(_pass.length) {
            if (_pass.length < 6) {
                shake('#step-one-password');
                showError('.tip-pass', '密码为6-16位');
                return;
            } else if(!_right){
                shake('#step-one-password');
                showError('.tip-pass', '密码必须包含字母和数字');
                return;
            }else {
                $('.tip-pass').html('');
                return _pass;

            }
        }else if(isSubmit){
            showError('.tip', '请输入密码');
            return;
        }
    }

    //点击获取验证码
    $('#get-sms').on('click',function(){
        var _disable =$(this).hasClass('disable');
        if(_disable){
            return;
        }else {
            getSMS();
        }
    });

    //获取验证码
    function getSMS(){
        XDD.Request({
            type: 'GET',
            url : '/user/sendSms',
            data: {
                smsType : "NORMAL_AUTH"
            },
            success : function(result){
                if(result.error_code == 0){
                    _alert('已向您的手机发送了短信，请注意查收')
                    var count = 60;
                    var setcount = function() {
                        if(count == 1) {
                            $('#get-sms').html("重发短信");
                            $('#get-sms').removeClass('disable')
                            clearTimeout(timeout);
                        }
                        else {
                            count--;
                            $('#get-sms').html("重发(" + count + ")");
                            $('#get-sms').addClass('disable');
                            setTimeout(setcount, 1000);
                        }
                    };
                    var timeout = setTimeout(setcount, 1000);
                }else{
                    _alert(result.error_msg);
                }
            }
        })
    }

    //验证验证码
    function checkSms(isSubmit){
        var _sms = $('#change-phone-sms').val();
        if(_sms.length){
            if(_sms.length !== 4){
                showError(".tip-sms","请输入4位短信验证码");
                shake('#change-phone-sms');
                return;
            }
            if(!util.isNumber(_sms)){
                showError(".tip-sms","短信验证码为4位数字");
                shake('#change-phone-sms');
                return;
            }
            else{
                $('.tip-sms').html('');
                return _sms;
            }
        }else if(isSubmit){
            showError(".tip-sms","请输入短信验证码");
            shake('#change-phone-sms');
            return;
        }else{
            return;
        }
    }



/*第二步*/
    //显示第二步
    function stepTwo(){
        $('.nav-one').removeClass('current').addClass('finshed');
        $('.nav-two').addClass('current');
        $('#step-one-sms').hide();
        $('#step-one-pass').hide();
        $('#step-two').show();
    }

    //手机号码检测
    function checkMobile(isSubmit){
        var _mobile =$('#new-mobile').val();
        if(_mobile){
            if(_mobile.length !== 11){
                shake("#new-mobile");
                showError(".tip-newmobile","请输入11位手机号");
                return;
            }
            if(!util.isMobilePhone(_mobile)){
                shake("#new-mobile");
                showError(".tip-newmobile","请输入正确的手机号");
                return;
            }
        }else if(isSubmit){
            shake("#new-mobile");
            showError(".tip-newmobile","请输入手机号");
            return;
        }else{
            shake("#new-mobile");
            showError(".tip-newmobile","请输入手机号");
            return;
        }
        return _mobile;
    }

    //检测手机是否占用
    function checkExist(obj,mobileOrName,callback){
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

    //获取新sms
    $('#get-new-sms').on('click',function(){
        var _mobile = checkMobile();
        if(_mobile){
            $('.tip-newmobile').html("正在检查手机号可用性...");
            checkExist(".tip-newmobile",_mobile,function(res){
                if(res.error_code == 0){
                    showError(".tip-newmobile","手机号可用");
                    $("#new-mobiletip").removeClass('red');
                    var _disable = $('#get-new-sms').hasClass('disable');
                    if(_disable){
                        return false;
                    }else {
                        getNewSMS();
                    }
                }else{
                    showError(".tip-newmobile","手机号已被注册（或不可用）");
                    shake("#new-mobile");
                }
            })
        }
    });

    //获取新手机验证码
    function  getNewSMS(){
        var _mobile =checkMobile();
        XDD.Request({
            type: 'GET',
            url : '/user/sendSms',
            data: {
                mobile:_mobile,
                smsType : "CHANGE_MOBILE"
            },
            success : function(result){
                if(result.error_code == 0){
                    _alert('已向您的手机发送了短信，请注意查收')
                    var count = 60;
                    var setcount = function() {
                        if(count == 1) {
                            $('#get-new-sms').html("重发短信");
                            $('#get-new-sms').removeClass('disable')
                            clearTimeout(timeout);
                        }
                        else {
                            count--;
                            $('#get-new-sms').html("重发(" + count + ")");
                            $('#get-new-sms').addClass('disable');
                            setTimeout(setcount, 1000);
                        }
                    };
                    var timeout = setTimeout(setcount, 1000);
                }else{
                    _alert(result.error_msg);
                }
            }
        })
    }

    //点击提交
    $('#next-step-two').on('click',function(){
        var _mobile = checkMobile(1),
            _sms = checkNewSms(1);
        if(_mobile&&_sms){
            XDD.Request({
                url: '/user/changeBind',
                data: {
                    code: _sms,
                    mobile: _mobile
                },
                success: function (result) {
                    console.log(result);
                    if (result.error_code == 0) {
                        _alert('修改成功!');
                        stepThree();

                    } else {
                        _alert(result.error_msg)
                    }
                }
            })
        }
    })

    //验证验证码
    function checkNewSms(isSubmit){
        var _sms = $('#new-phone-sms').val();
        if(_sms.length){
            if(_sms.length !== 4){
                showError(".tip-newsms","请输入4位短信验证码");
                shake('#new-phone-sms');
                return false;
            }
            if(!util.isNumber(_sms)){
                showError(".tip-newsms","短信验证码为4位数字");
                shake('#new-phone-sms');
                return false;
            }
            else{
                $('.tip-sms').html('');
                return _sms;
            }
        }else if(isSubmit){
            showError(".tip-newsms","请输入短信验证码");
            shake('#new-phone-sms');
            return;
        }else{
            return;
        }
    }
/*第三步*/
    //显示第三步
    function stepThree(){
        $('.nav-two').removeClass('current').addClass('finshed');
        $('.nav-three').addClass('current');
        $('#step-two').hide();
        $('#step-three').show();
        returnIndex();
    }



    //5s跳转首页
    function returnIndex(){
        var count = 5;
        var setcount = function() {
            if(count == 1) {
                window.location.href ="/account/accountSecurity";
                clearTimeout(timeout);
            }
            else {
                count--;
                $('#next-step-three').html(count+"S跳转");
                setTimeout(setcount, 1000);
            }
        };
        var timeout = setTimeout(setcount, 1000);
    }







    function showError(obj,txt){
        $(obj).html(txt);
        $(obj).addClass('red');
    }

    //晃动
    function shake(obj){
        $(obj).animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
            .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40)
            .animate({marginLeft:"0px"},40).focus();
    }


}

module.exports = {
    init :function(){
        bind();
    }
}

});
