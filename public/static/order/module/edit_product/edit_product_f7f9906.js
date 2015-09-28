define("order/module/edit_product/edit_product",function(require,exports,module){var popup=require("common/module/popup/popup.js"),CommonBox=require("order/module/commonBox.js"),AddSelectTimeBox=require("order/module/addSelectTimeBox/addSelectTimeBox.js"),tpl=[function(_template_object){var _template_fun_array=[],fn=function(__data__){var _template_varName="";for(var name in __data__)_template_varName+="var "+name+'=__data__["'+name+'"];';if(eval(_template_varName),_template_fun_array.push('<div id="editProduct"><div class="editProduct-title clearfix"><div class="editProduct-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">修改产装信息</div><div class="editProduct-comfire"><button class="comfireBtn">确定修改</button></div></div><div class="editProduct-content clearfix">'),list&&0!==list.length){_template_fun_array.push("");for(var i=0;i<list.length;i++){if(_template_fun_array.push('<dl class="address-item clearfix" data-item="',"undefined"==typeof(i+1)?"":baidu.template._encodeHTML(i+1),'" data-change="'),_template_fun_array.push(list[i].address_can_change?"1":"0"),_template_fun_array.push('"><input type="hidden" value="',"undefined"==typeof list[i].product_address_id?"":baidu.template._encodeHTML(list[i].product_address_id),'" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',"undefined"==typeof(i+1)?"":baidu.template._encodeHTML(i+1),"</span>"),list[i].address_can_change||_template_fun_array.push('<span class="address_complete">（已运抵，不可修改）</span>'),_template_fun_array.push('</h4></div><div class="address-del"></div></dd>'),list[i].address_can_change?_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location" data-pid="',"undefined"==typeof list[i].address_provinceid?"":baidu.template._encodeHTML(list[i].address_provinceid),'" data-cid="',"undefined"==typeof list[i].address_cityid?"":baidu.template._encodeHTML(list[i].address_cityid),'" data-aid="',"undefined"==typeof list[i].address_townid?"":baidu.template._encodeHTML(list[i].address_townid),'"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" class="package_address" id="package_address" name="package_address" value="',"undefined"==typeof(list[i].box_address||"")?"":baidu.template._encodeHTML(list[i].box_address||""),'" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt>'):_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">详细装箱地址</span></label></div><div class="item-content"><p>',"undefined"==typeof list[i].box_address_detail?"":baidu.template._encodeHTML(list[i].box_address_detail),"</p></div></dt>"),_template_fun_array.push('<div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content">'),list[i].address_can_change?_template_fun_array.push('<input type="text" class="linkman" id="linkman" name="linkman" value="',"undefined"==typeof(list[i].contactName||"")?"":baidu.template._encodeHTML(list[i].contactName||""),'" />'):_template_fun_array.push("<p>","undefined"==typeof(list[i].contactName||"未填写")?"":baidu.template._encodeHTML(list[i].contactName||"未填写"),"</p>"),_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content">'),list[i].address_can_change?_template_fun_array.push('<input type="text" id="contact" class="contact" name="contact" value="',"undefined"==typeof(list[i].contactNumber||"")?"":baidu.template._encodeHTML(list[i].contactNumber||""),'"/>'):_template_fun_array.push("<p>","undefined"==typeof(list[i].contactNumber||"未填写")?"":baidu.template._encodeHTML(list[i].contactNumber||"未填写"),"</p>"),_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content">'),list[i].box_date&&0!==list[i].box_date){_template_fun_array.push("");var flag=1;_template_fun_array.push("");for(var d=0;d<list[i].box_date.length;d++)_template_fun_array.push(""),list[i].box_date[d].time_can_change?_template_fun_array.push('<div class="package_date_selectBox data_',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),' clearfix" data-flag="',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),'" data-timeId="',"undefined"==typeof list[i].box_date[d].product_time_id?"":baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',"undefined"==typeof list[i].box_date[d].product_supply_time?"":baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="1"></div>'):_template_fun_array.push('<div class="package_date_selectBox data_',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),' clearfix hidden" data-flag="',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),'" data-timeId="',"undefined"==typeof list[i].box_date[d].product_time_id?"":baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',"undefined"==typeof list[i].box_date[d].product_supply_time?"":baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="0"></div><p>',"undefined"==typeof list[i].box_date[d].product_supply_time?"":baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),"</p>"),_template_fun_array.push(""),flag+=1,_template_fun_array.push("");_template_fun_array.push("")}else _template_fun_array.push('<div class="package_date_selectBox data_1 clearfix" data-flag="1"></div>');_template_fun_array.push('<a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>')}_template_fun_array.push("")}else _template_fun_array.push('<dl class="address-item clearfix" data-item="1" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">1</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1" data-canChange="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');_template_fun_array.push(' <button class="add-other-address">添加其他产装地址</button></div></div>'),_template_varName=null}(_template_object);return fn=null,_template_fun_array.join("")}][0],addTpl=[function(_template_object){var _template_fun_array=[],fn=function(__data__){var _template_varName="";for(var name in __data__)_template_varName+="var "+name+'=__data__["'+name+'"];';eval(_template_varName),_template_fun_array.push('<dl class="address-item clearfix" data-item="',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),'" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',"undefined"==typeof flag?"":baidu.template._encodeHTML(flag),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>'),_template_varName=null}(_template_object);return fn=null,_template_fun_array.join("")}][0],CONST={is_require:!0,no_require:!1,input_type:"INPUT",weight_type:"WEIGHT",select_type:"SELECT",address_select_type:"ADDRESS"},EditProduct=function(e){function a(){}function i(e){function a(e){this.$scope=e;{var a=this;this.itemBox=new CommonBox({scope:e,config_array:[{keyName:"package_location",selector:e.find(".package_location"),require:CONST.is_require,type:CONST.address_select_type,height:23},{keyName:"product_address_id",selector:".product_address_id",require:CONST.no_require,type:CONST.input_type},{keyName:"package_address",selector:".package_address",require:CONST.is_require,type:CONST.input_type},{keyName:"linkman",selector:".linkman",require:CONST.no_require,type:CONST.input_type},{keyName:"contact",selector:".contact",require:CONST.no_require,type:CONST.input_type}]}),a.timeBoxEvent=new AddSelectTimeBox({container:a,height:23})}}return a.prototype.check=function(){var e=this.itemBox.check();return e.timeList=this.timeBoxEvent.getTimeBoxId(),e},new a(e)}var s={data:{addressInfo:[],orderid:""}},t=$.extend({},s,e);return a.prototype=new popup({title:!1,height:500,width:700}),a.prototype.constructor=a,a.prototype.init=function(e){var a=(this.list=[],1),s=this,d=tpl({list:e||t.data.addressInfo});this.setContent(d);var n=this.DOM={scope:this.pop.find(".editProduct-content"),del_AddressBtn:$('<a class="del-AddressBtn" href="javaScript:">删除该地址</a>'),item:this.pop.find(".editProduct-content .address-item")};n.item.each(function(){var e=$(this),a=new i(e);s.list.push(a)}),a=this.flag=n.item.length,n.scope.on("click",".add-other-address",function(){if(!(a>19)){a++;var e=$(this),t=addTpl({flag:a}),d=$(t);e.before(d);var c=(d.find(".address-del"),new i(d));s.list.push(c);var l=n.scope.find(".address-item .address-title .address-name .num");l.each(function(e){$(this).text(e+1)})}}),n.scope.on("mouseover",".address-item",function(){var e=$(this),a=e.attr("data-item"),i=e.attr("data-change");1!=a&&i&&0!=i&&(n.del_AddressBtn.show(),e.find(".address-del").html(n.del_AddressBtn),n.del_AddressBtn.attr("data-item",a))}),n.scope.on("mouseleave",".address-item",function(){var e=$(this),a=e.attr("data-item");1!=a&&(n.del_AddressBtn.hide(),n.del_AddressBtn.removeAttr("data-item",a))}),n.scope.on("click",".del-AddressBtn",function(){var e=$(this),a=e.attr("data-item");if(1!=a){var i,t=e.parents(".address-item"),d=s.list.length;for(i=0;d>i;i++)if(s.list[i]&&s.list[i].$scope){var c=s.list[i].$scope.attr("data-item");c==a&&s.list.splice(i,1)}t.remove();var l=n.scope.find(".address-item .address-title .address-name .num");l.each(function(e){$(this).text(e+1)})}})},a.prototype.bind=function(){var e=this,a={cancelBtn:e.pop.find(".editProduct-cancel .cancelBtn"),comfireBtn:e.pop.find(".editProduct-comfire .comfireBtn")};a.cancelBtn.on("click",function(){e.hide()}),a.comfireBtn.on("click",function(){var a,i=[],s=!0,d=e.list.length;for(a=0;d>a;a++){var n=e.list[a].check(),c={};if(c.product_address_id=n.product_address_id.val||"",n.package_location.val.province.id&&n.package_location.val.city.id&&n.package_location.val.area.id){var l={address:n.package_address.val};l.provinceid=n.package_location.val.province.id,l.cityid=n.package_location.val.city.id,l.townid=n.package_location.val.area.id}c.contactName=n.linkman.val,c.contactNumber=n.contact.val,c.box_date=[],l&&(c.box_address=l);for(var r=n.timeList.length;r--;){var o={product_time_id:n.timeList[r].time_id};1==n.timeList[r].can_change&&(o.product_supply_time=n.timeList[r].time),c.box_date.push(o)}i.push(c);for(var p in n)n&&"boolean"==typeof n[p].checkPass&&!n[p].checkPass&&(console.log(p),s=!1)}s&&XDD.Request({url:"/carteam/order/address_confirm",data:{address_info:i,orderid:t.data.orderid},success:function(a){0==a.error_code?(e.hide(),_alert(a.error_msg||"修改成功！"),window.location.reload()):_alert(a.error_msg||"未知错误！")}},!0)})},new a};module.exports=EditProduct});