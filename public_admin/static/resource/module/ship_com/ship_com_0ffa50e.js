define("resource/module/ship_com/ship_com",function(e,t,a){"use strict";var n=e("common/module/util/util"),i=antd.Modal,l=antd.message,s={name_zh:"",name_en:"",contact_name:"",mobile:"",address:""},c=React.createClass({displayName:"Formcom",getInitialState:function(){return{data:s}},handleChange:function(e){this.props.data[e]=event.target.value,this.setState({data:this.props.data})},render:function(){return React.createElement("dl",{className:"x-form"},React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"船中文名"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.data.name_zh,onChange:this.handleChange.bind(this,"name_zh")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},React.createElement("i",null,"*"),"船英文名"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.data.name_en,onChange:this.handleChange.bind(this,"name_en")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"联系人"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.data.contact_name,onChange:this.handleChange.bind(this,"contact_name")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"联系人电话"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.data.mobile,onChange:this.handleChange.bind(this,"mobile")}))),React.createElement("dd",null,React.createElement("span",{className:"title"},"联系地址"),React.createElement("div",{className:"content"},React.createElement("input",{type:"text",value:this.props.data.address,onChange:this.handleChange.bind(this,"address")}))))}}),d=React.createClass({displayName:"shipCom",getInitialState:function(){return{visible:!1,loading:!1,data:s,type:"edit"}},show:function(e,t){this.setState({visible:!0,loading:!1,data:e,type:t})},handleOk:function(){var e=this;if(!this.state.data.name_zh||!this.state.data.name_en)return void l.error("必填项未填");var t="/api/ship/addCompany";"edit"==this.state.type&&(t="/api/ship/alterCompany"),this.setState({loading:!0,visible:!0}),n.post(t,this.state.data).then(function(){e.setState({loading:!1,visible:!1}),location.reload()},function(t){e.setState({loading:!1}),l.error(t)})},handleCancel:function(){this.setState({visible:!1,loading:!1})},render:function(){return React.createElement("div",null,React.createElement(i,{title:"edit"==this.state.type?"编辑船公司信息":"添加船公司",visible:this.state.visible,onOk:this.handleOk,footer:[React.createElement("button",{key:"back",className:"ant-btn ant-btn-lg",onClick:this.handleCancel},"返 回"),React.createElement("button",{key:"submit",className:"ant-btn ant-btn-primary ant-btn-lg "+(this.state.loading?"ant-btn-loading":""),onClick:this.handleOk},"提 交")]},React.createElement(c,{data:this.state.data})))}});a.exports=d});