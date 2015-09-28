define('www/order/widget/view/order_details/order_details', function(require, exports, module) {

var EditProduct = require("www/order/module/edit_product/edit_product.js");
var ConfiremProduct = require("www/order/module/confirm_product/confirm_product.js");
var Cards = require("www/common/module/cards/cards.js");
var PswConfirmPop = require("www/order/module/comfirm_psw_pop/comfirm_psw_pop.js");

function bind(addressInfo, orderid, dispatch){
	var pswConfirmPop = new PswConfirmPop();
   	var logisticsCards = new Cards({
    	width: 500
	});
	var DOM = {
		$scope: $("#order_details"),

		btnModify: $("#modify_productInfo"),
		btnQuit: $("#quit_order"),

		btnDispatch: $('#btn_dispatch'),
        btnRebuild: $("#reconstruct_order"),
        funcs_control: $('#order_details .funcs.control'),
        funcs_look: $('#order_details .funcs.look'),
        dispatch_info: $('#order_details .dispatch-info')
	}

	// 修改产装信息
	var editProduct = EditProduct({
		data: {
			addressInfo: addressInfo,
			orderid: orderid
		}
	});

	// 确认产装信息
	var confiremProduct = ConfiremProduct({
		data: {
			addressInfo: addressInfo,
			orderid: orderid
		},
		clickEditBtn: function(){
			editProduct.init();
			editProduct.bind();
			editProduct.show();
		},
		clickConfirmBtn: function(){
			DOM.funcs_control.show();
			DOM.funcs_look.hide();
			DOM.dispatch_info.addClass('dispatch-editing-info');
			logisticsCards.hide();
		}
	});
	if(dispatch && dispatch == 1){
		confiremProduct.show();
		logisticsCards.hide();
		
	} else if(dispatch && dispatch == 2){
		DOM.funcs_control.show();
		DOM.funcs_look.hide();
		DOM.dispatch_info.addClass('dispatch-editing-info');
		logisticsCards.hide();
	} else {
		DOM.funcs_control.hide();
		DOM.funcs_look.show();
		DOM.dispatch_info.removeClass('dispatch-editing-info');
		logisticsCards.hide();
	}

	// 修改产装信息按钮事件
	DOM.btnModify.click(function() {
		editProduct.init();
		editProduct.bind();
		editProduct.show();
		logisticsCards.hide();
	});
	DOM.btnQuit.click(function(){
		pswConfirmPop.show();
		pswConfirmPop.onComplete = function(pws){
			XDD.Request({
				url: '/carteam/order/reConstruct_verify',
				data: {
					orderid: orderid,
					password: pws
				},
				type: 'get',
				success: function(res){
					pswConfirmPop.hide();
					if(res.error_code == 0){
						_alert('退载成功！');
						window.location.href = '/orderDetail?order_id=' + orderid;
					} else {
						_alert(res.error_msg || '退载失败！');
					}

				}
			})
		}
	});
    var timerCard;
    DOM.$scope.on('mouseover', '.order-info .second-title .edit-records', function(e) {
        var this_obj = $(this);
        var showCards = function(this_obj){
        	var order_id = this_obj.attr('data-orderid');

	        var html = '';
	        historyCards.hide();
	        XDD.Request({
	            url: '/carteam/order/modify_history',
	            type: 'get',
	            data:{
	                orderid: order_id
	            },
	            success: function(res){
	                if(res.error_code == 0){
	                    var i,
	                        length = res.data.length;
	                    if(length > 0){
	                        for (i = 0; i < length; i++) {
	                            html += '<p>' + res.data[i].date + '</p>\
	                            <p><span style="margin-right:5px;">'+res.data[i].user+'</span>'+res.data[i].operateType+'</p>\
	                            <p style="margin-bottom:15px;">'+res.data[i].content+'</p>'; 
	                        };
	                    } else {
	                        html = '<p style="text-align:center;">暂无修改记录！</p>'
	                    }
	                    
	                    historyCards.setContent(html);
	                    historyCards.show(e);
	                } else {
	                    historyCards.setContent(html);
	                    historyCards.show(e);
	                }
	            }
	        });
        }

        timerCard = setTimeout(function(){showCards(this_obj)}, 800);
        
    }).on('mouseleave', '.order-info .second-title .edit-records', function(e) {
    	clearTimeout(timerCard);
        historyCards.hide();
    });


}


module.exports = {
	init:function(addressInfo, orderid, dispatch){
		bind(addressInfo, orderid, dispatch);
	}
}

});
