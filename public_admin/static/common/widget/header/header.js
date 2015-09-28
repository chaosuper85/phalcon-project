define('common/widget/header/header', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var message = antd.message;

module.exports = function () {

	util.bind(document.getElementById('logout'), 'click', function () {
		util.get('/api/logOut').then(function (json) {
			if (json.error_code == 0) {
				message.success('退出成功');
				location.href = '/login';
			} else {
				message.error(json.error_msg);
			}
		}, function (error) {
			message.error(error);
		});
	});
};

});
