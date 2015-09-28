define('order/widget/view/product_address_list/product_address_list', function(require, exports, module) {

/**
 * Created by wll on 15/9/7.
 */



function bind(){
    //dom
    var scope = $('.scope'),
        _length = scope.length;
    for(var i= 0;i<_length;i++){
        isNull(scope[i]);

    }

    //判断是否数据齐全
    function isNull(obj){
        var _td = $(obj).children('td'),
            _tdlength = _td.length -1;
        for(var j=0;j<_tdlength;j++){
            var a = $(_td[j]).text();
            if(!a){
                var a = $(_td[5]).children('a');
                $(a).addClass('disable');
            }
        }
    }

    //diable下不可点击下载
    $('.print').on('click',function(){
        var a = $(this).hasClass('disable');
        if(a){
            return false;
        }
    })
}


module.exports = {
    init : function(){

        bind();
    }
}

});
