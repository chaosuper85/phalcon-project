[function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="editBoxNum"><div class="editBoxNum-title clearfix"><div class="editBoxNum-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传箱号/铅封号</div><div class="editBoxNum-comfire"><button class="comfireBtn">确定</button></div></div><dl class="editBoxNum-items clearfix"><dd class="item"><div class="item-name"><label for="editBoxNum-box-num">箱号：</label></div><div class="item-content"><input type="hidden" id="editBoxNum-box-id" name="editBoxNum-box-id" value="" /><input type="text" placeholder="请输入箱号" id="editBoxNum-box-num" name="editBoxNum-box-num" value="" /maxlength="11"></div><div class="item-message clearfix"><div class="error-rule hidden"><i class="icon-warn"></i><span>箱号只能是数字或者英文字母组成</span></div><div class="error-message hidden"><i class="icon-warn"></i><span>您未输入箱号</span></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dd><dd class="item-ensupe"><div class="item-name"><label for="editBoxNum-box-num"></label></div></dd><dd class="item"><div class="item-name"><label for="editBoxNum-seal-num">铅封号：</label></div><div class="item-content"><input type="text" placeholder="请输入铅封号" id="editBoxNum-seal-num" name="editBoxNum-seal-num" value="" maxlength="30"/></div><div class="item-message clearfix"><div class="error-rule hidden"><i class="icon-warn"></i><span>铅封号只能是数字或者英文字母组成</span></div><div class="error-message hidden"><i class="icon-warn"></i><span>您未输入铅封号</span></div><div class="right-message hidden"><i class="icon-right"></i></div></div></dd><dd class="item-ensupe"><div class="item-name"><label for="editBoxNum-box-num"></label></div></dd></dl></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0]