/*!www/common/module/popup/popup.js*/
;define('www/common/module/popup/popup', function(require, exports, module) {

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

/*!www/order/module/ensupe_pop/ensupe_pop.js*/
;define('www/order/module/ensupe_pop/ensupe_pop', function(require, exports, module) {

/**
 * [上传或修改箱号铅封号]
 * @type {[type]}
 */
var popup = require('www/common/module/popup/popup.js');
var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="editBoxNum"><div class="editBoxNum-title clearfix"><div class="editBoxNum-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传箱号/铅封号</div><div class="editBoxNum-comfire"><button class="comfireBtn">确定</button></div></div><dl class="editBoxNum-items clearfix"><dd class="item"><div class="item-name"><label for="editBoxNum-box-num">箱号：</label></div><div class="item-content"><input type="hidden" id="editBoxNum-box-id" name="editBoxNum-box-id" value="" /><input type="text" placeholder="请输入箱号" id="editBoxNum-box-num" name="editBoxNum-box-num" value="" /></div></dd><dd class="item"><div class="item-name"><label for="editBoxNum-seal-num">铅封号：</label></div><div class="item-content"><input type="text" placeholder="请输入铅封号" id="editBoxNum-seal-num" name="editBoxNum-seal-num" value="" /></div></dd></dl></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
function EnsupePop(_option){
    var html,
        title,
        defaults = {
            data: {
                id: '',
                boxNum: '',
                sealNum: '',
                orderId:''
            },
            type: 'add'
        },
        options = $.extend({}, defaults, _option);

    if(options.type === 'add'){
        title = '上传箱号/铅封号';
    } else {
        title = '修改箱号/铅封号';
    }
    
	function _EnsupePop(options){
        var type = this.type = options.type;

        var DOM = this.DOM = {};
        this.DOM = {
            title: this.pop.find('.editBoxNum-title .title-text'),
            comfireBtn: this.pop.find('.editBoxNum-title .editBoxNum-comfire .comfireBtn'),
            cancelBtn: this.pop.find('.editBoxNum-title .editBoxNum-cancel .cancelBtn'),
            boxId: this.pop.find('.editBoxNum-items #editBoxNum-box-id'),
            boxNum: this.pop.find('.editBoxNum-items #editBoxNum-box-num'),
            sealNum: this.pop.find('.editBoxNum-items #editBoxNum-seal-num')
        }

        /** run function */
        init_popup(this);

        /** bind event */
        var that = this;
        this.DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });
        this.DOM.comfireBtn.on('click', function(event) {
            save(that);
            //requestData();
        });
	}

	_EnsupePop.prototype = new popup({
        height:300,
        width :700,
        tpl:tpl
    });

    _EnsupePop.prototype.constructor = _EnsupePop;

    _EnsupePop.prototype.setAdd = function(_opt){
        type = this.type = 'add';
        title = '上传箱号/铅封号';

        this.DOM.title.text(title);
        this.DOM.boxId.val(_opt.boxId);
        this.DOM.boxNum.val('');
        this.DOM.sealNum.val('');
    }

    _EnsupePop.prototype.setEdit = function(_editData){
        if(!_editData.boxId) return;
        if(!_editData.boxNum) return;
        if(!_editData.sealNum) return;

        type = this.type = 'edit';
        title = '修改箱号/铅封号';

        this.DOM.title.text(title);
        this.DOM.boxId.val(_editData.boxId);
        this.DOM.boxNum.val(_editData.boxNum);
        this.DOM.sealNum.val(_editData.sealNum);
    }

    _EnsupePop.prototype.onComplete = function(){};

    function init_popup(that){
        /** title set */
        that.DOM.title.text(title);
        that.DOM.boxId.val(options.data.id);
        that.DOM.boxNum.val(options.data.boxNum);
        that.DOM.sealNum.val(options.data.sealNum);
    }

    function save(that){
        var url, subData, msg;
        if(that.type === 'add'){
            var url = '/carteam/order/save_box_ensupe';
            subData = {
                box_code: that.DOM.boxNum.val(),
                box_ensupe: that.DOM.sealNum.val(),
                order_box_id: that.DOM.boxId.val(),
                order_freight_id:options.data.orderId

            };
            msg = '上传成功！'
        } else {
            var url = '/carteam/order/save_box_ensupe';
            subData  = {
                order_box_id: that.DOM.boxId.val(),
                box_code: that.DOM.boxNum.val(),
                box_ensupe: that.DOM.sealNum.val(),
                order_freight_id:options.data.orderId
            };
            msg = '修改成功！'
        }
        XDD.Request({
            url: url,
            data: subData,
            success: function(res){
                if(res.error_code == 0){
                    that.hide();
                    _alert(msg);
                    if(typeof that.onComplete === 'function'){
                        that.onComplete(subData);
                    }
                    
                } else {
                    _alert(res.error_msg || '未知错误！');
                }
            }
        }, true);

    }

	return new _EnsupePop(options);
}
module.exports = EnsupePop;

});

