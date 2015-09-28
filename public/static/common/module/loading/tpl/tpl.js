[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="XDD-loading"><div class="XDD-loading-bg"></div><img class="XDD-loading-img" src="',typeof( img ) === 'undefined'?'':baidu.template._encodeHTML( img ),'" alt="loading"></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]