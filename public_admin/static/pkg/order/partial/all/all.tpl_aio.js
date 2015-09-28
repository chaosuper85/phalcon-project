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

/*!order/module/pop_orderManager/pop_orderManager.jsx*/
;define('order/module/pop_orderManager/pop_orderManager', function(require, exports, module) {

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

/*!order/partial/all/all.jsx*/
;define('order/partial/all/all', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var pop_orderManager = require('order/module/pop_orderManager/pop_orderManager');

var confirm = antd.confirm;
var message = antd.message;

var params = undefined;
var search_type = "enterprisename";
var search_type_val = "";
var search_type_name = ['enterprisename', 'order_freightagent_mobile', 'order_teammobile', 'supervisor_name'];

function render() {

	var Select = antd.Select;
	var Option = Select.Option;

	React.render(React.createElement(Select, { defaultValue: search_type, style: { width: 100 }, onChange: handleChange, placeholder: "查询类型" }, React.createElement(Option, { value: "enterprisename" }, "公司名"), React.createElement(Option, { value: "order_freightagent_mobile" }, "货代手机号"), React.createElement(Option, { value: "order_teammobile" }, "车队手机号"), React.createElement(Option, { value: "supervisor_name" }, "跟单员")), document.getElementById('filter_search_type'));
	function handleChange(value) {
		search_type = value;
	}

	bindEvent();
}

function bindEvent() {
	// 添加跟单员
	var pop;
	util.get('/api/ordersuper/orderAdmins?page_size=100').then(function (json) {
		if (json.error_code == 0) {
			pop = new pop_orderManager(json.data.pageInfo);
		} else {
			message.error('跟单员数据请求失败');
		}
	}, function (error) {
		console.log(error);
	});
	var $editManager = document.querySelectorAll('.editManager');
	util.bind($editManager, 'click', function (e) {
		try {
			pop.show(this.getAttribute('data-id'), this.getAttribute('data-uid'));
		} catch (e) {
			message.error('正在请求数据跟单员数据');
		}
	});

	document.getElementById('clearAll').addEventListener('click', function () {
		var status = util.getParam('order_status');
		var query = '';
		if (status) {
			query += '?order_status=' + status;
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
		search_type_name.map(function (name) {
			if (params.hasOwnProperty(name)) {
				params[name] = '';
			}
		});
		params[search_type] = $search_input.value;
		util.redirectParam(params);
	});
}

module.exports = {
	init: function init(param) {
		params = param || {};
		search_type_name.map(function (name) {
			if (params.hasOwnProperty(name)) {
				search_type = name;
				search_type_val = params[name];
			}
		});
		render();
	}
};

});
