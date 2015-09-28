define('order/widget/view/print_product_address/print_product_address', function(require, exports, module) {

/*
 *下载产装单
 */

function bind(){
	DOM = {
		btnDownload:$("#download_address")
	}

	DOM.btnDownload.click(function(){
		//window.location.href = "/carteam/order/download/product_address?boxid=2355";
	});
}


 module.exports = {
 	init:function(){
 		bind();
 	}
 }

});
