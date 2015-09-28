define('order/widget/view/print_reconstrucct_msg/print_reconstrucct_msg', function(require, exports, module) {

var CommonBox = require("order/widget/view/order_complete/commonBox.js");
var AddressBox = require("order/widget/view/order_complete/addressBox.js");
require("common/module/searchBox/searchBox.js");
var Reconstruct = require("order/module/comfirm_psw_pop/comfirm_psw_pop.js");
var select = require('common/module/select/select.js');


// 常量
var CONST = {
	is_require: true,
	no_require: false,
	input_type: 'INPUT',
	weight_type: 'WEIGHT',
	select_type: 'SELECT'
};

var confrimReconstruct = Reconstruct();

var global_order;
module.exports = {
	init:function(orderid, product_box_type){

		$('#order_complete_rec #ship_company').XDDSearchBox({
			resKeyName: 'china_name',
			appKeyName: 'english_name',
			keyId: 'company_id'
		});
		$('#order_complete_rec #ship_name').XDDSearchBox({
			url: '/carteam/order/search_ship_name',
			resKeyName: 'china_name',
			appKeyName: 'eng_name',
			keyId: 'ship_name_id'
		});
		$('#order_complete_rec #ship_yard').XDDSearchBox({
			url: '/carteam/order/search_yard',
			resKeyName: 'yard_name',
			keyId: 'yard_id'
		});

		bind(orderid);
	}
}

function bind(orderid){
	var reconstruct = Reconstruct({
		data:{
			 orderid:orderid
		}
	});
	var box = load();
	$("#order_complete_rec .order-save").on('click', function(event) {
		var fcData = box.fcBox.check(),
			shipData = box.shipBox.check(),
			productData = box.productBox.check();

		var canSubmit = true;


		if(!pass(fcData) || !pass(shipData) || !pass(productData) || !canSubmit){
			_alert('您有信息未填写！');
			return
		};
		confrimReconstruct.show();

		confrimReconstruct.onComplete = function(pwd){
			var subData = {
				 box_20gp_count: productData['20gp'].val,
		         box_40gp_count: productData['40gp'].val,
		         box_40hq_count: productData['40hg'].val,
		         orderid: orderid,
		         password: pwd,
		         product_box_type: productData.product_type.val,
		         product_desc: productData.product_remark.val,
		         product_name: productData.product_name.val,
		         product_weight: productData.product_weight.val,
		         ship_name: shipData.name.val,
		         // ship_name_id: $('#order_complete_rec #ship_name').attr('data-id'),
		         ship_ticket: shipData.num.val,
		         shipping_company: shipData.company.val,
		         // shipping_company_id: $('#order_complete_rec #ship_company').attr('data-id'),
		         tidan_code: shipData.tidan.val,
		         yard: shipData.yard.val
		         // yard_id: $('#order_complete_rec #ship_yard').attr('data-id')
		         
	        };
			XDD.Request({
                url:'/carteam/order/reConstruct',
                data:subData,
                type: 'get',
                success:function(result){
                    confrimReconstruct.hide();
                    if(result.error_code == 0){
                    	_alert('重建成功！');
                        window.location.href= "/order/list";
                        
                    }else{
                        _alert(result.error_msg);
                    }
                }
            })
		}
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

function load(){
	var fcBox, shipBox, productBox;
	// 委托方／承运方
	fcBox = new CommonBox({
		scope: $('#order_complete_rec .carteam_freight'),
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
		scope: $('#order_complete_rec .ship_info'),
		config_array:[{
			keyName: 'company',
	 		selector: '#ship_company',
	 		require: CONST.is_require,
	 		type: CONST.input_type
		},{
			keyName: 'tidan',
	 		selector: '#tidan',
	 		require: CONST.is_require,
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
		},{
			keyName: 'ship_remark',
	 		selector: '#ship_remark',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		}]
	});

	// 货物信息
	productBox = new CommonBox({
		scope: $('#order_complete_rec .product_info'),
		config_array: [{
			keyName: 'product_type',
	 		selector: '#product_type',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: '20gp',
	 		selector: '#product_20gp',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: '40gp',
	 		selector: '#product_40gp',
	 		require: CONST.no_require,
	 		type: CONST.input_type
		},{
			keyName: '40hg',
	 		selector: '#product_40hg',
	 		require: CONST.no_require,
	 		type: CONST.input_type
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

	return {
		fcBox: fcBox,
		shipBox: shipBox,
		productBox: productBox
	}
}

});
