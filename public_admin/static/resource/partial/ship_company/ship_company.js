define('resource/partial/ship_company/ship_company', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var ShipCom = require('resource/module/ship_com/ship_com');

var message = antd.message;
var Select = antd.Select;
var Option = Select.Option;

var pop = document.createElement("div");
pop.id = 'pop_shipcom';
document.getElementById('popups').appendChild(pop);

module.exports = {
	init: function init() {
		var pop_edit = React.render(React.createElement(ShipCom, null), pop);
		util.bind(document.querySelectorAll('.ship_edit'), 'click', function () {
			var td = this.parentNode,
			    data = {
				id: this.getAttribute('data-id'),
				name_zh: util.siblings(td, 'name_zh')[0].innerHTML,
				name_en: util.siblings(td, 'name_en')[0].innerHTML,
				contact_name: util.siblings(td, 'contact_name')[0].innerHTML,
				mobile: util.siblings(td, 'mobile')[0].innerHTML,
				address: util.siblings(td, 'address')[0].innerHTML
			};
			pop_edit.show(data, 'edit');
		});

		util.bind(document.getElementById('add_com'), 'click', function () {
			pop_edit.show({
				name_zh: '',
				name_en: '',
				contact_name: '',
				mobile: '',
				address: ''
			}, 'add');
		});
	}
};

});
