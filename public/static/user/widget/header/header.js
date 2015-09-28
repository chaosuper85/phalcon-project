define('user/widget/header/header', function(require, exports, module) {

function bind(){
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
	$('#ucenter').click(function(){
		location.href = '/account/personalInfo'
	})
}

module.exports = {
	init : function(){
		bind();
	}
}

});
