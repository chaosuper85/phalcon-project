[function(_template_object) {
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

}][0]