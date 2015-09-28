define('admin/partial/role/role', function(require, exports, module) {

"use strict";

var util = require('common/module/util/util');
var message = antd.message;

var Tree = React.createClass({ displayName: "Tree",
	getInitialState: function getInitialState() {
		return {
			visible: true
		};
	},

	render: function render() {
		return React.createElement("ul", { className: "tree" }, React.createElement("li", { className: "node_one" }, "一级菜单"));
	}

});

module.exports = function () {
	React.render(React.createElement(Tree, null), document.getElementById('role-tree'));
};

});
