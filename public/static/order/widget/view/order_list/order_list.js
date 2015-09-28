define('order/widget/view/order_list/order_list', function(require, exports, module) {

var remark = require("order/module/remark/remark.js");
var select = require('common/module/select/select.js');

module.exports = {
	init:function(usertype, searchType){
		searchEvent(usertype, searchType);

		var Btns = {
				remark : $('.btn_remark')
			},
			pop_remark = null;


		Btns.remark.click(function(){

			var obj = $(this),
				orderId = obj.attr('data-odid');

			if(!pop_remark){
				pop_remark = remark();
			}
			pop_remark.setId(orderId);
			pop_remark.show();

		})
	}
}

function searchEvent(usertype, searchType){
	/** 搜索 */
	var $searchBtn = $("#order_search .search-btn");
	var $searchValue = $("#order_search input[name='searchValue']");
	var $searchType = $("#order_search input[name='searchType']");
	var $clearBtn = $("#order_search .clear-btn");

	var companyName = '';
	if(usertype == 1){
		companyName = '委托方';
	} else {
		companyName = '承运方';
	}
	
	var tyle_list = ['', companyName, '提单号', '运单号', '船公司'];

	var searchTypeSelector;



	if(searchType){
		searchTypeSelector = new select({
			container: '#searchTypeSelector',
			options: [{
				value: 1,
				text: tyle_list[1]
			},{
				value: 2,
				text: tyle_list[2]
			},{
				value: 3,
				text: tyle_list[3]
			},{
				value: 4,
				text: tyle_list[4]
			}],
			height: 44,
			width: 60,
			defaultValue: searchType || 1,
			defaultText: tyle_list[searchType] || tyle_list[1],
			text_align: 'center',
			options_max_height: 300,
			onSelectChange: function(val, text, select_obj){
				if(val){
					$searchValue.attr('placeholder', '请输入' + text);
					$searchType.val(val);
				} else {
					$searchValue.attr('placeholder', tyle_list[1] + '提单号/运单号/船公司');
					$searchType.val(0);
				}
			}
		});

		$searchValue.attr('placeholder', tyle_list[searchType]);
	} else {
		searchTypeSelector = new select({
			container: '#searchTypeSelector',
			options: [{
				value: 1,
				text: tyle_list[1]
			},{
				value: 2,
				text: tyle_list[2]
			},{
				value: 3,
				text: tyle_list[3]
			},{
				value: 4,
				text: tyle_list[4]
			}],
			height: 44,
			width: 60,
			defaultValue: 1,
			defaultText: tyle_list[1],
			text_align: 'center',
			options_max_height: 300,
			onSelectChange: function(val, text, select_obj){
				if(val){
					$searchValue.attr('placeholder', '请输入' + text);
					$searchType.val(val);
				} else {
					$searchValue.attr('placeholder', tyle_list[1] + '提单号/运单号/船公司');
					$searchType.val(0);
				}
			}
		});
		$searchValue.attr('placeholder', tyle_list[1]);
	}
	
	$searchValue.on('keyup', function(event) {
		if(event.keycode == 13){
			$searchBtn.trigger('click');
		}
	});

	$searchBtn.on('click', function(event) {
		var searchValue = $searchValue.val();
		var searchType = $searchType.val();
		if(!searchType || (searchType == 0 && searchValue)){
			_alert('请选择搜索类型！');
			event.preventDefault();
		}
	});
	$clearBtn.on('click', function(event) {
		$searchValue.val("");
	});
}

});
