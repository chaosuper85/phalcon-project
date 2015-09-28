define('admin/module/user/adduser', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');

var Modal = antd.Modal;
var message = antd.message;

var init_data = {
	name: '',
	pwd: '',
	real_name: '',
	mobile: '',
	email: ''
};

var Addform = React.createClass({ displayName: "Addform",
	getInitialState: function getInitialState() {
		return {
			data: this.props.userdata
		};
	},
	handleChange: function handleChange(type) {
		this.props.userdata[type] = event.target.value;
		this.setState({
			data: this.props.userdata
		});
	},
	render: function render() {
		return React.createElement("dl", { className: "x-form" }, React.createElement("dd", null, React.createElement("span", { className: "title" }, React.createElement("i", null, "*"), "用户名"), React.createElement("div", { className: "content" }, this.props.type == 'add' ? React.createElement("input", { type: "text", value: this.props.userdata.name, onChange: this.handleChange.bind(this, 'name') }) : React.createElement("p", null, this.props.userdata.name))), React.createElement("dd", null, React.createElement("span", { className: "title" }, React.createElement("i", null, "*"), "真实姓名"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.userdata.real_name, onChange: this.handleChange.bind(this, 'real_name') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, this.props.type == 'add' ? React.createElement("i", null, "*") : '', "密码"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "password", value: this.props.userdata.pwd, onChange: this.handleChange.bind(this, 'pwd') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, "手机号"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.userdata.mobile, onChange: this.handleChange.bind(this, 'mobile') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, "Email"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.userdata.email, onChange: this.handleChange.bind(this, 'email') }))));
	}
});

var addUser = React.createClass({ displayName: "addUser",
	getInitialState: function getInitialState() {
		return {
			visible: false,
			loading: false,
			data: init_data,
			type: 'edit'
		};
	},
	show: function show(data, type) {
		this.setState({
			visible: true,
			loading: false,
			data: data,
			type: type
		});
	},
	handleOk: function handleOk() {
		var _this = this;

		var url = '/api/account/add';
		if (this.state.type == 'add') {
			if (!this.state.data.name || !this.state.data.real_name || !this.state.data.pwd) {
				message.error('必填项未填');
				this.setState({ loading: false });
				return;
			}
		} else {
			if (!this.state.data.name || !this.state.data.real_name) {
				message.error('必填项未填');
				this.setState({ loading: false });
				return;
			}
			url = '/api/account/alter';
		}
		this.setState({
			visible: true,
			loading: true
		});
		util.post(url, this.state.data).then(function (json) {
			_this.setState({
				loading: false,
				visible: false
			});
			location.reload();
		}, function (error) {
			_this.setState({ loading: false });
			message.error(error);
		});
	},
	handleCancel: function handleCancel() {
		this.setState({
			visible: false,
			loading: false
		});
	},
	render: function render() {
		return React.createElement("div", null, React.createElement(Modal, { title: this.state.type == 'edit' ? '编辑用户' : '添加用户',
			visible: this.state.visible,
			onOk: this.handleOk,
			footer: [React.createElement("button", { key: "back", className: "ant-btn ant-btn-lg", onClick: this.handleCancel }, "返 回"), React.createElement("button", { key: "submit", className: 'ant-btn ant-btn-primary ant-btn-lg ' + (this.state.loading ? 'ant-btn-loading' : ''), onClick: this.handleOk }, "提 交")] }, React.createElement(Addform, { userdata: this.state.data, type: this.state.type })));
	}
});

module.exports = addUser;

});
