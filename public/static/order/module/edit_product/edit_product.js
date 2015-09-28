define('order/module/edit_product/edit_product', function(require, exports, module) {

/*
 *修改产装信息弹窗
 */
var popup = require('common/module/popup/popup.js');
var CommonBox = require("order/module/commonBox.js");
var AddSelectTimeBox = require("order/module/addSelectTimeBox/addSelectTimeBox.js");

var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="editProduct"><div class="editProduct-title clearfix"><div class="editProduct-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">修改产装信息</div><div class="editProduct-comfire"><button class="comfireBtn">确定</button></div></div><div class="editProduct-content clearfix">');if(!list || list.length === 0){_template_fun_array.push('<dl class="address-item clearfix" data-item="1" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">1</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1" data-canChange="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');}else{_template_fun_array.push('');for(var i = 0; i < list.length; i++){_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'" data-change="');if(list[i].address_can_change){_template_fun_array.push('1');}else{_template_fun_array.push('0');}_template_fun_array.push('"><input type="hidden" value="',typeof( list[i].product_address_id ) === 'undefined'?'':baidu.template._encodeHTML( list[i].product_address_id ),'" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( i + 1 ) === 'undefined'?'':baidu.template._encodeHTML( i + 1 ),'</span></h4></div><div class="address-del"></div></dd>');if(!list[i].address_can_change){_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">详细装箱地址</span></label></div><div class="item-content"><p>',typeof( list[i].box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address_detail ),'</p></div></dt>');}else{_template_fun_array.push('<dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location" data-pid="',typeof( list[i].address_provinceid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_provinceid ),'" data-cid="',typeof( list[i].address_cityid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_cityid ),'" data-aid="',typeof( list[i].address_townid ) === 'undefined'?'':baidu.template._encodeHTML( list[i].address_townid ),'"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" class="package_address" id="package_address" name="package_address" value="',typeof( list[i].box_address || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].box_address || '' ),'" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt>');}_template_fun_array.push('<div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content">');if(!list[i].address_can_change){_template_fun_array.push('<p>',typeof( list[i].contactName || '未填写' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName || '未填写' ),'</p>');}else{_template_fun_array.push('<input type="text" class="linkman" id="linkman" name="linkman" value="',typeof( list[i].contactName || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactName || '' ),'" />');}_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content">');if(!list[i].address_can_change){_template_fun_array.push('<p>',typeof( list[i].contactNumber || '未填写' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber || '未填写' ),'</p>');}else{_template_fun_array.push('<input type="text" id="contact" class="contact" name="contact" value="',typeof( list[i].contactNumber || '' ) === 'undefined'?'':baidu.template._encodeHTML( list[i].contactNumber || '' ),'"/>');}_template_fun_array.push('</div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content">');if(!list[i].box_date || list[i].box_date === 0){_template_fun_array.push('<div class="package_date_selectBox data_1 clearfix" data-flag="1"></div>');}else{_template_fun_array.push('');var flag = 1;_template_fun_array.push('');for(var d = 0; d < list[i].box_date.length; d++){;_template_fun_array.push('');if(list[i].box_date[d].time_can_change){_template_fun_array.push('<div class="package_date_selectBox data_',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),' clearfix" data-flag="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-timeId="',typeof(list[i].box_date[d].product_time_id) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="1"></div>');}else{_template_fun_array.push('<div class="package_date_selectBox data_',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),' clearfix hidden" data-flag="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-timeId="',typeof(list[i].box_date[d].product_time_id) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_time_id),'" data-time="',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'" data-canChange="0"></div><p>',typeof(list[i].box_date[d].product_supply_time) === 'undefined'?'':baidu.template._encodeHTML(list[i].box_date[d].product_supply_time),'</p>');}_template_fun_array.push('');flag = flag + 1;_template_fun_array.push('');}_template_fun_array.push('');}_template_fun_array.push('<a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');}_template_fun_array.push('');}_template_fun_array.push(' <button class="add-other-address">添加其他产装地址</button></div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var addTpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="address-item clearfix" data-item="',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'" data-change="1"><input type="hidden" value="" name="product_address_id" class="product_address_id"/><dd class="address-title"><div class="address-name"><h4>产装地址<span class="num">',typeof( flag ) === 'undefined'?'':baidu.template._encodeHTML( flag ),'</span></h4></div><div class="address-del"></div></dd><dt class="item clearfix"><div class="item-name"><label for="package_location"><span class="name">装箱地</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_location"></div></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item clearfix"><div class="item-name"><label for="package_address"><span class="name">详细地址</span><span class="icon-require">*</span></label></div><div class="item-content"><input type="text" value="" class="package_address" id="package_address" name="package_address" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix"><dt class="item item-linkman"><div class="item-name"><label for="linkman"><span class="name">工厂联系人</span></label></div><div class="item-content"><input type="text" value="" class="linkman" id="linkman" name="linkman" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><dt class="item item-contact"><div class="item-name"><label for="contact"><span class="name">联系方式</span></label></div><div class="item-content"><input type="text" value="" id="contact" name="contact" class="contact" /></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt></div><dt class="item package_date clearfix"><div class="item-name"><label for="package_date"><span class="name">装箱时间</span><span class="icon-require">*</span></label></div><div class="item-content"><div class="package_date_selectBox data_1 clearfix" data-flag="1"></div><a class="add-date" href="javaScript:">+增加其它装箱时间</a></div><div class="item-message clearfix"><div class="error-message hidden"><i class="icon-warn"></i></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dt><div class="clearfix address-bottom-line"></div></dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
// 常量
var CONST = {
    is_require: true,
    no_require: false,
    input_type: 'INPUT',
    weight_type: 'WEIGHT',
    select_type: 'SELECT',
    address_select_type: 'ADDRESS'
};
var EditProduct = function(_option){
	var title,
        defaults = {
            data: {
                addressInfo: [],
                orderid: ''
            }
        },
        options = $.extend({}, defaults, _option); 

	function _EditProduct(){
        
	}
 
	_EditProduct.prototype = new popup({
        title :false, 
        height:500,
        width :700
	});

	_EditProduct.prototype.constructor = _EditProduct;

    _EditProduct.prototype.init = function(addressInfo){
        var list = this.list = [];
        var flag = 1;

        var that = this;
        var html = tpl({
            list: addressInfo || options.data.addressInfo
        });
        this.setContent(html);

        /** @type {[type]} [description] */
        var DOM = this.DOM = {
            scope: this.pop.find('.editProduct-content'),
            del_AddressBtn: $('<a class="del-AddressBtn" href="javaScript:">删除该地址</a>'),
            item: this.pop.find('.editProduct-content .address-item')
        }
        DOM.item.each(function(index, el) {
            var $scope = $(this);

            var addressItem = new AddressItem($scope);

            that.list.push(addressItem);
        });

        flag = this.flag = DOM.item.length;

        /** DOM Bind Event */
        DOM.scope.on('click', '.add-other-address', function(event) {
            if(flag > 19) return;
            flag++;

            var obj = $(this);
            var add_html = addTpl({
                flag: flag
            });
            var $scope = $(add_html);
            obj.before($scope);
            var delBtn_container = $scope.find('.address-del');
            var addressItem = new AddressItem($scope);

            that.list.push(addressItem);

            var $num = DOM.scope.find('.address-item .address-title .address-name .num');
            $num.each(function(index, el) {
                $(this).text(index + 1);
            });
        });

        DOM.scope.on('mouseover', '.address-item', function(event) {
            var obj = $(this);
            var flag = obj.attr('data-item');
            var useDel = obj.attr('data-change');
            if(flag == 1) return;
            if(!useDel || useDel == 0) return;

            DOM.del_AddressBtn.show();
            obj.find('.address-del').html(DOM.del_AddressBtn);
            DOM.del_AddressBtn.attr('data-item', flag);
        });

        DOM.scope.on('mouseleave', '.address-item', function(event) {
            var obj = $(this);
            var flag = obj.attr('data-item');
            if(flag == 1) return;

            DOM.del_AddressBtn.hide();
            DOM.del_AddressBtn.removeAttr('data-item', flag);
        });

        DOM.scope.on('click', '.del-AddressBtn', function(event) {
            var obj = $(this);
            var item_f = obj.attr('data-item');

            if(item_f == 1) return;

            var $item = obj.parents('.address-item');
            var i,
                length = that.list.length;
           
            for (i = 0; i < length; i++) {
                if(that.list[i] && that.list[i].$scope){
                    var _f = that.list[i].$scope.attr('data-item');
                    if(_f == item_f){
                        that.list.splice(i, 1);
                    }
                }
                
                
                
            };
            $item.remove();
            var $num = DOM.scope.find('.address-item .address-title .address-name .num');
            $num.each(function(index, el) {
                $(this).text(index + 1);
            });
        });
    }

    _EditProduct.prototype.bind = function(){
        var that = this;
        var DOM = {
            cancelBtn: that.pop.find('.editProduct-cancel .cancelBtn'),
            comfireBtn: that.pop.find('.editProduct-comfire .comfireBtn')
        }

        DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });

        DOM.comfireBtn.on('click', function(event) {
            var sub_list = [],
                i,
                isPass = true,
                length = that.list.length;

                for (i = 0; i < length; i++) {
                    var getData = that.list[i].check();
                    var sub_data = {};
                    
                    sub_data.product_address_id = getData.product_address_id.val || '';
                    /** 地址 */
                    
                    if(getData.package_location.val.province.id && getData.package_location.val.city.id && getData.package_location.val.area.id){
                        var box_address = {
                            address: getData.package_address.val
                        }
                        box_address.provinceid = getData.package_location.val.province.id;
                        box_address.cityid = getData.package_location.val.city.id;
                        box_address.townid = getData.package_location.val.area.id;
                    }
                    /* 上传数据 */
                    sub_data.contactName = getData.linkman.val;
                    sub_data.contactNumber = getData.contact.val;
                    sub_data.box_date = [];

                    if(box_address){
                        sub_data.box_address = box_address;
                    }

                    var time_length = getData.timeList.length;
                    while(time_length--){
                        var time_data = {
                            product_time_id: getData.timeList[time_length].time_id
                        };
                        if(getData.timeList[time_length].can_change == 1){
                            time_data.product_supply_time = getData.timeList[time_length].time;
                        }
                        sub_data.box_date.push(time_data);
                    }

                    sub_list.push(sub_data);

                    for(var k in getData){
                        if(getData && typeof getData[k].checkPass === 'boolean' && !getData[k].checkPass){
                            console.log(k);
                            isPass = false;
                        }
                    }
                };

                if(!isPass){
                    return
                }
                XDD.Request({
                    url: '/carteam/order/address_confirm',
                    data: {
                        address_info: sub_list,
                        orderid: options.data.orderid
                    },
                    success: function(res){
                        if(res.error_code == 0){
                            that.hide();
                            _alert(res.error_msg || '修改成功！');
                            window.location.reload();
                        } else {
                            _alert(res.error_msg || '未知错误！');
                        }
                    }
                }, true);
        });
    }

    function AddressItem($scope) {
        function _AddressItem($scope){
            this.$scope = $scope; 
            var that = this;
            var itemBox = this.itemBox = new CommonBox({
                scope: $scope,
                config_array: [{
                    keyName: 'package_location',
                    selector: $scope.find('.package_location'),
                    require: CONST.is_require,
                    type: CONST.address_select_type,
                    height: 23
                },{
                    keyName: 'product_address_id',
                    selector: '.product_address_id',
                    require: CONST.no_require,
                    type: CONST.input_type
                }
                ,{
                    keyName: 'package_address',
                    selector: '.package_address',
                    require: CONST.is_require,
                    type: CONST.input_type
                },{
                    keyName: 'linkman',
                    selector: '.linkman',
                    require: CONST.no_require,
                    type: CONST.input_type
                },{
                    keyName: 'contact',
                    selector: '.contact',
                    require: CONST.no_require,
                    type: CONST.input_type
                }]
            });
            var timeBoxEvent = that.timeBoxEvent = new AddSelectTimeBox({
                container: that,
                height: 23
            });
            
        }

        _AddressItem.prototype.check = function(){
            var data = this.itemBox.check();

            data.timeList = this.timeBoxEvent.getTimeBoxId();
            return data;
        }
        return new _AddressItem($scope);
    }

	return new _EditProduct();

}

module.exports = EditProduct; 

});
