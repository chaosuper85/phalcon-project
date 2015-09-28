define('resource/module/ship_com/ship_com', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var Modal = antd.Modal;
var message = antd.message;

var init_data = {
	name_zh: '',
	name_en: '',
	contact_name: '',
	mobile: '',
	address: ''
};

var Formcom = React.createClass({ displayName: "Formcom",
	getInitialState: function getInitialState() {
		return {
			data: init_data
		};
	},

	handleChange: function handleChange(type) {
		this.props.data[type] = event.target.value;
		this.setState({
			data: this.props.data
		});
	},

	render: function render() {
		return React.createElement("dl", { className: "x-form" }, React.createElement("dd", null, React.createElement("span", { className: "title" }, React.createElement("i", null, "*"), "船中文名"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.data.name_zh, onChange: this.handleChange.bind(this, 'name_zh') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, React.createElement("i", null, "*"), "船英文名"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.data.name_en, onChange: this.handleChange.bind(this, 'name_en') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, "联系人"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.data.contact_name, onChange: this.handleChange.bind(this, 'contact_name') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, "联系人电话"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.data.mobile, onChange: this.handleChange.bind(this, 'mobile') }))), React.createElement("dd", null, React.createElement("span", { className: "title" }, "联系地址"), React.createElement("div", { className: "content" }, React.createElement("input", { type: "text", value: this.props.data.address, onChange: this.handleChange.bind(this, 'address') }))));
	}
});

var shipCom = React.createClass({ displayName: "shipCom",
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

		if (!this.state.data.name_zh || !this.state.data.name_en) {
			message.error('必填项未填');
			return;
		}
		var url = '/api/ship/addCompany';
		if (this.state.type == 'edit') {
			url = '/api/ship/alterCompany';
		}
		this.setState({ loading: true, visible: true });
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
		return React.createElement("div", null, React.createElement(Modal, { title: this.state.type == 'edit' ? '编辑船公司信息' : '添加船公司',
			visible: this.state.visible,
			onOk: this.handleOk,
			footer: [React.createElement("button", { key: "back", className: "ant-btn ant-btn-lg", onClick: this.handleCancel }, "返 回"), React.createElement("button", { key: "submit", className: 'ant-btn ant-btn-primary ant-btn-lg ' + (this.state.loading ? 'ant-btn-loading' : ''), onClick: this.handleOk }, "提 交")] }, React.createElement(Formcom, { data: this.state.data })));
	}
});

module.exports = shipCom;

});
