[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="XDD-loading-Btn" class="clearfix"><img class="icon-load-img" src="',typeof( img ) === 'undefined'?'':baidu.template._encodeHTML( img ),'"></i><span class="icon-load-txt">正在提交...</span></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]