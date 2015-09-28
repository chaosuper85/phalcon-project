define('www/order/widget/com/order_file_download/order_file_download', function(require, exports, module) {


function bind(){

    $('#download-all').on('click',function(){
        var href_one =$('#download-one').attr('href'),
            href_two =$('#download-two').attr('href'),
            length = $('body').children('iframe').size();
        console.log('length=='+length)
        if(length<2){
            $('body').append('<iframe src="' + href_one + '" style="display:none;"target="_blank"/><iframe src="' + href_two + '" style="display:none;"target="_blank"/>');
        }else{
            var iframe = $('body').children('iframe');
            $(iframe).remove();
            var _length = $('body').children('iframe').size();
            console.log('_length=='+_length)
            if(_length<2){
                $('body').append('<iframe src="' + href_one + '" style="display:none;" target="_blank"/><iframe src="' + href_two + '" style="display:none;" target="_blank"/>');
            }
        }
    })
}

module.exports = {
	init:function(){
        bind();
	}
}

});
