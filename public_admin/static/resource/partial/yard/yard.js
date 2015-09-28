define('resource/partial/yard/yard', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var message = antd.message;
var Select = antd.Select;
var Option = Select.Option;

module.exports = {
	init: function init(param) {
		var type = null,
		    params = param;

		if ('create_type' in params) {
			type = params.create_type;
		}

		React.render(React.createElement(Select, { defaultValue: type, style: { width: 100 }, onChange: handleChange, placeholder: "数据来源" }, React.createElement(Option, { value: "1" }, "后台添加"), React.createElement(Option, { value: "0" }, "用户添加")), document.getElementById('filter-from'));
		function handleChange(value) {
			params['create_type'] = value;
			util.redirectParam(params);
		}

		var $search_input = document.querySelector('.filter-input');
		if (params.yard_name) {
			$search_input.value = params.yard_name;
		}

		document.getElementById('search').addEventListener('click', function () {
			if (!$search_input.value) {
				message.error('请输入搜索内容');
				return;
			}
			params['yard_name'] = $search_input.value;
			util.redirectParam(params);
		});
	}
};

});
