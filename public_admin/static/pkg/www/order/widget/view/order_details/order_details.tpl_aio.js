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

/*!www/common/module/util.js*/
;define('www/common/module/util', function(require, exports, module) {

/*
 * 基础组件
 * by kenny
 */
 
var util = {

    isNull : function(s){ 
    	if(typeof(s) == "string"){ 
    		if(s.length == 0){ 
				return true;
			} else if(s.replace(/\s/g,"").length == 0){ 
				return true;
			} else { 
				return false;
			}
    	} else { 
    		return false;
    	}
			
	},
    
    isNumber : function(s) {
        return /^[0-9]*$/.test(s);
    },
    
	isMobilePhone : function(s) {
        if(s.length !== 11){
            return false;
        }
		return /^1[3-8]\d{9}/.test(s);
	},
	
	isEmail: function(s) {
		return /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(s);
	},
    
    isUname: function(s) {
        return /^[a-z][a-z0-9]{3,12}$/.test(s);
    },

    isPassword: function(s){
        return /[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/.test(s);
    },
	
	encodeHTML: function(s) {
        return s
			.replace(/\s{2,}/g,"")
			.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    },
	
	decodeHTML: function(s){
        var str = s
            .replace(/&quot;/g, '"')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, "&");
        //处理转义的中文和实体字符
        return str.replace(/&#([\d]+);/g, function(_0, _1) {
            return String.fromCharCode(parseInt(_1, 10));
        });
    },
    
	getParam: function(){
    
        var url = window.location.href;
        
        if (url.indexOf("?") > -1) {
            var query = url.slice(url.indexOf("?") + 1);
        } else if (url.indexOf("#") > -1) {
            var query = url.slice(url.indexOf("#") + 1);
        } else {
            return {};
        }
        
        if (query == ""){
            return {};
        }
        
        var param = {};
        var p = query.split("&");
        
        for (var i = 0; i < p.length; i++) {
            var q = p[i].split("=");
            if (!q[1]) {
                continue;
            }
            var idx = q[1].indexOf("#");
            q[1] = idx > -1 ? q[1].slice(0, idx) : q[1];
            param[q[0]] = q[1];
        }
        return param;
    },
    
    getQuery: function(name){
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if(r != null){
            return unescape(r[2]); 
            return null;
        }
    },
    
    /**
     * 将json对象转化为url字串格式
     * json: json对象
     * encode: 编码函数，如:encodeURIComponent
     */
    getUrl: function(json, encode) {
        var s = [];
        encode = encode || function(v) {
            return v
        };
        for (var n in json) {
            var value = json[n];
            if (value != '' && value != null && typeof(value) != 'undefined') {
                s.push(n + "=" + encode(value));
            }
        }
        return s.join("&");
    },
    
    //字符串长度补全
    fixed: function(str, length) {
        var str = str.toString();
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    },
    
    /**
     * 判断是否为非负整数
     */
    isInteger: function(num) {
        return /^\+?[1-9][0-9]*$/.test(num);
    },
    
    /**
     * 停止事件冒泡传播
     * @param {Event}
     */
    stopBubble: function(e) {
        var e = window.event || e;
        e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
    },

    /**
     * 阻止默认事件处理
     * @param {Event}
     */
    preventDefault: function(e) {
        var e = window.event || e;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        return false;
    },
    
    /**
     * 计算窗口尺寸
     */
    getClientSize: function() {
        var doc = document;
        return {
            width: doc.body.clientWidth,
            height: doc.body.clientHeight
        };
    },
    
    /**
     * 是否支持placeholder
     */
    hasPlaceholderSupport: function () {
        return 'placeholder' in document.createElement('input');
    },

    /**
     * 判断对象是否为空
     */
    isEmptyObject: function(obj){ 
    	if(typeof(obj) == 'object'){ 
    		for(var name in obj){ 
    			return false;
    			break;
    		}
    		return true;
    	} else { 
    		return true;
    	}
    }

}

module.exports = util;

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

/*!www/common/module/storage.js*/
;define('www/common/module/storage', function(require, exports, module) {

/**
 * 前端缓存组件
 * userData + LocalStorage
 */

var storage = {};

/**
 * 验证字符串是否合法的键名
 */
storage._isValidKey = function (key) {
    return (new RegExp("^[^\\x00-\\x20\\x7f\\(\\)<>@,;:\\\\\\\"\\[\\]\\?=\\{\\}\\/\\u0080-\\uffff]+\x24")).test(key);
};

/**
 * 在IE7及其以下版本中，采用UserData的方式进行本地存储
 */
storage.userData = (function () {

    // 所有的key
    var _clearAllKey = "_ALL.KEY_";

    // 创建并获取这个input:hidden实例
    var _getInstance = function () {
        //把UserData绑定到input:hidden上
        var _input = null;
        //是的，不要惊讶，这里每次都会创建一个input:hidden并增加到DOM树种
        //目的是避免数据被重复写入，提早造成“磁盘空间写满”的Exception
        _input = document.createElement("input");
        _input.type = "hidden";
        _input.addBehavior("#default#userData");
        document.body.appendChild(_input);
        return _input;
    };

    /**
     * 将数据通过UserData的方式保存到本地，文件名为：文件名为：config.key[1].xml
     * @param {String} key 待存储数据的key，和config参数中的key是一样的
     * @param {Object} config 待存储数据相关配置
     * @cofnig {String} key 待存储数据的key
     * @config {String} value 待存储数据的内容
     * @config {String|Object} [expires] 数据的过期时间，可以是数字，单位是毫秒；也可以是日期对象，表示过期时间
     * @private
     */
    var __setItem = function (key, config) {
        try {
            var input = _getInstance();
            //创建一个Storage对象
            var storageInfo = config || {};
            //设置过期时间
            if (storageInfo.expires) {
                var expires;
                //如果设置项里的expires为数字，则表示数据的能存活的毫秒数
                if ('number' == typeof storageInfo.expires) {
                    expires = new Date();
                    expires.setTime(expires.getTime() + storageInfo.expires);
                }
                input.expires = expires.toUTCString();
            }
            //存储之前，先移除storageInfo对象中的expires值
            storageInfo.expires = null;
            //存储数据
            input.setAttribute(storageInfo.key, storageInfo.value);
            //存储到本地文件，文件名为：storageInfo.key[1].xml
            input.save(storageInfo.key);
        } catch(e) {}
    };

    /**
     * 将数据通过UserData的方式保存到本地，文件名为：文件名为：config.key[1].xml
     * @param {String} key 待存储数据的key，和config参数中的key是一样的
     * @param {Object} config 待存储数据相关配置
     * @cofnig {String} key 待存储数据的key
     * @config {String} value 待存储数据的内容
     * @config {String|Object} [expires] 数据的过期时间，可以是数字，单位是毫秒；也可以是日期对象，表示过期时间
     * @private
     */
    var _setItem = function (key, config) {
        //保存有效内容
        __setItem(key, config);

        //下面的代码用来记录当前保存的key，便于以后clearAll
        result = _getItem({
            key: _clearAllKey
        });
        if (result) {
            result = {
                value: result
            };
        } else {
            result = {
                key: _clearAllKey,
                value: ""
            };
        }
        if (! (new RegExp("(^|\|)" + key + "(\||$)")).test(result.value)) {
            result.value += "|" + key;
            //保存键
            __setItem(_clearAllKey, result);
        }
    };

    /**
     * 提取本地存储的数据
     * @param {String} config 待获取的存储数据相关配置
     * @cofnig {String} key 待获取的数据的key
     * @return {String} 本地存储的数据，获取不到时返回null
     * @example 
     * storage.get({
     *    key : "username"
     * });
     * @private
     */
    var _getItem = function (config) {
        try {
            var input = _getInstance();
            //载入本地文件，文件名为：config.key[1].xml
            input.load(config.key);
            //取得数据
            return input.getAttribute(config.key) || null;
        } catch(e) {
            return null;
        }
    };

    /**
     * 移除某项存储数据
     * @param {Object} config 配置参数
     * @cofnig {String} key 待存储数据的key
     * @private
     */
    var _removeItem = function (config) {
        try {
            var input = _getInstance();
            //载入存储区块
            input.load(config.key);
            //移除配置项
            input.removeAttribute(config.key);
            //强制使其过期
            var expires = new Date();
            expires.setTime(expires.getTime() - 1);
            input.expires = expires.toUTCString();
            input.save(config.key);
        } catch(e) {}
    };

    /**
     * 移除所有的本地数据
     * @return 无
     * @private 
     */
    var _clearAll = function () {
        result = _getItem({
            key: _clearAllKey
        });
        if (result) {
            result = {
                value: result
            };
            var allKeys = (result.value || "").split("|");
            var count = allKeys.length;
            for (var i = 0; i < count; i++) {
                _removeItem({
                    key: allKeys[i]
                });
            }
        }
    };

    return {
        getItem: _getItem,
        setItem: _setItem,
        removeItem: _removeItem,
        clearAll: _clearAll
    };
})();

/**
 * 判断当前浏览器是否支持本地存储：window.localStorage
 */
storage._isSupportLocalStorage = function () {
    return ('localStorage' in window) && (window['localStorage'] !== null);
};

storage.isSupportStorage = function () {
    return ('localStorage' in window) && (window['localStorage'] !== null) || document.all;
};

/**
 * 将数据进行本地存储（只能存储字符串信息）
 * storage.set({
 *    key : "username",
 *    value : "kenny",
 *    expires : 3600 * 1000
 * });
 */
storage.set = function (config) {

    if (!storage._isValidKey(config.key)) {
        return;
    }

    var storageInfo = config || {};
    //支持本地存储的浏览器：IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.setItem(storageInfo.key, storageInfo.value);
        if (storageInfo.expires) {
            // 仅允许传毫秒
            var _date = new Date();
            
            window.localStorage.setItem(storageInfo.key + ".expires", _date.getTime() + storageInfo.expires);
        }
    } else { //IE7及以下版本，采用UserData方式
        storage.userData.setItem(storageInfo.key, storageInfo);
    }
};

/**
 * 提取本地存储的数据
 * storage.get({
 *    key : "username"
 * });
 */
storage.get = function (config) {

    var result = null;
    
    if (!storage._isValidKey(config.key)) {
        return result;
    }
    //IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        var _value = window.localStorage.getItem(config.key);
        //过期时间判断，如果过期了，则移除该项
        if (_value) {
            var expire = window.localStorage.getItem(config.key + ".expires");

            if (expire) {
                var _expire = new Date(parseInt(expire, 10));
            } else {
                var _expire = null;
            }

            result = {
                'value': _value,
                'expires': _expire
            };
            if (result && result.expires && result.expires < new Date()) {
                result = null;
                window.localStorage.removeItem(config.key);
            }
        }
    } else {
        //这里不用单独判断其expires，因为UserData本身具有这个判断
        result = storage.userData.getItem(config);
        if (result) {
            result = {
                value: result
            };
        }
    }

    return result ? result.value : null;
};

/**
 * 移除某一项本地存储的数据
 * storage.remove({
 *    key : "username"
 * });
 */
storage.remove = function (config) {
    //IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.removeItem(config.key);
        window.localStorage.removeItem(config.key + ".expires");
    } else {
        storage.userData.removeItem(config);
    }
};

