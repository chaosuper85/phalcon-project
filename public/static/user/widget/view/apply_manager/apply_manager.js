define('user/widget/view/apply_manager/apply_manager', function(require, exports, module) {

require('common/module/upload/uploader.js');





function bind(){
	$("#upload-licence").AjaxFileUpload({
		onComplete: function(filename, response) {
			console.log(filename,response);
		}
	})
	
	$("#upload-official").AjaxFileUpload({
		onComplete: function(filename, response) {
			console.log(filename,response);
		}
	})

	
}



module.exports = {
	init :function(){
		bind();
	}
}

});
