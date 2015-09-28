define("admin/module/user/adduser",function(e,t,a){"use strict";var n=e("common/module/util/util"),s=antd.Modal,l=antd.message,i={name:"",pwd:"",real_name:"",mobile:"",email:""},c=React.createClass({displayName:"Addform",getInitialState:function(){return{data:this.props.userdata}},handleChange:function(e){this.props.userdata[e]=event.target.value,this.setState({data:this.props.userdata})},render:function(){return React.createElement("dl",{className:"x-form"},React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"用户名"),React.createElement("div",{className:"content"},"add"==this.props.type?React.createElement("input",{type:"text",value:this.props.userdata.name,onChange:this.handleChange.bind(this,"name")}):React.createElement("p",null,this.props.userdata.name))),React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"真实姓名"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.real_name,onChange:this.handleChange.bind(this,"real_name")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"add"==this.props.type?React.createElement("i",null,"*"):"","密码"),React.createElement("div",{className:"content"},React.createElement("input",{type:"password",value:this.props.userdata.pwd,onChange:this.handleChange.bind(this,"pwd")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"手机号"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.mobile,onChange:this.handleChange.bind(this,"mobile")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"Email"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.userdata.email,onChange:this.handleChange.bind(this,"email")}))))}}),d=React.createClass({displayName:"addUser",getInitialState:function(){return{visible:!1,loading:!1,data:i,type:"edit"}},show:function(e,t){this.setState({visible:!0,loading:!1,data:e,type:t})},handleOk:function(){var e=this,t="/api/account/add";if("add"==this.state.type){if(!this.state.data.name||!this.state.data.real_name||!this.state.data.pwd)return l.error("必填项未填"),void this.setState({loading:!1})}else{if(!this.state.data.name||!this.state.data.real_name)return l.error("必填项未填"),void this.setState({loading:!1});t="/api/account/alter"}this.setState({visible:!0,loading:!0}),n.post(t,this.state.data).then(function(){e.setState({loading:!1,visible:!1}),location.reload()},function(t){e.setState({loading:!1}),l.error(t)})},handleCancel:function(){this.setState({visible:!1,loading:!1})},render:function(){return React.createElement("div",null,React.createElement(s,{title:"edit"==this.state.type?"编辑用户":"添加用户",visible:this.state.visible,onOk:this.handleOk,footer:[React.createElement("button",{key:"back",className:"ant-btn ant-btn-lg",onClick:this.handleCancel},"返 回"),React.createElement("button",{key:"submit",className:"ant-btn ant-btn-primary ant-btn-lg "+(this.state.loading?"ant-btn-loading":""),onClick:this.handleOk},"提 交")]},React.createElement(c,{userdata:this.state.data,type:this.state.type})))}});a.exports=d});