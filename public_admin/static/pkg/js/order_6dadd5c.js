;/*!/order/module/pop_orderManager/pop_orderManager.jsx*/
define("order/module/pop_orderManager/pop_orderManager",function(e,t,n){"use strict";function a(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var i=function(){function e(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(t,n,a){return n&&e(t.prototype,n),a&&e(t,a),t}}(),r=e("common/module/util/util"),o=antd.Modal,s=antd.Radio,l=antd.Radio.Group,u=antd.message,d=document.createElement("div");d.id="pop_orderManager",document.getElementById("popups").appendChild(d);var c={},h=React.createClass({displayName:"Popup",getInitialState:function(){return{visible:!1}},show:function(){this.setState({visible:!0})},handleOk:function(){var e=this;return this.state.value?void r.post("/api/ordersuper/changeOrderAdmin",{admin_userid:this.state.value,orderid:this.state.id}).then(function(){window.location.reload(),e.setState({visible:!1})},function(){e.setState({visible:!1})}):void u.error("请选择跟单员")},onChange:function(e){this.setState({value:e.target.value})},setId:function(e,t){this.setState({id:e,value:t})},render:function(){return React.createElement("div",null,React.createElement("button",{className:"ant-btn ant-btn-primary",onClick:this.showModal},"显示对话框"),React.createElement(o,{title:"选择跟单员",visible:this.state.visible,onOk:this.handleOk,onCancel:this.handleCancel},React.createElement(l,{onChange:this.onChange,value:this.state.value,"data-id":this.state.id},c.data.map(function(e){return React.createElement(s,{value:e.id},e.username)}))))}}),p=function(){function e(t){a(this,e),c=t,this.pop=React.render(React.createElement(h,null),d)}return i(e,[{key:"show",value:function(e,t){this.pop.setId(e,t),this.pop.show()}}]),e}();n.exports=p});
;/*!/order/partial/all/all.jsx*/
define("order/partial/all/all",function(e,t,r){"use strict";function a(){function e(e){l=e}var t=antd.Select,r=t.Option;React.render(React.createElement(t,{defaultValue:l,style:{width:100},onChange:e,placeholder:"查询类型"},React.createElement(r,{value:"tidan_code"},"提单号"),React.createElement(r,{value:"yundan_code"},"运单号"),React.createElement(r,{value:"enterprisename"},"公司名"),React.createElement(r,{value:"order_freightagent_mobile"},"货代手机号"),React.createElement(r,{value:"order_teammobile"},"车队手机号"),React.createElement(r,{value:"supervisor_name"},"跟单员")),document.getElementById("filter_search_type")),n()}function n(){var e;o.get("/api/ordersuper/orderAdmins?page_size=100").then(function(t){0==t.error_code?e=new i(t.data.pageInfo):c.error("跟单员数据请求失败")},function(e){console.log(e)});var t=document.querySelectorAll(".editManager");o.bind(t,"click",function(){try{e.show(this.getAttribute("data-id"),this.getAttribute("data-uid"))}catch(t){c.error("正在请求数据跟单员数据")}}),document.getElementById("clearAll").addEventListener("click",function(){var e=o.getParam("order_status"),t="";e&&(t+="?order_status="+e),location.href=window.location.pathname+t});var r=document.querySelector(".filter-input");r.value=u,document.getElementById("search").addEventListener("click",function(){return r.value?(m.map(function(e){d.hasOwnProperty(e)&&(d[e]="")}),d[l]=r.value,void o.redirectParam(d)):void c.error("请输入搜索内容")})}var o=e("common/module/util/util"),i=e("order/module/pop_orderManager/pop_orderManager"),c=(antd.confirm,antd.message),d=void 0,l="tidan_code",u="",m=["enterprisename","order_freightagent_mobile","order_teammobile","supervisor_name","tidan_code","yundan_code"];r.exports={init:function(e){d=e||{},m.map(function(e){d.hasOwnProperty(e)&&(l=e,u=d[e])}),"page_no"in d&&delete d.page_no,a()}}});
;/*!/order/partial/my/my.jsx*/
define("order/partial/my/my",function(e,t,n){"use strict";function a(){function e(e){d=e}var t=antd.Select,n=t.Option;React.render(React.createElement(t,{defaultValue:d,style:{width:100},onChange:e,placeholder:"查询类型"},React.createElement(n,{value:"tidan_code"},"提单号"),React.createElement(n,{value:"yundan_code"},"运单号"),React.createElement(n,{value:"enterprisename"},"公司名"),React.createElement(n,{value:"order_freightagent_mobile"},"货代手机号"),React.createElement(n,{value:"order_teammobile"},"车队手机号")),document.getElementById("filter_search_type")),r()}function r(){document.getElementById("clearAll").addEventListener("click",function(){var e=o.getParam("order_status"),t="";e&&(t+="?order_status="+e),location.href=window.location.pathname+t});var e=document.querySelector(".filter-input");e.value=l,document.getElementById("search").addEventListener("click",function(){return e.value?(u.map(function(e){i.hasOwnProperty(e)&&(i[e]="")}),i[d]=e.value,void o.redirectParam(i)):void c.error("请输入搜索内容")})}var o=e("common/module/util/util"),c=antd.message,i=void 0,d="tidan_code",l="",u=["enterprisename","order_freightagent_mobile","order_teammobile","tidan_code","yundan_code"];n.exports={init:function(e){i=e||{},u.map(function(e){i.hasOwnProperty(e)&&(d=e,l=i[e])}),"page_no"in i&&delete i.page_no,a()}}});