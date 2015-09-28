define('www/order/widget/view/order_details/partial/box_info/box_info', function(require, exports, module) {

var EnsupePop = require("www/order/module/ensupe_pop/ensupe_pop.js");
var AssignPop = require("www/order/module/assign_pop/assign_pop.js");
var Cards = require("www/common/module/cards/cards.js");
var select = require('www/common/module/select/select.js');
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
var Confirm = require('www/common/module/confirm/confirm.js');

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
		$scope: $('.boxInfo-wrapper'),
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

            // // 数据修改
            // global_box[flag].box_code = res.box_code;
            // global_box[flag].box_ensupe = res.box_ensupe;
            // // 数据回填
            // $code_td.text(res.box_code);
            // $ensupe_td.text(res.box_ensupe);
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
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
            _alert('该箱子已经产装完成！')
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
			// // 数据修改
   //          global_box[flag].box_code = res.box_code;
   //          global_box[flag].box_ensupe = res.box_ensupe;
   //          // 数据回填
   //          $code_td.text(res.box_code);
   //          $ensupe_td.text(res.box_ensupe);
   //          
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
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
            // $driver_name_td.text(res.driver_info.driver_name);
            // $car_num_td.text(res.driver_info.car_num);

            // /** 数据回填 */
            // global_box[flag].driver_info.id = res.driver_info.driver_id;
            // global_box[flag].driver_info.name = res.driver_info.driver_name;
            // global_box[flag].driver_info.car_number = res.driver_info.car_num;

            // /** 地址回填, 时间回填 */
            // var address_detail_html = '';
            // global_box[flag].address_info = [];

            // for (var i = 0; i < res.address_info.length; i++) {
            //     address_detail_html += box_details_tpl({
            //         i: i,
            //         length: res.address_info.length,
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         box_time: res.address_info[i].box_time
            //     });

            //     global_box[flag].address_info.push({
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         product_address_id: res.address_info[i].product_address_id,
            //         box_time: res.address_info[i].box_time,
            //         product_time_id: res.address_info[i].product_time_id
            //     });
            // };

            // var $box_completes = $address_details.find('.box-complete');
            // var $box_no_completes = $address_details.find('.box-no-complete');
            // var c_length = $box_completes.length;
            // var c_n_length = $box_no_completes.length;

            // if(c_n_length === 0){
            //     $address_details.html(address_detail_html);
            // } else {
            //     if(c_n_length !== 0){
            //         $box_no_completes.remove();
            //     }
            //     $($box_completes[c_length-1]).before(address_detail_html);
            // }

            // this_obj.html('修改箱号/铅封号');
            // this_obj.removeClass('add-assign').addClass('edit-assign');
            // 
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
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
            // $driver_name_td.text(res.driver_info.driver_name);
            // $car_num_td.text(res.driver_info.car_num);

            // /** 数据回填 */
            // global_box[flag].driver_info.id = res.driver_info.driver_id;
            // global_box[flag].driver_info.name = res.driver_info.driver_name;
            // global_box[flag].driver_info.car_number = res.driver_info.car_num;

            // /** 地址回填, 时间回填 */
            // var address_detail_html = '';
            // global_box[flag].address_info = [];

            // for (var i = 0; i < res.address_info.length; i++) {
            //     address_detail_html += box_details_tpl({
            //         i: i,
            //         length: res.address_info.length,
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         box_time: res.address_info[i].box_time
            //     });

            //     global_box[flag].address_info.push({
            //         box_address_detail: res.address_info[i].box_address_detail,
            //         product_address_id: res.address_info[i].product_address_id,
            //         box_time: res.address_info[i].box_time,
            //         product_time_id: res.address_info[i].product_time_id
            //     });
            // };

            // var $box_completes = $address_details.find('.box-complete');
            // var $box_no_completes = $address_details.find('.box-no-complete');
            // var c_length = $box_completes.length;
            // var c_n_length = $box_no_completes.length;

            // if(c_n_length === 0){
            //     $address_details.html(address_detail_html);
            // } else {
            //     if(c_n_length !== 0){
            //         $box_no_completes.remove();
            //     }
            //     $($box_completes[c_length-1]).before(address_detail_html);
            // }
            window.location.href = '/orderDetail?order_id=' + orderid + '&dispatch=2';
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
                            html = '<p style="text-align:center;">暂无物流信息！</p>'
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
                        txt = '司机未确认产装，是否确认产装？'
                    } else if(type == 100){
                        txt = '司机已确认产装，是否确认产装？'
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
