define("common/module/popup/popup",function(e,t,n){"use strict";function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var i=function(){function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),a=antd.Modal,l=document.getElementById("popups"),r=function(){function e(t){o(this,e);var n=document.createElement("div");n.id=t.id,l.appendChild(n);var i=t.content,r=React.createClass({displayName:"Diolog",getInitialState:function(){return{visible:!1}},showModal:function(){this.setState({visible:!0})},handleOk:function(){console.log("点击了确定"),this.setState({visible:!1})},render:function(){return React.createElement("div",null,React.createElement(a,{title:t.title,visible:this.state.visible,onOk:this.handleOk,onCancel:this.handleCancel,footer:t.footer},React.createElement(i,null)))}}),c=React.render(React.createElement(r,null),document.getElementById(t.id));this.popup=c}return i(e,[{key:"show",value:function(){this.popup.showModal()}}]),e}();n.exports=r});