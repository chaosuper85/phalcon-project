define("resource/partial/ship_company/ship_company",function(e,n,i){"use strict";var t=e("common/module/util/util"),a=e("resource/module/ship_com/ship_com"),c=(antd.message,antd.Select),d=(c.Option,document.createElement("div"));d.id="pop_shipcom",document.getElementById("popups").appendChild(d),i.exports={init:function(){var e=React.render(React.createElement(a,null),d);t.bind(document.querySelectorAll(".ship_edit"),"click",function(){var n=this.parentNode,i={id:this.getAttribute("data-id"),name_zh:t.siblings(n,"name_zh")[0].innerHTML,name_en:t.siblings(n,"name_en")[0].innerHTML,contact_name:t.siblings(n,"contact_name")[0].innerHTML,mobile:t.siblings(n,"mobile")[0].innerHTML,address:t.siblings(n,"address")[0].innerHTML};e.show(i,"edit")}),t.bind(document.getElementById("add_com"),"click",function(){e.show({name_zh:"",name_en:"",contact_name:"",mobile:"",address:""},"add")})}}});