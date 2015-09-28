define('www/order/module/assign_pop/assign_pop', function(require, exports, module) {

/**
 * update by wzq
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
_template_fun_array.push('<div id="assignPop"><div class="assignPop-title clearfix"><div class="assignPop-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传信息</div><div class="assignPop-comfire"><button class="comfireBtn">确定</button></div></div><dl class="assignPop-driver-item first clearfix"><dd class="item"><div class="item-name"><label for="assignPop-seal-num">司机：</label></div><div class="item-content"><p class="driver-name"></p>                <div id="driver-selector"></div></div>            <div class="item-message clearfix">                <div class="error-message hidden"><i class="icon-warn"></i></div>                <div class="right-message hidden"><i class="icon-right"></i></div>            </div></dd></dl><div class="assignPop-address-complete-info"></div><div class="assignPop-address-info"></div><button class="add-other-BoxInfo">添加其他产装信息</button></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var item_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="assign-adrress-items ');if(!canChange){_template_fun_array.push('hidden');}_template_fun_array.push('" data-flag="',typeof(flag) === 'undefined'?'':baidu.template._encodeHTML(flag),'"><div class="clearfix address-bottom-line"></div><input type="hidden" class="assign_driver_id" name="assign_driver_id" value="',typeof( assign_driver_id ) === 'undefined'?'':baidu.template._encodeHTML( assign_driver_id ),'"/><input type="hidden" class="order_product_addressid" name="order_product_addressid" value="',typeof( order_product_addressid ) === 'undefined'?'':baidu.template._encodeHTML( order_product_addressid ),'"/><input type="hidden" class="order_product_timeid" name="order_product_timeid" value="',typeof( order_product_timeid ) === 'undefined'?'':baidu.template._encodeHTML( order_product_timeid ),'"/>');if(canChange){_template_fun_array.push('<dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱地址：</label></div><div class="item-content">            <div class="boxAddress-selector"></div></div>        <div class="item-message clearfix">            <div class="error-message hidden"><i class="icon-warn"></i></div>            <div class="right-message hidden"><i class="icon-right"></i></div>        </div></dd><div class="assign-delContainer"></div><dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱时间：</label></div><div class="item-content">            <div class="boxData-selector"></div></div>        <div class="item-message clearfix">            <div class="error-message hidden"><i class="icon-warn"></i></div>            <div class="right-message hidden"><i class="icon-right"></i></div>        </div></dd>');}_template_fun_array.push('</dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var complete_item_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<dl class="assign-adrress-complete-items"><div class="clearfix address-bottom-line"></div><dd class="item"><div class="item-name"><label>详细装箱地址：</label></div><div class="item-content">',typeof( box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( box_address_detail ),'</div></dd><div class="assign-delContainer"></div><dd class="item"><div class="item-name"><label for="assignPop-seal-num">装箱时间：</label></div><div class="item-content">',typeof( box_time ) === 'undefined'?'':baidu.template._encodeHTML( box_time ),'</div></dd></dl>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var select = require('www/common/module/select/select.js');


function AssignPop(_option){
    var ADDRESS_OPTIONS = [], 
        TIME_OPRTIONS = [];
    var html,
        title,
        defaults = {
            data: {
                id: '',
                carTeam_id: '',
                address_info: []
            },
            carTeam_id: '',
            select_options: [],
            type: 'add',
            can_open: false,
            msg: '未加载完数据，请重试！'
        },
        options = $.extend({}, defaults, _option);
        
    if(options.type === 'add'){
        title = '添加司机/装箱信息';
        var res_mssage = '添加成功！';
    } else {
        title = '修改司机/装箱信息';
        var res_mssage = '修改成功！'
    }

    // 数据处理
    (function(){
        for (var i = 0; i < options.select_options.length; i++) {
            ADDRESS_OPTIONS.push({
                value: options.select_options[i].product_address_id,
                text: options.select_options[i].box_address_detail
            })
        };
    })();

    function _AssignPop(){
        var type = this.type = options.type;
        var flag = this.flag = 0;   // 计时器

        var DOM = this.DOM = {};
        this.DOM = {
            title: this.pop.find('.assignPop-title .title-text'),
            comfireBtn: this.pop.find('.assignPop-title .assignPop-comfire .comfireBtn'),
            cancelBtn: this.pop.find('.assignPop-title .assignPop-cancel .cancelBtn'),
            addBtn: this.pop.find('#assignPop .add-other-BoxInfo'),
            delBtn: $('<a class="del-btn" href="javaScript:">删除</a>'),
            driver: this.pop.find('#driver-selector'),
            items_container: this.pop.find('.assignPop-address-info'),
            items_complete_container: this.pop.find('.assignPop-address-complete-info'),
            items: this.pop.find('.assign-adrress-items')
        }

        this.can_open = options.can_open;
        this.msg = options.msg;
        this.driverSelector;
        this.address_list = [];
        // 计数器
        flag = this.DOM.items.length;

        /** run function */
        this.init_popup();

        /** bind event */
        var that = this;
        this.DOM.addBtn.on('click', function(event) {
            if(flag == 0){
                flag = 1;
            }
            flag++;

            var this_obj = $(this);
            var add_item = $(item_tpl({
                flag: flag,
                assign_driver_id: '',
                order_product_addressid: '',
                order_product_timeid: '',
                canChange: true
            }));
            that.DOM.items_container.append(add_item);

            //选择组件,装箱时间
            var _boxDataSelect = new select({
                width:380,
                container : add_item.find(".boxData-selector"),
                options : [],
                defaultText: '请先选择装箱地址',
                defaultValue : '0',
                options_max_height: 160,
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');
                }
            });
            //选择组件,装箱地址
            var _boxAddressSelect = new select({
                width:380,
                container : add_item.find(".boxAddress-selector"),
                options : ADDRESS_OPTIONS,
                defaultText: '请选择装箱地址',
                defaultValue : '0',
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');

                    _boxDataSelect.setSelectedVal('');
                    _boxDataSelect.setSelectedText('请先选择装箱地址');
                    _boxDataSelect.setOptions([]);
                    (function(val){
                        TIME_OPRTIONS = [];
                        for (var i = 0; i < options.select_options.length; i++) {
                            if(val == options.select_options[i].product_address_id){
                                var box_time = options.select_options[i].box_date;
                                for (var j = 0; j < box_time.length; j++) {
                                    TIME_OPRTIONS.push({
                                        value: box_time[j].product_time_id,
                                        text: box_time[j].product_supply_time
                                    });
                                };
                            }
                        };
                    })(val);

                    _boxDataSelect.setSelectedText('请选择装箱时间');
                    _boxDataSelect.setOptions(TIME_OPRTIONS);
                }
            });

            that.address_list.push({
                address: _boxAddressSelect,
                time: _boxDataSelect,
                assign_id: add_item.find("input[name='assign_driver_id']"),
                order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                flag: flag
            });
        });

        /** 鼠标悬浮显示删除按钮 */
        this.pop.on('mouseover', '.assign-adrress-items', function(event) {
            var this_obj = $(this),
                getFlag = this_obj.attr('data-flag'),
                delBtn_container = this_obj.find('.assign-delContainer');

            if(getFlag == 1) return;

            delBtn_container.html(that.DOM.delBtn);

            that.DOM.delBtn.show();
            that.DOM.delBtn.attr('data-flag', getFlag);
        });

        /** 鼠标离开隐藏删除按钮 */
        this.pop.on('mouseleave', '.assign-adrress-items', function(event) {
            that.DOM.delBtn.hide();
            that.DOM.delBtn.removeAttr('data-flag');
        });

        /** 点击删除按钮 */
        this.pop.on('click', '.del-btn', function(event) {
            var this_obj = $(this),
                getFlag = this_obj.attr('data-flag');

            var i;
            if(getFlag == 1) return;
            if(that.address_list.length == 0) return;

            for (i = 0; i < that.address_list.length; i++) {
                if(getFlag == that.address_list[i].flag){
                    that.address_list.splice(i, 1);
                }
            };
            this_obj.parents('.assign-adrress-items').remove();
        });

        /** 点击确定按钮 */
        this.DOM.comfireBtn.on('click', function(event) {
            save(that);
        });

        /** 点击取消按钮 */
        this.DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });
    }

    //popup尺寸
    _AssignPop.prototype = new popup({
        height:550,
        width :700,
        tpl:tpl
    });

    _AssignPop.prototype.constructor = _AssignPop;

    _AssignPop.prototype.setAdd = function(box_id){
        /** 加载时间和地址 */
        flag = this.flag = 1;
        type = this.type = 'add';
        title = '添加司机/装箱信息';
        res_mssage = '添加成功！';
        this.address_list = [];


        this.DOM.items_container.html('');
        this.DOM.items_complete_container.html('');

        this.driverSelector.setSelectedText('请选择司机');
        this.driverSelector.setSelectedVal('');
        
        this.pop.find('.driver-name').text('').hide();
        this.DOM.driver.show();

        var add_item = $(item_tpl({
            flag: flag,
            canChange: true,
            assign_driver_id: '',
            order_product_addressid: '',
            order_product_timeid: ''
        }));
        this.DOM.items_container.html(add_item);

        //选择组件,装箱时间
        var _boxDataSelect = new select({
            width:380,
            container : add_item.find(".boxData-selector"),
            options : [],
            defaultText: '请先选择装箱地址',
            defaultValue : '0',
            options_max_height: 160,
            onSelectChange: function(val, txt, obj){
                obj.select.select_box.removeClass('error');
            }
        });

        //选择组件,装箱地址
        var _boxAddressSelect = new select({
            width:380,
            container : add_item.find(".boxAddress-selector"),
            options : ADDRESS_OPTIONS,
            defaultText: '请选择装箱地址',
            defaultValue : '0',
            onSelectChange: function(val, txt, obj){
                obj.select.select_box.removeClass('error');

                _boxDataSelect.setSelectedVal('');
                _boxDataSelect.setSelectedText('请先选择装箱地址');
                _boxDataSelect.setOptions([]);
                (function(val){
                    TIME_OPRTIONS = [];
                    for (var i = 0; i < options.select_options.length; i++) {
                        if(val == options.select_options[i].product_address_id){
                            var box_time = options.select_options[i].box_date;
                            for (var j = 0; j < box_time.length; j++) {
                                TIME_OPRTIONS.push({
                                    value: box_time[j].product_time_id,
                                    text: box_time[j].product_supply_time
                                });
                            };
                        }
                    };
                })(val);
                
                _boxDataSelect.setSelectedText('请选择装箱时间');
                _boxDataSelect.setOptions(TIME_OPRTIONS);
            }
        });

        this.address_list.push({
            address: _boxAddressSelect,
            time: _boxDataSelect,
            assign_id: add_item.find("input[name='assign_driver_id']"),
            order_product_addressid: add_item.find("input[name='order_product_addressid']"),
            order_product_timeid: add_item.find("input[name='order_product_timeid']"),
            flag: flag
        });
        options.data.id = box_id;
    }

    _AssignPop.prototype.setEdit = function(opt){
        var that = this;
        type = that.type = 'edit';
        title = '修改司机/装箱信息';
        res_mssage = '修改成功！';
        this.address_list = [];

        that.DOM.items_container.html('');
        that.DOM.items_complete_container.html('');

        that.driverSelector.setSelectedText(opt.driver_info.name + '/' + opt.driver_info.car_number);
        that.driverSelector.setSelectedVal(opt.driver_info.id);
        
        if(typeof opt.driver_info.driver_can_change === 'boolean' && !opt.driver_info.driver_can_change){
            that.pop.find('.driver-name').text(opt.driver_info.name).show();
            that.DOM.driver.hide();
        } else {
            that.pop.find('.driver-name').text(opt.driver_info.name).hide();
            that.DOM.driver.show();
        }

        if(opt.address_info.length == 0){
            flag = that.flag = 1;
            var add_item = $(item_tpl({
                flag: flag,
                canChange: true,
                assign_driver_id: '',
                order_product_timeid: ''
            }));
            that.DOM.items_container.html(add_item);

            
            //选择组件,装箱时间
            var _boxDataSelect = new select({
                width:380,
                container : add_item.find(".boxData-selector"),
                options : [],
                defaultText: '请选择装箱时间',
                defaultValue : '0',
                options_max_height: 160,
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');
                }
            });

            //选择组件,装箱地址
            var _boxAddressSelect = new select({
                width:380,
                container : add_item.find(".boxAddress-selector"),
                options : ADDRESS_OPTIONS,
                defaultText: '请选择装箱地址',
                defaultValue : '0',
                onSelectChange: function(val, txt, obj){
                    obj.select.select_box.removeClass('error');

                    _boxDataSelect.setSelectedVal('');
                    _boxDataSelect.setSelectedText('请先选择装箱地址');
                    _boxDataSelect.setOptions([]);
                    (function(val){
                        TIME_OPRTIONS = [];
                        for (var i = 0; i < options.select_options[i].length; i++) {
                            
                            if(val == options.select_options[i].product_address_id){
                                var box_time = options.select_options[i].box_date;
                                for (var j = 0; j < box_time.length; j++) { 
                                    TIME_OPRTIONS.push({
                                        value: box_time[j].product_time_id,
                                        text: box_time[j].product_supply_time
                                    });
                                };
                            }
                        };
                    })(val);

                    _boxDataSelect.setSelectedText('请选择装箱时间');
                    _boxDataSelect.setOptions(TIME_OPRTIONS);
                }
            });
            that.address_list.push({
                address: _boxAddressSelect,
                time: _boxDataSelect,
                assign_id: add_item.find("input[name='assign_driver_id']"),
                order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                flag: flag
            });
            
        } else {
            var newFlag = 1;
            
            for (var i = 0; i < opt.address_info.length; i++) { 

                if(opt.address_info[i].assign_status > 2){
                    var complete_item = $(complete_item_tpl({
                        box_address_detail: opt.address_info[i].box_address_detail,
                        box_time: opt.address_info[i].box_time
                    }));

                    that.DOM.items_complete_container.append(complete_item);
                }

                var canChange = false;
                if(opt.address_info[i].assign_status < 3){
                    canChange = true;
                }
                var add_item = $(item_tpl({
                    flag: newFlag,
                    canChange: canChange,
                    assign_driver_id: opt.address_info[i].assign_id,
                    order_product_addressid: opt.address_info[i].product_address_id,
                    order_product_timeid: opt.address_info[i].product_time_id
                }));
                that.DOM.items_container.append(add_item);
                
                (function(val){
                    TIME_OPRTIONS = [];
                    for (var m = 0; m < options.select_options.length; m++) {
                        
                        if(val == options.select_options[m].product_address_id){

                            var box_time = options.select_options[m].box_date;
                            for (var j = 0; j < box_time.length; j++) { 
                                TIME_OPRTIONS.push({
                                    value: box_time[j].product_time_id,
                                    text: box_time[j].product_supply_time
                                });
                            };
                        }
                    };
                })(opt.address_info[i].product_address_id);
                var $timeSelectContainer = add_item.find(".boxData-selector");
                var $addressSelectContainer = add_item.find(".boxAddress-selector");

                if($timeSelectContainer.length !== 0 || $addressSelectContainer.length !== 0){
                    //选择组件,装箱时间
                    var _boxDataSelect = new select({
                        width:380,
                        container : add_item.find(".boxData-selector"),
                        options : TIME_OPRTIONS,
                        defaultText: opt.address_info[i].box_time,
                        defaultValue : opt.address_info[i].product_time_id,
                        options_max_height: 160,
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');
                        }
                    });

                    //选择组件,装箱地址
                    var _boxAddressSelect = new select({
                        width:380,
                        container : add_item.find(".boxAddress-selector"),
                        options : ADDRESS_OPTIONS,
                        defaultText: opt.address_info[i].box_address_detail,
                        defaultValue : opt.address_info[i].product_address_id,
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');

                            _boxDataSelect.setSelectedVal('');
                            _boxDataSelect.setSelectedText('请先选择装箱地址');
                            _boxDataSelect.setOptions([]);
                            (function(val){
                                TIME_OPRTIONS = [];
                                for (var i = 0; i < options.select_options.length; i++) {

                                    if(val == options.select_options[i].product_address_id){
                                        var box_time = options.select_options[i].box_date;

                                        for (var j = 0; j < box_time.length; j++) { 
                                            TIME_OPRTIONS.push({
                                                value: box_time[j].product_time_id,
                                                text: box_time[j].product_supply_time
                                            });
                                        };
                                    }
                                };
                            })(val);

                            _boxDataSelect.setSelectedText('请选择装箱时间');
                            _boxDataSelect.setOptions(TIME_OPRTIONS);
                        }
                    });
                    that.address_list.push({
                        address: _boxAddressSelect,
                        time: _boxDataSelect,
                        assign_id: add_item.find("input[name='assign_driver_id']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        flag: newFlag
                    });
                } else {
                    that.address_list.push({
                        assign_id: add_item.find("input[name='assign_driver_id']"),
                        order_product_addressid: add_item.find("input[name='order_product_addressid']"),
                        order_product_timeid: add_item.find("input[name='order_product_timeid']"),
                        flag: newFlag
                    });
                }
                newFlag++;
            };
            
        }
        options.data.id = opt.box_id;
    }
    _AssignPop.prototype.onComplete = function(){}

    _AssignPop.prototype.init_popup = function(){
        var that = this;
        /** title set */
        that.DOM.title.text(title);

        /** 加载司机options */
        XDD.Request({
            url: '/carteam/order/choose_driver',
            type: 'post',
            data: {
                carTeamId: options.carTeam_id
            },
            success: function(res){
                if(res.error_code == 0){
                    var res_driver_list = res.data,
                        list_length = res_driver_list.length;
                    if(list_length == 0){
                        that.msg = '您未添加司机信息，请与管理员联系！'
                        return
                    }

                    var i, 
                        driver_list = [];

                    for (var i = 0; i < list_length; i++) {
                        driver_list.push({
                            value: res_driver_list[i].userid,
                            text: res_driver_list[i].driver_name + '/' + res_driver_list[i].car_number,
                        });
                    };


                    that.driverSelector = new select({
                        width:380,
                        container : that.DOM.driver,
                        options : driver_list,
                        defaultText: '请选择司机',
                        defaultValue : '',
                        onSelectChange: function(val, txt, obj){
                            obj.select.select_box.removeClass('error');
                        }
                    });
                    that.can_open = true;
                    that.msg = '';
                } else {
                    that.msg = res.error_msg || '未知错误！';
                    return
                }
            }
        },true);
    }

    function save(that){
        var driver_id = that.driverSelector.val();
        var driverInfo = (function(str){
            var check_index = str.indexOf('/');
            var _driverInfo = {};
            var _driver_name = str.substring(0, check_index);
            var _car_num = str.substring(check_index + 1, str.length);

            return {
                driver_name: _driver_name,
                car_num: _car_num
            }
        })(that.driverSelector.text());

        var onComplete_data = {
            driver_info: {
                driver_id: driver_id,
                driver_name: driverInfo.driver_name,
                car_num: driverInfo.car_num
            },
            address_info: []
        };
        if(!driver_id || driver_id == 0){
            that.driverSelector.select.select_box.addClass('error');
            shake(that.driverSelector.select.select_box);
            return
        }
        var order_assign_info = getAddressList(that.address_list);

        if(!order_assign_info || order_assign_info.length === 0) return;

        var i,
            info_length = order_assign_info.length;

        for (i = 0; i < info_length; i++) {
            order_assign_info[i].driver_user_id = driver_id;
            order_assign_info[i].order_freight_boxid = options.data.id;
            order_assign_info[i].order_freight_id = options.data.orderId;

            onComplete_data.address_info.push({
                product_address_id: order_assign_info[i].order_product_addressid,
                box_address_detail: order_assign_info[i].order_product_address_text,
                product_time_id: order_assign_info[i].order_product_timeid,
                box_time: order_assign_info[i].order_product_time_text
            });

            delete order_assign_info[i].order_product_address_text;
            delete order_assign_info[i].order_product_time_text;
            if(!order_assign_info[i].assign_id){
                delete order_assign_info[i].assign_id;
            }
            if(!order_assign_info[i].order_product_addressid){
                delete order_assign_info[i].order_product_addressid;
            }
            if(!order_assign_info[i].order_product_timeid){
                delete order_assign_info[i].order_product_timeid;
            }
        };

        XDD.Request({
            url: '/carteam/order/assign_edit_save',
            data: {
                order_assign_info: order_assign_info
            },
            success: function(res){
                if(res.error_code == 0){
                    that.hide();
                    _alert(res_mssage);
                    if(typeof that.onComplete === 'function'){
                        that.onComplete(onComplete_data);
                    }
                } else {
                    that.hide();
                    _alert(res.error_msg || '未知错误！');
                }
            }
        }, true);
    }

    function getAddressList(_list){
        var i,
            address_length = _list.length;

        var isPass = true;
        var list = [];
        for (i = 0; i < address_length; i++) {

            if(_list[i].address && _list[i].address){
                if(!_list[i].address.val() || _list[i].address.val() == 0){
                    _list[i].address.select.select_box.addClass('error');
                    shake(_list[i].address.select.select_box);
                    isPass = false;
                } else if(!_list[i].time.val() || _list[i].time.val() == 0){
                    _list[i].time.select.select_box.addClass('error');
                    shake(_list[i].time.select.select_box);
                    isPass = false;
                } else {
                    list.push({
                        order_product_addressid: _list[i].address.val(),
                        order_product_address_text: _list[i].address.text(),
                        order_product_timeid: _list[i].time.val(),
                        order_product_time_text: _list[i].time.text(),
                        assign_id: _list[i].assign_id.val()
                    });
                }
            } else {

                list.push({
                    order_product_addressid: _list[i].order_product_addressid.val(),
                    order_product_timeid: _list[i].order_product_timeid.val(),
                    order_product_address_text: '',
                    order_product_time_text: '',
                    assign_id: _list[i].assign_id.val()
                });
            }
        };

        if(isPass) return list;
        if(!isPass) return false;
    }

    function shake(obj){ 
        obj.animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40)
           .animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40);
    }

    //新建select
    function AddSelector($scope){
        this.$scope = $scope;
        var _boxaddress = $scope.find(".boxAddress-selector"),
            _boxData = $scope.find(".boxData-selector") ;
        //选择组件,装箱地址
        var _boxAddressSelect = new select({
            width:380,
            container : _boxaddress,
            options : boxAddress,
            defaultText: '请选择装箱地址',
            defaultValue : '0'
        });

        //选择组件,装箱时间
        var _boxDataSelect = new select({
            width:380,
            container : _boxData,
            options : boxData,
            defaultText: '请选择装箱时间',
            defaultValue : '0'
        });
        return;
    }

    return new _AssignPop();
}

module.exports = AssignPop;


});
