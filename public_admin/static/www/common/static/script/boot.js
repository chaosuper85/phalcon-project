(function(window){

	if (typeof console == "undefined") {
        window.console = {
        	log : function(){}
        }
    }

    var apiUrl = '/api';

    window.XDD = {

    	// toJson设为true data转为json字符串

    	Request : function(opt,toJson){
    		if(typeof opt !== 'object'){
	            console.log('option must be an object')
	            return;
	        }
	        var option = opt || {};

	        var _data = option.data || {};

	        var content_type = 'application/x-www-form-urlencoded';

			if(toJson){
				_data = $.toJSON(_data);
				content_type = 'application/json';
			}

	        $.ajax({
	            type  : option.type || 'POST',
	             url  : apiUrl + option.url,
	        dataType  : option.dataType || "json",
	            data  : _data,
         contentType  : content_type,
	           error  : option.error || function(res){
	                if(typeof option.error === 'function'){
	                	option.error(res);
	                }else{
	                	alert('网络好像有问题，请检查您的网络')
	                }
	            },
	         success  : function(res){
	                if(0){
	                	// TODO some common progress
	                    return;
	                }
	                typeof option.success === 'function' && option.success(res)
	            }
	        })
    	}
    }

    var myAlert = require('www/common/module/alert/alert.js');

    var __alert = myAlert();
    
    window._alert = function(msg){
        __alert.setData(msg);
        __alert.show();
    }

    window._alert.hide = function(){
        __alert.hide();
    }
    
    window.baidu = {
    	template: {}
    };
    // 百度编译器转义
    window.baidu.template._encodeHTML = function (source) {
	    return String(source)
	        .replace(/&/g,'&amp;')
	        .replace(/</g,'&lt;')
	        .replace(/>/g,'&gt;')
	        .replace(/\\/g,'&#92;')
	        .replace(/"/g,'&quot;')
	        .replace(/'/g,'&#39;');
	};
	// 转义UI UI变量使用在HTML页面标签onclick等事件函数参数中
	window.baidu.template._encodeEventHTML = function (source) {
    return String(source)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;')
        .replace(/\\\\/g,'\\')
        .replace(/\\\//g,'\/')
        .replace(/\\n/g,'\n')
        .replace(/\\r/g,'\r');
};


})(window)