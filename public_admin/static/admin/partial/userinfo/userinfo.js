define('admin/partial/userinfo/userinfo', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var AddUser = require('admin/module/user/adduser');

var message = antd.message;
var Select = antd.Select;
var Option = Select.Option;

var pop = document.createElement("div");
pop.id = 'pop_addUser';
document.getElementById('popups').appendChild(pop);

module.exports = {
	init: function init() {
		var _pop = React.render(React.createElement(AddUser, null), pop);
		util.bind(document.querySelectorAll('.user_edit'), 'click', function () {
			var td = this.parentNode,
			    data = {
				id: util.siblings(td, 'user_id')[0].innerHTML,
				name: util.siblings(td, 'user_name')[0].innerHTML,
				pwd: '',
				real_name: util.siblings(td, 'user_real')[0].innerHTML,
				mobile: util.siblings(td, 'user_mobile')[0].innerHTML,
				email: util.siblings(td, 'user_email')[0].innerHTML
			};
			console.log(data);
			_pop.show(data, 'edit');
		});

		util.bind(document.getElementById('add-new'), 'click', function () {
			_pop.show({
				name: '',
				pwd: '',
				real_name: '',
				mobile: '',
				email: ''
			}, 'add');
		});
	}
};

});
