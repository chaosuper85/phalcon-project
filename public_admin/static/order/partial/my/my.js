define('order/partial/my/my', function(require, exports, module) {

"use strict";

var util = require('common/module/util/util');
var message = antd.message;
var params = undefined;
var search_type = "enterprisename";
var search_type_val = "";
var search_type_name = ['enterprisename', 'order_freightagent_mobile', 'order_teammobile'];

function render() {

	var Select = antd.Select;
	var Option = Select.Option;

	React.render(React.createElement(Select, { defaultValue: search_type, style: { width: 100 }, onChange: handleChange, placeholder: "查询类型" }, React.createElement(Option, { value: "enterprisename" }, "公司名"), React.createElement(Option, { value: "order_freightagent_mobile" }, "货代手机号"), React.createElement(Option, { value: "order_teammobile" }, "车队手机号")), document.getElementById('filter_search_type'));
	function handleChange(value) {
		search_type = value;
	}

	bindEvent();
}

function bindEvent() {

	document.getElementById('clearAll').addEventListener('click', function () {
		var status = util.getParam('order_status');
		var query = '';
		if (status) {
			query += '?order_status=' + status;
		}
		location.href = window.location.pathname + query;
	});

	var $search_input = document.querySelector('.filter-input');
	$search_input.value = search_type_val;

	document.getElementById('search').addEventListener('click', function () {
		if (!$search_input.value) {
			message.error('请输入搜索内容');
			return;
		}
		search_type_name.map(function (name) {
			if (params.hasOwnProperty(name)) {
				params[name] = '';
			}
		});
		params[search_type] = $search_input.value;
		util.redirectParam(params);
	});
}

module.exports = {
	init: function init(param) {
		params = param || {};
		search_type_name.map(function (name) {
			if (params.hasOwnProperty(name)) {
				search_type = name;
				search_type_val = params[name];
			}
		});
		render();
	}
};

});
