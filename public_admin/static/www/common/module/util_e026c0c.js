define('www/common/module/util', function(require, exports, module) {

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
