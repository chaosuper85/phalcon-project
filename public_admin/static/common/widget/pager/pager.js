define('common/widget/pager/pager', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');

function onChange(page) {
	var params = util.getParam() || {};
	params.page_no = page;

	var path = util.getUrl(params);
	location.href = window.location.pathname + '?' + path;
}

function render(total, current) {
	var Pagination = antd.Pagination;
	React.render(React.createElement(Pagination, { showQuickJumper: true, onChange: onChange, current: parseInt(current), total: total, pageSize: 10 }), document.getElementById('pager'));
}

module.exports = {
	init: function init(total, current) {
		render(total, current);
	}
};

});
