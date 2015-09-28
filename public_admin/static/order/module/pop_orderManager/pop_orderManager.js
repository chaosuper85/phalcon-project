define('order/module/pop_orderManager/pop_orderManager', function(require, exports, module) {

/*
 * 跟单总览 添加/更换跟单员
 */

'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var util = require('common/module/util/util');
var Modal = antd.Modal;
var Radio = antd.Radio;
var RadioGroup = antd.Radio.Group;
var message = antd.message;

var pop = document.createElement("div");
pop.id = 'pop_orderManager';
document.getElementById('popups').appendChild(pop);

var data_manager = {};

var Popup = React.createClass({ displayName: "Popup",
	getInitialState: function getInitialState() {
		return {
			visible: false
		};
	},
	show: function show() {
		this.setState({
			visible: true
		});
	},
	handleOk: function handleOk() {
		var _this = this;

		if (!this.state.value) {
			message.error('请选择跟单员');
			return;
		}
		util.post('/api/ordersuper/changeOrderAdmin', {
			admin_userid: this.state.value,
			orderid: this.state.id
		}).then(function (json) {
			window.location.reload();
			_this.setState({ visible: false });
		}, function (error) {
			_this.setState({ visible: false });
		});
	},
	onChange: function onChange(e) {
		// console.log(e.target.value);
		this.setState({
			value: e.target.value
		});
	},
	setId: function setId(id, uid) {
		this.setState({
			id: id,
			value: uid
		});
	},
	render: function render() {
		return React.createElement("div", null, React.createElement("button", { className: "ant-btn ant-btn-primary", onClick: this.showModal }, "显示对话框"), React.createElement(Modal, { title: "选择跟单员",
			visible: this.state.visible,
			onOk: this.handleOk,
			onCancel: this.handleCancel }, React.createElement(RadioGroup, { onChange: this.onChange, value: this.state.value, "data-id": this.state.id }, data_manager.data.map(function (p) {
			return React.createElement(Radio, { value: p.id }, p.username);
		}))));
	}
});

var Pop_orderManager = (function () {
	function Pop_orderManager(data) {
		_classCallCheck(this, Pop_orderManager);

		data_manager = data;
		this.pop = React.render(React.createElement(Popup, null), pop);
	}

	_createClass(Pop_orderManager, [{
		key: 'show',
		value: function show(id, uid) {
			this.pop.setId(id, uid);
			this.pop.show();
		}
	}]);

	return Pop_orderManager;
})();

module.exports = Pop_orderManager;

});
