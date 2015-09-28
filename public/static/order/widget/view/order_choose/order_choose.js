define('order/widget/view/order_choose/order_choose', function(require, exports, module) {

/**
 * Created by wll on 15/8/26.
 * info
 */

var select = require('common/module/select/select.js');

function bind(){

    //选择组件    test 567
    var _select = new select({
        container : "#docks-selector",
        options : [{text : '天津',value : '1'}],
        defaultValue : '1',
        defaultText : '天津',
        width : 280
    });

    //$('#choose-in').on('click',function(){
    //    $(this).addClass('current');
    //    $('#choose-out').removeClass('current');
    //})

    $('#choose-out').on('click',function(){
        $(this).addClass('current');
        $('#choose-in').removeClass('current');
    })



}

module.exports = {
    init : function(){

        bind();
    }
}

});
