define('order/widget/view/modify_record/modify_record', function(require, exports, module) {



function bind(){
        // var html = '';
        // XDD.Request({
        //     url: '/carteam/order/modify_history',
        //     type: 'get',
        //     data:{
        //         orderid: order_id
        //     },
        //     success: function(res){
        //         if(res.error_code == 0){
        //             var i,
        //                 length = res.data.length;
        //             if(length > 0){
        //                 for (i = 0; i < length; i++) {
        //                     html += '<p>' + res.data[i].date + '</p>\
        //                     <p><span style="margin-right:5px;">'+res.data[i].user+'</span>'+res.data[i].operateType+'</p>\
        //                     <p style="margin-bottom:15px;">'+res.data[i].content+'</p>'; 
        //                 };
        //             } else {
        //                 html = '<p style="text-align:center;">暂无修改记录！</p>'
        //             }
                    
        //             logisticsCards.setContent(html);
        //             logisticsCards.show(e);
        //         } else {
        //             logisticsCards.setContent(html);
        //             logisticsCards.show(e);
        //         }
        //     }
        // });
}










module.exports = {
	init:function(){
		bind();
	}
}

});
