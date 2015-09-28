define('common/module/popup/popup', function(require, exports, module) {

/*
 *  通用弹窗组件
 *  by kenny
 */

"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Modal = antd.Modal;
var $wrap = document.getElementById('popups');

var Popup = (function () {
	function Popup(opt) {
		_classCallCheck(this, Popup);

		var pop = document.createElement("div");
		pop.id = opt.id;
		$wrap.appendChild(pop);

		var Content = opt.content;

		var Diolog = React.createClass({ displayName: "Diolog",
			getInitialState: function getInitialState() {
				return {
					visible: false
				};
			},
			showModal: function showModal() {
				this.setState({
					visible: true
				});
			},
			handleOk: function handleOk() {
				console.log('点击了确定');
				this.setState({
					visible: false
				});
			},
			render: function render() {
				return React.createElement("div", null, React.createElement(Modal, { title: opt.title,
					visible: this.state.visible,
					onOk: this.handleOk,
					onCancel: this.handleCancel,
					footer: opt.footer }, React.createElement(Content, null)));
			}
		});

		var popup = React.render(React.createElement(Diolog, null), document.getElementById(opt.id));

		this.popup = popup;
	}

	_createClass(Popup, [{
		key: "show",
		value: function show() {
			this.popup.showModal();
		}
	}]);

	return Popup;
})();

module.exports = Popup;

});
