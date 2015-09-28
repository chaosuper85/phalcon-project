define('user/widget/view/account_info/account_info', function(require, exports, module) {

/**
 * Created by wll on 15/8/7.
 */

require('common/module/upload/uploader.js');
var util = require('common/module/util.js');


function bind() {
    var _src;
    //$('#upload-headimg').AjaxFileUpload({
    //    onComplete: function (filename, response) {
    //        console.log(filename, response);
    //        $('#head-img').attr('src', response.data.pic_url + '?imageMogr2/thumbnail/150x150');
    //        _src = response.data.pic_url;
    //    }
    //});

    //点击编辑
    $('html body').on('click', '#user_info .user-content .edit #edit-info', function () {
        $('.show').hide();
    //  $('.btn-head').show();
        $('.input-edit').show();
        $(this).hide();
        $('#submit-info').show();
        setInput()
    });

    //input获取数据
    function setInput() {
        for (var i = 0; i <= 2; i++) {
            var _this = $(".input" + i),
                prev = _this.prev(),
                value = prev.text();
            if(prev.hasClass('none')){
                _this.val();
            }else {
                _this.attr('value', value);
            }
        }
    }
    //input click
    $('html body').on('click','#user_info .edit-dd input',function() {
        $(this).siblings('#right-mark').addClass('invisible');
    });


    //点击提交
    $('html body').on('click','#user_info .user-content .edit #submit-info',function() {

        //  _avatarPicUrl = _src,
        var _realName = checkName(1),
            _phone = $('#input-telephone').val(),
            _contactMobile = checkMobile(1);

        if (_contactMobile&&_realName) {
            XDD.Request({
                url: '/account/updateBaseInfo',
                data: {
                //    avatarPicUrl: _avatarPicUrl || '',
                    realName: _realName,
                    contactMobile: _contactMobile,
                    telephone_number: _phone
                },
                success: function (result) {
                    console.log(result);
                    if (result.error_code == 0) {
                        _alert('资料修改成功！');
                        $('#name').text(_realName);
                        $('#phone').text(_contactMobile);
                        $('#telephone').text(_phone);
                        $('.show').show();
                        $('.input-edit').hide();
                        $(this).hide();
                        $('#edit-info').show();
                        $('#submit-info').hide();
                        $('.tip').hide();

                    } else {
                        _alert(result.error_msg);
                    }
                }
            })
        }
    });

    //姓名验证
    function checkName(isSubmit){
        var _this = $('#input-name'),
            name = _this.val();
        if(!name == ''){
            _this.siblings('.tip').removeClass('invisible');
            checkPassed('#input-name');
            return name;
        }else{
            _this.siblings('.tip').removeClass('invisible');
            _this.siblings('.right-mark').addClass('invisible');
            shake('#input-name');
            return false;
        }
    }
    //联系方式验证
    function checkMobile(isSubmit){
        var _this = $('#input-phone'),
            contactMobile = _this.val(),
            right = util.isMobilePhone(contactMobile);
        if(right){
            _this.siblings('.tip').removeClass('invisible');
            checkPassed('#input-phone');
            return contactMobile;
        }else{
            _this.siblings('.tip').removeClass('invisible');
            _this.siblings('.right-mark').addClass('invisible');
            shake('#input-phone');
            return false;
        }
    }



    //检测通过
    function checkPassed(obj){
        var _i = $(obj).siblings('.tip').children('i');
            $(_i).addClass('i-right');
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
