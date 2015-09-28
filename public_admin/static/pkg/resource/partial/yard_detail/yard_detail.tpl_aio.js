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

/*!resource/module/citycode/citycode.jsx*/
;define('resource/module/citycode/citycode', function(require, exports, module) {

/*
 * 高德地图citycode
 */

"use strict";

module.exports = {
	'tianjin': {
		"code": "022",
		"city": "天津"
	},
	'shanghai': {
		"code": "021",
		"city": "上海"
	},
	'beijing': {
		"code": "010",
		"city": "北京"
	},
	'qingdao': {
		"code": "0532",
		"city": "青岛"
	}
};

});

/*!resource/partial/yard_detail/yard_detail.jsx*/
;define('resource/partial/yard_detail/yard_detail', function(require, exports, module) {

'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var util = require('common/module/util/util');
var city = require('resource/module/citycode/citycode');

var message = antd.message;
var Select = antd.Select;
var Option = Select.Option;
var confirm = antd.confirm;

// 全局变量
var map,
    yard_id,
    search_txt = '',
    citySelector_table,
    searchSuggestion,
    citySelector_map,
    city_this = 'tianjin',
    markers = {
	'location_type_1': '',
	'location_type_2': '',
	'location_type_3': '',
	'location_type_4': '',
	'location_type_5': ''
},
    points = {
	'location_type_1': '',
	'location_type_2': '',
	'location_type_3': '',
	'location_type_4': '',
	'location_type_5': ''
},
    txt = {
	'location_type_1': '重车入口',
	'location_type_2': '重车出口',
	'location_type_3': '轻车入口',
	'location_type_4': '轻车出口',
	'location_type_5': '堆场中心'
};

// 城市选择器
var CitySelect = React.createClass({ displayName: "CitySelect",
	getInitialState: function getInitialState() {
		var city = this.props.city;
		return {
			value: city
		};
	},
	// 城市选择器双向绑定
	changeCity: function changeCity(value) {
		city_this = value;
		citySelector_table.setVal(value);
		citySelector_map.setVal(value);
		map.setCity(city[city_this].city);
	},
	setVal: function setVal(value) {
		this.setState({
			value: value
		});
	},
	render: function render() {
		return React.createElement(Select, { defaultValue: "tianjin", value: this.state.value, style: { width: 100, height: 28, border: 'none' }, onChange: this.changeCity, placeholder: "城市" }, React.createElement(Option, { value: "tianjin" }, "天津"), React.createElement(Option, { value: "shanghai" }, "上海"), React.createElement(Option, { value: "beijing" }, "北京"), React.createElement(Option, { value: "qingdao" }, "青岛"));
	}
});

//搜索建议下拉框
var Suggestion = React.createClass({ displayName: "Suggestion",
	getInitialState: function getInitialState() {
		return {
			tips: [],
			data: '',
			value: ''
		};
	},
	handleChange: function handleChange(e) {
		var _this = this;

		var value = event.target.value;
		this.setState({
			value: value
		});
		AMap.service(["AMap.Autocomplete"], function () {
			var autoOptions = {
				city: city[city_this].code //城市
			};
			var auto = new AMap.Autocomplete(autoOptions);
			//查询成功时返回查询结果
			if (value.length > 0) {
				auto.search(value, function (status, result) {
					_this.autoComplete(result);
				});
			}
		});
	},
	closeTip: function closeTip() {
		this.setState({
			tips: []
		});
	},
	tipClick: function tipClick() {
		var obj = event.target,
		    key = obj.getAttribute('data-key'),
		    name = '',
		    _location = null;

		if (this.state.data[key] && this.state.data[key].name) {
			name = this.state.data[key].name;
		} else {
			name = obj.getAttribute('data-name');
		}
		if (this.state.data[key] && this.state.data[key].location) {
			_location = this.state.data[key].location;
		}
		this.setState({
			value: name,
			tips: [],
			data: ''
		});
		setTimeout(function () {
			map.panTo(_location);
		}, 200);
	},
	autoComplete: function autoComplete(result) {
		var _this2 = this;

		var tips = [];
		if (result && result.tips) {
			tips = result.tips.map(function (tip, key) {
				return React.createElement("li", { "data-key": key, onClick: _this2.tipClick, className: "clearfix", "data-name": tip.name, "data-lat": tip.location ? tip.location.lat : '', "data-lng": tip.location ? tip.location.lng : '' }, tip.name, " ", React.createElement("span", { className: "grey" }, tip.district));
			});
			tips.push(React.createElement("li", { className: "tips-close", onClick: this.closeTip }, React.createElement("i", { className: "iconfont icon-cuowu" })));
		}
		this.setState({
			tips: tips,
			data: result.tips
		});
	},
	render: function render() {
		return React.createElement("div", null, React.createElement("input", { type: "text", placeholder: "搜索堆场位置",
			onChange: this.handleChange,
			value: this.state.value }), React.createElement("ul", { className: "search-tips" }, this.state.tips));
	}
});

function bindEvent() {
	// 点击位置类型
	var $mark_btns = document.querySelectorAll('.locations .mark');
	util.bind($mark_btns, 'click', function () {
		var lat = this.getAttribute('data-lat'),
		    lng = this.getAttribute('data-lng'),
		    type = this.getAttribute('type'),
		    name = this.innerHTML;

		if (lat || lng) {
			confirm({
				title: '重新定位',
				content: '确定将”' + name + '“位置移动到当前地图中心位置？',
				onOk: function onOk() {
					map.addMarker(null, type);
					message.success('已添加新的【' + name + '】位置，请点击【提交】保存您的修改，或者拖拽移动调整坐标位置', 5);
				}
			});
		} else {
			confirm({
				title: '添加定位',
				content: '在当前地图中心位置添加一个“' + name + '”坐标？',
				onOk: function onOk() {
					map.addMarker(null, type);
					message.success('已添加新的【' + name + '】位置，请点击【提交】保存您的修改，或者拖拽移动调整坐标位置', 5);
				}
			});
		}
	});

	util.bind($mark_btns, 'mouseover', function () {
		var type = this.getAttribute('type');
		if (markers[type]) {
			markers[type].setAnimation("AMAP_ANIMATION_BOUNCE");
			// map.panTo(markers[type].getPosition());
		}
	});

	util.bind($mark_btns, 'mouseleave', function () {
		var type = this.getAttribute('type');
		if (markers[type]) {
			markers[type].setAnimation("AMAP_ANIMATION_NONE");
		}
	});

	util.bind(document.querySelector('.cancle'), 'click', function () {
		location.reload();
	});

	// 手动触发搜索
	util.bind(document.getElementById('search-btn'), 'click', function () {
		map.search(searchSuggestion.state.value);
	});

	// 提交数据
	var $submit = document.getElementById('yard-submit'),
	    $data_name = document.getElementById('data_name');

	util.bind($submit, 'click', function () {
		var yard_name = $data_name.value,
		    cock_city_code = city_this,
		    locations = [];

		if (!yard_name) {
			message.error('请输入堆场名');
			return;
		}

		for (var i = 1; i <= 5; i++) {
			if (!points['location_type_' + i]) {
				continue;
			}
			locations.push({
				location_detail_type: i,
				latitude: points['location_type_' + i][1],
				longitude: points['location_type_' + i][0]
			});
		}
		var data = {
			yard_name: yard_name,
			cock_city_code: cock_city_code,
			locations: locations,
			yard_id: yard_id
		};
		if (!yard_id) {
			delete data.yard_id;
		}

		util.post('/api/yard/save', data, true).then(function (data) {
			if (data.data && data.data.yard_id) {
				location.href = "/yard/yarddetail?yard_id=" + data.data.yard_id;
			} else {
				location.reload();
			}
		});
	});
}

// 地图类

var MapClass = (function () {
	function MapClass() {
		_classCallCheck(this, MapClass);

		this.map = new AMap.Map("map", {
			resizeEnable: true,
			center: [116.397428, 39.90923],
			zoom: 11
		});
	}

	// 初始化地图

	_createClass(MapClass, [{
		key: 'addMarker',
		value: function addMarker(point, type) {
			if (markers[type]) {
				markers[type].setMap(null);
				markers[type] = null;
			}
			if (!point) {
				_setLocation(type, this.map.getCenter());
			} else {
				points[type] = point;
			}
			var marker = new AMap.Marker({
				position: point || this.map.getCenter(), // 不传point则打点地图中心
				draggable: true,
				cursor: 'move',
				raiseOnDrag: true,
				offset: { x: -15, y: -30 },
				content: '<i class="iconfont icon-mark ' + type + ' marker"></i>'
			});
			marker.setTitle(txt[type]);

			markers[type] = marker;

			marker.setMap(this.map);

			// marker绑定事件
			var obj = document.querySelector('.locations .mark[type=' + type + ']');
			marker.on('dragend', function () {
				_setLocation(type, this.getPosition());
			});
			marker.on('mouseover', function () {
				util.addClass(obj, 'active');
			});
			marker.on('mouseout', function () {
				util.removeClass(obj, 'active');
			});
		}
	}, {
		key: 'panTo',
		value: function panTo(positon) {
			this.map.panTo(positon);
			this.map.setZoom(14);
		}
	}, {
		key: 'setViewport',
		value: function setViewport() {
			var _this3 = this;

			setTimeout(function () {
				_this3.map.setFitView();
			}, 200);
		}
	}, {
		key: 'setCity',
		value: function setCity(city) {
			var _this4 = this;

			this.map.setCity(city);
			setTimeout(function () {
				_this4.map.setZoom(11);
			}, 200);
		}
	}, {
		key: 'search',
		value: function search(q) {
			var _this5 = this;

			searchSuggestion.closeTip();
			var MSearch;
			AMap.service(["AMap.PlaceSearch"], function () {
				MSearch = new AMap.PlaceSearch({ //构造地点查询类
					pageSize: 1,
					pageIndex: 1,
					city: city[city_this].code
				});
				//关键字查询
				MSearch.search(q, function (status, result) {
					if (status === 'complete' && result.info === 'OK') {
						if (result.poiList && result.poiList.pois) {
							message.success('已定位到' + result.poiList.pois[0].name + '，位于' + result.poiList.pois[0].address);
							_this5.map.panTo(result.poiList.pois[0].location);
							_this5.map.setZoom(14);
						} else {
							message.error('未找到地址');
						}
					} else {
						message.error('未找到地址');
					}
				});
			});
		}
	}]);

	return MapClass;
})();

function initMap(data) {
	var hasLocation = false;
	map = new MapClass();
	for (var key in points) {
		if (key in data) {
			if (data[key].longitude && data[key].latitude) {
				hasLocation = true;
				map.addMarker([data[key].longitude, data[key].latitude], key);
			}
		}
	}
	if (hasLocation) {
		map.setViewport();
	} else {
		map.setCity(city[city_this].city);
	}
}

function _setLocation(key, p) {

	var obj = document.querySelector('.locations .mark[type=' + key + ']'),
	    obj_txt = util.siblings(obj, 'txt');

	points[key] = [p.lng, p.lat];
	obj.setAttribute('data-lng', p.lng);
	obj.setAttribute('data-lat', p.lat);

	obj_txt[0].innerHTML = '<span class="changed">[已修改]</span>' + p.lng + ',' + p.lat;
}

function init(data) {
	city_this = data.cock_city_code || 'tianjin';
	yard_id = data.id || '';
	initMap(data);

	// 初始化城市选择器
	citySelector_table = React.render(React.createElement(CitySelect, { from: "table", city: city_this }), document.getElementById('yard-city'));
	citySelector_map = React.render(React.createElement(CitySelect, { from: "map", city: city_this }), document.getElementById('yard-city-map'));

	// 搜索框suggestion
	searchSuggestion = React.render(React.createElement(Suggestion, null), document.getElementById('search-input'));

	bindEvent();
}

module.exports = init;

});
