define('www/order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar', function(require, exports, module) {

var DOM = {
	box_item: $('#order_trace_sidebar li')
}
module.exports = {
	init: function(box_time_line){
		setSidebarClass();
		DOM.box_item.on('click', function(event) {
			DOM.box_item.removeClass('active');
			$(this).addClass('active');
		});
	}
}

/**
 * [设置左边栏初始样式]
 */
function setSidebarClass(){
	var _href = window.location.href;
	var getHashIndex = _href.indexOf('#/');
	var getBoxNum = _href.substring(getHashIndex + 2, _href.length);
	DOM.box_item.removeClass('active');
	if(!getBoxNum || isNaN(getBoxNum) || getBoxNum == 1 || !DOM.box_item[getBoxNum - 1]){
		$(DOM.box_item[0]).addClass('active');
	} else {
		$(DOM.box_item[getBoxNum - 1]).addClass('active');
	}
}

});