/*!www/common/module/select/select.js*/
;define('www/common/module/select/select', function(require, exports, module) {

/**
 * [select组建]
 * @param  container       (string || object)      [传入一个选择器，建议以id为选择器，例如:'#test'，或者可选入jquery对象]
 * @param  width           (number)      [选择框宽度]
 * @param  height          (number)      [选择框高度]
 * @param  font_size       (number)      [选择框里字体大小]
 * @param  onSelectChange  (function)    [选择后回调函数]
 * @param  onSelectClick   (function)    [点击选择框时回调函数]
 * @param  options         (array)       [选项，以数组形式]
 * @param  options = [{text: 'example', value: 'example_value'}]                                        
 * by weiqi
 */
var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div class="select-box"><div class="selected"><span class="selected-text" data-value="',typeof( defaultValue ) === 'undefined'?'':baidu.template._encodeHTML( defaultValue ),'" title="');if(defaultText && defaultValue){_template_fun_array.push('',typeof( defaultText ) === 'undefined'?'':baidu.template._encodeHTML( defaultText ),''); }else{ _template_fun_array.push('',typeof( placeholder || '请选择' ) === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择' ),'');}_template_fun_array.push('">');if(defaultText && defaultValue){_template_fun_array.push('',typeof( defaultText ) === 'undefined'?'':baidu.template._encodeHTML( defaultText ),''); }else{ _template_fun_array.push('',typeof( placeholder || '请选择' ) === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择' ),'');}_template_fun_array.push('</span><i class="select-box-icon-drop down"></i></div><ul class="select-option">'); var length = options.length;_template_fun_array.push(''); if(length > 0){ _template_fun_array.push('');if(placeholder){_template_fun_array.push('<li class="');if(!defaultText || !defaultValue){_template_fun_array.push('active');}_template_fun_array.push('" data-value="" title="',typeof( placeholder || '请选择') === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择'),'"><a href="javaScript:">',typeof( placeholder || '请选择') === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择'),'</a></li>');}_template_fun_array.push(''); for(var i = 0; i < length; i++){ _template_fun_array.push('<li class="');if(defaultValue && defaultValue == options[i].value){_template_fun_array.push('active');}_template_fun_array.push('" data-value="',typeof( options[i].value ) === 'undefined'?'':baidu.template._encodeHTML( options[i].value ),'" title="',typeof( options[i].text ) === 'undefined'?'':baidu.template._encodeHTML( options[i].text ),'"><a href="javaScript:">',typeof( options[i].text ) === 'undefined'?'':baidu.template._encodeHTML( options[i].text ),'</a></li>'); } _template_fun_array.push(''); }else{ _template_fun_array.push('');if(placeholder){_template_fun_array.push('<li class="');if(!defaultText || !defaultValue){_template_fun_array.push('active');}_template_fun_array.push('" data-value="',typeof( defaultValue ) === 'undefined'?'':baidu.template._encodeHTML( defaultValue ),'" title="',typeof( placeholder || '请选择') === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择'),'"><a href="javaScript:">',typeof( placeholder || '请选择') === 'undefined'?'':baidu.template._encodeHTML( placeholder || '请选择'),'</a></li>');}_template_fun_array.push(''); } _template_fun_array.push('</ul></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
function SelectBox(_option){
    var html,
        option = {
        container: _option.container || '',
        width: _option.width || 160,
        height: _option.height || 30,
        font_size: _option.font_size || 14,
        onSelectChange: _option.onSelectChange || '',
        onSelectClick: _option.onSelectClick || '',
        options: _option.options || [],
        options_max_height: _option.options_max_height || 200,
        placeholder: _option.placeholder || '',
        defaultValue: _option.defaultValue || '',
        defaultText: _option.defaultText || '',
        text_align: _option.text_align || 'left'
    };
    if(!$){
        console.log('依赖jquery！请引入jquery1.7+');
        return
    }
    if(!option.container) return


    this.placeholder = option.placeholder || '';
    this.defaultValue = option.defaultValue || '';
    this.defaultText = option.defaultText || '';

    this.select = {};
    this.select.style = {};

    // select容器样式
    this.select.style.text_align = option.text_align;
    this.select.style.width = option.width;
    this.select.style.height = option.height;
    this.select.style.font_size = option.font_size;
    this.select.style.options_max_height = option.options_max_height;

    // select容器
    if(typeof option.container === 'string'){
        this.select.select_container = $(option.container)
    }
    if(typeof option.container === 'object'){
        this.select.select_container = option.container
    }

    html = tpl({
        options: option.options,
        defaultValue: option.defaultValue,
        defaultText: option.defaultText,
        placeholder: option.placeholder
    }); 
    this.select.select_container.html(html);

    // 获取容器
    this.select.select_box = this.select.select_container.find(".select-box");
    this.select.selected_container = this.select.select_container.find(".select-box .selected");
    this.select.selected_option = this.select.selected_container.find('.selected-text');
    this.select.arrow = this.select.selected_container.find('.select-box-icon-drop');
    this.select.options_container = this.select.select_container.find(".select-box .select-option");

     // 设置样式
    this.setSelectTextAlign();
    this.setSelectFontSize();
    this.setSelectWidth();
    this.setSelectHeight();
    this.setOptionsHeight();

    // 绑定事件
    bindBoxClick(this, option.onSelectClick);
    bindBoxLeave(this);
    bindSelect(this, option.onSelectChange);
}

/**
 * [设置字体位置]
 */
SelectBox.prototype.setSelectTextAlign = function(text_align){
    var text_align = text_align || this.select.style.text_align;
    this.select.select_container.css('text-align', text_align);
}
/**
 * [设置字体大小]
 */
SelectBox.prototype.setSelectFontSize = function(_font_size){
    var font_size = _font_size || this.select.style.font_size;
    this.select.select_container.css('font-size', font_size);
}
/**
 * [设置宽度]
 */
SelectBox.prototype.setSelectWidth = function(_width){
    var width = _width || this.select.style.width
    this.select.select_container.css('width', width);
}
/**
 * [设置高度]
 */
SelectBox.prototype.setSelectHeight = function(_height){
    var height = _height || this.select.style.height;
    this.select.select_container.css({
        'line-height': height + 'px',
        'height': height + 'px'
    });
    this.select.options_container.css('top', height-5 + 'px');
}

/**
 * [设置option容器最高高度]
 */
SelectBox.prototype.setOptionsHeight = function(_height){
    var height = _height || this.select.style.options_max_height;
    this.select.options_container.css({
        'max-height': height + 'px'
    });
}

/**
 * [显示选择框]
 * @return {[type]} [description]
 */
SelectBox.prototype.show = function(){
    var arrow = this.select.arrow;
    this.select.select_box.css('z-index', '9999');
    arrow.addClass('up').removeClass('down');

    this.select.options_container.slideDown('fast');
}

/**
 * [隐藏选择框]
 * @return {[type]} [description]
 */
SelectBox.prototype.hide = function(){
    var arrow = this.select.arrow;

    this.select.select_box.css('z-index', '1');
    arrow.addClass('down').removeClass('up');

    this.select.options_container.slideUp('fast');
}

/**
 * [设置选项]
 * @param  options         (array)       [选项，以数组形式]
 * @param  options = [{text: 'example', value: 'example_value'}] 
 */
SelectBox.prototype.setOptions = function(options){
    if(typeof options !== 'object') return
    var options_container = this.select.options_container,
        html = '',
        length = options.length;

    for (var i = 0; i < length; i++) {
        var obj = options[i];
        if(obj.text){
            var text = obj.text;
        } else {
            var text = '';
        }
        if(obj.value){
            var value = obj.value;
        } else {
            var value = '';
        }
        if(this.val() == value){
            html += '<li class="active" data-value="' + value + '" title="' + text +'"><a href="javaScript:">' + text + '</a></li>';
        } else {
            html += '<li data-value="' + value + '" title="' + text +'"><a href="javaScript:">' + text + '</a></li>';
        }
        
    };
    options_container.html(html);
}

/**
 * [添加选项]
 * @param  options         (array)       [选项，以数组形式]
 * @param  options = [{text: 'example', value: 'example_value'}] 
 */
SelectBox.prototype.appendOptions = function(options){
    if(typeof options !== 'object') return
    var options_container = this.select.options_container,
        html = '',
        length = options.length;
    for (var i = 0; i < length; i++) {
        var obj = options[i];
        if(obj.text){
            var text = obj.text;
        } else {
            var text = '';
        }
        if(obj.value){
            var value = obj.value;
        } else {
            var value = '';
        }
        if(this.val() == value){
            html += '<li class="active" data-value="' + value + '" title="' + text +'"><a href="javaScript:">' + text + '</a></li>';
        } else {
            html += '<li data-value="' + value + '" title="' + text +'"><a href="javaScript:">' + text + '</a></li>';
        }
    };
    options_container.append(html);
}

/**
 * [获取选中的值]
 * @return [返回字符串]
 */
SelectBox.prototype.val = function(){
    return this.select.selected_option.attr('data-value');
}

/**
 * [获取选中的文字]
 * @return [返回字符串]
 */
SelectBox.prototype.text = function(){
    return this.select.selected_option.text();
}

/**
 * [设置被选中的值]
 * @param {[type]} placeholder [string]
 */
SelectBox.prototype.setSelectedText = function(text){
    this.select.selected_option.attr('title', text);
    this.select.selected_option.text(text);
}

/**
 * [设置被选中的值]
 * @param {[type]} placeholder [string]
 */
SelectBox.prototype.setSelectedVal = function(value){
    this.select.selected_option.attr('data-value', value);

    var $items = this.select.options_container.find('li');
    if($items.length === 0) return;
    $items.each(function(index, el) {
        var $this_item = $(this);
        var this_val = $this_item.attr('data-value');
        $this_item.removeClass('active');
        if(value == this_val){
            $this_item.addClass('active');
        }
    });
}


/**
 * [点击select框]
 * @param  {Function} callback [description]
 * @return {[type]}            [description]
 */
var bindBoxClick = function(that, callback){
    that.select.selected_container.on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var arrow = that.select.arrow;
        if(arrow.hasClass('down')){
            that.show();
        } else if(arrow.hasClass('up')){
            that.hide();
        }
        
        if(typeof callback === 'function'){
            callback(that);
        }
    });
}

