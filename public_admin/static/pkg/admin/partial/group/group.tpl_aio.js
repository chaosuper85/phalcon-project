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

/*!admin/module/group/pop_alluser.jsx*/
;define('admin/module/group/pop_alluser', function(require, exports, module) {

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

/*!admin/partial/group/group.jsx*/
;define('admin/partial/group/group', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var popalluser = require('admin/module/group/pop_alluser');

var message = antd.message;
var pop = undefined;
var alluser = undefined;

(function boot() {
	var uls = document.querySelectorAll('.user_list'),
	    navs = document.querySelectorAll('.role_nav .item');
	if (location.href.split("#")[1]) {
		util.addClass(uls[location.href.split("#")[1]], 'active');
		util.addClass(navs[location.href.split("#")[1]], 'active');
	} else {
		util.addClass(uls[0], 'active');
		util.addClass(navs[0], 'active');
	}

	util.bind(document.querySelectorAll('.role_nav .item'), 'click', function () {
		var obj = this,
		    index = obj.getAttribute('data-index'),
		    ul = uls[index];

		util.removeSiblingsClass(obj, 'active');
		util.addClass(obj, 'active');

		util.removeSiblingsClass(ul, 'active');
		util.addClass(ul, 'active');

		window.location.href = location.pathname + '#' + index;
	});

	var loadUser = false;
	util.bind(document.querySelectorAll('.add_user'), 'click', function () {
		var obj = this,
		    id = obj.getAttribute('data-id');

		if (!pop) {
			if (loadUser) {
				message.info('请稍候，正在加载用户数据');
				return;
			}
			message.info('正在加载用户数据');
			loadUser = true;
			util.get('/api/account/userList').then(function (json) {
				if (json.error_code == 0) {
					pop = new popalluser(json.data.data);
					pop.show(id);
				} else {
					message.error(json.error_msg);
				}
				loadUser = false;
			}, function (error) {
				message.error(error);
				loadUser = false;
			});
		} else {
			pop.show(id);
		}
	});

	util.bind(document.querySelectorAll('.remove_user'), 'click', function () {
		var obj = this,
		    uid = obj.getAttribute('data-uid'),
		    id = obj.getAttribute('data-id');
		if (!uid || !id) {
			message.error('出错啦，请刷新重试');
			return;
		}
		util.post('/api/acl/delGroupUser', {
			group_id: id,
			id: uid
		}).then(function (json) {
			if (json.error_code == 0) {
				message.success('移除成功');
				window.location.reload();
			} else {
				message.error(json.error_msg);
			}
		}, function (error) {
			message.error('添加失败，请重试');
		});
	});
})();

});
