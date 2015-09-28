define('user/widget/view/account_security/change_pass', function(require, exports, module) {

/**
 * Created by wll on 15/8/13.
 */
var util = require('common/module/util.js');

function bind() {

    //$('#pass').on('blur',function(){
    //    var _pass = $(this).val();
    //    if(!_pass){
    //        shake('#pass');
    //        showError('#tip-pass', '请输入密码');
    //    }
    //});

    $('#new-pass').on('blur',function() {
        checkPass('#new-pass');
    });

    $('#new-repass').on('blur',function(){
        checkRepass('#new-pass','#new-repass');
    })

        //检查密码
    function checkPass(obj,isSubmit) {
        var _pass = $(obj).val(),
            _length = _pass.length,
            _right = util.isPassword(_pass);
        if (_length) {
            if (_length < 6) {
                shake(obj);
                showError(obj, '密码为6-16位');
                return false;
            } else if (!_right) {
                shake(obj);
                showError(obj, '密码必须包含字母和数字');
                return false;
            } else {
                showError(obj,'');//这是正确提示
                return _pass;
            }
        } else if (isSubmit) {
            shake(obj);
            showError(obj, '请输入密码');
            return false;
        }
    }

    //重复密码
    function checkRepass(obj1,obj2,isSubmit){
        var _pass = $(obj1).val(),
            _repass = $(obj2).val();
        if(_repass.length){
            if(_repass !== _pass){
                shake(obj2);
                showError(obj2,"两次输入密码不一致");
                return false;
            } else{
                showError(obj2,'');//这是正确提示
                return _repass;
            }
        }else if(isSubmit){
            shake(obj2);
            showError(obj2,"请再次输入密码");
            return false;
        }
    }

    //点击提交验证密码
    $('.next-step').on('click',function(){
        var _pass = checkPass('#pass',1);
        if(_pass){
            XDD.Request({
                url: '/user/validatePwd',
                data: {
                    pwd: _pass
                },
                success: function (result) {
                    if (result.error_code == 0) {
                        changePass(_pass);
                    } else {
                        _alert(result.error_msg)
                    }
                }
            })

        }
    })

    //提交新密码
    function changePass(pass) {

        var _pass = pass,
            _newpass = checkPass('#new-pass', 1);
        if(_newpass){
            var  _newrepass = checkRepass('#new-pass', '#new-repass', 1);
        }
        if(_newpass&&_newrepass){
           XDD.Request({
            url: '/account/changePwd ',
            data: {
                oldPwd: _pass,
                newPwd: _newpass
            },
            success: function (result) {
                if (result.error_code == 0) {
                    _alert('修改密码成功！');
                    setTimeout("location.href = '/account/personalInfo'",2000);
                } else {
                    _alert(result.error_msg);
                }
            }
          })
       }
    }

    //晃动
    function shake(obj) {
        $(obj).animate({marginLeft: "-1px"}, 40).animate({marginLeft: "2px"}, 40)
            .animate({marginLeft: "-2px"}, 40).animate({marginLeft: "1px"}, 40)
            .animate({marginLeft: "0px"}, 40).focus();
    }

    //错误提示
    function showError(obj,txt){
        var _tip = $(obj).siblings('.tip');
        $(_tip).addClass('red').html(txt);
    }



}
module.exports = {
    init :function(){
        bind();
    }
}

});