/**
 * [离开box时隐藏select框]
 * @param  {[type]}   that     [description]
 * @return {[type]}            [description]
 */
var bindBoxLeave = function(that){
    that.select.select_container.on('mouseleave', function(event) {
        that.hide();
    });
}

/**
 * [选择]
 * @param  {[type]}   that     [description]
 * @param  {Function} callback [description]
 * @return {[type]}            [description]
 */
var bindSelect = function(that, callback){
    that.select.options_container.on('click', 'li', function(event) {
        /* Act on the event */
        var obj = $(this);
        var selected_value = obj.attr('data-value');
        var selected_text = obj.attr('title');

        var _selected_value = that.select.selected_option.attr('data-value');
        var _selected_text = that.select.selected_option.attr('title');

        that.select.selected_option.attr('data-value', selected_value);
        that.select.selected_option.attr('title', selected_text);
        that.select.selected_option.text(selected_text);

        // 清空原有的active，加上选中的active
        var options = that.select.options_container.find('li');
        options.each(function(index, el) {
            var option = $(this);
            var option_value = option.attr('data-value'); 

            option.removeClass('active');
            if(selected_value == option_value){
                option.addClass('active');
            }
        });

        that.hide();

        if(_selected_value !== selected_value && typeof callback === 'function'){
            callback(selected_value, selected_text, that);
        }
    });
}

module.exports = SelectBox;

});

