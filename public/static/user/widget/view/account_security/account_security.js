define('user/widget/view/account_security/account_security', function(require, exports, module) {

/**
 * Created by wll on 15/8/3.
 */
var select = require('user/widget/view/account_security/select_change_mobile.js');
var pop_select =select();


function bind(){

    //点击更改手机号按钮
    $('html body').on('click', '#user-account-security .user-content #change-mobile', function () {
        pop_select.show();
    });



}

module.exports = {
    init :function(){
        bind();
    }
}

});
