define("order/partial/all/all",function(e,t,r){"use strict";function a(){function e(e){l=e}var t=antd.Select,r=t.Option;React.render(React.createElement(t,{defaultValue:l,style:{width:100},onChange:e,placeholder:"查询类型"},React.createElement(r,{value:"tidan_code"},"提单号"),React.createElement(r,{value:"yundan_code"},"运单号"),React.createElement(r,{value:"enterprisename"},"公司名"),React.createElement(r,{value:"order_freightagent_mobile"},"货代手机号"),React.createElement(r,{value:"order_teammobile"},"车队手机号"),React.createElement(r,{value:"supervisor_name"},"跟单员")),document.getElementById("filter_search_type")),n()}function n(){var e;o.get("/api/ordersuper/orderAdmins?page_size=100").then(function(t){0==t.error_code?e=new i(t.data.pageInfo):c.error("跟单员数据请求失败")},function(e){console.log(e)});var t=document.querySelectorAll(".editManager");o.bind(t,"click",function(){try{e.show(this.getAttribute("data-id"),this.getAttribute("data-uid"))}catch(t){c.error("正在请求数据跟单员数据")}}),document.getElementById("clearAll").addEventListener("click",function(){var e=o.getParam("order_status"),t="";e&&(t+="?order_status="+e),location.href=window.location.pathname+t});var r=document.querySelector(".filter-input");r.value=u,document.getElementById("search").addEventListener("click",function(){return r.value?(m.map(function(e){d.hasOwnProperty(e)&&(d[e]="")}),d[l]=r.value,void o.redirectParam(d)):void c.error("请输入搜索内容")})}var o=e("common/module/util/util"),i=e("order/module/pop_orderManager/pop_orderManager"),c=(antd.confirm,antd.message),d=void 0,l="tidan_code",u="",m=["enterprisename","order_freightagent_mobile","order_teammobile","supervisor_name","tidan_code","yundan_code"];r.exports={init:function(e){d=e||{},m.map(function(e){d.hasOwnProperty(e)&&(l=e,u=d[e])}),"page_no"in d&&delete d.page_no,a()}}});