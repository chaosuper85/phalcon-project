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

/*!admin/module/user/adduser.jsx*/
;define('admin/module/user/adduser', function(require, exports, module) {

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

/*!admin/partial/userinfo/userinfo.jsx*/
;define('admin/partial/userinfo/userinfo', function(require, exports, module) {

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
