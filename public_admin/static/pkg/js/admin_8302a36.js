;/*!/admin/module/group/pop_alluser.jsx*/
define("admin/module/group/pop_alluser",function(e,t,n){"use strict";function a(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var r=function(){function e(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(t,n,a){return n&&e(t.prototype,n),a&&e(t,a),t}}(),i=e("common/module/util/util"),o=antd.Modal,l=(antd.Radio,antd.Radio.Group,antd.message),s=document.createElement("div");s.id="pop_alluser",document.getElementById("popups").appendChild(s);var u=[],c=React.createClass({displayName:"Popup",getInitialState:function(){return{visible:!1}},show:function(){this.setState({visible:!0})},handleOk:function(){var e=this,t=event.target.getAttribute("data-id"),n=this.state.id;return t&&n?void i.post("/api/acl/addGroupUser",{group_id:n,user_id:t}).then(function(t){0==t.error_code?(l.success("添加成功"),e.setState({visible:!1}),window.location.reload()):l.error(t.error_msg)},function(){l.error("添加失败，请重试"),e.setState({visible:!1})}):void l.error("出错啦，请刷新重试")},setId:function(e){this.setState({id:e})},render:function(){var e=this;return React.createElement("div",null,React.createElement(o,{title:"向该组添加一个用户",visible:this.state.visible,onOk:this.handleOk,footer:"",onCancel:this.handleCancel},React.createElement("ul",{className:"pop_all_user clearfix"},u.map(function(t){return React.createElement("li",{"data-id":t.id,onClick:e.handleOk},t.real_name?t.username+"-("+t.real_name+")":t.username)}))))}}),d=function(){function e(t){a(this,e),u=t,this.pop=React.render(React.createElement(c,null),document.getElementById("pop_alluser"))}return r(e,[{key:"show",value:function(e){this.pop.setId(e),this.pop.show()}}]),e}();n.exports=d});
;/*!/admin/module/user/adduser.jsx*/
define("admin/module/user/adduser",function(e,t,a){"use strict";var n=e("common/module/util/util"),s=antd.Modal,l=antd.message,i={name:"",pwd:"",real_name:"",mobile:"",email:""},c=React.createClass({displayName:"Addform",getInitialState:function(){return{data:this.props.userdata}},handleChange:function(e){this.props.userdata[e]=event.target.value,this.setState({data:this.props.userdata})},render:function(){return React.createElement("dl",{className:"x-form"},React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"用户名"),React.createElement("div",{className:"content"},"add"==this.props.type?React.createElement("input",{type:"text",value:this.props.userdata.name,onChange:this.handleChange.bind(this,"name")}):React.createElement("p",null,this.props.userdata.name))),React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"真实姓名"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.real_name,onChange:this.handleChange.bind(this,"real_name")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"add"==this.props.type?React.createElement("i",null,"*"):"","密码"),React.createElement("div",{className:"content"},React.createElement("input",{type:"password",value:this.props.userdata.pwd,onChange:this.handleChange.bind(this,"pwd")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"手机号"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.mobile,onChange:this.handleChange.bind(this,"mobile")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"Email"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.email,onChange:this.handleChange.bind(this,"email")}))))}}),d=React.createClass({displayName:"addUser",getInitialState:function(){return{visible:!1,loading:!1,data:i,type:"edit"}},show:function(e,t){this.setState({visible:!0,loading:!1,data:e,type:t})},handleOk:function(){var e=this,t="/api/account/add";if("add"==this.state.type){if(!this.state.data.name||!this.state.data.real_name||!this.state.data.pwd)return l.error("必填项未填"),void this.setState({loading:!1})}else{if(!this.state.data.name||!this.state.data.real_name)return l.error("必填项未填"),void this.setState({loading:!1});t="/api/account/alter"}this.setState({visible:!0,loading:!0}),n.post(t,this.state.data).then(function(){e.setState({loading:!1,visible:!1}),location.reload()},function(t){e.setState({loading:!1}),l.error(t)})},handleCancel:function(){this.setState({visible:!1,loading:!1})},render:function(){return React.createElement("div",null,React.createElement(s,{title:"edit"==this.state.type?"编辑用户":"添加用户",visible:this.state.visible,onOk:this.handleOk,footer:[React.createElement("button",{key:"back",className:"ant-btn ant-btn-lg",onClick:this.handleCancel},"返 回"),React.createElement("button",{key:"submit",className:"ant-btn ant-btn-primary ant-btn-lg "+(this.state.loading?"ant-btn-loading":""),onClick:this.handleOk},"提 交")]},React.createElement(c,{userdata:this.state.data,type:this.state.type})))}});a.exports=d});
;/*!/admin/partial/group/group.jsx*/
define("admin/partial/group/group",function(e){"use strict";var t=e("common/module/util/util"),r=e("admin/module/group/pop_alluser"),i=antd.message,o=void 0;!function(){var e=document.querySelectorAll(".user_list"),a=document.querySelectorAll(".role_nav .item");location.href.split("#")[1]?(t.addClass(e[location.href.split("#")[1]],"active"),t.addClass(a[location.href.split("#")[1]],"active")):(t.addClass(e[0],"active"),t.addClass(a[0],"active")),t.bind(document.querySelectorAll(".role_nav .item"),"click",function(){var r=this,i=r.getAttribute("data-index"),o=e[i];t.removeSiblingsClass(r,"active"),t.addClass(r,"active"),t.removeSiblingsClass(o,"active"),t.addClass(o,"active"),window.location.href=location.pathname+"#"+i});var n=!1;t.bind(document.querySelectorAll(".add_user"),"click",function(){var e=this,a=e.getAttribute("data-id");if(o)o.show(a);else{if(n)return void i.info("请稍候，正在加载用户数据");i.info("正在加载用户数据"),n=!0,t.get("/api/account/userList").then(function(e){0==e.error_code?(o=new r(e.data.data),o.show(a)):i.error(e.error_msg),n=!1},function(e){i.error(e),n=!1})}}),t.bind(document.querySelectorAll(".remove_user"),"click",function(){var e=this,r=e.getAttribute("data-uid"),o=e.getAttribute("data-id");return r&&o?void t.post("/api/acl/delGroupUser",{group_id:o,user_id:r}).then(function(e){0==e.error_code?(i.success("移除成功"),window.location.reload()):i.error(e.error_msg)},function(){i.error("添加失败，请重试")}):void i.error("出错啦，请刷新重试")})}()});
;/*!/admin/partial/role/role.jsx*/
define("admin/partial/role/role",function(e,t,n){"use strict";var a=(e("common/module/util/util"),antd.message,React.createClass({displayName:"Tree",getInitialState:function(){return{visible:!0}},render:function(){return React.createElement("ul",{className:"tree"},React.createElement("li",{className:"node_one"},"一级菜单"))}}));n.exports=function(){React.render(React.createElement(a,null),document.getElementById("role-tree"))}});
;/*!/admin/partial/user/user.jsx*/
define("admin/partial/user/user",function(e,n,i){"use strict";var d=e("common/module/util/util"),l=e("admin/module/user/adduser"),t=(antd.message,antd.Select),r=(t.Option,document.createElement("div"));r.id="pop_addUser",document.getElementById("popups").appendChild(r),i.exports={init:function(){var e=React.render(React.createElement(l,null),r);d.bind(document.querySelectorAll(".user_edit"),"click",function(){var n=this.parentNode,i={id:d.siblings(n,"user_id")[0].innerHTML,name:d.siblings(n,"user_name")[0].innerHTML,pwd:"",real_name:d.siblings(n,"user_real")[0].innerHTML,mobile:d.siblings(n,"user_mobile")[0].innerHTML,email:d.siblings(n,"user_email")[0].innerHTML};console.log(i),e.show(i,"edit")}),d.bind(document.getElementById("add-new"),"click",function(){e.show({name:"",pwd:"",real_name:"",mobile:"",email:""},"add")})}}});
;/*!/admin/partial/userinfo/userinfo.jsx*/
define("admin/partial/userinfo/userinfo",function(e,n,i){"use strict";var d=e("common/module/util/util"),l=e("admin/module/user/adduser"),t=(antd.message,antd.Select),r=(t.Option,document.createElement("div"));r.id="pop_addUser",document.getElementById("popups").appendChild(r),i.exports={init:function(){var e=React.render(React.createElement(l,null),r);d.bind(document.querySelectorAll(".user_edit"),"click",function(){var n=this.parentNode,i={id:d.siblings(n,"user_id")[0].innerHTML,name:d.siblings(n,"user_name")[0].innerHTML,pwd:"",real_name:d.siblings(n,"user_real")[0].innerHTML,mobile:d.siblings(n,"user_mobile")[0].innerHTML,email:d.siblings(n,"user_email")[0].innerHTML};console.log(i),e.show(i,"edit")}),d.bind(document.getElementById("add-new"),"click",function(){e.show({name:"",pwd:"",real_name:"",mobile:"",email:""},"add")})}}});