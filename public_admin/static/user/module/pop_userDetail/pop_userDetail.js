define('user/module/pop_userDetail/pop_userDetail', function(require, exports, module) {

/*
 * 跟单总览 添加/更换跟单员
 */

'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var util = require('common/module/util/util');
var api = require('user/module/api/api');
var txt = {
	pass: "通过",
	reject: "驳回",
	lock: "锁定",
	unlock: "解锁",
	del: "删除"
};

var Modal = antd.Modal;
var message = antd.message;
var confirm = antd.confirm;

var pop = document.createElement("div");
pop.id = 'pop_userDetail';
document.getElementById('popups').appendChild(pop);

var Popup = React.createClass({ displayName: "Popup",
	getInitialState: function getInitialState() {
		return {
			visible: false,
			load: {
				pass: false,
				reject: false,
				lock: false,
				unlock: false,
				del: false
			}
		};
	},
	show: function show(data) {
		console.log(data);
		this.props.data = data;
		this.setState({
			visible: true
		});
	},
	handleOk: function handleOk() {
		this.setState({ visible: false });
	},
	onChange: function onChange(e) {
		// console.log(e.target.value);
		this.setState({
			value: e.target.value
		});
	},
	actions: function actions(key) {
		var _this = this;

		var url = '';
		if (this.props.type == 'agent') {
			url = api.agent[key] + '?id[]=' + this.props.data.team.id;
		} else {
			url = api.team[key] + '?id[]=' + this.props.data.team.id;
		}

		confirm({
			title: txt[key],
			content: '确认 "' + txt[key] + '" 该用户？',
			onOk: function onOk() {
				message.info('正在发送请求...', 3);
				return new Promise(function (resolve) {
					util.get(url).then(function (json) {
						console.log(json);

						if (json.error_code == 0) {
							message.success(json.error_msg, 3);
							location.reload();
						} else {
							message.error(json.error_msg, 3);
						}
						_this.setState({
							load: _defineProperty({}, key, false)
						});
						resolve();
					}, function (error) {
						_this.setState({
							load: _defineProperty({}, key, false)
						});
						resolve();
					});
				});
			}
		});

		this.setState({
			load: _defineProperty({}, key, true),
			visible: false
		});
	},

	render: function render() {
		if (this.props.type == 'agent') {
			var pic = '';
			if (this.props.data.team.cargo_pic) {
				pic = React.createElement("div", null, React.createElement("a", { href: this.props.data.team.cargo_pic, target: "_blank" }, React.createElement("img", { src: this.props.data.team.cargo_pic })));
			} else {
				pic = '未上传';
			}
			return React.createElement("div", null, React.createElement("button", { className: "ant-btn ant-btn-primary", onClick: this.showModal }, "显示对话框"), React.createElement(Modal, { title: "货代详情：" + this.props.data.com.enterprise_name, width: "800", visible: this.state.visible, onOk: this.handleOk, onCancel: this.handleCancel,
				footer: [React.createElement("button", { key: "pass", className: 'ant-btn ant-btn-primary ' + (this.state.load.pass ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'pass') }, "通过"), React.createElement("button", { key: "reject", className: 'ant-btn ant-btn-primary ' + (this.state.load.reject ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'reject') }, "驳回"), React.createElement("button", { key: "lock", className: 'ant-btn ant-btn-primary ' + (this.state.load.lock ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'lock') }, "锁定"), React.createElement("button", { key: "unlock", className: 'ant-btn ant-btn-primary ' + (this.state.load.unlock ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'unlock') }, "解锁"), React.createElement("button", { key: "del", className: 'ant-btn ant-btn-primary ' + (this.state.load.del ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'del') }, "删除")] }, React.createElement("div", { className: "user-detail" }, React.createElement("div", { className: "wrap-left" }, React.createElement("dl", null, React.createElement("dd", null, React.createElement("div", { className: "title" }, "货代名称"), React.createElement("div", { className: "content" }, this.props.data.com.enterprise_name ? this.props.data.com.enterprise_name : '无')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "企业类型"), React.createElement("div", { className: "content" }, "货代")), React.createElement("dd", null, React.createElement("div", { className: "title" }, "所在城市"), React.createElement("div", { className: "content" }, this.props.data.com.city_id)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "成立时间"), React.createElement("div", { className: "content" }, this.props.data.com.established_date ? this.props.data.com.established_date : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "营业执照号"), React.createElement("div", { className: "content" }, this.props.data.com.enterprise_licence)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "营业执照"), React.createElement("div", { className: "content" }, React.createElement("a", { href: this.props.data.com.cargo_pic, target: "_blank" }, React.createElement("img", { src: this.props.data.com.cargo_pic })))))), React.createElement("div", { className: "wrap-right" }, React.createElement("dl", null, React.createElement("dd", null, React.createElement("div", { className: "title" }, "用户名"), React.createElement("div", { className: "content" }, this.props.data.user.username)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "注册手机"), React.createElement("div", { className: "content" }, this.props.data.user.mobile)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "真实姓名"), React.createElement("div", { className: "content" }, this.props.data.user.real_name ? this.props.data.user.real_name : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "联系手机"), React.createElement("div", { className: "content" }, this.props.data.user.contactNumber ? this.props.data.user.contactNumber : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "座机"), React.createElement("div", { className: "content" }, this.props.data.user.telephone_number ? this.props.data.user.telephone_number : '未填写')))))));
		} else {
			return React.createElement("div", null, React.createElement("button", { className: "ant-btn ant-btn-primary", onClick: this.showModal }, "显示对话框"), React.createElement(Modal, { title: "车队详情：" + this.props.data.com.enterprise_name, width: "800", visible: this.state.visible, onOk: this.handleOk, onCancel: this.handleCancel,
				footer: [React.createElement("button", { key: "pass", className: 'ant-btn ant-btn-primary ' + (this.state.load.pass ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'pass') }, "通过"), React.createElement("button", { key: "reject", className: 'ant-btn ant-btn-primary ' + (this.state.load.reject ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'reject') }, "驳回"), React.createElement("button", { key: "lock", className: 'ant-btn ant-btn-primary ' + (this.state.load.lock ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'lock') }, "锁定"), React.createElement("button", { key: "unlock", className: 'ant-btn ant-btn-primary ' + (this.state.load.unlock ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'unlock') }, "解锁"), React.createElement("button", { key: "del", className: 'ant-btn ant-btn-primary ' + (this.state.load.del ? 'ant-btn-loading' : ''), onClick: this.actions.bind(this, 'del') }, "删除")] }, React.createElement("div", { className: "user-detail" }, React.createElement("div", { className: "wrap-left" }, React.createElement("dl", null, React.createElement("dd", null, React.createElement("div", { className: "title" }, "车队名称"), React.createElement("div", { className: "content" }, this.props.data.com.enterprise_name ? this.props.data.com.enterprise_name : '无')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "企业类型"), React.createElement("div", { className: "content" }, "车队")), React.createElement("dd", null, React.createElement("div", { className: "title" }, "所在城市"), React.createElement("div", { className: "content" }, this.props.data.com.city_id)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "成立时间"), React.createElement("div", { className: "content" }, this.props.data.com.established_date ? this.props.data.com.established_date : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "营业执照号"), React.createElement("div", { className: "content" }, this.props.data.com.enterprise_licence)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "营业执照"), React.createElement("div", { className: "content" }, React.createElement("a", { href: this.props.data.com.cargo_pic, target: "_blank" }, React.createElement("img", { src: this.props.data.com.cargo_pic })))))), React.createElement("div", { className: "wrap-right" }, React.createElement("dl", null, React.createElement("dd", null, React.createElement("div", { className: "title" }, "用户名"), React.createElement("div", { className: "content" }, this.props.data.user.username)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "注册手机"), React.createElement("div", { className: "content" }, this.props.data.user.mobile)), React.createElement("dd", null, React.createElement("div", { className: "title" }, "真实姓名"), React.createElement("div", { className: "content" }, this.props.data.user.real_name ? this.props.data.user.real_name : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "联系手机"), React.createElement("div", { className: "content" }, this.props.data.user.contactNumber ? this.props.data.user.contactNumber : '未填写')), React.createElement("dd", null, React.createElement("div", { className: "title" }, "座机"), React.createElement("div", { className: "content" }, this.props.data.user.telephone_number ? this.props.data.user.telephone_number : '未填写')))))));
		}
	}
});

var Pop_userDetail = (function () {
	function Pop_userDetail(data, type) {
		_classCallCheck(this, Pop_userDetail);

		this.pop = React.render(React.createElement(Popup, { data: data, type: type }), pop);
	}

	_createClass(Pop_userDetail, [{
		key: 'showDetail',
		value: function showDetail(data, type) {
			this.pop.show(data);
		}
	}]);

	return Pop_userDetail;
})();

module.exports = Pop_userDetail;

});
