define('www/order/module/confirm_product/confirm_product', function(require, exports, module) {

/*
 *确定产装信息弹窗
 */
var popup = require('www/common/module/popup/popup.js');

var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="confirmProduct"><div class="confirmProduct-title clearfix"><div class="confirmProduct-edit"><button class="editBtn">修改</button></div><div class="title-text">确认产装信息</div><div class="confirmProduct-comfire"><button class="comfireBtn">确定</button></div></div><div class="confirmProduct-content clearfix">');if(!list || list.length === 0){_template_fun_array.push('<dl class="address-item clearfix" data-item="1"><dd class="address-title"><div class="address-name"><h4>未添加产装信息！</h4></div></dd></dl>');}else{_template_fun_array.push('');for(var i = 0; i < list.length; i++){_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'"><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label><span class="name">装箱地址</span></label></div><div class="item-content">',typeof( list[i].box_address_detail) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address_detail),'</div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label><span class="name">工厂联系人</span></label></div><div class="item-content">',typeof( list[i].contactName) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName),'</div></dt><dt class="item item-contact"><div class="item-name"><label><span class="name">联系方式</span></label></div><div class="item-content">',typeof( list[i].contactNumber) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber),'</div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label><span class="name">装箱时间</span></label></div><div class="item-content">');if(!list[i].box_date || list[i].box_date === 0){_template_fun_array.push('<p>未添加装箱时间</p>');}else{_template_fun_array.push('');for(var d = 0; d < list[i].box_date.length; d++){_template_fun_array.push('<p>',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'</p>');}_template_fun_array.push('');}_template_fun_array.push('</div></dt>');if(list.length != i + 1){_template_fun_array.push('<div class="clearfix address-bottom-line"></div>');}_template_fun_array.push('</dl>');}_template_fun_array.push('');}_template_fun_array.push('</div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];

var ConfirmProduct = function(_option){
	var defaults = {
            clickEditBtn: function(){},
            clickConfirmBtn: function(){}
        },
        options = $.extend({}, defaults, _option); 

	function _ConfirmProduct(){
        var html = tpl({
            list: options.data.addressInfo
        });
        this.setContent(html);

        var DOM = this.DOM = {
            editBtn: this.pop.find(".confirmProduct-edit .editBtn"),
            comfireBtn: this.pop.find(".confirmProduct-comfire .comfireBtn")
        }

        var that = this;
        /** bind event */
        // 修改
        DOM.editBtn.on('click', function(event) {
            that.hide();
            if(typeof options.clickEditBtn === 'function'){
                options.clickEditBtn();
            }
        });

        // 确定
        DOM.comfireBtn.on('click', function(event) {
            that.hide();
            if(typeof options.clickConfirmBtn === 'function'){
                options.clickConfirmBtn();
            }
        });
	}
 
	_ConfirmProduct.prototype = new popup({
        title :false, 
        height:500,
        width :700
	});

	_ConfirmProduct.prototype.constructor = _ConfirmProduct;

	return new _ConfirmProduct();
}

module.exports = ConfirmProduct; 

});
