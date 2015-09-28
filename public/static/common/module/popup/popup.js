define('common/module/popup/popup', function(require, exports, module) {

/*
 *  通用弹窗组件
 *  by kenny
 */

var $body = $('body'),
	$win = $(window);

function popup(opt){
    var title = this.title = opt.title || '',
        height = this.height = opt.height || 500,
        width = this.width = opt.width || 500,
        tpl = this.tpl = opt.tpl || function(){return ''},
        data = this.data = opt.data || {},

		$pop = this.pop = $('<div class="popup"></div>'),
		header = '<h2 class="unselectable"><span class="pop-title">' + title + '</span><a class="pop-close" title="关闭"></a></h2>',
		content = '<div class="content"></div>';

    if($(".popup-bg").length > 0){
        var $bg = this.bg = $(".popup-bg");
    }else{
        var $bg = this.bg = $('<div class="popup-bg"></div>');
        $body.append($bg);
    }

    $pop.css('height' , height+'px');
    $pop.css('width' , width+'px');
	
	$body.append($pop);
	if(title){
		$pop.append(header);
	}else{
		$pop.append('<a class="pop-close" title="关闭"></a>');
	}
	$pop.append(content);
	$pop.find(".content").append(tpl(data));
		
	bindEvent(this);
}


popup.prototype.show = function(){
	
	var _height = this.height,
		_width = this.width;
	
	this.pop.css("left",($win.width()/2 - (parseInt(_width)/2)) + "px");
	this.pop.css("top",($win.height()/2.5 - (parseInt(_height)/2)) + "px");
	this.pop.css("opacity",0).show();
	this.bg.css("opacity",0).show();
	this.bg.animate({
        opacity : 0.7
    },300);
    this.pop.animate({
        // top : $win.height()/2 - parseInt(_height)/2 + "px",
        opacity : 1
    },300);
	
} 

popup.prototype.hide = function(){
	this.pop.hide();
	this.bg.hide();
}

popup.prototype.setTitle = function(title){
	var obj = this.pop.find('.pop-title');
	obj.html(title);
}
popup.prototype.setHeight = function(height){
	var obj = this.pop;
    obj.css('height' , height+'px');
}
popup.prototype.setContent = function(content){
	var obj = this.pop.find(".content");
	obj.empty().html(content);
}

function bindEvent(obj){
	var that = obj;
	var close = that.pop.find('.pop-close');
    drag({ 
    	"obj": that.pop,
    	"title": that.pop.find("h2")
    });
	// bind close buttom
	close.bind('click',function(){
		that.hide();
	});
	that.bg.bind('click',function(){
		that.hide();
	});
}

/* 拖拽 */
function drag(option){

	var $div = option.obj,
		$title = option.title;

	if(typeof($title[0]) == 'undefined'){
		return;
	}

	var _x,_y;
	/* popup起始坐标 */
	var start_point = { 
		x : 0,
		y : 0
	}
	/* 鼠标点击时 */
	var mousedown_point = { 
		x : "",
		y : ""
	}

	$title.on("mousedown",function(e){
		if(e.target.tagName.toLowerCase()=='a'){
			return;
		}
		$('body').addClass('unselectable');

		$title.setCapture && $title.setCapture();
		start_point.x = parseInt($div.css('left')),
		start_point.y = parseInt($div.css('top'));

		mousedown_point = getMousePoint(e);

		_x = mousedown_point.x - start_point.x,
		_y = mousedown_point.y - start_point.y;

		$(document).bind("mousemove",function(e){ 
			mousemove_point = getMousePoint(e);
            var x = mousemove_point.x - _x,
                y = mousemove_point.y - _y;
            $div.css({top: y,left: x});
		}).bind('mouseup', function(e) {
			$('body').removeClass('unselectable');
			$title.releaseCapture && $title.releaseCapture();
			$(this).unbind('mousemove');
			$(document).unbind('mousemove').unbind('mouseup');
		});;
		
		return false;
	});
}

function getMousePoint(ev){ 
	 ev = ev || window.event;
	if(ev.pageX || ev.pageY){ 
		return {x:ev.pageX, y:ev.pageY};
	} else { 
	 	return {
		  	x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		  	y:ev.clientY + document.body.scrollTop  - document.body.clientTop
		}
	}
}

module.exports = popup;


});