/*!www/order/module/assign_pop/assign_pop.js*/
;define('www/order/module/assign_pop/assign_pop', function(require, exports, module) {

/**
 * update by wzq
 */

var popup = require('www/common/module/popup/popup.js');
var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="assignPop"><div class="assignPop-title clearfix"><div class="assignPop-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传信息</div><div class="assignPop-comfire"><button class="comfireBtn">确定</button></div></div><dl class="assignPop-driver-item first clearfix"><dd class="item"><div class="item-name"><label for="assignPop-seal-num">司机：</label></div><div class="item-content"><p class="driver-name"></p>                <div id="driver-selector"></div></div>            <div class="item-message clearfix">                <div class="error-message hidden"><i class="icon-warn"></i></div>                <div class="right-message hidden"><i class="icon-right"></i></div>            </div></dd></dl><div class="assignPop-address-complete-info"></div><div class="assignPop-address-info"></div><button class="add-other-BoxInfo">添加其他产装信息</button></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var item_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="assign-adrress-items ');if(!canChange){_template_fun_array.push('hidden');}_template_fun_array.push('" data-flag="',typeof(flag) === 'undefined'?'':baidu.template._encodeHTML(flag),'"><div class="clearfix address-bottom-line"></div><input type="hidden" class="assign_driver_id" name="assign_driver_id" value="',typeof( assign_driver_id ) === 'undefined'?'':baidu.template._encodeHTML( assign_driver_id ),'"/><input type="hidden" class="order_product_addressid" name="order_product_addressid" value="',typeof( order_product_addressid ) === 'undefined'?'':baidu.template._encodeHTML( order_product_addressid ),'"/><input type="hidden" class="order_product_timeid" name="order_product_timeid" value="',typeof( order_product_timeid ) === 'undefined'?'':baidu.template._encodeHTML( order_product_timeid ),'"/>');if(canChange){_template_fun_array.push('<dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱地址：</label></div><div class="item-content">            <div class="boxAddress-selector"></div></div>        <div class="item-message clearfix">            <div class="error-message hidden"><i class="icon-warn"></i></div>            <div class="right-message hidden"><i class="icon-right"></i></div>        </div></dd><div class="assign-delContainer"></div><dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱时间：</label></div><div class="item-content">            <div class="boxData-selector"></div></div>        <div class="item-message clearfix">            <div class="error-message hidden"><i class="icon-warn"></i></div>            <div class="right-message hidden"><i class="icon-right"></i></div>        </div></dd>');}_template_fun_array.push('</dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var complete_item_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="assign-adrress-complete-items"><div class="clearfix address-bottom-line"></div><dd class="item"><div class="item-name"><label>详细装箱地址：</label></div><div class="item-content">',typeof( box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( box_address_detail ),'</div></dd><div class="assign-delContainer"></div><dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱时间：</label></div><div class="item-content">',typeof( box_time ) === 'undefined'?'':baidu.template._encodeHTML( box_time ),'</div></dd></dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var select = require('www/common/module/select/select.js');


function AssignPop(_option){
    var ADDRESS_OPTIONS = [], 
        TIME_OPRTIONS = [];
    var html,
        title,
        defaults = {
            data: {
                id: '',
                carTeam_id: '',
                address_info: []
            },
            carTeam_id: '',
            select_options: [],
            type: 'add',
            can_open: false,
            msg: '未加载完数据，请重试！'
        },
        options = $.extend({}, defaults, _option);
        
    if(options.type === 'add'){
        title = '添加司机/装箱信息';
        var res_mssage = '添加成功！';
    } else {
        title = '修改司机/装箱信息';
        var res_mssage = '修改成功！'
    }

    // 数据处理
    (function(){
        for (var i = 0; i < options.select_options.length; i++) {
            ADDRESS_OPTIONS.push({
                value: options.select_options[i].product_address_id,
                text: options.select_options[i].box_address_detail
            })
        };
    })();

    function _AssignPop(){
        var type = this.type = options.type;
        var flag = this.flag = 0;   // 计时器

        var DOM = this.DOM = {};
        this.DOM = {
            title: this.pop.find('.assignPop-title .title-text'),
            comfireBtn: this.pop.find('.assignPop-title .assignPop-comfire .comfireBtn'),
            cancelBtn: this.pop.find('.assignPop-title .assignPop-cancel .cancelBtn'),
            addBtn: this.pop.find('#assignPop .add-other-BoxInfo'),
            delBtn: $('<a class="del-btn" href="javaScript:">删除</a>'),
            driver: this.pop.find('#driver-selector'),
            items_container: this.pop.find('.assignPop-address-info'),
            items_complete_container: this.pop.find('.assignPop-address-complete-info'),
            items: this.pop.find('.assign-adrress-items')
        }

        this.can_open = options.can_open;
        this.msg = options.msg;
        this.driverSelector;
        this.address_list = [];
        // 计数器
        flag = this.DOM.items.length;

        /** run function */
        this.init_popup();

        /** bind event */
        var that = this;
        this.DOM.addBtn.on('click', function(event) {
            if(flag == 0){
                flag = 1;
            }
            flag++;

            var this_obj = $(this);
            var add_item = $(item_tpl({
                flag: flag,
                assign_driver_id: '',
                order_product_addressid: '',
                order_product_timeid: '',
                canChange: true
            }));
            that.DOM.items_container.append(add_item);

            //选择组件,装箱时间
            var _boxDataSelect = new select({
                width:380,
                container : add_item.find(".boxData-selector"),
                options : [],
                defaultText: '请先选择装箱地址',
                defaultValue : '0',
                options_max_height: 160,
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');
                }
            });
            //选择组件,装箱地址
            var _boxAddressSelect = new select({
                width:380,
                container : add_item.find(".boxAddress-selector"),
                options : ADDRESS_OPTIONS,
                defaultText: '请选择装箱地址',
                defaultValue : '0',
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');

                    _boxDataSelect.setSelectedVal('');
                    _boxDataSelect.setSelectedText('请先选择装箱地址');
                    _boxDataSelect.setOptions([]);
                    (function(val){
                        TIME_OPRTIONS = [];
                        for (var i = 0; i < options.select_options.length; i++) {
                            if(val == options.select_options[i].product_address_id){
                                var box_time = options.select_options[i].box_date;
                                for (var j = 0; j < box_time.length; j++) {
                                    TIME_OPRTIONS.push({
                                        value: box_time[j].product_time_id,
                                        text: box_time[j].product_supply_time
                                    });
                                };
                            }
                        };
                    })(val);

                    _boxDataSelect.setSelectedText('请选择装箱时间');
                    _boxDataSelect.setOptions(TIME_OPRTIONS);
                }
            });

            that.address_list.push({
                address: _boxAddressSelect,
                time: _boxDataSelect,
                assign_id: add_item.find("input[name='assign_driver_id']"),
                order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                flag: flag
            });
        });

        /** 鼠标悬浮显示删除按钮 */
        this.pop.on('mouseover', '.assign-adrress-items', function(event) {
            var this_obj = $(this),
                getFlag = this_obj.attr('data-flag'),
                delBtn_container = this_obj.find('.assign-delContainer');

            if(getFlag == 1) return;

            delBtn_container.html(that.DOM.delBtn);

            that.DOM.delBtn.show();
            that.DOM.delBtn.attr('data-flag', getFlag);
        });

        /** 鼠标离开隐藏删除按钮 */
        this.pop.on('mouseleave', '.assign-adrress-items', function(event) {
            that.DOM.delBtn.hide();
            that.DOM.delBtn.removeAttr('data-flag');
        });

        /** 点击删除按钮 */
        this.pop.on('click', '.del-btn', function(event) {
            var this_obj = $(this),
                getFlag = this_obj.attr('data-flag');

            var i;
            if(getFlag == 1) return;
            if(that.address_list.length == 0) return;

            for (i = 0; i < that.address_list.length; i++) {
                if(getFlag == that.address_list[i].flag){
                    that.address_list.splice(i, 1);
                }
            };
            this_obj.parents('.assign-adrress-items').remove();
        });

        /** 点击确定按钮 */
        this.DOM.comfireBtn.on('click', function(event) {
            save(that);
        });

        /** 点击取消按钮 */
        this.DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });
    }

    //popup尺寸
    _AssignPop.prototype = new popup({
        height:550,
        width :700,
        tpl:tpl
    });

    _AssignPop.prototype.constructor = _AssignPop;

    _AssignPop.prototype.setAdd = function(box_id){
        /** 加载时间和地址 */
        flag = this.flag = 1;
        type = this.type = 'add';
        title = '添加司机/装箱信息';
        res_mssage = '添加成功！';
        this.address_list = [];


        this.DOM.items_container.html('');
        this.DOM.items_complete_container.html('');

        this.driverSelector.setSelectedText('请选择司机');
        this.driverSelector.setSelectedVal('');
        
        this.pop.find('.driver-name').text('').hide();
        this.DOM.driver.show();

        var add_item = $(item_tpl({
            flag: flag,
            canChange: true,
            assign_driver_id: '',
            order_product_addressid: '',
            order_product_timeid: ''
        }));
        this.DOM.items_container.html(add_item);

        //选择组件,装箱时间
        var _boxDataSelect = new select({
            width:380,
            container : add_item.find(".boxData-selector"),
            options : [],
            defaultText: '请先选择装箱地址',
            defaultValue : '0',
            options_max_height: 160,
            onSelectChange: function(val, txt, obj){
                obj.select.select_box.removeClass('error');
            }
        });

        //选择组件,装箱地址
        var _boxAddressSelect = new select({
            width:380,
            container : add_item.find(".boxAddress-selector"),
            options : ADDRESS_OPTIONS,
            defaultText: '请选择装箱地址',
            defaultValue : '0',
            onSelectChange: function(val, txt, obj){
                obj.select.select_box.removeClass('error');

                _boxDataSelect.setSelectedVal('');
                _boxDataSelect.setSelectedText('请先选择装箱地址');
                _boxDataSelect.setOptions([]);
                (function(val){
                    TIME_OPRTIONS = [];
                    for (var i = 0; i < options.select_options.length; i++) {
                        if(val == options.select_options[i].product_address_id){
                            var box_time = options.select_options[i].box_date;
                            for (var j = 0; j < box_time.length; j++) {
                                TIME_OPRTIONS.push({
                                    value: box_time[j].product_time_id,
                                    text: box_time[j].product_supply_time
                                });
                            };
                        }
                    };
                })(val);
                
                _boxDataSelect.setSelectedText('请选择装箱时间');
                _boxDataSelect.setOptions(TIME_OPRTIONS);
            }
        });

        this.address_list.push({
            address: _boxAddressSelect,
            time: _boxDataSelect,
            assign_id: add_item.find("input[name='assign_driver_id']"),
            order_product_addressid: add_item.find("input[name='order_product_addressid']"),
            order_product_timeid: add_item.find("input[name='order_product_timeid']"),
            flag: flag
        });
        options.data.id = box_id;
    }

    _AssignPop.prototype.setEdit = function(opt){
        var that = this;
        type = that.type = 'edit';
        title = '修改司机/装箱信息';
        res_mssage = '修改成功！';
        this.address_list = [];

        that.DOM.items_container.html('');
        that.DOM.items_complete_container.html('');

        that.driverSelector.setSelectedText(opt.driver_info.name + '/' + opt.driver_info.car_number);
        that.driverSelector.setSelectedVal(opt.driver_info.id);
        
        if(typeof opt.driver_info.driver_can_change === 'boolean' && !opt.driver_info.driver_can_change){
            that.pop.find('.driver-name').text(opt.driver_info.name).show();
            that.DOM.driver.hide();
        } else {
            that.pop.find('.driver-name').text(opt.driver_info.name).hide();
            that.DOM.driver.show();
        }

        if(opt.address_info.length == 0){
            flag = that.flag = 1;
            var add_item = $(item_tpl({
                flag: flag,
                canChange: true,
                assign_driver_id: '',
                order_product_timeid: ''
            }));
            that.DOM.items_container.html(add_item);

            
            //选择组件,装箱时间
            var _boxDataSelect = new select({
                width:380,
                container : add_item.find(".boxData-selector"),
                options : [],
                defaultText: '请选择装箱时间',
                defaultValue : '0',
                options_max_height: 160,
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');
                }
            });

            //选择组件,装箱地址
            var _boxAddressSelect = new select({
                width:380,
                container : add_item.find(".boxAddress-selector"),
                options : ADDRESS_OPTIONS,
                defaultText: '请选择装箱地址',
                defaultValue : '0',
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');

                    _boxDataSelect.setSelectedVal('');
                    _boxDataSelect.setSelectedText('请先选择装箱地址');
                    _boxDataSelect.setOptions([]);
                    (function(val){
                        TIME_OPRTIONS = [];
                        for (var i = 0; i < options.select_options[i].length; i++) {
                            
                            if(val == options.select_options[i].product_address_id){
                                var box_time = options.select_options[i].box_date;
                                for (var j = 0; j < box_time.length; j++) { 
                                    TIME_OPRTIONS.push({
                                        value: box_time[j].product_time_id,
                                        text: box_time[j].product_supply_time
                                    });
                                };
                            }
                        };
                    })(val);

                    _boxDataSelect.setSelectedText('请选择装箱时间');
                    _boxDataSelect.setOptions(TIME_OPRTIONS);
                }
            });
            that.address_list.push({
                address: _boxAddressSelect,
                time: _boxDataSelect,
                assign_id: add_item.find("input[name='assign_driver_id']"),
                order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                flag: flag
            });
            
        } else {
            var newFlag = 1;
            
            for (var i = 0; i < opt.address_info.length; i++) { 

                if(opt.address_info[i].assign_status > 2){
                    var complete_item = $(complete_item_tpl({
                        box_address_detail: opt.address_info[i].box_address_detail,
                        box_time: opt.address_info[i].box_time
                    }));

                    that.DOM.items_complete_container.append(complete_item);
                }

                var canChange = false;
                if(opt.address_info[i].assign_status < 3){
                    canChange = true;
                }
                var add_item = $(item_tpl({
                    flag: newFlag,
                    canChange: canChange,
                    assign_driver_id: opt.address_info[i].assign_id,
                    order_product_addressid: opt.address_info[i].product_address_id,
                    order_product_timeid: opt.address_info[i].product_time_id
                }));
                that.DOM.items_container.append(add_item);
                
                (function(val){
                    TIME_OPRTIONS = [];
                    for (var m = 0; m < options.select_options.length; m++) {
                        
                        if(val == options.select_options[m].product_address_id){

                            var box_time = options.select_options[m].box_date;
                            for (var j = 0; j < box_time.length; j++) { 
                                TIME_OPRTIONS.push({
                                    value: box_time[j].product_time_id,
                                    text: box_time[j].product_supply_time
                                });
                            };
                        }
                    };
                })(opt.address_info[i].product_address_id);
                var $timeSelectContainer = add_item.find(".boxData-selector");
                var $addressSelectContainer = add_item.find(".boxAddress-selector");

                if($timeSelectContainer.length !== 0 || $addressSelectContainer.length !== 0){
                    //选择组件,装箱时间
                    var _boxDataSelect = new select({
                        width:380,
                        container : add_item.find(".boxData-selector"),
                        options : TIME_OPRTIONS,
                        defaultText: opt.address_info[i].box_time,
                        defaultValue : opt.address_info[i].product_time_id,
                        options_max_height: 160,
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');
                        }
                    });

                    //选择组件,装箱地址
                    var _boxAddressSelect = new select({
                        width:380,
                        container : add_item.find(".boxAddress-selector"),
                        options : ADDRESS_OPTIONS,
                        defaultText: opt.address_info[i].box_address_detail,
                        defaultValue : opt.address_info[i].product_address_id,
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');

                            _boxDataSelect.setSelectedVal('');
                            _boxDataSelect.setSelectedText('请先选择装箱地址');
                            _boxDataSelect.setOptions([]);
                            (function(val){
                                TIME_OPRTIONS = [];
                                for (var i = 0; i < options.select_options.length; i++) {

                                    if(val == options.select_options[i].product_address_id){
                                        var box_time = options.select_options[i].box_date;

                                        for (var j = 0; j < box_time.length; j++) { 
                                            TIME_OPRTIONS.push({
                                                value: box_time[j].product_time_id,
                                                text: box_time[j].product_supply_time
                                            });
                                        };
                                    }
                                };
                            })(val);

                            _boxDataSelect.setSelectedText('请选择装箱时间');
                            _boxDataSelect.setOptions(TIME_OPRTIONS);
                        }
                    });
                    that.address_list.push({
                        address: _boxAddressSelect,
                        time: _boxDataSelect,
                        assign_id: add_item.find("input[name='assign_driver_id']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        flag: newFlag
                    });
                } else {
                    that.address_list.push({
                        assign_id: add_item.find("input[name='assign_driver_id']"),
                        order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        flag: newFlag
                    });
                }
                newFlag++;
            };
            
        }
        options.data.id = opt.box_id;
    }
    _AssignPop.prototype.onComplete = function(){}

    _AssignPop.prototype.init_popup = function(){
        var that = this;
        /** title set */
        that.DOM.title.text(title);

        /** 加载司机options */
        XDD.Request({
            url: '/carteam/order/choose_driver',
            type: 'post',
            data: {
                carTeamId: options.carTeam_id
            },
            success: function(res){
                if(res.error_code == 0){
                    var res_driver_list = res.data,
                        list_length = res_driver_list.length;
                    if(list_length == 0){
                        that.msg = '您未添加司机信息，请与管理员联系！'
                        return
                    }

                    var i, 
                        driver_list = [];

                    for (var i = 0; i < list_length; i++) {
                        driver_list.push({
                            value: res_driver_list[i].userid,
                            text: res_driver_list[i].driver_name + '/' + res_driver_list[i].car_number,
                        });
                    };


                    that.driverSelector = new select({
                        width:380,
                        container : that.DOM.driver,
                        options : driver_list,
                        defaultText: '请选择司机',
                        defaultValue : '',
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');
                        }
                    });
                    that.can_open = true;
                    that.msg = '';
                } else {
                    that.msg = res.error_msg || '未知错误！';
                    return
                }
            }
        },true);
    }

    function save(that){
        var driver_id = that.driverSelector.val();
        var driverInfo = (function(str){
            var check_index = str.indexOf('/');
            var _driverInfo = {};
            var _driver_name = str.substring(0, check_index);
            var _car_num = str.substring(check_index + 1, str.length);

            return {
                driver_name: _driver_name,
                car_num: _car_num
            }
        })(that.driverSelector.text());

        var onComplete_data = {
            driver_info: {
                driver_id: driver_id,
                driver_name: driverInfo.driver_name,
                car_num: driverInfo.car_num
            },
            address_info: []
        };
        if(!driver_id || driver_id == 0){
            that.driverSelector.select.select_box.addClass('error');
            shake(that.driverSelector.select.select_box);
            return
        }
        var order_assign_info = getAddressList(that.address_list);

        if(!order_assign_info || order_assign_info.length === 0) return;

        var i,
            info_length = order_assign_info.length;

        for (i = 0; i < info_length; i++) {
            order_assign_info[i].driver_user_id = driver_id;
            order_assign_info[i].order_freight_boxid = options.data.id;
            order_assign_info[i].order_freight_id = options.data.orderId;

            onComplete_data.address_info.push({
                product_address_id: order_assign_info[i].order_product_addressid,
                box_address_detail: order_assign_info[i].order_product_address_text,
                product_time_id: order_assign_info[i].order_product_timeid,
                box_time: order_assign_info[i].order_product_time_text
            });

            delete order_assign_info[i].order_product_address_text;
            delete order_assign_info[i].order_product_time_text;
            if(!order_assign_info[i].assign_id){
                delete order_assign_info[i].assign_id;
            }
            if(!order_assign_info[i].order_product_addressid){
                delete order_assign_info[i].order_product_addressid;
            }
            if(!order_assign_info[i].order_product_timeid){
                delete order_assign_info[i].order_product_timeid;
            }
        };

        XDD.Request({
            url: '/carteam/order/assign_edit_save',
            data: {
                order_assign_info: order_assign_info
            },
            success: function(res){
                if(res.error_code == 0){
                    that.hide();
                    _alert(res_mssage);
                    if(typeof that.onComplete === 'function'){
                        that.onComplete(onComplete_data);
                    }
                } else {
                    that.hide();
                    _alert(res.error_msg || '未知错误！');
                }
            }
        }, true);
    }

    function getAddressList(_list){
        var i,
            address_length = _list.length;

        var isPass = true;
        var list = [];
        for (i = 0; i < address_length; i++) {

            if(_list[i].address && _list[i].address){
                if(!_list[i].address.val() || _list[i].address.val() == 0){
                    _list[i].address.select.select_box.addClass('error');
                    shake(_list[i].address.select.select_box);
                    isPass = false;
                } else if(!_list[i].time.val() || _list[i].time.val() == 0){
                    _list[i].time.select.select_box.addClass('error');
                    shake(_list[i].time.select.select_box);
                    isPass = false;
                } else {
                    list.push({
                        order_product_addressid: _list[i].address.val(),
                        order_product_address_text: _list[i].address.text(),
                        order_product_timeid: _list[i].time.val(),
                        order_product_time_text: _list[i].time.text(),
                        assign_id: _list[i].assign_id.val()
                    });
                }
            } else {

                list.push({
                    order_product_addressid: _list[i].order_product_addressid.val(),
                    order_product_timeid: _list[i].order_product_timeid.val(),
                    order_product_address_text: '',
                    order_product_time_text: '',
                    assign_id: _list[i].assign_id.val()
                });
            }
        };

        if(isPass) return list;
        if(!isPass) return false;
    }

    function shake(obj){ 
        obj.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
           .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40);
    }

    //新建select
    function AddSelector($scope){
        this.$scope = $scope;
        var _boxaddress = $scope.find(".boxAddress-selector"),
            _boxData = $scope.find(".boxData-selector") ;
        //选择组件,装箱地址
        var _boxAddressSelect = new select({
            width:380,
            container : _boxaddress,
            options : boxAddress,
            defaultText: '请选择装箱地址',
            defaultValue : '0'
        });

        //选择组件,装箱时间
        var _boxDataSelect = new select({
            width:380,
            container : _boxData,
            options : boxData,
            defaultText: '请选择装箱时间',
            defaultValue : '0'
        });
        return;
    }

    return new _AssignPop();
}