/**
 * 清除所有本地存储的数据
 * storage.clearALl();
 */
storage.clearAll = function () {
    //支持本地存储的浏览器：IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.clear();
    } else {
        storage.userData.clearAll();
    }
};

module.exports = storage;

});

/*!www/common/module/address/address.js*/
;define('www/common/module/address/address', function(require, exports, module) {

var storage = require('www/common/module/storage.js');
var SelectBox = require('www/common/module/select/select.js');

/**
 * [地址选择]
 * @param  {[type]} opt [description]
 * @return {[type]}     [description]
 * by weiqi
 */
function _Address(opt){
	var select_province,
		select_city,
		select_area,
		address_data,
		onProvinceSelectChange,
		onCitySelectChange,
		onAreaSelectChange,
		_level;

	/**
	 * [地址选择框]
	 * @param {[type]} _option [description]
	 */
	function Address(_option){
		var option = {
			wrap: _option.wrap || '',
	        width: _option.width || 80,
	        height: _option.height || 30,
	        font_size: _option.font_size || 14,
	        onProvinceSelectChange: _option.onProvinceSelectChange || '',
	        onCitySelectChange: _option.onCitySelectChange || '',
	        onAreaSelectChange: _option.onAreaSelectChange || '',
	        options_max_height: _option.options_max_height || 200,
	        level: _option.level || 2,
	        text_align: _option.text_align || 'left'
		}
		var dom = {
			wrap : $(option.wrap) || null,
			province : $('<div class="address_province"></div>'),
			city : $('<div class="address_city"></div>'),
			area : $('<div class="address_area"></div>')
		};

		this.select = {};
		this.province = {};
		this.city = {};
		this.area = {};

		this.dom = dom;

		this.level = _level = option.level;

		this.select.style = {
			width: option.width,
			height: option.height,
			font_size: option.font_size,
			options_max_height: option.options_max_height,
			text_align: option.text_align
		}

		onProvinceSelectChange = option.onProvinceSelectChange;
		onCitySelectChange = option.onCitySelectChange;
		onAreaSelectChange = option.onAreaSelectChange;

		this._render();

	}

	Address.prototype._render = function(){

		this.dom.wrap
			.append(this.dom.province)
		this.level >= 2 && this.dom.wrap
			.append(this.dom.city)
		this.level >= 3 && this.dom.wrap
			.append(this.dom.area)

		this.province.select = select_province = new SelectBox({
			container: this.dom.wrap.find('.address_province'),
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			text_align: this.select.style.text_align,
			options_max_height: this.select.style.options_max_height,
			placeholder: '省/直辖市',
			onSelectChange: provinceChange
		});
		if(this.level === 2){
			this.city.select = select_city = new SelectBox({
				container: this.dom.wrap.find('.address_city'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择省',
				onSelectChange: onCitySelectChange
			});
		} else if(this.level > 2){
			this.city.select = select_city = new SelectBox({
				container: this.dom.wrap.find('.address_city'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择省',
				onSelectChange: cityChange
			});
		}
		if(this.level >= 3){
			this.area.select = select_area = new SelectBox({
				container: this.dom.wrap.find('.address_area'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择市',
				onSelectChange: areaChange
			});
		}

		this._getCity();
	}

	Address.prototype.setData = function(_address_opt){
		var opt = _address_opt || {};
		var pid = opt.pid || this.dom.wrap.attr('data-pid'),
			cid = opt.pid || this.dom.wrap.attr('data-cid'),
			aid = opt.pid || this.dom.wrap.attr('data-aid');

		if(!pid || !cid || !aid) return;

		this.province.select.setSelectedVal(pid);
		setProvince(pid, cid, aid, this);
	}

	function setProvince(pid, cid, aid, that){
		var province_name = '';
		for(var p in that.data[0]){
			if(that.data[0][p].id === pid){
				province_name = that.data[0][p].cityName;
			}
		}
		that.province.select.setSelectedText(province_name);

		setCity(pid, cid, aid, that)
	}

	function setCity(pid, cid, aid, that){
		that.city.select.setSelectedText('请选择市');
		that.city.select.setSelectedVal('');

		var city_name = '',
			cities = [];
		
		for(var i in address_data[0]){
			var codeid,
				city_data = {};
			if(address_data[0][i].id == pid){
				codeid = address_data[0][i].codeid;				
			}
		}
		
		for(var k in address_data[codeid]){
			if(cid == address_data[codeid][k].id){

				city_name = address_data[codeid][k].cityName;
			}
			city_data = {
				text: address_data[codeid][k].cityName,
				value: address_data[codeid][k].id
			}
			cities.push(city_data);
		}

		select_city.setOptions(cities);

		that.city.select.setSelectedText(city_name);
		that.city.select.setSelectedVal(cid);
		if(_level <= 2) return;


		that.area.select.setSelectedText('请先选择市');
		that.area.select.setSelectedVal('');
		that.area.select.setOptions([]);

		setArea(pid, cid, aid, that);
	}

	function setArea(pid, cid, aid, that){
		var city_name = that.city.select.text();
		that.city.select.setSelectedText('正在加载中...');
		XDD.Request({
			url : "/city/getSubLocation",
			data : {
				id : cid
			},
			success : function(res){
				if(res.error_code == 0){
					var area = [];
					var area_name = '';
					for(var i in res.data){
						var area_data = {
							text: res.data[i].cityName,
							value: res.data[i].id
						}
						if(aid == res.data[i].id){
							area_name = res.data[i].cityName;
						}
						area.push(area_data);
					}
					that.area.select.setOptions(area);

					that.area.select.setSelectedText(area_name);
				}else{
					_alert(res.error_msg);
					that.area.select.setSelectedText('网络错误，请刷新页面！');
				}
				that.area.select.setSelectedVal(aid);
				
				that.city.select.setSelectedText(city_name);
			}
		});
	}

	Address.prototype._getCity = function(){

		var me = this;
		var _cities = storage.get({'key':'address_cities'});
		if(_cities){
			me._initData($.evalJSON(_cities));
			me.setData();
		}else{
			XDD.Request({
				url : "/city/getProvinceCitys",
				success : function(res){
					if(res.error_code == 0){
						_cities = $.toJSON(res.data);
						storage.set({'key':'address_cities','value':_cities,'expires':72*3600*1000});
						me._initData(res.data);

						me.setData();
					}else{
						_alert('res.error_msg')
					}
				}
			})
		}
	}

	/**
	 * [获取地址]
	 * @return {[type]} [description]
	 */
	Address.prototype.val = function(){
		var province = {},
			city = {},
			area = {};

		this.province.select ? province.id = this.province.select.val() : province.id = '';
		this.province.select ? province.name = this.province.select.text() : province.name = '';

		this.city.select ? city.id = this.city.select.val() : city.id = '';
		this.city.select ? city.name = this.city.select.text() : city.name = '';

		this.area.select ? area.id = this.area.select.val() : area.id = '';
		this.area.select ? area.name = this.area.select.text() : area.name = '';
		return {
			province: province,
			city: city,
			area: area
		}
	}

	/**
	 * [获取省]
	 * @return {[type]} [description]
	 */
	Address.prototype.getProvince = function(){
		var province = {};
		this.province.select ? province.id = this.province.select.val() : province.id = '';
		this.province.select ? province.name = this.province.select.text() : province.name = '';

		return province
	}

	/**
	 * [获取市]
	 * @return {[type]} [description]
	 */
	Address.prototype.getCity = function(){
		var city = {};
		this.city.select ? city.id = this.city.select.val() : city.id = '';
		this.city.select ? city.name = this.city.select.text() : city.name = '';

		return city
	}

	/**
	 * [获取地区]
	 * @return {[type]} [description]
	 */
	Address.prototype.getArea = function(){
		var area = {};
		this.area.select ? area.id = this.area.select.val() : area.id = '';
		this.area.select ? area.name = this.area.select.text() : area.name = '';

		return area
	}

	Address.prototype._initData = function(data){

		var cities = {};
		for(var i in data){
			var item = data[i],
				pid = item.parentid;
			if(!cities.hasOwnProperty(pid))
				cities[pid] = [];
			cities[pid].push(item);
		}
		var cities_option = [];

		for(var j in cities[0]){
			var city_data = {
				text: cities[0][j].cityName,
				value: cities[0][j].id
			}
			cities_option.push(city_data);
		}

		this.province.select.setOptions(cities_option);
		this.data = address_data = cities;
	}

	function provinceChange(id, province){
		select_city.setSelectedText('请选择市');
		select_city.setSelectedVal('');

		var cities = [];
		
		for(var i in address_data[0]){
			var codeid,
				city_data = {};
			if(address_data[0][i].id == id){
				codeid = address_data[0][i].codeid;				
			}
		}

		for(var k in address_data[codeid]){
			city_data = {
				text: address_data[codeid][k].cityName,
				value: address_data[codeid][k].id
			}
			cities.push(city_data);
		}
		

		select_city.setOptions(cities);
		if(_level <= 2) return;


		select_area.setSelectedText('请先选择市');
		select_area.setSelectedVal('');
		select_area.setOptions(new Array());

		if(typeof onProvinceSelectChange === 'function'){
			onProvinceSelectChange(id, province, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	function cityChange(id, city){
		select_area.setSelectedText('请选择区');
		select_area.setSelectedVal('');

		select_city.setSelectedText('正在加载中...');
		XDD.Request({
			url : "/city/getSubLocation",
			data : {
				id : id
			},
			success : function(res){
				if(res.error_code == 0){
					var area = [];
					for(var i in res.data){
						var area_data = {
							text: res.data[i].cityName,
							value: res.data[i].id
						}
						area.push(area_data);
					}
					select_area.setOptions(area);
				}else{
					_alert(res.error_msg);
				}
				select_city.setSelectedText(city);
			}
		});
		if(typeof onCitySelectChange === 'function'){
			onCitySelectChange(id, city, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	function areaChange(id, area){
		if(typeof onAreaSelectChange === 'function'){
			onAreaSelectChange(id, area, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	return new Address(opt);
}

module.exports = _Address;

});

/*!www/order/module/commonBox.js*/
;define('www/order/module/commonBox', function(require, exports, module) {

var util = require("www/common/module/util.js");
var SelectBox = require("www/common/module/select/select.js");
var Address = require("www/common/module/address/address.js");

// 常量
var CONST = {
	is_require: true,
	no_require: false,
	input_type: 'INPUT',
	weight_type: 'WEIGHT',
	select_type: 'SELECT',
	address_select_type: 'ADDRESS'
};
function CommonBox(_option){
	var SCOPE = _option.scope || $('body'),
		config_array;
	// config_array = [{
	// 		keyName: String,
	// 		selector: String,
	// 		require: Boolean,
	// 		type: String,
	// 		selectOptions: array
	// }]	
	typeof _option.config_array === 'object' ? config_array = _option.config_array : config_array = [];

	var DOM = {};

	var length = config_array.length;

	for (var i = 0; i < length; i++) {
		var config_data = config_array[i];

		var keyName = config_data.keyName || '',
			selector = config_data.selector || '',
			require = config_data.require,
			type = config_data.type || '',
			selectPlaceholder = config_data.selectPlaceholder || ''
			selectOptions = config_data.selectOptions || [];
			height = config_data.height || 30;


		DOM[keyName] = {};
		(function(keyName, selector, require, type, height){
			if(type === CONST.input_type || type === CONST.weight_type){
				DOM[keyName].$ = $(SCOPE).find(selector);
				DOM[keyName].require = require;
				DOM[keyName].type = type;
			}
			if(type === CONST.select_type){
				DOM[keyName].$ = new SelectBox({
					container: $(SCOPE).find(selector),
					width: 500,
					height: height,
					options: selectOptions,
					placeholder: selectPlaceholder,
					onSelectChange: function(val, txt, selectBox){
						selectBox.select.select_box.removeClass('error');
						var messageBox = getMessageBox(selectBox.select.select_box);
						messageBox.error.hide();

						if(!util.isNull(val)){
							messageBox.right.show();
						} else {
							messageBox.right.hide();
						}
					}
				});
				DOM[keyName].require = require;
				DOM[keyName].type = type;
			}
			if(type === CONST.address_select_type){

				DOM[keyName].$ = new Address({
					wrap: selector,
					width: 145,
					level: 3,
					height: height,
					text_align: 'center',
					onProvinceSelectChange: function(id, province, select){
						if(id){
							select.province.select.select_box.removeClass('error');
						}
						
					},
					onCitySelectChange: function(id, city, select){
						if(id){
							select.city.select.select_box.removeClass('error');
						}
					},
					onAreaSelectChange: function(id, area, select){
						if(id){
							select.area.select.select_box.removeClass('error');
							var messageBox = getMessageBox(select.area.select.select_box);
							messageBox.error.hide();
							messageBox.right.show();
						}

					}
				});
				DOM[keyName].require = require;
				DOM[keyName].type = type;
			}
			
		})(keyName, selector, require, type, height);
	};

	this.$SCOPE = SCOPE;
	this.DOM = DOM;

	bindEvent(DOM, this.$SCOPE);
}

/**
 * [检查必填项是否是空]
 * @type {Object}
 */
CommonBox.prototype = {
	check: function(){
		var that = this,
			data = {};

		for(var k in that.DOM){
			var keyName = k;

			data[keyName] = {
				val: that.DOM[keyName].$.val()
			};

			switch(that.DOM[keyName].type){
				case CONST.input_type:
					var messageBox = getMessageBox(that.DOM[keyName].$);

					if(messageBox.error.length === 0 && messageBox.message.length === 0 && messageBox.right.length === 0){
						data[keyName].checkPass = true;
						break;
					}
					if(that.DOM[keyName].require && util.isNull(data[keyName].val)){
						data[keyName].checkPass = false;

						that.DOM[keyName].$.addClass('error');
						shake(that.DOM[keyName].$);

						that.DOM[keyName].$.focus();

						messageBox.error.show();
						messageBox.right.hide();
					} else if(!that.DOM[keyName].require){
						data[keyName].checkPass = true;

						messageBox.error.hide();
						messageBox.right.hide();
					} else {
						that.DOM[keyName].$.removeClass('error');
						data[keyName].checkPass = true;

						messageBox.error.hide();
						messageBox.right.show();
					}
					break;
				case CONST.select_type: 
					var messageBox = getMessageBox(that.DOM[keyName].$.select.select_box);

					if(that.DOM[keyName].require && util.isNull(data[keyName].val)){
						data[keyName].checkPass = false;

						that.DOM[keyName].$.select.select_box.addClass('error');
						shake(that.DOM[keyName].$.select.select_box);
						
						
						messageBox.error.show();
						messageBox.right.hide();
					} else {
						that.DOM[keyName].$.select.select_box.removeClass('error');

						data[keyName].checkPass = true;

						messageBox.error.hide();
						messageBox.right.show();
					}
					break;
				case CONST.weight_type:
					var messageBox = getMessageBox(that.DOM[keyName].$);
					var box_num = 0;
					var weight_objs = that.DOM[keyName].$.parent('li').parent('ul').find('input');
					weight_objs.each(function(index, el) {
						var w_obj = $(this);
						box_num = box_num + w_obj.val();
					});
					if(util.isNumber(box_num) && box_num <= 0){
						that.DOM[keyName].$.addClass('error');
						data[keyName].checkPass = false;
						shake(that.DOM[keyName].$);
						
						that.DOM[keyName].$.focus();

						messageBox.error.show();
						messageBox.right.hide();
					} else {
						that.DOM[keyName].$.removeClass('error');

						data[keyName].checkPass = true;

						messageBox.error.hide();
						messageBox.right.show();
					}
					break;
				case CONST.address_select_type:
					var messageBox = getMessageBox(that.DOM[keyName].$.dom.wrap);

					if(messageBox.error.length === 0 || messageBox.message.length === 0 || messageBox.right.length === 0){
						data[keyName].checkPass = true;
						break;
					}

					var province = that.DOM[keyName].$.getProvince().id,
						city = that.DOM[keyName].$.getCity().id,
						area = that.DOM[keyName].$.getArea().id;

					if(!province){
						that.DOM[keyName].$.province.select.select.select_box.addClass('error');
						that.DOM[keyName].$.city.select.select.select_box.addClass('error');
						that.DOM[keyName].$.area.select.select.select_box.addClass('error');

						shake(that.DOM[keyName].$.dom.wrap);

						data[keyName].checkPass = false;

						messageBox.error.show();
						messageBox.right.hide();
					} else if(province && !city){
						that.DOM[keyName].$.province.select.select.select_box.removeClass('error');

						that.DOM[keyName].$.city.select.select.select_box.addClass('error');
						that.DOM[keyName].$.area.select.select.select_box.addClass('error');

						shake(that.DOM[keyName].$.dom.wrap);

						data[keyName].checkPass = false;

						messageBox.error.show();
						messageBox.right.hide();
					} else if(province && city && !area){
						that.DOM[keyName].$.province.select.select.select_box.removeClass('error');
						that.DOM[keyName].$.city.select.select.select_box.removeClass('error');

						shake(that.DOM[keyName].$.dom.wrap);

						data[keyName].checkPass = false;

						that.DOM[keyName].$.area.select.select.select_box.addClass('error');

						messageBox.error.show();
						messageBox.right.hide();
					} else {
						that.DOM[keyName].$.province.select.select.select_box.removeClass('error');
						that.DOM[keyName].$.city.select.select.select_box.removeClass('error');
						that.DOM[keyName].$.area.select.select.select_box.removeClass('error');

						data[keyName].checkPass = true;

						messageBox.error.hide();
						messageBox.right.show();
					}
					break;
			}
		}

		return data;
	}
};

/**
 * [给输入框绑定事件]
 * @param  {[type]} DOM [description]
 * @return {[type]}     [description]
 */
function bindEvent(DOM, $scope){
	for(var k in DOM){
		var keyName = k,
			require = DOM[k].require,
			obj = DOM[k].$,
			type = DOM[k].type;

		
		(function(keyName, obj, require, type){
			if(!require) return;
			// 普通input
			if(type === CONST.input_type){
				$scope.on('keyup', '#' + obj.attr('id'), function(event) {
					var obj_val = $(this).val();

					var messageBox = getMessageBox($(this));

					$(this).removeClass('error');
					if(util.isNull(obj_val)){
						messageBox.right.hide();
					}
					if(!util.isNull(obj_val)){
						messageBox.error.hide();
						messageBox.right.show();
					}
				});

				$scope.on('blur', '#' + obj.attr('id'), function(event) {
					var obj_val = $(this).val();

					var messageBox = getMessageBox($(this));
					
					if(util.isNull(obj_val)){
						$(this).addClass('error');

						messageBox.error.show();
						messageBox.right.hide();
					} else {
						$(this).removeClass('error');

						messageBox.error.hide();
						messageBox.right.show();
					}
				});
			};
			// 重量input
			if(type === CONST.weight_type){
				var $decrease = obj.siblings('.decrease');
				var $plus = obj.siblings('.plus');

				var weight_objs = obj.parent('li').parent('ul').find('input');

				$decrease.on('click', function(event) {
					var hideError = true;
					var messageBox = getMessageBox(obj);
					var box_num = 0;

					obj.removeClass('error');
					try {
						var _value = new Number(obj.val());
					} catch (e){
						var _value = 0;
						obj.val(0);
						return
					}
					
					if(util.isNumber(_value) && _value > 0){
						obj.val(_value - 1);
						box_num = _value - 1;
					} else {
						obj.val(0);
					}

					box_num = box_num + _value;
					weight_objs.each(function(index, el) {
						var weight_obj = $(this);

						box_num = box_num + weight_obj.val();
					});

					if(box_num > 0){
						weight_objs.removeClass('error');
						messageBox.error.hide();
					}
				});

				$plus.on('click', function(event) {
					var hideError = true;
					var messageBox = getMessageBox(obj);
					var box_num = 0;

					obj.removeClass('error');
					try {
						var _value = new Number(obj.val());
					} catch (e){
						var _value = 0;
						obj.val(0);
						return
					}
					if(util.isNumber(_value) && _value >= 0){
						obj.val(_value + 1);
						box_num = _value + 1;
					} else {
						obj.val(0);
					}

					weight_objs.each(function(index, el) {
						var weight_obj = $(this);
						
						box_num = box_num + weight_obj.val();
					});
					if(box_num > 0){
						weight_objs.removeClass('error');
						messageBox.error.hide();
					}
				});

				obj.on('keydown', function(e) {
					var keyCode = e.keyCode;

					if(keyCode > 47 && keyCode < 58) return;
					if(keyCode === 8) return

					util.preventDefault(e);
				});

				obj.on('keyup', function(e) {
					var keyCode = e.keyCode;
					var box_num = 0;

					if(!(keyCode > 47 && keyCode < 58)) return;
					if(!keyCode === 8) return

					try {
						var _value = new Number(obj.val());
					} catch (e){
						obj.val(0);
						return
					}
					box_num = box_num + _value;
					var messageBox = getMessageBox(obj);

					

					if(!util.isNumber(_value) || _value <= 0) return;
					obj.removeClass('error');
					weight_objs.each(function(index, el) {
						var weight_obj = $(this);

						box_num = box_num + weight_obj.val();
					});

					if(box_num > 0){
						weight_objs.removeClass('error');
						messageBox.error.hide();
					}
				});
			}
			
		})(keyName, obj, require, type);
	}
}

function shake(obj){ 
	obj.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
	   .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40);
}

/**
 * [获取消息信息]
 * @param  {[type]} obj [description]
 * @return {[type]}     [description]
 */
function getMessageBox(obj){
	var DOM = {
		message: obj.parents('.item-content').siblings('.item-message')
	};
	DOM.error = DOM.message.find('.error-message');
	DOM.right = DOM.message.find('.right-message');

	return DOM;
}
module.exports = CommonBox;

});

/*!www/common/module/selectTimeBox/selectTimeBox.js*/
;define('www/common/module/selectTimeBox/selectTimeBox', function(require, exports, module) {

var util = require("www/common/module/util.js");
var SelectBox = require('www/common/module/select/select.js');

/**
 * [时间选择]
 * @param  {[type]} opt [description]
 * @return {[type]}     [description]
 * by weiqi
 */
function _SelectTimeBox(opt){
	var CONST = {
		st_year: 2000,
		ed_year: 2020,
		min: [00, 30]
	}
	var select_year,
		select_month,
		select_day,
		select_hour,
		select_min,
		select_sec,
		_level;
	function SelectTimeBox(_option){
		var option = {
			wrap: _option.wrap || '',
	        width: _option.width || 68,
	        height: _option.height || 30,
	        font_size: _option.font_size || 14,
	        level: _option.level || 3,
	        st_year: _option.st_year || CONST.st_year,
	        ed_year: _option.ed_year || CONST.ed_year
		}
		var dom = {
			wrap : $(option.wrap) || null,
			year : $('<div class="select_time_year"></div>'),
			month : $('<div class="select_time_month"></div>'),
			day : $('<div class="select_time_day"></div>'),
			hour : $('<div class="select_time_hour"></div>'),
			min : $('<div class="select_time_min"></div>'),
			sec : $('<div class="select_time_sec"></div>')
		};

		this.st_year = option.st_year;
		this.ed_year = option.ed_year;

		this.select = {};
		this.year = {};
		this.month = {};
		this.day = {};
		this.hour = {};
		this.min = {};
		this.sec = {};

		this.dom = dom;

		this.select.style = {
			width: option.width,
			height: option.height,
			font_size: option.font_size,
			options_max_height: option.options_max_height
		}

		this.level = _level = option.level;

		this._render();
	}


	SelectTimeBox.prototype._render = function(){

		var nowData = new Date();
		var nowMonth = nowData.getMonth() + 1;
		if(nowMonth < 10){
			nowMonth = '0' + nowMonth;
		}
		var lineHeight_str = 'style="line-height:' + this.select.style.height + 'px;"';
		this.dom.wrap
			.append(this.dom.year).append('<div class="select_year_text" ' + lineHeight_str + '>年</div>')
			.append(this.dom.month).append('<div class="select_month_text" ' + lineHeight_str + '>月</div>')
			.append(this.dom.day).append('<div class="select_day_text" ' + lineHeight_str + '>日</div>')
		this.level > 3 && this.dom.wrap
			.append(this.dom.hour).append('<div class="select_hours_text" ' + lineHeight_str + '>时</div>')
		this.level > 4 && this.dom.wrap
			.append(this.dom.min).append('<div class="select_min_text" ' + lineHeight_str + '>分</div>')
		this.level > 5 && this.dom.wrap
			.append(this.dom.sec).append('<div class="select_sec_text" ' + lineHeight_str + '>秒</div>')

		// 年
		this.year.select = select_year = new SelectBox({
			container: this.dom.year,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowData.getFullYear(),
			defaultValue: nowData.getFullYear(),
			onSelectChange: initMonth,
			text_align: 'center',
			options: initData(this.st_year, this.ed_year)
		});
		// 月
		this.month.select = select_month = new SelectBox({
			container: this.dom.month,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowMonth,
			defaultValue: nowMonth,
			onSelectChange: initMonth,
			text_align: 'center',
			options: initData(1, 12)
		});
		// 日
		this.day.select = select_day = new SelectBox({
			container: this.dom.day,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowData.getDate(),
			defaultValue: nowData.getDate(),
			onSelectChange: '',
			text_align: 'center'
		});
		if(this.level > 3){
			this.hour.select = select_hour = new SelectBox({
				container: this.dom.hour,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: nowData.getHours(),
				defaultValue: nowData.getHours(),
				onSelectChange: '',
				text_align: 'center',
				options: initHours()
			});
		}
		if(this.level > 4){
			this.min.select = select_min = new SelectBox({
				container: this.dom.min,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: '00',
				defaultValue: '00',
				onSelectChange: '',
				text_align: 'center',
				options: initMin()
			});
		}
		if(this.level === 6){
			this.sec.select = select_sec = new SelectBox({
				container: this.dom.sec,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: '00',
				defaultValue: '00',
				onSelectChange: '',
				text_align: 'center',
				options: initData(1, 60)
			});
		}
		// this._getCity();
		initMonth('notOnChange');
		this.setTime();
	}

	SelectTimeBox.prototype.setTime = function(_time){
		var time = _time || this.dom.wrap.attr('data-time');
		if(!time) return;

		try{
			var gDate = new Date(time);
		} catch(e){
			console.log(e);
			var gDate = new Date();
		}
		var gYear = gDate.getFullYear(),
			gMonth = gDate.getMonth() + 1,
			gDay = gDate.getDate(),
			gHours = gDate.getHours(),
			gMinutes = gDate.getMinutes(),
			gSeconds = gDate.getSeconds();

		this.year.select.setSelectedVal(gYear);
		this.year.select.setSelectedText(gYear);

		this.month.select.setSelectedVal(gMonth);
		this.month.select.setSelectedText(gMonth);

		initMonth('notOnChange');

		this.day.select.setSelectedVal(gDay);
		this.day.select.setSelectedText(gDay);

		if(!this.hour.select) return;
		this.hour.select.setSelectedVal(gHours);
		this.hour.select.setSelectedText(gHours);

		if(!this.min.select) return;
		this.min.select.setSelectedVal(gMinutes);
		this.min.select.setSelectedText(gMinutes);

		if(!this.sec.select) return;
		this.sec.select.setSelectedVal(gSeconds);
		this.sec.select.setSelectedText(gSeconds);

	}

	SelectTimeBox.prototype.val = function(){
		var year = '',
			month = '',
			day = '',
			min = '',
			hour = '',
			sec = '';

		var time = '';

		this.year.select ? year = this.year.select.val() : year = '';
		this.month.select ? month = this.month.select.val() : month = '';
		this.day.select ? day = this.day.select.val() : day = '';

		time = year + '-' + month + '-' + day;

		if(_level > 3){
			this.hour.select ? hour = this.hour.select.val() : hour = '';
			time += ' ' + hour;
		}
		if(_level > 4){
			this.min.select ? min = this.min.select.val() : min = '';
			time += ':' + min;
		}
		if(_level > 5){
			this.sec.select ? sec = this.sec.select.val() : sec = '';
			time += ':' + sec;
		}
		return time;
	}

	// 加载数据
	function initData(st, ed){
		var array = [];

		var i = st;
		while(i){
			var data = {};
			var num = i;

			if(i < 10){
				var l = '0',
					num_str = num.toString();

				data = {
					value: l + num_str,
					text: l + num_str
				}
			} else {
				data = {
					value: i,
					text: i
				}
			}
			array.push(data);
			if(i === ed) break;
			i++;
		}
		return array;
	}

	// 加载时
	function initHours(){
		var hours = [];
		var i = 1;
		while(i){
			var data = {};
			var num = i;

			if(i < 10){
				var l = '0',
					num_str = num.toString();

				data = {
					value: l + num_str,
					text: l + num_str
				}
			} else {
				data = {
					value: i,
					text: i
				}
			}
			hours.push(data);
			if(i === 23) break;
			i++;
		}
		return hours;
	}

	// 加载分
	function initMin(){
		var length = CONST.min.length;
		var min = [];
		for (var i = 0; i < length; i++) {
			
			var data = {
				value: CONST.min[i],
				text: CONST.min[i]
			}
			min.push(data);
		};
		return min;
	}

	// 加载月
	function initMonth(type){
		var year_str = select_year.val(),
			month_str = select_month.val();

		if(!util.isNumber(year_str)) return;
		if(!util.isNumber(month_str)) return;

		if(typeof type !== 'string' || type !== 'notOnChange'){
			select_day.setSelectedVal('01');
			select_day.setSelectedText('01');
		}

		var year = parseInt(year_str),
			month = parseInt(month_str);

		var i = 1;
		var days = [];
		if(month === 1 || month === 3 || month === 5 || month === 7 || month === 8 || month === 10 || month === 12){
			while(i){
				days.push(setMonthValue(i));
				if(i === 31) break;
				i++;
			}
		} else if(month === 4 || month === 6 || month === 9 || month === 11){
			while(i){
				days.push(setMonthValue(i));
				if(i === 30) break;
				i++;
			}
		} else if(month === 2){
			if((year%4 === 0 && year%100 !== 0) || (year%100 === 0 && year%400 === 0)){
				while(i){
					days.push(setMonthValue(i));
					if(i === 29) break;
					i++;
				}
			}else{
				while(i){
					days.push(setMonthValue(i));
					if(i === 28) break;
					i++;
				}
			}
		}
		select_day.setOptions(days);
	}

	function setMonthValue(i){
		var num = i;
		var data = {};
		if(i < 10){
			var l = '0',
				num_str = num.toString();

			return {
				value: l + num_str,
				text: l + num_str
			}
		}
		return {
			value: i,
			text: i
		}
	}
	return new SelectTimeBox(opt);
}

module.exports = _SelectTimeBox;

});

/*!www/common/module/addSelectTimeBox/addSelectTimeBox.js*/
;define('www/common/module/addSelectTimeBox/addSelectTimeBox', function(require, exports, module) {

/**
 * [添加时间控件]
 * 传入container（jq对象）、addName添加按钮（string）
 * @param {[type]} _option [description]
 * by weiqi
 */
var SelectTimeBox = require("www/common/module/selectTimeBox/selectTimeBox.js");
function AddSelectTimeBox(_option){
    function _AddSelectTimeBox(_option){
        var option = {
            container: _option.container || $('body'),
            addName: _option.addName || '.add-date',
            height:  _option.height || 30
        };
        var $scope = option.container.$scope || option.container;
        var addName = option.addName;

        var selectTimeBox = this.selectTimeBox = [];
        var flag = 1;
        var del_Btn = $('<a class="delTimeBtn" href="javaScript:" title="删除装箱时间"><i class="icon-del-timeBox" data-flag=""></i></a>');
        
        var $items = $scope.find('.package_date_selectBox');
        if($items.length !== 0){
            flag = $items.length;
            $items.each(function(index, el) {
                var f = index + 1;
                selectTimeBox.push({
                    container: '.package_date_selectBox.data_' + f,
                    flag: f,
                    box: new SelectTimeBox({
                        wrap: $scope.find('.package_date_selectBox.data_' + f),
                        level: 5,
                        height: option.height
                    }),
                    timeData: {
                        id: $scope.find('.package_date_selectBox.data_' + f).attr('data-timeId'),
                        can_change: $scope.find('.package_date_selectBox.data_' + f).attr('data-canChange') || 1
                    }
                });
            });
        } else {
            selectTimeBox.push({
                container: '.package_date_selectBox.data_1',
                flag: flag,
                box: new SelectTimeBox({
                    wrap: $scope.find('.package_date_selectBox.data_1'),
                    level: 5,
                    height: option.height
                })
            });
        }

        

        // 添加按钮
        $scope.on('click', addName, function(event) {
            var select_time_list = $scope.find('.package_date_selectBox');
            if(select_time_list.length > 9) return;

            flag++;
            var addBtn = $(this);
            var timeSelectBox = $('<div class="package_date_selectBox data_' + flag + ' clearfix" data-flag="' + flag + '"></div>');

            addBtn.before(timeSelectBox);
            selectTimeBox.push({
                container: timeSelectBox,
                flag: flag,
                box: new SelectTimeBox({
                    wrap: $scope.find('.package_date_selectBox.data_' + flag),
                    level: 5,
                    height: option.height
                }),
                timeData: {
                    id: '',
                    can_change: 1
                }
            });
        });
        // 鼠标悬浮删除按钮显示
        $scope.on('mouseover', '.package_date_selectBox', function(event) {
            var $selectBox = $(this);
            var getFlag = $selectBox.attr('data-flag');
            if(getFlag == 1) return;
            $selectBox.append(del_Btn);
            del_Btn.show();
            del_Btn.attr('data-flag', getFlag);
        });

        // 鼠标离开删除按钮消失
        $scope.on('mouseleave', '.package_date_selectBox', function(event) {
            var $selectBox = $(this);
            var getFlag = $selectBox.attr('data-flag');
            if(getFlag == 1) return;
            $selectBox.append(del_Btn);
            del_Btn.hide();
            del_Btn.removeAttr('data-flag');
        });

        // 点击删除按钮
        $scope.on('click', '.delTimeBtn', function(event) {
            var del_Btn_obj = $(this);
            var getFlag = del_Btn_obj.attr('data-flag');
            var length = selectTimeBox.length;
            for (var i = 0; i < length; i++) {
                if(selectTimeBox[i].flag == getFlag){
                    selectTimeBox.splice(i, 1);
                    $scope.find('.package_date_selectBox.data_' + getFlag).remove();
                    return
                }
            };
            
        });
    }
    // 获取时间选择对象，返回数组
    _AddSelectTimeBox.prototype.getTimeBox = function(){
        var i,
            length = this.selectTimeBox.length;
        var timeList = [];
        for (i = 0; i < length; i++) {
            timeList.push(this.selectTimeBox[i].box.val());
        };
        return timeList;
    }
    // 获取时间选择对象，返回数组
    _AddSelectTimeBox.prototype.getTimeBoxId = function(){
        var i,
            length = this.selectTimeBox.length;
        var timeList = [];
        for (i = 0; i < length; i++) {
            timeList.push({
                time: this.selectTimeBox[i].box.val(),
                time_id: this.selectTimeBox[i].timeData.id,
                can_change: this.selectTimeBox[i].timeData.can_change
            });
        };
        return timeList;
    }
    return new _AddSelectTimeBox(_option);
}
module.exports = AddSelectTimeBox;

});

/*!www/order/module/edit_product/edit_product.js*/
;define('www/order/module/edit_product/edit_product', function(require, exports, module) {

/*
 *修改产装信息弹窗
 */
var popup = require('www/common/module/popup/popup.js');
var CommonBox = require("www/order/module/commonBox.js");
var AddSelectTimeBox = require("www/common/module/addSelectTimeBox/addSelectTimeBox.js");

var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="editProduct"><div class="editProduct-title clearfix"><div class="editProduct-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">修改产装信息</div><div class="editProduct-comfire"><button class="comfireBtn">确定</button></div></div><div class="editProduct-content clearfix">');if(!list || list.length === 0){_template_fun_array.push('<dl class="address-item clearfix" data-item="1" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">1</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1" data-canChange="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');}else{_template_fun_array.push('');for(var i = 0; i < list.length; i++){_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'" data-change="');if(list[i].address_can_change){_template_fun_array.push('1');}else{_template_fun_array.push('0');}_template_fun_array.push('"><input type="hidden" value="',typeof( list[i].product_address_id ) === 'undefined'?'':baidu.template._encodeHTML( list[i].product_address_id ),'" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'</span></h4></div><div class="address-del"></div></dd>');if(!list[i].address_can_change){_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">详细装箱地址</span></label></div><div class="item-content"><p>',typeof( list[i].box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address_detail ),'</p></div></dt>');}else{_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location" data-pid="',typeof( list[i].address_provinceid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_provinceid ),'" data-cid="',typeof( list[i].address_cityid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_cityid ),'" data-aid="',typeof( list[i].address_townid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_townid ),'"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" class="package_address" id="package_address" name="package_address" value="',typeof( list[i].box_address || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address || '' ),'" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt>');}_template_fun_array.push('<div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content">');if(!list[i].address_can_change){_template_fun_array.push('<p>',typeof( list[i].contactName || '未填写' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName || '未填写' ),'</p>');}else{_template_fun_array.push('<input type="text" class="linkman" id="linkman" name="linkman" value="',typeof( list[i].contactName || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName || '' ),'" />');}_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content">');if(!list[i].address_can_change){_template_fun_array.push('<p>',typeof( list[i].contactNumber || '未填写' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber || '未填写' ),'</p>');}else{_template_fun_array.push('<input type="text" id="contact" class="contact" name="contact" value="',typeof( list[i].contactNumber || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber || '' ),'"/>');}_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content">');if(!list[i].box_date || list[i].box_date === 0){_template_fun_array.push('<div class="package_date_selectBox data_1 clearfix" data-flag="1"></div>');}else{_template_fun_array.push('');var flag = 1;_template_fun_array.push('');for(var d = 0; d < list[i].box_date.length; d++){;_template_fun_array.push('');if(list[i].box_date[d].time_can_change){_template_fun_array.push('<div class="package_date_selectBox data_',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),' clearfix" data-flag="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-timeId="',typeof(list[i].box_date[d].product_time_id) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="1"></div>');}else{_template_fun_array.push('<div class="package_date_selectBox data_',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),' clearfix hidden" data-flag="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-timeId="',typeof(list[i].box_date[d].product_time_id) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="0"></div><p>',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'</p>');}_template_fun_array.push('');flag = flag + 1;_template_fun_array.push('');}_template_fun_array.push('');}_template_fun_array.push('<a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');}_template_fun_array.push('');}_template_fun_array.push(' <button class="add-other-address">添加其他产装地址</button></div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var addTpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
// 常量
var CONST = {
    is_require: true,
    no_require: false,
    input_type: 'INPUT',
    weight_type: 'WEIGHT',
    select_type: 'SELECT',
    address_select_type: 'ADDRESS'
};
var EditProduct = function(_option){
	var title,
        defaults = {
            data: {
                addressInfo: [],
                orderid: ''
            }
        },
        options = $.extend({}, defaults, _option); 

	function _EditProduct(){
        
	}
 
	_EditProduct.prototype = new popup({
        title :false, 
        height:500,
        width :700
	});

	_EditProduct.prototype.constructor = _EditProduct;

    _EditProduct.prototype.init = function(addressInfo){
        var list = this.list = [];
        var flag = 1;

        var that = this;
        var html = tpl({
            list: addressInfo || options.data.addressInfo
        });
        this.setContent(html);

        /** @type {[type]} [description] */
        var DOM = this.DOM = {
            scope: this.pop.find('.editProduct-content'),
            del_AddressBtn: $('<a class="del-AddressBtn" href="javaScript:">删除该地址</a>'),
            item: this.pop.find('.editProduct-content .address-item')
        }
        DOM.item.each(function(index, el) {
            var $scope = $(this);

            var addressItem = new AddressItem($scope);

            that.list.push(addressItem);
        });

        flag = this.flag = DOM.item.length;

        /** DOM Bind Event */
        DOM.scope.on('click', '.add-other-address', function(event) {
            if(flag > 19) return;
            flag++;

            var obj = $(this);
            var add_html = addTpl({
                flag: flag
            });
            var $scope = $(add_html);
            obj.before($scope);
            var delBtn_container = $scope.find('.address-del');
            var addressItem = new AddressItem($scope);

            that.list.push(addressItem);

            var $num = DOM.scope.find('.address-item .address-title .address-name .num');
            $num.each(function(index, el) {
                $(this).text(index + 1);
            });
        });

        DOM.scope.on('mouseover', '.address-item', function(event) {
            var obj = $(this);
            var flag = obj.attr('data-item');
            var useDel = obj.attr('data-change');
            if(flag == 1) return;
            if(!useDel || useDel == 0) return;

            DOM.del_AddressBtn.show();
            obj.find('.address-del').html(DOM.del_AddressBtn);
            DOM.del_AddressBtn.attr('data-item', flag);
        });

        DOM.scope.on('mouseleave', '.address-item', function(event) {
            var obj = $(this);
            var flag = obj.attr('data-item');
            if(flag == 1) return;

            DOM.del_AddressBtn.hide();
            DOM.del_AddressBtn.removeAttr('data-item', flag);
        });

        DOM.scope.on('click', '.del-AddressBtn', function(event) {
            var obj = $(this);
            var item_f = obj.attr('data-item');

            if(item_f == 1) return;

            var $item = obj.parents('.address-item');
            var i,
                length = that.list.length;
           
            for (i = 0; i < length; i++) {
                if(that.list[i] && that.list[i].$scope){
                    var _f = that.list[i].$scope.attr('data-item');
                    if(_f == item_f){
                        that.list.splice(i, 1);
                    }
                }
                
                
                
            };
            $item.remove();
            var $num = DOM.scope.find('.address-item .address-title .address-name .num');
            $num.each(function(index, el) {
                $(this).text(index + 1);
            });
        });
    }

    _EditProduct.prototype.bind = function(){
        var that = this;
        var DOM = {
            cancelBtn: that.pop.find('.editProduct-cancel .cancelBtn'),
            comfireBtn: that.pop.find('.editProduct-comfire .comfireBtn'),
        }

        DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });

        DOM.comfireBtn.on('click', function(event) {
            var sub_list = [],
                i,
                isPass = true,
                length = that.list.length;

                for (i = 0; i < length; i++) {
                    var getData = that.list[i].check();
                    var sub_data = {};
                    
                    sub_data.product_address_id = getData.product_address_id.val || '';
                    /** 地址 */
                    
                    if(getData.package_location.val.province.id && getData.package_location.val.city.id && getData.package_location.val.area.id){
                        var box_address = {
                            address: getData.package_address.val
                        }
                        box_address.provinceid = getData.package_location.val.province.id;
                        box_address.cityid = getData.package_location.val.city.id;
                        box_address.townid = getData.package_location.val.area.id;
                    }
                    /* 上传数据 */
                    sub_data.contactName = getData.linkman.val;
                    sub_data.contactNumber = getData.contact.val;
                    sub_data.box_date = [];

                    if(box_address){
                        sub_data.box_address = box_address;
                    }

                    var time_length = getData.timeList.length;
                    while(time_length--){
                        var time_data = {
                            product_time_id: getData.timeList[time_length].time_id
                        };
                        if(getData.timeList[time_length].can_change == 1){
                            time_data.product_supply_time = getData.timeList[time_length].time;
                        }
                        sub_data.box_date.push(time_data);
                    }

                    sub_list.push(sub_data);

                    for(var k in getData){
                        if(getData && typeof getData[k].checkPass === 'boolean' && !getData[k].checkPass){
                            console.log(k);
                            isPass = false;
                        }
                    }
                };

                if(!isPass){
                    return
                }
                XDD.Request({
                    url: '/carteam/order/address_confirm',
                    data: {
                        address_info: sub_list,
                        orderid: options.data.orderid
                    },
                    success: function(res){
                        if(res.error_code == 0){
                            that.hide();
                            _alert(res.error_msg || '修改成功！');
                            window.location.reload();
                        } else {
                            _alert(res.error_msg || '未知错误！');
                        }
                    }
                }, true);
        });
    }

    function AddressItem($scope) {
        function _AddressItem($scope){
            this.$scope = $scope; 
            var that = this;
            var itemBox = this.itemBox = new CommonBox({
                scope: $scope,
                config_array: [{
                    keyName: 'package_location',
                    selector: $scope.find('.package_location'),
                    require: CONST.is_require,
                    type: CONST.address_select_type,
                    height: 23
                },{
                    keyName: 'product_address_id',
                    selector: '.product_address_id',
                    require: CONST.no_require,
                    type: CONST.input_type
                }
                ,{
                    keyName: 'package_address',
                    selector: '.package_address',
                    require: CONST.is_require,
                    type: CONST.input_type
                },{
                    keyName: 'linkman',
                    selector: '.linkman',
                    require: CONST.no_require,
                    type: CONST.input_type
                },{
                    keyName: 'contact',
                    selector: '.contact',
                    require: CONST.no_require,
                    type: CONST.input_type
                }]
            });
            var timeBoxEvent = that.timeBoxEvent = new AddSelectTimeBox({
                container: that,
                height: 23
            });
            
        }

        _AddressItem.prototype.check = function(){
            var data = this.itemBox.check();

            data.timeList = this.timeBoxEvent.getTimeBoxId();
            return data;
        }
        return new _AddressItem($scope);
    }

	return new _EditProduct();

}

module.exports = EditProduct; 

});

/*!www/order/module/confirm_product/confirm_product.js*/
;define('www/order/module/confirm_product/confirm_product', function(require, exports, module) {

/*
 *确定产装信息弹窗
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
_template_fun_array.push('<div id="confirmProduct"><div class="confirmProduct-title clearfix"><div class="confirmProduct-edit"><button class="editBtn">修改</button></div><div class="title-text">确认产装信息</div><div class="confirmProduct-comfire"><button class="comfireBtn">确定</button></div></div><div class="confirmProduct-content clearfix">');if(!list || list.length === 0){_template_fun_array.push('<dl class="address-item clearfix" data-item="1"><dd class="address-title"><div class="address-name"><h4>未添加产装信息！</h4></div></dd></dl>');}else{_template_fun_array.push('');for(var i = 0; i < list.length; i++){_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'"><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label><span class="name">装箱地址</span></label></div><div class="item-content">',typeof( list[i].box_address_detail) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address_detail),'</div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label><span class="name">工厂联系人</span></label></div><div class="item-content">',typeof( list[i].contactName) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName),'</div></dt><dt class="item item-contact"><div class="item-name"><label><span class="name">联系方式</span></label></div><div class="item-content">',typeof( list[i].contactNumber) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber),'</div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label><span class="name">装箱时间</span></label></div><div class="item-content">');if(!list[i].box_date || list[i].box_date === 0){_template_fun_array.push('<p>未添加装箱时间</p>');}else{_template_fun_array.push('');for(var d = 0; d < list[i].box_date.length; d++){_template_fun_array.push('<p>',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'</p>');}_template_fun_array.push('');}_template_fun_array.push('</div></dt>');if(list.length != i + 1){_template_fun_array.push('<div class="clearfix address-bottom-line"></div>');}_template_fun_array.push('</dl>');}_template_fun_array.push('');}_template_fun_array.push('</div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];

var ConfirmProduct = function(_option){
	var defaults = {
            clickEditBtn: function(){},
            clickConfirmBtn: function(){}
        },
        options = $.extend({}, defaults, _option); 

	function _ConfirmProduct(){
        var html = tpl({
            list: options.data.addressInfo
        });
        this.setContent(html);

        var DOM = this.DOM = {
            editBtn: this.pop.find(".confirmProduct-edit .editBtn"),
            comfireBtn: this.pop.find(".confirmProduct-comfire .comfireBtn")
        }

        var that = this;
        /** bind event */
        // 修改
        DOM.editBtn.on('click', function(event) {
            that.hide();
            if(typeof options.clickEditBtn === 'function'){
                options.clickEditBtn();
            }
        });

        // 确定
        DOM.comfireBtn.on('click', function(event) {
            that.hide();
            if(typeof options.clickConfirmBtn === 'function'){
                options.clickConfirmBtn();
            }
        });
	}
 
	_ConfirmProduct.prototype = new popup({
        title :false, 
        height:500,
        width :700
	});

	_ConfirmProduct.prototype.constructor = _ConfirmProduct;

	return new _ConfirmProduct();
}

module.exports = ConfirmProduct; 

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

/*!www/order/module/comfirm_psw_pop/comfirm_psw_pop.js*/
;define('www/order/module/comfirm_psw_pop/comfirm_psw_pop', function(require, exports, module) {

/*
 *确认退载重建弹窗
 */
var popup = require('www/common/module/popup/popup.js');

function PswConfirmPop(_option) {
	var html,
        title,
        defaults = {},
        options = $.extend({}, defaults, _option);
    var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="passwordConfirm"><div class="logo"></div><dl><dd class="clearfix"><input type="password" id="quit_confirm" placeholder="请输入密码" maxlength="16"/><span class="icon-pass"></span></dd><dd id="quit_captcha_wrap" class="clearfix hidden"><input type="text" id="quit_captcha" placeholder="验证码" maxlength="4"/><div class="captcha-wrap"></div></dd></dl><div id="quit_tip" class="invisible"></div><a href="javascript:;" id="confirm_submit">确定</a></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
    function _PswConfirmPop(options) {
        var submit = this.pop.find("#confirm_submit"),
            password = this.pop.find("#quit_confirm"),
            captcha = this.pop.find("#quit_captcha"),
            tip = this.pop.find("#quit_tip"),
            errorType = '';

        this.DOM = {
            submit: submit,
            password: password,
            captcha: captcha,
            tip: tip
        }
        bind(this);
    }

    _PswConfirmPop.prototype = new popup({
        title: false,
        height: 280,
        width: 330,
        tpl: tpl
    });
    _PswConfirmPop.prototype.constructor = _PswConfirmPop;

    _PswConfirmPop.prototype.setHeight = function(h) {
        this.pop.height(h);
    }
    _PswConfirmPop.prototype.onComplete = function() {};

    function bind(that) {
        that.DOM.submit.on('click', function(event) {
            var pwd = checkPass(that);
            if (!pwd) return;

            that.onComplete(pwd);
        });
        $("#passwordConfirm").keydown(function(event){
            if(event.keyCode==13){
                that.DOM.submit.on();
            }
        })
    }

    function checkPass(that) {
        var pwd = that.DOM.password.val();
        if (pwd.length == 0) {
            that.DOM.tip.html('请输入密码');
            that.DOM.tip.removeClass('invisible');
            that.DOM.password && shake(that.DOM.password);
            setTimeout(function() {
                that.DOM.tip.addClass('invisible');
            }, 5000);
            return
        }

        if (pwd.length < 6) {
            that.DOM.tip.html('密码不少于6位');
            that.DOM.tip.removeClass('invisible');
            that.DOM.password && shake(that.DOM.password);
            setTimeout(function() {
                that.DOM.tip.addClass('invisible');
            }, 5000);
            return
        }
        that.DOM.tip.addClass('invisible');
        return pwd;
    }

    function shake(obj) {
        obj.animate({
                marginLeft: "-1px"
            }, 40).animate({
                marginLeft: "2px"
            }, 40)
            .animate({
                marginLeft: "-2px"
            }, 40).animate({
                marginLeft: "1px"
            }, 40)
            .animate({
                marginLeft: "0px"
            }, 40).focus();
    }

    return new _PswConfirmPop();
}
module.exports = PswConfirmPop;

});

/*!www/order/widget/view/order_details/order_details.js*/
;define('www/order/widget/view/order_details/order_details', function(require, exports, module) {

var EditProduct = require("www/order/module/edit_product/edit_product.js");
var ConfiremProduct = require("www/order/module/confirm_product/confirm_product.js");
var Cards = require("www/common/module/cards/cards.js");
var PswConfirmPop = require("www/order/module/comfirm_psw_pop/comfirm_psw_pop.js");

function bind(addressInfo, orderid, dispatch){
	var pswConfirmPop = new PswConfirmPop();
   	var logisticsCards = new Cards({
    	width: 500
	});
	var DOM = {
		$scope: $("#order_details"),

		btnModify: $("#modify_productInfo"),
		btnQuit: $("#quit_order"),

		btnDispatch: $('#btn_dispatch'),
        btnRebuild: $("#reconstruct_order"),
        funcs_control: $('#order_details .funcs.control'),
        funcs_look: $('#order_details .funcs.look'),
        dispatch_info: $('#order_details .dispatch-info')
	}

	// 修改产装信息
	var editProduct = EditProduct({
		data: {
			addressInfo: addressInfo,
			orderid: orderid
		}
	});

	// 确认产装信息
	var confiremProduct = ConfiremProduct({
		data: {
			addressInfo: addressInfo,
			orderid: orderid
		},
		clickEditBtn: function(){
			editProduct.init();
			editProduct.bind();
			editProduct.show();
		},
		clickConfirmBtn: function(){
			DOM.funcs_control.show();
			DOM.funcs_look.hide();
			DOM.dispatch_info.addClass('dispatch-editing-info');
			logisticsCards.hide();
		}
	});
	if(dispatch && dispatch == 1){
		confiremProduct.show();
		logisticsCards.hide();
		
	} else if(dispatch && dispatch == 2){
		DOM.funcs_control.show();
		DOM.funcs_look.hide();
		DOM.dispatch_info.addClass('dispatch-editing-info');
		logisticsCards.hide();
	} else {
		DOM.funcs_control.hide();
		DOM.funcs_look.show();
		DOM.dispatch_info.removeClass('dispatch-editing-info');
		logisticsCards.hide();
	}

	// 修改产装信息按钮事件
	DOM.btnModify.click(function() {
		editProduct.init();
		editProduct.bind();
		editProduct.show();
		logisticsCards.hide();
	});
	DOM.btnQuit.click(function(){
		pswConfirmPop.show();
		pswConfirmPop.onComplete = function(pws){
			XDD.Request({
				url: '/carteam/order/reConstruct_verify',
				data: {
					orderid: orderid,
					password: pws
				},
				type: 'get',
				success: function(res){
					pswConfirmPop.hide();
					if(res.error_code == 0){
						_alert('退载成功！');
						window.location.href = '/orderDetail?order_id=' + orderid;
					} else {
						_alert(res.error_msg || '退载失败！');
					}

				}
			})
		}
	});
    var timerCard;
    DOM.$scope.on('mouseover', '.order-info .second-title .edit-records', function(e) {
        var this_obj = $(this);
        var showCards = function(this_obj){
        	var order_id = this_obj.attr('data-orderid');

	        var html = '';
	        historyCards.hide();
	        XDD.Request({
	            url: '/carteam/order/modify_history',
	            type: 'get',
	            data:{
	                orderid: order_id
	            },
	            success: function(res){
	                if(res.error_code == 0){
	                    var i,
	                        length = res.data.length;
	                    if(length > 0){
	                        for (i = 0; i < length; i++) {
	                            html += '<p>' + res.data[i].date + '</p>\
	                            <p><span style="margin-right:5px;">'+res.data[i].user+'</span>'+res.data[i].operateType+'</p>\
	                            <p style="margin-bottom:15px;">'+res.data[i].content+'</p>'; 
	                        };
	                    } else {
	                        html = '<p style="text-align:center;">暂无修改记录！</p>'
	                    }
	                    
	                    historyCards.setContent(html);
	                    historyCards.show(e);
	                } else {
	                    historyCards.setContent(html);
	                    historyCards.show(e);
	                }
	            }
	        });
        }

        timerCard = setTimeout(function(){showCards(this_obj)}, 800);
        
    }).on('mouseleave', '.order-info .second-title .edit-records', function(e) {
    	clearTimeout(timerCard);
        historyCards.hide();
    });


}


module.exports = {
	init:function(addressInfo, orderid, dispatch){
		bind(addressInfo, orderid, dispatch);
	}
}

});
