define('common/widget/table/date_filter.tpl/date', function(require, exports, module) {

"use strict";

var util = require('common/module/util/util');
var Datepicker = antd.Datepicker;

module.exports = {
	init: function init(params, from, to) {
		var StartPicker = React.createClass({ displayName: "StartPicker",
			handleChange: function handleChange(value) {
				params[from] = util.getDate(value);
				util.redirectParam(params);
			},
			render: function render() {
				return React.createElement(Datepicker, { style: { width: 120 }, onSelect: this.handleChange, placeholder: "开始时间", value: params[from], format: "yyyy-MM-dd" });
			}
		});
		var EndPicker = React.createClass({ displayName: "EndPicker",
			handleChange: function handleChange(value) {
				params[to] = util.getDate(value);
				util.redirectParam(params);
			},
			render: function render() {
				return React.createElement(Datepicker, { style: { width: 120 }, onSelect: this.handleChange, placeholder: "结束时间", value: params[to], format: "yyyy-MM-dd" });
			}
		});
		React.render(React.createElement(StartPicker, null), document.getElementById('filter_time_start'));
		React.render(React.createElement(EndPicker, null), document.getElementById('filter_time_end'));
	}
};

});
