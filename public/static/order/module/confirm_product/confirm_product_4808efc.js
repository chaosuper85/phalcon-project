define("order/module/confirm_product/confirm_product",function(require,exports,module){var popup=require("common/module/popup/popup.js"),tpl=[function(_template_object){var _template_fun_array=[],fn=function(__data__){var _template_varName="";for(var name in __data__)_template_varName+="var "+name+'=__data__["'+name+'"];';if(eval(_template_varName),_template_fun_array.push('<div id="confirmProduct"><div class="confirmProduct-title clearfix"><div class="confirmProduct-edit"><button class="editBtn">修改</button></div><div class="title-text">确认产装信息</div><div class="confirmProduct-comfire"><button class="comfireBtn">确定</button></div></div><div class="confirmProduct-content clearfix">'),list&&0!==list.length){_template_fun_array.push("");for(var i=0;i<list.length;i++){if(_template_fun_array.push('<dl class="address-item clearfix" data-item="',"undefined"==typeof(i+1)?"":baidu.template._encodeHTML(i+1),'"><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',"undefined"==typeof(i+1)?"":baidu.template._encodeHTML(i+1),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label><span class="name">装箱地址</span></label></div><div class="item-content">',"undefined"==typeof list[i].box_address_detail?"":baidu.template._encodeHTML(list[i].box_address_detail),'</div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label><span class="name">工厂联系人</span></label></div><div class="item-content">',"undefined"==typeof list[i].contactName?"":baidu.template._encodeHTML(list[i].contactName),'</div></dt><dt class="item item-contact"><div class="item-name"><label><span class="name">联系方式</span></label></div><div class="item-content">',"undefined"==typeof list[i].contactNumber?"":baidu.template._encodeHTML(list[i].contactNumber),'</div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label><span class="name">装箱时间</span></label></div><div class="item-content">'),list[i].box_date&&0!==list[i].box_date){_template_fun_array.push("");for(var d=0;d<list[i].box_date.length;d++)_template_fun_array.push("<p>","undefined"==typeof list[i].box_date[d].product_supply_time?"":baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),"</p>");_template_fun_array.push("")}else _template_fun_array.push("<p>未添加装箱时间</p>");_template_fun_array.push("</div></dt>"),list.length!=i+1&&_template_fun_array.push('<div class="clearfix address-bottom-line"></div>'),_template_fun_array.push("</dl>")}_template_fun_array.push("")}else _template_fun_array.push('<dl class="address-item clearfix" data-item="1"><dd class="address-title"><div class="address-name"><h4>未添加产装信息！</h4></div></dd></dl>');_template_fun_array.push("</div></div>"),_template_varName=null}(_template_object);return fn=null,_template_fun_array.join("")}][0],ConfirmProduct=function(t){function e(){var t=tpl({list:i.data.addressInfo});this.setContent(t);var e=this.DOM={editBtn:this.pop.find(".confirmProduct-edit .editBtn"),comfireBtn:this.pop.find(".confirmProduct-comfire .comfireBtn")},a=this;e.editBtn.on("click",function(){a.hide(),"function"==typeof i.clickEditBtn&&i.clickEditBtn()}),e.comfireBtn.on("click",function(){a.hide(),"function"==typeof i.clickConfirmBtn&&i.clickConfirmBtn()})}var a={clickEditBtn:function(){},clickConfirmBtn:function(){}},i=$.extend({},a,t);return e.prototype=new popup({title:!1,height:500,width:700}),e.prototype.constructor=e,new e};module.exports=ConfirmProduct});