/*!common/module/util/util.jsx*/
;define('common/module/util/util', function(require, exports, module) {

/*
 * 基础组件
 * by kenny
 */
'use strict';

var message = antd.message;

var util = {

    post: function post(url, data, isJson) {
        var _this = this;

        var promise = new Promise(function (resolve, reject) {
            var client = new XMLHttpRequest();
            client.open('POST', url, true);
            client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            client.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            client.setRequestHeader("Accept", "application/json");
            client.onload = handler;
            client.responseType = "json";
            if (isJson) {
                client.send(JSON.stringify(data));
            } else {
                client.send(_this.getUrl(data));
            }

            function handler() {
                if (this.status === 200) {
                    if (this.response.error_code == 0) {
                        message.success('操作成功');
                        resolve(this.response);
                    } else {
                        message.error(this.response.error_msg);
                    }
                } else {
                    message.error('网络错误');
                    reject(new Error(this.statusText));
                }
            }
        });
        return promise;
    },

    get: function get(url) {
        var promise = new Promise(function (resolve, reject) {
            var client = new XMLHttpRequest();
            client.open('GET', url, true);
            client.onload = handler;
            client.responseType = "json";
            client.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            client.setRequestHeader("Accept", "application/json");
            client.send();

            function handler() {
                if (this.status === 200) {
                    resolve(this.response);
                } else {
                    reject(new Error(this.statusText));
                }
            }
        });
        return promise;
    },

    getParam: function getParam() {

        var url = window.location.href;

        if (url.indexOf("?") > -1) {
            var query = url.slice(url.indexOf("?") + 1);
        } else if (url.indexOf("#") > -1) {
            var query = url.slice(url.indexOf("#") + 1);
        } else {
            return {};
        }

        if (query == "") {
            return {};
        }

        var param = {};
        var p = query.split("&");

        for (var i = 0; i < p.length; i++) {
            var q = p[i].split("=");
            if (!q[1]) {
                continue;
            }
            var idx = q[1].indexOf("#");
            q[1] = idx > -1 ? q[1].slice(0, idx) : q[1];
            param[q[0]] = q[1];
        }
        return param;
    },

    getTheParam: function getTheParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        }
        return null;
    },

    redirectParam: function redirectParam(params) {
        location.href = window.location.pathname + '?' + util.getUrl(params);
    },

    /**
     * 将json对象转化为url字串格式
     * json: json对象
     * encode: 编码函数，如:encodeURIComponent
     */
    getUrl: function getUrl(json, encode) {
        var s = [];
        encode = encode || function (v) {
            return v;
        };
        for (var n in json) {
            var value = json[n];
            if (value != '' && value != null && typeof value != 'undefined') {
                s.push(n + "=" + encode(value));
            }
        }
        return s.join("&");
    },
    /**
     * 输入时间戳 转化为2015-1-1
     */
    getDate: function getDate(timestamp) {
        var day = timestamp.getDate();
        var month = timestamp.getMonth() + 1;
        var year = timestamp.getFullYear();

        return year + '-' + month + '-' + day;
    },

    /*
     * event
     */
    bind: function bind(el, eventName, eventHandler) {
        if (!el) return;
        if (el.length !== undefined) {
            if (typeof Array.from !== 'undefined') {
                Array.from(el, function (_el, i) {
                    _el.addEventListener(eventName, eventHandler);
                });
            } else {
                for (var i = 0; i < el.length; i++) {
                    el[i].addEventListener(eventName, eventHandler);
                };
            }
        } else {
            el.addEventListener(eventName, eventHandler);
        }
    },

    unbind: function unbind(el, eventName, eventHandler) {
        if (!el) return;
        if (el.length !== undefined) {
            if (typeof Array.from !== 'undefined') {
                Array.from(el, function (_el, i) {
                    _el.removeEventListener(eventName, eventHandler);
                });
            } else {
                for (var i = 0; i < el.length; i++) {
                    el[i].removeEventListener(eventName, eventHandler);
                };
            }
        } else {
            el.removeEventListener(eventName, eventHandler);
        }
    },
    /*
     * class相关
     */
    removeSiblingsClass: function removeSiblingsClass(el, className) {
        var me = this;
        var siblings = me.siblings(el);
        siblings.forEach(function (item, i) {
            if (me.hasClass(item, className)) {
                me.removeClass(item, className);
            }
        });
    },

    hasClass: function hasClass(el, className) {
        if (el.classList) {
            return el.classList.contains(className);
        } else {
            return new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
        }
    },

    addClass: function addClass(el, className) {
        if (el.classList) {
            el.classList.add(className);
        } else {
            el.className += ' ' + className;
        }
    },

    removeClass: function removeClass(el, className) {
        if (el.classList) {
            el.classList.remove(className);
        } else {
            el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        }
    },

    siblings: function siblings(el, className) {
        var _this2 = this;

        return Array.prototype.filter.call(el.parentNode.children, function (child) {
            if (className) {
                if (_this2.hasClass(child, className)) {
                    return child !== el;
                }
            } else {
                return child !== el;
            }
        });
    },

    trigger: function trigger(el, _event) {
        var event = document.createEvent('HTMLEvents');
        event.initEvent(_event, true, false);
        el.dispatchEvent(event);
    }

};

module.exports = util;

});

/*!common/widget/table/table.jsx*/
;define('common/widget/table/table', function(require, exports, module) {

/*
 * 通用table事件
 */

"use strict";

module.exports = (function () {
	var table = $(".x-table");

	// radio事件
	var radios = table.find("tbody input[type=checkbox]"),
	    radio_all = table.find(".radio-all"),
	    trs = table.find("tbody tr");

	radio_all.click(function () {
		var val = this.checked;
		radios.prop("checked", val);
		if (val) {
			trs.addClass('active');
		} else {
			trs.removeClass('active');
		}
	});

	radios.change(function () {
		var obj = $(this),
		    val = this.checked,
		    tr = obj.parents('tr');

		if (val) {
			tr.addClass('active');
		} else {
			tr.removeClass('active');
		}
	});
})();

});

/*!user/module/api/api.jsx*/
;define('user/module/api/api', function(require, exports, module) {

"use strict";

module.exports = {
	agent: {
		pass: "/api/agent/auditPass",
		reject: "/api/agent/auditReject",
		lock: "/api/agent/lockAgent",
		unlock: "/api/agent/unlockAgent",
		del: "/api/agent/delAgent"
	},
	team: {
		pass: "/api/carTeam/auditPass",
		reject: "/api/carTeam/auditReject",
		lock: "/api/carTeam/lockCarTeam",
		unlock: "/api/carTeam/unlockCarTeam",
		del: "/api/carTeam/delCarTeam"
	}
};

});

/*!user/module/pop_userDetail/pop_userDetail.jsx*/
;define('user/module/pop_userDetail/pop_userDetail', function(require, exports, module) {

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

/*!user/partial/carteam/carteam.jsx*/
;define('user/partial/carteam/carteam', function(require, exports, module) {

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
