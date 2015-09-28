[function(_template_object) {
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

}][0]