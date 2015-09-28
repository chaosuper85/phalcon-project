[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="assignPop"><div class="assignPop-title clearfix"><div class="assignPop-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传信息</div><div class="assignPop-comfire"><button class="comfireBtn">确定</button></div></div><dl class="assignPop-driver-item first clearfix"><dd class="item"><div class="item-name"><label for="assignPop-seal-num">司机：</label></div><div class="item-content"><p class="driver-name"></p>                <div id="driver-selector"></div></div>            <div class="item-message clearfix">                <div class="error-message hidden"><i class="icon-warn"></i></div>                <div class="right-message hidden"><i class="icon-right"></i></div>            </div></dd></dl><div class="assignPop-address-complete-info"></div><div class="assignPop-address-info"></div><button class="add-other-BoxInfo">添加其他产装信息</button></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]