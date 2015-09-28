[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<ul class="XDD_searchBox_result"></ul>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]