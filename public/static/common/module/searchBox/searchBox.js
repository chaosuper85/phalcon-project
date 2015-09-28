define('common/module/searchBox/searchBox', function(require, exports, module) {

/**
 * [搜索提示]
 * $('input').XDDSearchBox(option) 使用方法
 * option参数:
 * @param {[string]} [string] [请求地址]
 * @param {[function]} [onKeyup] [输入时回调函数]
 * @param {[function]} [onSelect] [选择后回调函数]
 * @param {[string]} [key] [传递到后台的字段名]
 * @param {[type]} [resKeyName] [返回的字段名字]
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
_template_fun_array.push('<ul class="XDD_searchBox_result"></ul>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var util = require("common/module/util.js");
var _txt = '';
;(function($){
    $.fn.XDDSearchBox = function (options) {
        //插件代码
    	var defaults = {
			url:     "/carteam/order/search_ship_company",
			onKeyup:   function(value) {},
			onSelect:   function(value) {}
		},
		settings = $.extend({}, defaults, options);

		var flag = -1;

		return this.each(function(index, el) {
			var $input = $(this);
			if(!$input.is("input")) return;
			if(!$input.attr("type") === "text") return;
			
			var $parent = $('<div class="XDD_searchBox_container"></div>');
			var $result = $(tpl());

			$input.before($parent);
			$parent.html($input);
			$parent.append($result);

			var style = {
				width: $input.innerWidth(),
				height: $input.innerHeight(),
				resultOffsetTop: $input.position().top
			};
			style.resultOffsetTop = style.height + style.resultOffsetTop;
			
			/* 结果样式 */
			$result.innerWidth(style.width);
			$result.css('top', style.resultOffsetTop);
			$result.css('line-height', style.height + 'px');

			/* 结果事件 */
			$result.on('mouseover', 'li', function(event) {
				var $this_obj = $(this);
				var $result_items = $result.find('li');

				$result_items.removeClass('hover');

				$this_obj.addClass('hover');

				$result_items.each(function(index, el) {
					var $this_item = $(this);
					
					if($this_item.hasClass('hover')){
						flag = index + 1;
						return
					}
				});
				$input.unbind('blur', onBlur);
			});

			$result.on('mouseleave', 'li', function(event) {
				var $this_obj = $(this);

				$this_obj.removeClass('hover');
				flag = -1;
				$input.bind('blur', onBlur);
			});

			$result.on('click', 'a', onResultClick);

			/* 绑定input事件 */
			$input.bind('keyup', onKeyup);
		});
    	
    	function onKeyup(e){
            var $element = $(e.target);
    		var txt = $element.val();
            
            var keyCode = e.keyCode;
            var $result = $element.siblings('.XDD_searchBox_result');
            var $result_items = $result.find('li');

            $element.removeAttr('data-id');

            settings.onKeyup(txt);
            if(util.isNull(txt)){
                $result.parent().css('z-index', '1');
                $result.hide();
                return;
            };
            
            if(keyCode === 13){
                keyBoardComfire($element, $result_items, $result);
                return;
            }
            if(keyCode === 38){
                keyBoardUp($result, $result_items);
                return;
            }
            if(keyCode === 40){
                keyBoardDown($result, $result_items);
                return;
            }
            
            if(txt == _txt){
                return;
            }
            _txt = txt;

            if(keyCode === 39 || keyCode === 37){
                return
            }
    		searchAjax(txt, $result, $element);
    	}

    	function onBlur(e){
    		var $element = $(e.target);
    		var txt = $element.val();

    		var $result = $element.siblings('.XDD_searchBox_result');
            $result.parent().css('z-index', '1');
    		$result.hide();
    	}

    	function keyBoardComfire($element, $result_items, $result){
    		if(flag < 1) return;

    		var length = $result_items.length;
    		if(flag > length) return;

    		$result_items.each(function(index, el) {
    			var $this_item = $(this);
    			$this_item.removeClass('hover');
    			if(flag === index + 1){
    				var txt = $element.val($this_item.find('a').text());
                    var id = $this_item.attr('data-id');

                    $element.attr('data-id', id);

                    $result.parent().css('z-index', '1');
    				$result.hide();
    				settings.onSelect(txt);
    			}
    		});
    	}
    	function keyBoardUp($result, $result_items){
            var length = $result_items.length;
            if(flag <= 1){
                flag = length + 1;
            }
            flag--;

            $result_items.each(function(index, el) {
                var $this_item = $(this);
                $this_item.removeClass('hover');
                if(flag === index + 1){
                    $result.scrollTop($this_item.innerHeight() * index);
                    $this_item.addClass('hover');
                }
            });
        }
        function keyBoardDown($result, $result_items){
            var length = $result_items.length;
            if(flag == -1 || flag >= length){
                flag = 0;
            }
            flag++;

    		$result_items.each(function(index, el) {
    			var $this_item = $(this);

    			$this_item.removeClass('hover');
    			if(flag === index + 1){
    				$result.scrollTop($this_item.innerHeight() * index);
    				$this_item.addClass('hover');
    			}
    		});
    	}
    	function onResultClick(e){
    		var $element = $(e.target);
    		var $result = $element.parent().parent();
    		var $input = $result.siblings('input');
    		var txt = $element.text();
            var id = $element.parent('li').attr('data-id');

    		$input.val(txt);
            $input.attr('data-id', id);

            $result.parent().css('z-index', '1');
    		$result.hide();

    		settings.onSelect(txt);
    	}
    	function searchAjax(txt, $result, $element){
    		var data = {};
    		var key = settings.key || 'keyWord';
    		var resKeyName = settings.resKeyName;
            var appKeyName = settings.appKeyName || '';
            var keyId = settings.keyId || '';
            
    		data[key] = txt;
    		XDD.Request({
				url: settings.url,
				data: data,
				success: function(res){
					if(res.error_code == 0){
						var html = '';
						if(!res.data || res.data.length === 0){
                            $result.parent().css('z-index', '1');
							$result.hide();
							return
						};
                        $result.parent().css('z-index', '99999');
						$result.show();
						for(var k in res.data){
                            var setIdVal = res.data[k][keyId] || '';
							if(txt == res.data[k][resKeyName] || txt == res.data[k][appKeyName]){
                                if(res.data[k][resKeyName]){
                                    $element.attr('data-id', setIdVal);
                                    html += '<li class="active" data-id="' + setIdVal + '"><a href="javaScript:">' + res.data[k][resKeyName] + '</a></li>';
                                }
                                if(appKeyName && res.data[k][appKeyName]){
                                    $element.attr('data-id', setIdVal);
                                    html += '<li class="active" data-id="' + setIdVal + '"><a href="javaScript:">' + res.data[k][appKeyName] + '</a></li>';
                                }
							} else {
                                if(res.data[k][resKeyName]){
                                    html += '<li data-id="' + setIdVal + '"><a href="javaScript:">' + res.data[k][resKeyName] + '</a></li>';
                                }
                                if(appKeyName && res.data[k][appKeyName]){
                                    html += '<li data-id="' + setIdVal + '"><a href="javaScript:">' + res.data[k][appKeyName] + '</a></li>';
                                }
							}
						}
						$result.html(html);
                        return
					}
                    $result.parent().css('z-index', '1');
					$result.hide();
					flag = -1;
				}
			});
    	}
    };
})(jQuery);

});
