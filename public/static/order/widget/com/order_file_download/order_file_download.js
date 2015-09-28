define('order/widget/com/order_file_download/order_file_download', function(require, exports, module) {


var $download = $("#download-all");
$download.show();

function bind(){
    var href_one =$('#download-one').attr('href'),
        href_two =$('#download-two').attr('href');

        $download.on('click',function(){
            download(href_one,href_two);
        })
    // if(!-[1,]){
    //    $download.on('click',function(){
    //         var length = $('body').children('iframe').size();
    //         if(length < 2){
    //             $('body').append('<iframe src="' + href_one + '" style="display:none;"target="_blank"/><iframe src="' + href_two + '" style="display:none;"target="_blank"/>');
    //         }else{
    //             var iframe = $('body').children('iframe');
    //             $(iframe).remove();
    //             var _length = $('body').children('iframe').size();
    //             console.log('_length=='+_length)
    //             if(_length<2){
    //                 $('body').append('<iframe src="' + href_one + '" style="display:none;" target="_blank"/><iframe src="' + href_two + '" style="display:none;" target="_blank"/>');
    //             }
    //         }
    //     })
    // }else{
    // }
}
function download(){
    var iframes = [];
    for(var i=0; i<arguments.length; i++) {
        if(!arguments[i]){
            continue;
        }
        var _iframe = $('<iframe style="visibility: collapse;"></iframe>');
        iframes.push(_iframe);
        $('body').append(_iframe);
        var content = _iframe[0].contentDocument;
        var form = '<form action="' + arguments[i] + '" method="GET"></form>';
        content.write(form);
        $('form', content).submit();
    }

    setTimeout(function(){
        for(var i=0,j=iframes.length; i<j;i++){
            iframes[i].remove();
        }
    },3000);
 }

module.exports = {
	init:function(){
        bind();
	}
}

});