module.exports = AssignPop;


});

/*!www/common/module/cards/cards.js*/
;define('www/common/module/cards/cards', function(require, exports, module) {

function Cards(_options){
    function _Cards(_options){
        var defaults = {
            width: 500
        },
        settings = this.settings = $.extend({}, defaults, _options),
        $body = $('body'),
        $card = $('<div class="card"></div>'),
        $square = $('<div class="square"></div>'),
        $content = $('<div class="content"></div>');

        this.card = $card;
        this.content = $content;

        $card.append($square).append($content);
        $body.append($card);

        $card.css('width', settings.width).css('height', 500);
    }

    _Cards.prototype = {
        show: function(e){
            var obj = e.target;
            var $body = $('body');
            var pos = {
                top: 0,
                left: 0
            };
            while(obj){
                pos.left += obj.offsetLeft;
                pos.top += obj.offsetTop;
                obj = obj.offsetParent;
            }

            var _top, _left;

            if(pos.left - this.settings.width < 1){
                _left = pos.left;
            } else {
                _left = pos.left - this.settings.width + 20;
            }
            _top = pos.top + 25;
            
            this.card.show();
            this.card.css('left', _left).css('top', _top);
        },
        hide: function(){
            this.card.hide();
        },
        setContent: function(html){
            this.content.html(html);
        }
    }
    return new _Cards(_options);
}

module.exports = Cards;

});

