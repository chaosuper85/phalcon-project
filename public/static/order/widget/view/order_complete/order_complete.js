define('order/widget/view/order_complete/order_complete', function(require, exports, module) {

var util = require("common/module/util.js");
var CommonBox = require("order/widget/view/order_complete/commonBox.js");
var AddressBox = require("order/widget/view/order_complete/addressBox.js");
require("common/module/searchBox/searchBox.js");
// 常量
var CONST = {
	is_require: true,
	no_require: false,
	input_type: 'INPUT',
	weight_type: 'WEIGHT',
	select_type: 'SELECT'
};

module.exports = {
	init:function(order_id, box_type_list){

		/* 箱型 */
		var box_type_array = [];
		for(var i in box_type_list){
			var box_type_data = {
				value: i,
				text: box_type_list[i]
			};
			box_type_array.push(box_type_data);
		}

		$('#ship_company').XDDSearchBox({
			resKeyName: 'china_name',
			appKeyName: 'english_name',
			keyId: 'company_id'
		});
		$('#ship_name').XDDSearchBox({
			url: '/carteam/order/search_ship_name',
			resKeyName: 'china_name',
			appKeyName: 'eng_name',
			keyId: 'ship_name_id'
		});
		$('#ship_yard').XDDSearchBox({
			url: '/carteam/order/search_yard',
			resKeyName: 'yard_name',
			keyId: 'yard_id'
		});
		$('#product_weight').on('keydown', function(e) {
			var keyCode = e.keyCode;

			if(keyCode > 47 && keyCode < 58) return;
			if(keyCode === 8) return

			util.preventDefault(e);
		});

		bind(order_id, box_type_array);
	}
}

function bind(order_id, box_type_array){
	var box = load(box_type_array),
		DOM = {
			save: $('.order-save')
		};

	DOM.save.on('click', function(event) {
		var fcData = box.fcBox.check(),
			shipData = box.shipBox.check(),
			productData = box.productBox.check(),
			addressData = box.addressBox.check();

		var freight = {}, 
		carteam = {},
		orderid = order_id,
		ship_info = {},
		product_info = {},
		address_info = [];

		var canSubmit = true;

		// 委托方
		freight.id = fcData.freight_id.val || '';
		freight.name = fcData.freight_name.val || '';

		// 车队
		carteam.id = fcData.carteam_id.val || '';
		carteam.name = fcData.carteam_name.val || '';

		// 船期信息
		ship_info.ship_company_id = $('#ship_company').attr('data-id') || ' ';
		ship_info.ship_company_name = shipData.company.val || '';

		ship_info.ship_name_id = $('#ship_name').attr('data-id') || ' ';
		ship_info.ship_name = shipData.name.val || '';

		ship_info.ship_ticket = shipData.num.val || '';

		ship_info.yard_id = $('#ship_yard').attr('data-id') || ' ';
		ship_info.yard_name = shipData.yard.val || '';

		ship_info.tidan_code = shipData.tidan.val || '';

		// 货物信息
		product_info.product_box_type = productData.product_type.val || '';
		product_info.box_type_number = [{
			number: productData['20gp'].val || '',
			type: '20GP'
		},{
			number: productData['40gp'].val || '',
			type: '40GP'
		},{
			number: productData['40hg'].val || '',
			type: '40HQ'
		}];
		product_info.product_name = productData.product_name.val || '';
		product_info.product_weight = productData.product_weight.val || '';
		product_info.product_notice = productData.product_remark.val || '';

		// 产装地址
		var i = addressData.length;
		while(i--){
			var data = {};
			data.box_date = addressData[i].timeList;
			data.address_provinceId = addressData[i].package_location.val.province.id || '';
			data.address_cityId = addressData[i].package_location.val.city.id || '';
			data.address_townId = addressData[i].package_location.val.area.id || '';
			data.box_address_detail = addressData[i].package_address.val || '';
			data.contactName = addressData[i].linkman.val;
			data.contactNumber = addressData[i].contact.val;

			address_info.push(data);
			
			canSubmit = pass(addressData[i]);
		}
		
		if(!pass(fcData) || !pass(shipData) || !pass(productData) || !canSubmit){
			_alert('您有信息未填写或填写格式不正确！');
			return
		};
		
		XDD.Request({
			url: '/carteam/order/confirm',
			data: {
				orderid: orderid,
				ship_info: ship_info,
				product_info: product_info,
				address_info: address_info
			},
			success: function(res){
				if(res.error_code === 0){
					_alert(res.error_msg || '接单成功！');
					window.location.href = '/order/list';
				} else {
					_alert(res.error_msg || '未知错误！');
				}
			}
		}, true);
		
		
	});
}

function pass(data){
	var isPass = true;
	for(var k in data){
		if(data[k] && typeof data[k].checkPass === 'boolean' && !data[k].checkPass) {	
			isPass = false;
		}
	}
	return isPass
}

function load(box_type_list){
	var fcBox, shipBox, productBox, addressBox;
	// 委托方／承运方
	fcBox = new CommonBox({
		scope: $('.carteam_freight'),
		config_array: [{
			keyName: 'freight_id',
	 		selector: '#freight_id',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: 'freight_name',
	 		selector: '#freight_name',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: 'carteam_id',
	 		selector: '#carteam_id',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: 'carteam_name',
	 		selector: '#carteam_name',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		}]
	});
	// 船期
	shipBox = new CommonBox({
		scope: $('.ship_info'),
		config_array:[{
			keyName: 'company',
	 		selector: '#ship_company',
	 		require: CONST.is_require,
	 		type: CONST.input_type
		},{
			keyName: 'tidan',
	 		selector: '#tidan',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: 'name',
	 		selector: '#ship_name',
	 		require: CONST.is_require,
	 		type: CONST.input_type
		},{
			keyName: 'num',
	 		selector: '#ship_num',
	 		require: CONST.is_require,
	 		type: CONST.input_type
		},{
			keyName: 'yard',
	 		selector: '#ship_yard',
	 		require: CONST.is_require,
	 		type: CONST.input_type,
		}]
	});

	// 货物信息
	productBox = new CommonBox({
		scope: $('.product_info'),
		config_array: [{
			keyName: 'product_type',
	 		selector: '#product_type',
	 		require: CONST.input_type,
	 		type: CONST.select_type,
	 		selectPlaceholder: '请选择货物箱型',
	 		selectOptions: box_type_list
		},{
			keyName: '20gp',
	 		selector: '#product_20gp',
	 		require: CONST.is_require,
	 		type: CONST.weight_type
		},{
			keyName: '40gp',
	 		selector: '#product_40gp',
	 		require: CONST.is_require,
	 		type: CONST.weight_type
		},{
			keyName: '40hg',
	 		selector: '#product_40hg',
	 		require: CONST.is_require,
	 		type: CONST.weight_type
		},{
			keyName: 'product_name',
	 		selector: '#product_name',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: 'product_weight',
	 		selector: '#product_weight',
	 		require: CONST.is_require,
	 		type: CONST.input_type
		},{
			keyName: 'product_remark',
	 		selector: '#product_remark',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		}]
	});

	// 地址
	addressBox = new AddressBox({
		item: 1
	});
	return {
		fcBox: fcBox,
		shipBox: shipBox,
		productBox: productBox,
		addressBox: addressBox
	}
}

});
