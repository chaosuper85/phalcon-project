define('order/widget/view/print_reconstrucct_msg/addressBox', function(require, exports, module) {

var util = require("common/module/util.js");
var CommonBox = require("order/widget/view/order_complete/commonBox.js");
var AddSelectTimeBox = require("order/module/addSelectTimeBox/addSelectTimeBox.js");
var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="address-item clearfix" data-item=&#39;',typeof( item ) === 'undefined'?'':baidu.template._encodeHTML( item ),'&#39;><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( item ) === 'undefined'?'':baidu.template._encodeHTML( item ),'</span></h4></div><div class="address-tip"><p>请详细填写每个产装地址同时间进行产装的的具体箱型箱量</p></div><div class="address-del"></div></dd><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i>装箱时间必填</div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i>装箱地必填</div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i>详细地址必填</div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i>工厂联系人必填</div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" class="contact" name="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i>联系方式必填</div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];

// 常量
var CONST = {
	SCOPE: '.address_info',
	is_require: true,
	no_require: false,
	input_type: 'INPUT',
	weight_type: 'WEIGHT',
	select_type: 'SELECT',
	address_select_type: 'ADDRESS'
};
function AddressBox(_option){
	var option = {
		item: _option.item || 1
	};
	var list = this.list = [];
	var $scope_list = $(CONST.SCOPE + ' .address-item');
	var item = option.item;

	var add_AddressBtn = $(CONST.SCOPE + ' .add-address');
	var del_AddressBtn = $('<a class="del-AddressBtn" href="javaScript:">删除该地址</a>');

	$(CONST.SCOPE).on('mouseover', '.address-item', function(event) {
		var obj = $(this);
		var item = obj.attr('data-item');
		if(item == 1) return;

		del_AddressBtn.show();
		obj.find('.address-del').html(del_AddressBtn);
		del_AddressBtn.attr('data-item', item);
	});

	$(CONST.SCOPE).on('mouseleave', '.address-item', function(event) {
		var obj = $(this);
		var item = obj.attr('data-item');
		if(item == 1) return;

		del_AddressBtn.hide();
		del_AddressBtn.removeAttr('data-item', item);
	});

	$(CONST.SCOPE).on('click', '.del-AddressBtn', function(event) {
		var obj = $(this);
		var item = obj.attr('data-item');

		if(item == 1) return;

		var $item = obj.parents('.address-item');
		var i,
			length = list.length;
		for (i = 0; i < length; i++) {
			list.splice(i, 1);
		};
		$item.remove();
		var $num = $(CONST.SCOPE).find('.address-item .address-title .address-name .num');
		$num.each(function(index, el) {
			$(this).text(index + 1);
		});
	});

	$scope_list.each(function(index, el) {
		var $scope = $(this);
		var delBtn_container = $scope.find('.address-del');
		var addressItem = new AddressItem($scope);

		list.push(addressItem);
	});

	add_AddressBtn.on('click', function(event) {
		if(item > 19) return;
		item++;

		var obj = $(this);
		var html = tpl({
			item: item
		});
		var $scope = $(html);
		obj.before($scope);
		var delBtn_container = $scope.find('.address-del');
		var addressItem = new AddressItem($scope);

		list.push(addressItem);

		var $num = $(CONST.SCOPE).find('.address-item .address-title .address-name .num');
		$num.each(function(index, el) {
			$(this).text(index + 1);
		});
	});
}

AddressBox.prototype.check = function(){
	var data = [],
		i,
		length = this.list.length;
	for (i = 0; i < length; i++) {
		data.push(this.list[i].check());
	};
	return data;
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
		 		type: CONST.address_select_type
			},{
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
			container: that
		});
		
	}

	_AddressItem.prototype.check = function(){
		var data = this.itemBox.check();
		data.timeList = this.timeBoxEvent.getTimeBox();
		return data;
	}
	return new _AddressItem($scope);
}

module.exports = AddressBox;



});
