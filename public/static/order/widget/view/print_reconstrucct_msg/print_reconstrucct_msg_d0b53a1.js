define("order/widget/view/print_reconstrucct_msg/print_reconstrucct_msg",function(e,r,t){function o(e){var r=(_({data:{orderid:e}}),p());$("#order_complete_rec .order-save").on("click",function(){var t=r.fcBox.check(),o=r.shipBox.check(),p=r.productBox.check(),a=!0;return i(t)&&i(o)&&i(p)&&a?(n.show(),void(n.onComplete=function(r,t){var i={box_20gp_count:p["20gp"].val,box_40gp_count:p["40gp"].val,box_40hq_count:p["40hg"].val,orderid:e,password:r,product_box_type:p.product_type.val,product_desc:p.product_remark.val,product_name:p.product_name.val,product_weight:p.product_weight.val,ship_name:o.name.val,ship_ticket:o.num.val,shipping_company:o.company.val,tidan_code:o.tidan.val,yard:o.yard.val},a=t.text();t.html("正在重建..."),t.attr("disabled","disabled"),XDD.Request({url:"/carteam/order/reConstruct",data:i,type:"get",success:function(e){n.hide(),0==e.error_code?(_alert("重建成功！"),window.location.href="/order/list"):_alert(e.error_msg),t.html(a),t.removeAttr("disabled")},error:function(){t.html(a),t.removeAttr("disabled")}})})):void _alert("您有信息未填写！")})}function i(e){var r=!0;for(var t in e)e[t]&&"boolean"==typeof e[t].checkPass&&!e[t].checkPass&&(r=!1);return r}function p(){var e,r,t;return e=new a({scope:$("#order_complete_rec .carteam_freight"),config_array:[{keyName:"freight_id",selector:"#freight_id",require:c.no_require,type:c.input_type},{keyName:"freight_name",selector:"#freight_name",require:c.no_require,type:c.input_type},{keyName:"carteam_id",selector:"#carteam_id",require:c.no_require,type:c.input_type},{keyName:"carteam_name",selector:"#carteam_name",require:c.no_require,type:c.input_type}]}),r=new a({scope:$("#order_complete_rec .ship_info"),config_array:[{keyName:"company",selector:"#ship_company",require:c.is_require,type:c.input_type},{keyName:"tidan",selector:"#tidan",require:c.is_require,type:c.input_type},{keyName:"name",selector:"#ship_name",require:c.is_require,type:c.input_type},{keyName:"num",selector:"#ship_num",require:c.is_require,type:c.input_type},{keyName:"yard",selector:"#ship_yard",require:c.is_require,type:c.input_type},{keyName:"ship_remark",selector:"#ship_remark",require:c.no_require,type:c.input_type}]}),t=new a({scope:$("#order_complete_rec .product_info"),config_array:[{keyName:"product_type",selector:"#product_type",require:c.no_require,type:c.input_type},{keyName:"20gp",selector:"#product_20gp",require:c.no_require,type:c.input_type},{keyName:"40gp",selector:"#product_40gp",require:c.no_require,type:c.input_type},{keyName:"40hg",selector:"#product_40hg",require:c.no_require,type:c.input_type},{keyName:"product_name",selector:"#product_name",require:c.no_require,type:c.input_type},{keyName:"product_weight",selector:"#product_weight",require:c.is_require,type:c.input_type},{keyName:"product_remark",selector:"#product_remark",require:c.no_require,type:c.input_type}]}),{fcBox:e,shipBox:r,productBox:t}}{var a=e("order/widget/view/order_complete/commonBox.js");e("order/widget/view/order_complete/addressBox.js")}e("common/module/searchBox/searchBox.js");var _=e("order/module/comfirm_psw_pop/comfirm_psw_pop.js"),c=(e("common/module/select/select.js"),{is_require:!0,no_require:!1,input_type:"INPUT",weight_type:"WEIGHT",select_type:"SELECT"}),n=_();t.exports={init:function(e){$("#order_complete_rec #ship_company").XDDSearchBox({resKeyName:"china_name",appKeyName:"english_name",keyId:"company_id"}),$("#order_complete_rec #ship_name").XDDSearchBox({url:"/carteam/order/search_ship_name",resKeyName:"china_name",appKeyName:"eng_name",keyId:"ship_name_id"}),$("#order_complete_rec #ship_yard").XDDSearchBox({url:"/carteam/order/search_yard",resKeyName:"yard_name",keyId:"yard_id"}),o(e)}}});