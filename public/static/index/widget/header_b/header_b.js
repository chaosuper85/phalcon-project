define('index/widget/header_b/header_b', function(require, exports, module) {

var login = require('common/module/login/login.js');
var pop_login = login();

function bind(){
	$('#login-btn').click(function(){
		pop_login.show();
	})
	$('#logout').click(function(){
		XDD.Request({
			type: "GET",
			url : "/index/logout",
			success : function(result){
	            if(result.error_code == 0){
	            	location.href = "/";
	            }else{
	            	_alert(result.error_msg);
	            }
	        }
		})
	})
}

module.exports = {
	init : function(){
		bind();
	}
}

});
