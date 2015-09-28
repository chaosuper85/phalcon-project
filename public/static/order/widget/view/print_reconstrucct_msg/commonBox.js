define('order/widget/view/print_reconstrucct_msg/commonBox', function(require, exports, module) {

var util = require("common/module/util.js");
var SelectBox = require("common/module/select/select.js");
var Address = require("common/module/address/address.js");

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

		DOM[keyName] = {};
		(function(keyName, selector, require, type){
			if(type === CONST.input_type || type === CONST.weight_type){
				DOM[keyName].$ = $(SCOPE).find(selector);
				DOM[keyName].require = require;
				DOM[keyName].type = type;
			}
			if(type === CONST.select_type){
				DOM[keyName].$ = new SelectBox({
					container: $(SCOPE).find(selector),
					width: 500,
					height: 34,
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
			
		})(keyName, selector, require, type);
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