/*!www/common/module/confirm/confirm.js*/
;define('www/common/module/confirm/confirm', function(require, exports, module) {

/**
 * 通用confirm
 * Created by wll on 15/8/21.
 */

var popup = require('www/common/module/popup/popup.js');
var tpl = [function(locals, filters, escape, rethrow) {
escape = escape || function (html){
  return String(html)
    .replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;');
};
var __stack = { lineno: 1, input: "<div id=\"confirm\">\n    请点击确定，继续执行!\n</div>\n<div class=\"confirm-pop-btn confirm-btn \">\n    <a href=\"javascript:;\" class=\"user-btn\" id=\"confirmBtn\">确认</a>\n    <a href=\"javascript:;\" class=\"user-btn\" id=\"cancelBtn\">取消</a>\n</div>", filename: "www/common/module/confirm/confirm.ejs" };
function rethrow(err, str, filename, lineno){
  var lines = str.split('\n')
    , start = Math.max(lineno - 3, 0)
    , end = Math.min(lines.length, lineno + 3);

  // Error context
  var context = lines.slice(start, end).map(function(line, i){
    var curr = i + start + 1;
    return (curr == lineno ? ' >> ' : '    ')
      + curr
      + '| '
      + line;
  }).join('\n');

  // Alter exception message
  err.path = filename;
  err.message = (filename || 'ejs') + ':'
    + lineno + '\n'
    + context + '\n\n'
    + err.message;
  
  throw err;
}
try {
var buf = [];
with (locals || {}) { (function(){ 
 buf.push('<div id="confirm">\n    请点击确定，继续执行!\n</div>\n<div class="confirm-pop-btn confirm-btn ">\n    <a href="javascript:;" class="user-btn" id="confirmBtn">确认</a>\n    <a href="javascript:;" class="user-btn" id="cancelBtn">取消</a>\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0];

var confirm_pop = function(){
    function A(){
        bind(this);
    }

    A.prototype = new popup({
        title :'确认信息',
        height:180,
        width :380,
        tpl:tpl
    });

    A.prototype.constructor = A;

    A.prototype.setData = function(cnt){
        $('#confirm').html(cnt);
    }

    A.prototype.onConfirm = function(){

    }

    function bind(that){
        that.pop.find('#confirmBtn').on('click',function(event) {
            that.hide();
            that.onConfirm();
        });

        that.pop.find('#cancelBtn').on('click',function(event) {
            that.hide();
        });
    }
    return new A();
}


module.exports = confirm_pop;

});

/*!www/order/widget/view/order_details/partial/box_info/box_info.js*/
;define('www/order/widget/view/order_details/partial/box_info/box_info', function(require, exports, module) {

var EnsupePop = require("www/order/module/ensupe_pop/ensupe_pop.js");
var AssignPop = require("www/order/module/assign_pop/assign_pop.js");
var Cards = require("www/common/module/cards/cards.js");
var select = require('www/common/module/select/select.js');
var box_details_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<ul class="box-no-complete ');if(length === i + 1){_template_fun_array.push('last');}_template_fun_array.push('"><li class="clearfix"><div class="address-content"><p class="address">',typeof( box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( box_address_detail ),'</p><p class="time">',typeof( box_time ) === 'undefined'?'':baidu.template._encodeHTML( box_time ),'</p></div><a href="javascript:;" class="complete" data-aid="',typeof( assign_driver_id ) === 'undefined'?'':baidu.template._encodeHTML( assign_driver_id ),'">产装完成</a></li></ul>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var Confirm = require('www/common/module/confirm/confirm.js');

var global_box;
var global_addressInfo;

var confirm_Pop = new Confirm();

function bind(_boxInfo, orderid, _addressInfo, carTeam_id){
    var logisticsCards = new Cards({
        width: 300
    });
    global_box = _boxInfo;
    global_addressInfo = _addressInfo;
	/** DOM */
	var DOM = {
		$scope: $('.boxInfo-wrapper'),
	};

    /*shuzu*/
    var  box_address = [],
        contactName = [],
        box_data = [];

	/** popup */
	var ensupe_pop = EnsupePop({
        data: {
            orderId: orderid
        }
    });

    // 上传箱号铅封号
	DOM.$scope.on('click', '.funcs a.add-ensupe', function(event) {
		
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = this_obj.attr('data-id');
        
        var flag = getBoxAddressIndex(box_id);

        var $code_td = $($td[1]),
            $ensupe_td = $($td[2]);

        ensupe_pop.setAdd({
            boxId: box_id
        });
        ensupe_pop.show();
		ensupe_pop.onComplete = function(res){

            // // 数据修改
            // global_box[flag].box_code = res.box_code;
            // global_box[flag].box_ensupe = res.box_ensupe;
            // // 数据回填
            // $code_td.text(res.box_code);
            // $ensupe_td.text(res.box_ensupe);
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
        };
	});
    // 修改箱号铅封号
	DOM.$scope.on('click', '.funcs a.edit-ensupe', function(event) {
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = this_obj.attr('data-id');
        
        var flag = getBoxAddressIndex(box_id);

        if(!global_box[flag].box_can_change){
            _alert('该箱子已经产装完成！')
            return;
        }

        var $code_td = $($td[1]),
            $ensupe_td = $($td[2]);

		ensupe_pop.setEdit({
			boxId: box_id,
            orderId: orderid,
			boxNum: global_box[flag].box_code,
			sealNum: global_box[flag].box_ensupe
		});
		ensupe_pop.show();
		ensupe_pop.onComplete = function(res){
			// // 数据修改
   //          global_box[flag].box_code = res.box_code;
   //          global_box[flag].box_ensupe = res.box_ensupe;
   //          // 数据回填
   //          $code_td.text(res.box_code);
   //          $ensupe_td.text(res.box_ensupe);
   //          
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
		};
	});

    // 添加司机／装箱信息
    var assign_pop = AssignPop({
        data: {
            orderId: orderid
        },
        carTeam_id: carTeam_id,
        select_options: global_addressInfo
    });

    DOM.$scope.on('click', '.funcs a.add-assign', function(event) {
        if(!assign_pop.can_open){
            _alert(assign_pop.msg);
            return
        }
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = $(this).attr('data-id');
        var flag = getBoxAddressIndex(box_id);

        var $driver_name_td = $($td[3]),
            $car_num_td = $($td[4]),
            $address_details = $($td[5]);

        assign_pop.setAdd(box_id);
        assign_pop.onComplete = function(res){
            // $driver_name_td.text(res.driver_info.driver_name);
            // $car_num_td.text(res.driver_info.car_num);

            // /** 数据回填 */
            // global_box[flag].driver_info.id = res.driver_info.driver_id;
            // global_box[flag].driver_info.name = res.driver_info.driver_name;
            // global_box[flag].driver_info.car_number = res.driver_info.car_num;

            // /** 地址回填, 时间回填 */
            // var address_detail_html = '';
            // global_box[flag].address_info = [];

            // for (var i = 0; i < res.address_info.length; i++) {
            //     address_detail_html += box_details_tpl({
            //         i: i,
            //         length: res.address_info.length,
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         box_time: res.address_info[i].box_time
            //     });

            //     global_box[flag].address_info.push({
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         product_address_id: res.address_info[i].product_address_id,
            //         box_time: res.address_info[i].box_time,
            //         product_time_id: res.address_info[i].product_time_id
            //     });
            // };

            // var $box_completes = $address_details.find('.box-complete');
            // var $box_no_completes = $address_details.find('.box-no-complete');
            // var c_length = $box_completes.length;
            // var c_n_length = $box_no_completes.length;

            // if(c_n_length === 0){
            //     $address_details.html(address_detail_html);
            // } else {
            //     if(c_n_length !== 0){
            //         $box_no_completes.remove();
            //     }
            //     $($box_completes[c_length-1]).before(address_detail_html);
            // }

            // this_obj.html('修改箱号/铅封号');
            // this_obj.removeClass('add-assign').addClass('edit-assign');
            // 
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
        }
        assign_pop.show();
        
    });

    // 添加司机／装箱信息
    DOM.$scope.on('click', '.funcs a.edit-assign', function(event) {
        if(!assign_pop.can_open){
            _alert(assign_pop.msg);
            return
        }
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = $(this).attr('data-id');
        var flag = getBoxAddressIndex(box_id);

        var $driver_name_td = $($td[3]),
            $car_num_td = $($td[4]),
            $address_details = $($td[5]);

        assign_pop.setEdit(global_box[flag]);

        assign_pop.onComplete = function(res){
            // $driver_name_td.text(res.driver_info.driver_name);
            // $car_num_td.text(res.driver_info.car_num);

            // /** 数据回填 */
            // global_box[flag].driver_info.id = res.driver_info.driver_id;
            // global_box[flag].driver_info.name = res.driver_info.driver_name;
            // global_box[flag].driver_info.car_number = res.driver_info.car_num;

            // /** 地址回填, 时间回填 */
            // var address_detail_html = '';
            // global_box[flag].address_info = [];

            // for (var i = 0; i < res.address_info.length; i++) {
            //     address_detail_html += box_details_tpl({
            //         i: i,
            //         length: res.address_info.length,
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         box_time: res.address_info[i].box_time
            //     });

            //     global_box[flag].address_info.push({
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         product_address_id: res.address_info[i].product_address_id,
            //         box_time: res.address_info[i].box_time,
            //         product_time_id: res.address_info[i].product_time_id
            //     });
            // };

            // var $box_completes = $address_details.find('.box-complete');
            // var $box_no_completes = $address_details.find('.box-no-complete');
            // var c_length = $box_completes.length;
            // var c_n_length = $box_no_completes.length;

            // if(c_n_length === 0){
            //     $address_details.html(address_detail_html);
            // } else {
            //     if(c_n_length !== 0){
            //         $box_no_completes.remove();
            //     }
            //     $($box_completes[c_length-1]).before(address_detail_html);
            // }
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
        }
        assign_pop.show();
    });

    var timerCard;
    // message test
    DOM.$scope.on('mouseover', '.funcs i.icon-status-message', function(e) {
        var this_obj = $(this);
        var showCards = function(this_obj){
            logisticsCards.hide();
            var boxId = this_obj.attr('data-boxid');

            var html = '';
            XDD.Request({
                url: '/order/boxtimeline',
                type: 'get',
                data:{
                    orderboxid: boxId
                },
                success: function(res){
                    if(res.error_code == 0){
                        var i,
                            length = res.data.length;
                        if(length > 0){
                            for (i = 0; i < length; i++) {
                                html += '<p><span style="margin-right:5px;">' + res.data[i].create_time + '</span>' + res.data[i].content + '</p>'; 
                            };
                        } else {
                            html = '<p style="text-align:center;">暂无物流信息！</p>'
                        }
                        
                        logisticsCards.setContent(html);
                        logisticsCards.show(e);
                    } else {
                        logisticsCards.setContent(html);
                        logisticsCards.show(e);
                    }
                }
            });
        }
        timerCard = setTimeout(function(){showCards(this_obj)}, 800);
    }).on('mouseleave', '.funcs i.icon-status-message', function(e) {
        clearTimeout(timerCard);
        logisticsCards.hide();
    });


    DOM.$scope.on('click', '.address-details a.complete', function(event) {
        var this_obj = $(this);
        var aid = this_obj.attr('data-aid');
        var type;

        var txt = '';
        if(!aid) return;
        XDD.Request({
            url: '/order/assignStatus',
            type: 'get',
            data:{
                assignId: aid,
                orderId: orderid
            },
            success: function(res){
                if(res.error_code == 0){
                    type = res.data.status
                    if(type == 2){
                        txt = '司机未确认产装，是否确认产装？'
                    } else if(type == 100){
                        txt = '司机已确认产装，是否确认产装？'
                    } else {
                        return
                    }
                    confirm_Pop.setData(txt);
                    confirm_Pop.show();
                } else {
                    _alert(res.error_msg || '未知错误！');
                }
            }
        });

        confirm_Pop.onConfirm = function(){
            XDD.Request({
                url: '/carteam/order/driver_equipment_complete',
                data:{
                    assign_id: aid
                },
                success: function(res){
                    if(res.error_code == 0){
                        _alert('产装完成！');
                       window.location.reload();
                    } else {
                        _alert(res.error_msg || '未知错误！');
                    }
                }
            }, true);
        }
    });
}

/**
 * [根据box_id获取索引]
 * @param  {[type]} box_id [description]
 * @return {[type]}        [description]
 */
function getBoxAddressIndex(box_id){
    if(!global_box || !box_id) return;
    var flag,
        i,
        length = global_box.length;
    for (i = 0; i < length; i++) {
        if(global_box[i].box_id == box_id){
            flag = i;
        }
    };

    return  flag;
}

module.exports = {
	init:function(_address, orderid, _addressInfo, carTeam_id){
		bind(_address, orderid, _addressInfo, carTeam_id);
	}
}


});
