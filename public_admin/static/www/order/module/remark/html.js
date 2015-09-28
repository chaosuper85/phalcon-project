[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="remark" class="unselectable"><h3>请对车队服务做出评价</h3><ul data-star="-1" class="star-wrapper"><li><span></span></li><li><span></span></li><li><span></span></li><li><span></span></li><li><span></span></li></ul><div class="remark-content"><div class="content-wrap">请选择</div></div><div class="submit-wrap"><a href="javascript:;" class="user-btn" id="remark-submit">提交评价</a></div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]