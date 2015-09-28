define('user/widget/view/account_company/company', function(require, exports, module) {

/**
 * Created by wll on 15/8/14.
 */

require('common/module/upload/uploader.js');
var address = require('common/module/address/address.js');
var select = require('common/module/select/select.js');
var time = require('common/module/selectTimeBox/selectTimeBox.js');
var confirm_pop = require('common/module/confirm/confirm.js');
var util = require('common/module/util.js');


function bind(obj){

    var _confirm;

    var status = 0;//状态码 1正确0错

    //地址组件
    var _address = new address({
        wrap : "#address-selector",
        level : 2,
        width : 160,
        height : 40
    });

    //select默认显示
    var _type2 = '货代用户' ,
        _type1 = '车队用户',
        _dft;

    if(obj=='2'){
        _dft = _type2;
    }else if(obj == '1'){
        _dft = _type1;
    }

    //选择组件
    var _select = new select({
        container : "#type-selector",
        options : [{text : '货代用户',value : '2'},{text : '车队用户',value : '1'}],
        defaultText: _dft,
        defaultValue : obj,
        height : 40
    });

    //时间组件
    var  d = new Date(),
        nowYear = +d.getFullYear();
    var _time = new time({
        wrap : '#company_date_selectBox',
        ed_year : nowYear,
        width : 70,
        height : 40
    });


    var _src,
        _licencePic ;
    //上传图片
    $('#upload-license').AjaxFileUpload({
        onComplete: function (filename, response) {
            console.log(filename, response);
            var b = selectFile(filename);
            if(!b){
                return;
            }
            $('#img-license').attr('src', response.data.pic_url);
             _src = response.data.pic_url;
            $('.img-license-content').removeClass('hidden');
            hideTip('.img-license-content');
        }
    });

    //删除图片
    $('#img-clean').on('click',function(){
        $('#img-license').attr('src', '');
        $('.img-license-content').addClass('hidden');
        showError('.img-license-content');
        _src = '';
        status = 0;
    });

    //点击图片跳转
    $('.license').on('click',function(){
        var _str = $('.license').attr('src');
        window.open(_str);
    });

    //上传的文件格式判断
    function selectFile(filename){
        var  a = filename.split('.'),
            length = a.length-1;
        if(a[length] == 'jpg'|| a[length]== 'jpeg'|| a[length]== 'pdf'|| a[length]== 'png'|| a[length]== 'gif'){
            return true;
        }else{
            _alert('您上传的文件格式不正确！请重新上传！');
            return false;
        }
    }

    //点击提交
    $('#submitInfo').on('click',function(){

        var _enterpriseName = isNull('#enterpriseName');
        if(_enterpriseName){
            hideTip('#enterpriseName');//正确则消失
        }else{
            shake('#enterpriseName');
            showError('#enterpriseName');
            return;
        }

        var _type = isSelect('#type-selector');
        if(_type){
            console.log('选择了',_type)
            hideTip('#type-selector');//正确则消失
        }else{
            shake('#type-selector');
            showError('#type-selector');
            return;
        }

        if(_type == '1'){
            _type = 'carteam';
        }else if(_type == '2'){
            _type = 'freight_agent';
        }

        console.log('类型 = '+_type);

        var _cityCode = isSelect('#address-selector','1');
        if(_cityCode){
            hideTip('#address-selector');//正确则消失
        }else{
            shake('#address-selector');
            showError('#address-selector');
            return;
        }

        var _buildDate = isTime('#company_date_selectBox');
        if(_buildDate){
            hideTip('#company_date_selectBox');//正确则消失
        }else{
            shake('#company_date_selectBox');
            showError('#company_date_selectBox');
            return;
        }

        var _contactMobile_city = isNumber('#contactMobile-city');
        if(_contactMobile_city){
            if(_contactMobile_city.length == 3||_contactMobile_city.length == 4 ){
                hideTip('#contactMobile-city');//正确则消失
            }else{
                shake('#contactMobile-city');
                showError('#contactMobile-city');
                return;
            }
        }else{
            shake('#contactMobile-city');
            showError('#contactMobile-city');
            return;
        }

        var _contactMobile_number = isNumber('#contactMobile-number');
        if(_contactMobile_number){
            if(_contactMobile_number.length == 7 || _contactMobile_number.length == 8 ){
                hideTip('#contactMobile-number');//正确则消失
            }else{
                shake('#contactMobile-number');
                showError('#contactMobile-number');
                return;
            }
        }else{
            shake('#contactMobile-number');
            showError('#contactMobile-number');
            return;
        }

        var _contactMobile_fenji = isNumber('#contactMobile-fenji');
        if(_contactMobile_fenji){
            if(_contactMobile_fenji.length < 3||_contactMobile_fenji.length > 5 ){
                alert('000');
                shake('#contactMobile-fenji');
                showError('#contactMobile-fenji');
                return;
            }else{
                hideTip('#contactMobile-fenji');//正确则消失
            }
        }else{
            hideTip('#contactMobile-fenji');//正确则消失
            console.log('fenji=',_contactMobile_fenji)
        }

        var _licenceNumber = isNumber('#licenseNumber');
        if(_licenceNumber){
            hideTip('#licenseNumber');//正确则消失
        }else{
            shake('#licenseNumber');
            showError('#licenseNumber');
            return;
        }

        if(_src){
             _licencePic = _src;
        }else{
            _licencePic = $('#img-license').attr('src');
        }

        console.log(_buildDate+'---------'+_licencePic);
        if(!_licencePic){
            showError('#img-license');
            status = 0;
            return;
        }else{
            status = 1;
            hideTip('#img-license');
        }

        if(status == 0){
            return false;
        }else if(status == 1){
            if(_confirm){
                _confirm.show();

            }else{
                    _confirm = new confirm_pop();
                    _confirm.setData('公司信息一经提交，不能修改');
                    _confirm.show();
            }
            makeSure();
        }

        //点击确认
        function makeSure(){
            $('#confirmBtn').on('click',function(){
                infoSubmit();
                _confirm.hide();
           //     $(this).unbind('click');
            });
            $('#cancelBtn').on('click',function(){
                console.log('cancle');
                _confirm.hide();
                $('#confirmBtn').unbind('click');
            })
        }

        //提交信息
        function infoSubmit(){
            XDD.Request({
                url : '/account/do_apply',

                data: {
                    enterpriseName : _enterpriseName,
                    type : _type,
                    cityCode : _cityCode,
                    builddate : _buildDate,
                    contactMobile_city : _contactMobile_city,
                    contactMobile_number : _contactMobile_number,
                    contactMobile_fenji : _contactMobile_fenji,
                    licenceNumber : _licenceNumber,
                    licencePic : _licencePic
                },
                success : function(result){
                    if(result.error_code == 0){
                        _alert('提交成功，请等待审核');
                        $('.pop-close').on('click',function(){
                             location.href = '/account/enterpriseInfo';
                        });
                        $('.popup-bg').on('click',function(){
                            location.href = '/account/enterpriseInfo';
                        });
                    }else{
                        _alert(result.error_msg);
                    }
                }
            },true)
        }
    });

    //选择不为空
    function isSelect(obj,adr){
        if(adr){
            var _city = _address.val(),
                _val = _city.city.id;
        }else{
            var _val = _select.val();
        }
       if(_val > 0){
           status = 1;
           return _val;
       } else{
           status = 0;
           return false;
       }
    }

    //input不能为空
    function isNull(obj){
        var _this = $(obj),
            _val = _this.val();
        if(_val){
            status = 1;
            return _val;
        }else{
            status = 0;
            return false;
        }
    }

    //时间不能超
    function isTime(obj){
        var _val = _time.val();
        var _build = new Date(_val.replace("-", "/").replace("-", "/"));
        var d =new Date(),
            str='';
        str +=d.getFullYear()+'-'; //获取当前年份
        str +=d.getMonth()+1+'-'; //获取当前月份（0——11）
        str +=d.getDate()+'-';
        var _now = new Date(str.replace("-","/").replace("-", "/"));
        if(_build > _now){
            console.log(_val);
            status = 0;
            return false;
        }else{
            status = 1;
            return _val;
        }
    }

    //数字判断
    function isNumber(obj){
        var data_number = $(obj).val();
        if(data_number){
            if(!util.isNumber(data_number)){
                status = 0;
                return false;
            }
        }else{
            status = 0;
            return false;
        }
        status = 1;
        return data_number;
    }

    //晃动
    function shake(obj){
        console.log('aa',$(obj));
        $(obj).animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
            .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40)
            .animate({marginLeft:"0px"},40).focus();
    }

    //错误提示
    function showError(obj){
        var _tip = $(obj).closest('.content').siblings('.tip');
        $(_tip).removeClass('invisible');
        $(_tip).children('i').removeClass('i-right');
        status = 0;
    }

    //错误消失
    function hideTip(obj){
        var _tip = $(obj).closest('.content').siblings('.tip'),
            _i = _tip.children('i');
        $(_tip).removeClass('invisible');
        $(_i).addClass('i-right')
    }



}

module.exports = {
	init : function(obj){

            bind(obj);
	}
}

});
