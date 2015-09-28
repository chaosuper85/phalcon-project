define('order/widget/view/order_details/partial/box_info/box_info', function(require, exports, module) {

var EnsupePop = require("order/module/ensupe_pop/ensupe_pop.js");
var AssignPop = require("order/module/assign_pop/assign_pop.js");
var Cards = require("common/module/cards/cards.js");
var select = require('common/module/select/select.js');
var box_details_tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<ul class="box-no-complete ');if(length === i + 1){_template_fun_array.push('last');}_template_fun_array.push('"><li class="clearfix"><div class="address-content"><p class="address">',typeof( box_address_detail ) === 'undefined'?'':baidu.template._encodeHTML( box_address_detail ),'</p><p class="time">',typeof( box_time ) === 'undefined'?'':baidu.template._encodeHTML( box_time ),'</p></div><a href="javascript:;" class="complete" data-aid="',typeof( assign_driver_id ) === 'undefined'?'':baidu.template._encodeHTML( assign_driver_id ),'">产装完成</a></li></ul>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var Confirm = require('common/module/confirm/confirm.js');

var global_box;
var global_addressInfo;

var confirm_Pop = new Confirm();

function bind(_boxInfo, orderid, _addressInfo, carTeam_id){
    var logisticsCards = new Cards({
        width: 300
    });
    global_box = _boxInfo;
    global_addressInfo = _addressInfo;
	/** DOM */
	var DOM = {
		$scope: $('.boxInfo-wrapper')
	};

    /*shuzu*/
    var  box_address = [],
        contactName = [],
        box_data = [];

	/** popup */
	var ensupe_pop = EnsupePop({
        data: {
            orderId: orderid
        }
    });

    // 上传箱号铅封号
	DOM.$scope.on('click', '.funcs a.add-ensupe', function(event) {
		
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = this_obj.attr('data-id');
        
        var flag = getBoxAddressIndex(box_id);

        var $code_td = $($td[1]),
            $ensupe_td = $($td[2]);

        ensupe_pop.setAdd({
            boxId: box_id
        });
        ensupe_pop.show();
		ensupe_pop.onComplete = function(res){
            window.location.href = '/order/details?orderid=' + orderid + '&dispatch=2';
        };
	});
    // 修改箱号铅封号
	DOM.$scope.on('click', '.funcs a.edit-ensupe', function(event) {
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = this_obj.attr('data-id');
        
        var flag = getBoxAddressIndex(box_id);

        if(!global_box[flag].box_can_change){
            _alert('该箱子已经产装完成！');
            return;
        }

        var $code_td = $($td[1]),
            $ensupe_td = $($td[2]);

		ensupe_pop.setEdit({
			boxId: box_id,
            orderId: orderid,
			boxNum: global_box[flag].box_code,
			sealNum: global_box[flag].box_ensupe
		});
		ensupe_pop.show();
		ensupe_pop.onComplete = function(res){
            window.location.href = '/order/details?orderid=' + orderid + '&dispatch=2';
		};
	});

    // 添加司机／装箱信息
    var assign_pop = AssignPop({
        data: {
            orderId: orderid
        },
        carTeam_id: carTeam_id,
        select_options: global_addressInfo
    });

    DOM.$scope.on('click', '.funcs a.add-assign', function(event) {
        if(!assign_pop.can_open){
            _alert(assign_pop.msg);
            return
        }
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = $(this).attr('data-id');
        var flag = getBoxAddressIndex(box_id);

        var $driver_name_td = $($td[3]),
            $car_num_td = $($td[4]),
            $address_details = $($td[5]);

        assign_pop.setAdd(box_id);
        assign_pop.onComplete = function(res){
            window.location.href = '/order/details?orderid=' + orderid + '&dispatch=2';
        }
        assign_pop.show();
        
    });

    // 添加司机／装箱信息
    DOM.$scope.on('click', '.funcs a.edit-assign', function(event) {
        if(!assign_pop.can_open){
            _alert(assign_pop.msg);
            return
        }
        var this_obj = $(this);
        var $tr = this_obj.parents('tr');
        var $td = $tr.find('td');

        var box_id = $(this).attr('data-id');
        var flag = getBoxAddressIndex(box_id);

        var $driver_name_td = $($td[3]),
            $car_num_td = $($td[4]),
            $address_details = $($td[5]);

        assign_pop.setEdit(global_box[flag]);

        assign_pop.onComplete = function(res){
            window.location.href = '/order/details?orderid=' + orderid + '&dispatch=2';
        }
        assign_pop.show();
    });
    var timerCard;
    // message test
    DOM.$scope.on('mouseover', '.funcs i.icon-status-message', function(e) {
        var this_obj = $(this);

        var showCards = function(this_obj){
            logisticsCards.hide();
            var boxId = this_obj.attr('data-boxid');

            var html = '';
            XDD.Request({
                url: '/order/boxtimeline',
                type: 'get',
                data:{
                    orderboxid: boxId
                },
                success: function(res){
                    if(res.error_code == 0){
                        var i,
                            length = res.data.length;
                        if(length > 0){
                            for (i = 0; i < length; i++) {
                                html += '<p><span style="margin-right:5px;">' + res.data[i].create_time + '</span>' + res.data[i].content + '</p>'; 
                            };
                        } else {
                            html = '<p style="text-align:center;">暂无物流信息！</p>';
                        }
                        
                        logisticsCards.setContent(html);
                        logisticsCards.show(e);
                    } else {
                        logisticsCards.setContent(html);
                        logisticsCards.show(e);
                    }
                }
            });
        }
        timerCard = setTimeout(function(){showCards(this_obj)}, 800);
    }).on('mouseleave', '.funcs i.icon-status-message', function(e) {
        clearTimeout(timerCard);
        logisticsCards.hide();
    });


    DOM.$scope.on('click', '.address-details a.complete', function(event) {
        var this_obj = $(this);
        var aid = this_obj.attr('data-aid');
        var type;

        var txt = '';
        if(!aid) return;
        XDD.Request({
            url: '/order/assignStatus',
            type: 'get',
            data:{
                assignId: aid,
                orderId: orderid
            },
            success: function(res){
                if(res.error_code == 0){
                    type = res.data.status
                    if(type == 2){
                        txt = '司机未确认产装，是否确认产装？';
                    } else if(type == 100){
                        txt = '司机已确认产装，是否确认产装？';
                    } else {
                        return
                    }
                    confirm_Pop.setData(txt);
                    confirm_Pop.show();
                } else {
                    _alert(res.error_msg || '未知错误！');
                }
            }
        });

        confirm_Pop.onConfirm = function(){
            XDD.Request({
                url: '/carteam/order/driver_equipment_complete',
                data:{
                    assign_id: aid
                },
                success: function(res){
                    if(res.error_code == 0){
                        _alert('产装完成！');
                       window.location.reload();
                    } else {
                        _alert(res.error_msg || '未知错误！');
                    }
                }
            }, true);
        }
    });
}

/**
 * [根据box_id获取索引]
 * @param  {[type]} box_id [description]
 * @return {[type]}        [description]
 */
function getBoxAddressIndex(box_id){
    if(!global_box || !box_id) return;
    var flag,
        i,
        length = global_box.length;
    for (i = 0; i < length; i++) {
        if(global_box[i].box_id == box_id){
            flag = i;
        }
    };

    return  flag;
}

module.exports = {
	init:function(_address, orderid, _addressInfo, carTeam_id){
		bind(_address, orderid, _addressInfo, carTeam_id);
	}
}


});
