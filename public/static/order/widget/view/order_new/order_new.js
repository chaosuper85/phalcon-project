define('order/widget/view/order_new/order_new', function(require, exports, module) {

/**
 * Created by wll on 15/8/26.
 */
require('common/module/upload/uploader.js');
var select = require('common/module/select/select.js');
var LoadingBtn = require('common/module/loadingBtn/loadingBtn.js');
var load = new LoadingBtn();
function bind(obj,yundan){

    var length = obj.length,
        carteam = [],
        _yundan = yundan,
        _carteamId,
        _chanzhuang,
        _tidan ,
        _tixiang,
        _data;

    //获取时间
    var d = new Date();
    var vYear = d.getFullYear();
    var vMon = d.getMonth() + 1;
    var vDay = d.getDate();
    _data="日期："+vYear+"-"+(vMon<10 ? "0" + vMon : vMon)+"-"+(vDay<10 ? "0"+ vDay : vDay);
    $('.number-left').html(_data);
    console.log('aaa='+_data);//输出时间

    //选项数据填充
    creatOptions(obj);
    function creatOptions(obj){
        for(var i=0;i<length;i++){
            var _value = obj[i].carteamId,
                _text = obj[i].carteamName;
            carteam[i] = {text : _text,value : _value};
        }
    }

    //选择组件,承运方
    var _select = new select({
        container : "#carteam-selector",
        width : 500,
        heigth : 34,
        options : carteam,
        defaultText: '请选择',
        defaultValue : '0'
    });

    //上传产装联系单
    $('#upload-chanzhuang').AjaxFileUpload({
        action: '/api/freight/order/upload_chanzhuang',
        onSubmit: function(){
            load.show({
                btn: $('#order-new .chanzhuang_btn'),
                txt: '正在上传...',
                showLoad: false
            });
        },
        onComplete: function (filename, response) {
            console.log(filename, response);

            var b =selectFile(filename);
            if(!b){
                return;
            }
            show('#upload-chanzhuang',filename);
            _chanzhuang = response.data.pic_url;
            load.hide();
        }
    });

    //上传提箱单
    $('#upload-tixiang').AjaxFileUpload({
        action: '/api/freight/order/upload_tixiang',
        onSubmit: function(){
            load.show({
                btn: $('#order-new .tixiang_btn'),
                txt: '正在上传...',
                showLoad: false
            });
        },
        onComplete: function (filename, response) {
            console.log(filename, response);
            var b =selectFile(filename);
            if(!b){
                return;
            }
            show('#upload-tixiang',filename);
            _tixiang = response.data.pic_url;
            load.hide();
        }
    });

    //显示，单
    function show(obj,text){
        var _parent = $(obj).parent('.btn-wrap'),
            _show = $(_parent).prev('.upload-show'),
            _span = $(_show).find('.filename');
        $(_show).removeClass('hidden');
        $(_span).html(text)
    }

    //删除，单
    $('#chanzhuang-delete').on('click',function(){
        _delete('#chanzhuang-delete');
        _chanzhuang = '';
    })
    $('#tixiang-delete').on('click',function(){
        _delete('#tixiang-delete');
        _tixiang = '';
    })
    function _delete(obj){
        var _box = $(obj).prev('.show-box'),
            _show = $(_box).parent('.upload-show'),
            _span = $(_box).children('.filename'),
            _mes = $(_show).parent('.item-content').next('.item-message'),
            _rig = $(_mes).children('.right-message');
        $(_span).html('');
        $(_show).addClass('hidden');
        $(_rig).addClass('hidden');
    }

    //上传的文件格式判断
    function selectFile(filename){
        var  a = filename.split('.'),
            length = a.length-1;
        if(a[length] == 'doc'|| a[length]== 'docx'|| a[length]== 'pdf'){
            return true;
        }else{
            _alert('您上传的文件格式不正确！请重新上传！')
            return false;
        }
    }

    //input不能为空
    function isNull(obj){
        var _this = $(obj),
            _val = _this.val();
        if(_val){
            return _val;
        }else{
            return false;
        }
    }
    //提单号不能包含中文字符
    function isChina(obj){
        var this_string = $(obj),
            string_val = this_string.val();
        if(/^(\d|[a-zA-Z])+$/.test(string_val)){
            return string_val;
        }
        else{
            return false;
        }
    }

    //获取车队选项
    function isSelect(){
        var _val = _select.val();
        return _val;
    }

    //提单号输入框失去焦点提示
    $('#tidan_name').blur(function(){
        var obj_val = $("#tidan_name").val();
        var _tidan = isNull('#tidan_name'),
        _tidanString = isChina('#tidan_name');

        if(_tidan){
            checkPass('#tidan_name');
        }else{
            showError('#tidan_name');
            $('#tidan_name').focus();
            $('#tidan_name').addClass('error');
            return;
        }
        if(obj_val != "" || obj_val != null){
            if(_tidanString){
                checkPass('#tidan_name');
            }else{
                showChina("#tidan_name");
                $('#tidan_name').focus();
                $('#tidan_name').addClass('error');
                return;
            }
        }
        else{

        }

    });

    //点击提交
    $('.order-creat').on('click',function(){
        var this_obj = $(this);
            _carteamId = isSelect(),
            _chanzhuang,
            _tidan = isNull('#tidan_name'),
            _tidanString = isChina('#tidan_name'),
            _tixiang;

        if(_carteamId == '0'){
            showError('.select-box');
            $('.select-box').focus();
            $('.select-box').addClass('error');
            return;
        }else{
            $('.select-box').removeClass('error');
            checkPass('.select-box');
        }

        if(_chanzhuang){
            checkPass('.chanzhuang-show');
        }else{
            showError('.chanzhuang-show');
            return;
        }

        if(_tidan){
            checkPass('#tidan_name');
        }else{
            showError('#tidan_name');
            $('#tidan_name').focus();
            $('#tidan_name').addClass('error');
            return;
        }

        if(_tidanString){
            checkPass('#tidan_name');
        }else{
            showChina('#tidan_name');
            $('#tidan_name').focus();
            $('#tidan_name').addClass('error');
            return;
        }

        if(_tixiang){
            checkPass('.tixiang-show');
        }else{
            showError('.tixiang-show');
            return;
        }
        load.show({
            btn: this_obj,
            txt: '正在创建订单...',
            showLoad: false
        });
        //提交订单
        XDD.Request({
            url : '/freight/order/new',
            data: {
                tidan: _tidan,
                yundan: _yundan ,
                carteamId: _carteamId,
                tixiang: _tixiang,
                chanzhuang: _chanzhuang
            },
            success : function(result){
                if(result.error_code == 0){
                    _alert('提交成功，请等待审核');
                    window.location.href = '/order/list';
                }else{
                    _alert(result.error_msg);
                }
                load.hide();
            }
        },true)
     })


    //错误提示
    function showError(obj){
        var _content = $(obj).parents('.item-content'),
            _mes = $(_content).next('.item-message'),
            _err =$(_mes).children('.error-message'),
            _rig = $(_mes).children('.right-message');
        $(_err).removeClass('hidden');
    }
    function showChina(obj){
        var _content = $(obj).parents('.item-content'),
            _mes = $(_content).next('.item-message'),
            _err =$(_mes).children('.china-notice'),
            _rig = $(_mes).children('.right-message');
        $(_err).removeClass('hidden');
        $(_rig).addClass('hidden');
    }

    //错误消失
    function checkPass(obj){
        var _content = $(obj).parents('.item-content'),
            _mes = $(_content).next('.item-message'),
            _err =$(_mes).children('.error-message'),
            _notice = $(_mes).children('.china-notice'),
            _rig = $(_mes).children('.right-message');
        $(obj).removeClass('error');
        $(_err).addClass('hidden');
        $(_rig).removeClass('hidden');
        $(_notice).addClass('hidden');
    }
}

module.exports = {
    init : function(obj,yundan){

        bind(obj,yundan);
    }
}


});
