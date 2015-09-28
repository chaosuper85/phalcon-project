define('admin/module/group/pop_alluser', function(require, exports, module) {

/*
 * 用户分组/显示全部用户
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
pop.id = 'pop_alluser';
document.getElementById('popups').appendChild(pop);

var data_user = [];

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

		var uid = event.target.getAttribute('data-id'),
		    id = this.state.id;
		if (!uid || !id) {
			message.error('出错啦，请刷新重试');
			return;
		}
		util.post('/api/acl/addGroupUser', {
			group_id: id,
			id: uid
		}).then(function (json) {
			if (json.error_code == 0) {
				message.success('添加成功');
				_this.setState({ visible: false });
				window.location.reload();
			} else {
				message.error(json.error_msg);
			}
		}, function (error) {
			message.error('添加失败，请重试');
			_this.setState({ visible: false });
		});
	},
	setId: function setId(id) {
		this.setState({
			id: id
		});
	},
	render: function render() {
		var _this2 = this;

		return React.createElement("div", null, React.createElement(Modal, { title: "向该组添加一个用户",
			visible: this.state.visible,
			onOk: this.handleOk,
			footer: '',
			onCancel: this.handleCancel }, React.createElement("ul", { className: "pop_all_user clearfix" }, data_user.map(function (p) {
			return React.createElement("li", { "data-id": p.id, onClick: _this2.handleOk }, p.real_name ? p.username + '-(' + p.real_name + ')' : p.username);
		}))));
	}
});

var Pop_alluser = (function () {
	function Pop_alluser(data) {
		_classCallCheck(this, Pop_alluser);

		data_user = data;
		this.pop = React.render(React.createElement(Popup, null), document.getElementById('pop_alluser'));
	}

	_createClass(Pop_alluser, [{
		key: 'show',
		value: function show(id) {
			this.pop.setId(id);
			this.pop.show();
		}
	}]);

	return Pop_alluser;
})();

module.exports = Pop_alluser;

});
