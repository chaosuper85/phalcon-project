define("order/partial/my/my",function(e,t,r){"use strict";function n(){function e(e){l=e}var t=antd.Select,r=t.Option;React.render(React.createElement(t,{defaultValue:l,style:{width:100},onChange:e,placeholder:"查询类型"},React.createElement(r,{value:"enterprisename"},"公司名"),React.createElement(r,{value:"order_freightagent_mobile"},"货代手机号"),React.createElement(r,{value:"order_teammobile"},"车队手机号")),document.getElementById("filter_search_type")),a()}function a(){document.getElementById("clearAll").addEventListener("click",function(){var e=o.getParam("order_status"),t="";e&&(t+="?order_status="+e),location.href=window.location.pathname+t});var e=document.querySelector(".filter-input");e.value=d,document.getElementById("search").addEventListener("click",function(){return e.value?(m.map(function(e){c.hasOwnProperty(e)&&(c[e]="")}),c[l]=e.value,void o.redirectParam(c)):void i.error("请输入搜索内容")})}var o=e("common/module/util/util"),i=antd.message,c=void 0,l="enterprisename",d="",m=["enterprisename","order_freightagent_mobile","order_teammobile"];r.exports={init:function(e){c=e||{},m.map(function(e){c.hasOwnProperty(e)&&(l=e,d=c[e])}),n()}}});