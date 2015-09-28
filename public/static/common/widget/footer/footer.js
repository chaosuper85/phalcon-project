define('common/widget/footer/footer', function(require, exports, module) {


var win = $(window),
	body = $('body'),
	footer = $('#footer'),
	height_w = win.height(),
	height_b = body.height();

if(height_b + 160 < height_w){
	body.height(height_w-160)
}

footer.removeClass('invisible');

});
