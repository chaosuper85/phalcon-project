define('order/module/remark/remark', function(require, exports, module) {

/*
 *  评分弹窗
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
_template_fun_array.push('<div id="remark" class="unselectable"><h3>请对车队服务做出评价</h3><ul data-star="-1" class="star-wrapper"><li><span></span></li><li><span></span></li><li><span></span></li><li><span></span></li><li><span></span></li></ul><div class="remark-content"><div class="content-wrap">请选择</div></div><div class="submit-wrap"><a href="javascript:;" class="user-btn" id="remark-submit">提交评价</a></div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var REMARK_TXT = ['失望','不满','一般','满意','很满意'];

var remark = function(){

	function _remark(){
		this.wrap = $('.star-wrapper');
		this.stars = this.wrap.find('li');
		this.wrap_txt = $('.content-wrap');
    	
    	bind.call(this);
	}

	_remark.prototype = new popup({
	    title :false,
	    height:380,
	    width :330,
	    tpl:tpl
	});

	_remark.prototype.constructor = _remark;

	_remark.prototype.setId = function(id){
		this.starOff();
		this.orderId = id;
	}

	_remark.prototype.starOn = function(index){
		this.starOff();

		var targets = [];
		
		for(var i=0;i<=index;i++){
			targets.push(this.stars[i])
		}

		$(targets).addClass('on');
		this.wrap_txt.html(REMARK_TXT[index]);
	}

	_remark.prototype.starOff = function(){
		this.stars.removeClass('on');
		this.wrap_txt.removeClass('red');
		this.wrap_txt.html('请选择');return;
	}

	function bind(){
		
		var me = this;

		me.stars.on('mouseenter',function(){
			var obj = $(this),
				index = obj.index();
			me.starOn(index);
		}).click(function(){
			var obj = $(this),
				index = obj.index();
			me.wrap.attr('data-star',index);
		})

		me.stars.on('mouseleave',function(){
			var _value = me.wrap.attr('data-star');
			me.starOn(_value);
		})

		$('#remark-submit').click(function(){
			var _value = parseInt(me.wrap.attr('data-star'))+1;
			if(_value<=0){
				me.wrap_txt.addClass('red')
			}else{
				console.log({
					orderID:me.orderId,
					value:_value
				})


              //  提交评价
                XDD.Request({
                    url : '/order/comment/detail',
                    data: {
                        order_id : me.orderId,
                        score : _value
                    },
                    success : function(result){
                        if(result.error_code == 0){
                            me.hide();
                            _alert('评价成功！')
                            setTimeout("window.location.href ='/order/list'",1000)
                        }else{
                            me.hide();
                            _alert(result.error_msg);
                        }
                    }
                },true)
			}
		})
	}

	return new _remark();

}

module.exports = remark;


});
