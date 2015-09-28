define('order/module/ensupe_pop/ensupe_pop', function(require, exports, module) {

/**
 * [上传或修改箱号铅封号]
 * @type {[type]}
 */
var popup = require('common/module/popup/popup.js');
var tpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="editBoxNum"><div class="editBoxNum-title clearfix"><div class="editBoxNum-cancel"><button class="cancelBtn">取消</button></div><div class="title-text">上传箱号/铅封号</div><div class="editBoxNum-comfire"><button class="comfireBtn">确定</button></div></div><dl class="editBoxNum-items clearfix"><dd class="item"><div class="item-name"><label for="editBoxNum-box-num">箱号：</label></div><div class="item-content"><input type="hidden" id="editBoxNum-box-id" name="editBoxNum-box-id" value="" /><input type="text" placeholder="请输入箱号" id="editBoxNum-box-num" name="editBoxNum-box-num" value="" /></div></dd><dd class="item"><div class="item-name"><label for="editBoxNum-seal-num">铅封号：</label></div><div class="item-content"><input type="text" placeholder="请输入铅封号" id="editBoxNum-seal-num" name="editBoxNum-seal-num" value="" /></div></dd></dl></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
function EnsupePop(_option){
    var html,
        title,
        defaults = {
            data: {
                id: '',
                boxNum: '',
                sealNum: '',
                orderId:''
            },
            type: 'add'
        },
        options = $.extend({}, defaults, _option);

    if(options.type === 'add'){
        title = '上传箱号/铅封号';
    } else {
        title = '修改箱号/铅封号';
    }
    
	function _EnsupePop(options){
        var type = this.type = options.type;

        var DOM = this.DOM = {};
        this.DOM = {
            title: this.pop.find('.editBoxNum-title .title-text'),
            comfireBtn: this.pop.find('.editBoxNum-title .editBoxNum-comfire .comfireBtn'),
            cancelBtn: this.pop.find('.editBoxNum-title .editBoxNum-cancel .cancelBtn'),
            boxId: this.pop.find('.editBoxNum-items #editBoxNum-box-id'),
            boxNum: this.pop.find('.editBoxNum-items #editBoxNum-box-num'),
            sealNum: this.pop.find('.editBoxNum-items #editBoxNum-seal-num')
        }

        /** run function */
        init_popup(this);

        /** bind event */
        var that = this;
        this.DOM.cancelBtn.on('click', function(event) {
            that.hide();
        });
        this.DOM.comfireBtn.on('click', function(event) {
            save(that);
            //requestData();
        });
	}

	_EnsupePop.prototype = new popup({
        height:300,
        width :700,
        tpl:tpl
    });

    _EnsupePop.prototype.constructor = _EnsupePop;

    _EnsupePop.prototype.setAdd = function(_opt){
        type = this.type = 'add';
        title = '上传箱号/铅封号';

        this.DOM.title.text(title);
        this.DOM.boxId.val(_opt.boxId);
        this.DOM.boxNum.val('');
        this.DOM.sealNum.val('');
    }

    _EnsupePop.prototype.setEdit = function(_editData){
        if(!_editData.boxId) return;
        if(!_editData.boxNum) return;
        if(!_editData.sealNum) return;

        type = this.type = 'edit';
        title = '修改箱号/铅封号';

        this.DOM.title.text(title);
        this.DOM.boxId.val(_editData.boxId);
        this.DOM.boxNum.val(_editData.boxNum);
        this.DOM.sealNum.val(_editData.sealNum);
    }

    _EnsupePop.prototype.onComplete = function(){};

    function init_popup(that){
        /** title set */
        that.DOM.title.text(title);
        that.DOM.boxId.val(options.data.id);
        that.DOM.boxNum.val(options.data.boxNum);
        that.DOM.sealNum.val(options.data.sealNum);
    }

    function save(that){
        var url, subData, msg;
        if(that.type === 'add'){
            var url = '/carteam/order/save_box_ensupe';
            subData = {
                box_code: that.DOM.boxNum.val(),
                box_ensupe: that.DOM.sealNum.val(),
                order_box_id: that.DOM.boxId.val(),
                order_freight_id:options.data.orderId

            };
            msg = '上传成功！'
        } else {
            var url = '/carteam/order/save_box_ensupe';
            subData  = {
                order_box_id: that.DOM.boxId.val(),
                box_code: that.DOM.boxNum.val(),
                box_ensupe: that.DOM.sealNum.val(),
                order_freight_id:options.data.orderId
            };
            msg = '修改成功！'
        }
        XDD.Request({
            url: url,
            data: subData,
            success: function(res){
                if(res.error_code == 0){
                    that.hide();
                    _alert(msg);
                    if(typeof that.onComplete === 'function'){
                        that.onComplete(subData);
                    }
                    
                } else {
                    _alert(res.error_msg || '未知错误！');
                }
            }
        }, true);

    }

	return new _EnsupePop(options);
}
module.exports = EnsupePop;

});
