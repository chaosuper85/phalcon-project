define("user/partial/agent/agent",function(e,t,n){"use strict";function a(){function e(e){m=e}var t=antd.Select,n=t.Option;React.render(React.createElement(t,{defaultValue:m,style:{width:80},onChange:e,placeholder:"查询类型"},React.createElement(n,{value:"agent_name"},"货代名称"),React.createElement(n,{value:"mobile"},"手机号"),React.createElement(n,{value:"name"},"用户名")),document.getElementById("filter_search_type")),React.render(React.createElement(t,{style:{width:100},onChange:e,placeholder:"平台",disabled:!0},React.createElement(n,{value:"PC"},"PC"),React.createElement(n,{value:"Android"},"Android"),React.createElement(n,{value:"iOS"},"iOS")),document.getElementById("filter_platform")),React.render(React.createElement(t,{style:{width:100},onChange:e,placeholder:"版本号",disabled:!0},React.createElement(n,{value:"1.0"},"1.0"),React.createElement(n,{value:"2.0"},"2.0"),React.createElement(n,{value:"3.0"},"3.0")),document.getElementById("filter_version")),r()}function r(){document.getElementById("clearAll").addEventListener("click",function(){var e=i.getParam("status"),t="";e&&(t+="?status="+e),location.href=window.location.pathname+t});var e=document.querySelector(".filter-input");e.value=s,document.getElementById("search").addEventListener("click",function(){return e.value?(u.name="",u.mobile="",u[m]=e.value,void i.redirectParam(u)):void d.info("请输入搜索内容")});var t=document.querySelectorAll(".btn_detail"),n=void 0;i.bind(t,"click",function(){var e=this,t=this.getAttribute("data-id");this.innerHTML='<i class="iconfont icon-loadc loading"></i>',i.get("/api/agent/auditDetail?id="+t).then(function(t){if(setTimeout(function(){e.innerHTML="查看"},500),0==t.error_code){if(!t.data)return void d.info("没有找到待审核信息");n||(n=new o(t.data,"agent")),n.showDetail(t.data,"agent")}else d.error(t.error_msg)},function(){e.innerHTML="查看"})});var a={pass:"通过",reject:"驳回",lock:"锁定",unlock:"解锁",del:"删除"};i.bind(document.querySelectorAll(".table-functions .btn"),"click",function(){var e=document.querySelectorAll(".checkbox_data:checked"),t=[];if(Array.from(e).forEach(function(e){t.push(e.getAttribute("data-id"))}),0==t.length)return void d.info("请选择");var n=this.getAttribute("data-type");l({title:a[n],content:'确认 "'+a[n]+'" 选中'+t.length+"个用户？",onOk:function(){return d.info("正在发送请求...",3),new Promise(function(e){$.ajax({type:"GET",dataType:"json",url:c.agent[n],data:{id:t},success:function(t){console.log(t),0==t.error_code?(d.success(t.error_msg,3),location.reload()):d.error(t.error_msg,3),e()}})})}})})}e("common/widget/table/table");var i=e("common/module/util/util"),o=e("user/module/pop_userDetail/pop_userDetail"),c=e("user/module/api/api"),l=antd.confirm,d=antd.message,u=void 0,m="agent_name",s="",f=["agent_name","name","mobile"];n.exports={init:function(e){u=e||{},f.map(function(e){u.hasOwnProperty(e)&&u[e]&&(m=e,s=u[e])}),a()}}});