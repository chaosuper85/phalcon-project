[function(_template_object) {
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

}][0]