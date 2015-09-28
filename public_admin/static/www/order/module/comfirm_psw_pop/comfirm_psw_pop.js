define('www/order/module/comfirm_psw_pop/comfirm_psw_pop', function(require, exports, module) {

/*
 *确认退载重建弹窗
 */
var popup = require('www/common/module/popup/popup.js');

function PswConfirmPop(_option) {
	var html,
        title,
        defaults = {},
        options = $.extend({}, defaults, _option);
    var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="passwordConfirm"><div class="logo"></div><dl><dd class="clearfix"><input type="password" id="quit_confirm" placeholder="请输入密码" maxlength="16"/><span class="icon-pass"></span></dd><dd id="quit_captcha_wrap" class="clearfix hidden"><input type="text" id="quit_captcha" placeholder="验证码" maxlength="4"/><div class="captcha-wrap"></div></dd></dl><div id="quit_tip" class="invisible"></div><a href="javascript:;" id="confirm_submit">确定</a></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
    function _PswConfirmPop(options) {
        var submit = this.pop.find("#confirm_submit"),
            password = this.pop.find("#quit_confirm"),
            captcha = this.pop.find("#quit_captcha"),
            tip = this.pop.find("#quit_tip"),
            errorType = '';

        this.DOM = {
            submit: submit,
            password: password,
            captcha: captcha,
            tip: tip
        }
        bind(this);
    }

    _PswConfirmPop.prototype = new popup({
        title: false,
        height: 280,
        width: 330,
        tpl: tpl
    });
    _PswConfirmPop.prototype.constructor = _PswConfirmPop;

    _PswConfirmPop.prototype.setHeight = function(h) {
        this.pop.height(h);
    }
    _PswConfirmPop.prototype.onComplete = function() {};

    function bind(that) {
        that.DOM.submit.on('click', function(event) {
            var pwd = checkPass(that);
            if (!pwd) return;

            that.onComplete(pwd);
        });
        $("#passwordConfirm").keydown(function(event){
            if(event.keyCode==13){
                that.DOM.submit.on();
            }
        })
    }

    function checkPass(that) {
        var pwd = that.DOM.password.val();
        if (pwd.length == 0) {
            that.DOM.tip.html('请输入密码');
            that.DOM.tip.removeClass('invisible');
            that.DOM.password && shake(that.DOM.password);
            setTimeout(function() {
                that.DOM.tip.addClass('invisible');
            }, 5000);
            return
        }

        if (pwd.length < 6) {
            that.DOM.tip.html('密码不少于6位');
            that.DOM.tip.removeClass('invisible');
            that.DOM.password && shake(that.DOM.password);
            setTimeout(function() {
                that.DOM.tip.addClass('invisible');
            }, 5000);
            return
        }
        that.DOM.tip.addClass('invisible');
        return pwd;
    }

    function shake(obj) {
        obj.animate({
                marginLeft: "-1px"
            }, 40).animate({
                marginLeft: "2px"
            }, 40)
            .animate({
                marginLeft: "-2px"
            }, 40).animate({
                marginLeft: "1px"
            }, 40)
            .animate({
                marginLeft: "0px"
            }, 40).focus();
    }

    return new _PswConfirmPop();
}
module.exports = PswConfirmPop;

});
