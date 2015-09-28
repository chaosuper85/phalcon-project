define('user/partial/carteam/carteam', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
require('common/widget/table/table');
var pop_userDetail = require('user/module/pop_userDetail/pop_userDetail');
var api = require('user/module/api/api');

var confirm = antd.confirm;
var message = antd.message;

var params = undefined;
var search_type = "team_name";
var search_type_val = "";
var search_type_name = ['team_name', 'name', 'mobile'];

function render() {

	var Select = antd.Select;
	var Option = Select.Option;

	React.render(React.createElement(Select, { defaultValue: search_type, style: { width: 80 }, onChange: handleChange, placeholder: "查询类型" }, React.createElement(Option, { value: "team_name" }, "车队名称"), React.createElement(Option, { value: "mobile" }, "手机号"), React.createElement(Option, { value: "name" }, "用户名")), document.getElementById('filter_search_type'));
	function handleChange(value) {
		search_type = value;
	}

	React.render(React.createElement(Select, { style: { width: 100 }, onChange: handleChange, placeholder: "平台", disabled: true }, React.createElement(Option, { value: "PC" }, "PC"), React.createElement(Option, { value: "Android" }, "Android"), React.createElement(Option, { value: "iOS" }, "iOS")), document.getElementById('filter_platform'));

	React.render(React.createElement(Select, { style: { width: 100 }, onChange: handleChange, placeholder: "版本号", disabled: true }, React.createElement(Option, { value: "1.0" }, "1.0"), React.createElement(Option, { value: "2.0" }, "2.0"), React.createElement(Option, { value: "3.0" }, "3.0")), document.getElementById('filter_version'));

	bindEvent();
}

function bindEvent() {

	document.getElementById('clearAll').addEventListener('click', function () {
		var status = util.getParam('status');
		var query = '';
		if (status) {
			query += '?status=' + status;
		}
		location.href = window.location.pathname + query;
	});

	var $search_input = document.querySelector('.filter-input');
	$search_input.value = search_type_val;
	document.getElementById('search').addEventListener('click', function () {
		if (!$search_input.value) {
			message.error('请输入搜索内容');
			return;
		}
		params.name = "";
		params.mobile = "";
		params[search_type] = $search_input.value;
		util.redirectParam(params);
	});

	// 查看详情
	var $detail = document.querySelectorAll('.btn_detail');
	var pop = undefined;
	util.bind($detail, 'click', function () {
		var _this = this;

		var id = this.getAttribute('data-id');
		this.innerHTML = '<i class="iconfont icon-loadc loading"></i>';
		util.get('/api/carTeam/auditDetail?id=' + id).then(function (json) {
			setTimeout(function () {
				_this.innerHTML = '查看';
			}, 500);
			if (json.error_code == 0) {
				if (!json.data) {
					message.info('没有找到待审核信息');
					return;
				}
				if (!pop) pop = new pop_userDetail(json.data, 'carteam');
				pop.showDetail(json.data, 'carteam');
			} else {
				message.error(json.error_msg);
			}
		}, function (error) {
			_this.innerHTML = '查看';
		});
	});

	var txt = {
		pass: "通过",
		reject: "驳回",
		lock: "锁定",
		unlock: "解锁",
		del: "删除"
	};
	util.bind(document.querySelectorAll('.table-functions .btn'), 'click', function () {
		var objs = document.querySelectorAll('.checkbox_data:checked');
		var ids = [];
		Array.from(objs).forEach(function (item) {
			ids.push(item.getAttribute('data-id'));
		});
		if (ids.length == 0) {
			message.info('请选择');
			return;
		}
		var type = this.getAttribute('data-type');
		confirm({
			title: txt[type],
			content: '确认 "' + txt[type] + '" 选中' + ids.length + '个用户？',
			onOk: function onOk() {
				message.info('正在发送请求...', 3);
				return new Promise(function (resolve) {
					$.ajax({
						type: 'GET',
						dataType: 'json',
						url: api.team[type],
						data: {
							id: ids
						},
						success: function success(data) {
							console.log(data);
							if (data.error_code == 0) {
								message.success(data.error_msg, 3);
								location.reload();
							} else {
								message.error(data.error_msg, 3);
							}
							resolve();
						}
					});
				});
			}
		});
	});
}

module.exports = {
	init: function init(param) {
		params = param || '';
		search_type_name.map(function (name) {
			if (params.hasOwnProperty(name) && params[name]) {
				search_type = name;
				search_type_val = params[name];
			}
		});
		render();
	}
};

});
