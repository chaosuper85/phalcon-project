define('www/order/widget/view/order_trace/templete/order_trace_details/order_trace_details', function(require, exports, module) {

var infoWindowTpl = [function(_template_object) {
var _template_fun_array=[];
var fn=(function(__data__){
var _template_varName='';
for(var name in __data__){
_template_varName+=('var '+name+'=__data__["'+name+'"];');
};
eval(_template_varName);
_template_fun_array.push('<div id="trace-info" class="info ',typeof(type) === 'undefined'?'':baidu.template._encodeHTML(type),'"><div class="text">',typeof(text) === 'undefined'?'':baidu.template._encodeHTML(text),'</div><div class="square"></div></div>');
_template_varName=null;
})(_template_object);
fn = null;
return _template_fun_array.join('');

}][0];
var Map = require('www/common/module/map/map.js');
var DOM = {
	item: $("#trace_details_content .content li"),
	box_details: $('#trace_details_content .details .box_detail'),
	trace_address: $("#trace_details_content .box_address span")
}

module.exports = {
	init : function(boxData){
		var trace_map = new Map('trace_map');
		windowOnLoad();
		showBoxDetail(boxData, trace_map);
		watchHashChange(boxData, trace_map);
	}
}

/**
 * [监听window完成加载]
 * @return {[type]} [description]
 */
function windowOnLoad(){
	window.onload = function(){
		setLineHeight();
	}
}

/**
 * [设置每一个流程线条高度]
 * @param  {[type]} ){			var obj           [description]
 * @return {[type]}           [description]
 */
function setLineHeight(){
	DOM.item_line = DOM.item.find('.line');
	DOM.item_line.each(function(){
		var obj = $(this);
		var parent_item = obj.parent('.traceline').parent('li');
		var _height = parent_item.outerHeight();
		obj.height(_height);
	});
}

/**
 * [展示详情]
 * @return {[type]} [description]
 */
function showBoxDetail(boxData, map){
	var _href = window.location.href;
	var getHashIndex = _href.indexOf('#/');
	var getBoxNum = _href.substring(getHashIndex + 2, _href.length);
	DOM.box_details.hide();
	if(!getBoxNum || isNaN(getBoxNum) || getBoxNum == 1 || !DOM.box_details[getBoxNum - 1]){
		$(DOM.box_details[0]).show();
		setLineHeight();
		if(boxData[0] && boxData[0].location_info){
			console.log(boxData[0].location_info.current);
			setMapPoint({
				packing_point: {
					lng: boxData[0].location_info.chanzhuang.latitude || 0,
					lat: boxData[0].location_info.chanzhuang.longitude || 0,
				},
				current_point: {
					lng: boxData[0].location_info.current.latitude || 0,
					lat: boxData[0].location_info.current.longitude || 0,
				},
				drop_point: {
					lng: boxData[0].location_info.duichang.latitude || 0,
					lat: boxData[0].location_info.duichang.longitude || 0,
				}
			}, map);
		} else {
			map.clear();
		}
	} else {
		$(DOM.box_details[getBoxNum - 1]).show();
		setLineHeight();
		if(boxData[getBoxNum] && boxData[getBoxNum].location_info){
			setMapPoint({
				packing_point: {
					lng: boxData[getBoxNum].location_info.chanzhuang.latitude || 0,
					lat: boxData[getBoxNum].location_info.chanzhuang.longitude || 0,
				},
				current_point: {
					lng: boxData[getBoxNum].location_info.current.latitude || 0,
					lat: boxData[getBoxNum].location_info.current.longitude || 0,
				},
				drop_point: {
					lng: boxData[getBoxNum].location_info.duichang.latitude || 0,
					lat: boxData[getBoxNum].location_info.duichang.longitude || 0,
				}
			}, map);
		} else {
			map.clear();
		}
	}
}

/**
 * [监听hash变化]
 * @return {[type]} [description]
 */
function watchHashChange(boxData, map){
	window.onhashchange = function(){
		console.log(boxData);
		showBoxDetail(boxData, map);
	}
}

/**
 * [设置地图坐标点]
 * @param {[type]} opt [description]
 */
function setMapPoint(opt, map){
	if(!opt.current_point) return;
	if(!opt.packing_point) return;
	if(!opt.drop_point) return;
	
	map.clear();
	// 自定义html
	var current_html = infoWindowTpl({
		type: 'current',
		text: '当前位置'
	});
	var packing_html = infoWindowTpl({
		type: 'normal',
		text: '产装地址'
	});
	var drop_html = infoWindowTpl({
		type: 'normal',
		text: '落箱堆场'
	});

	// 当前位置创建标注
	var current_marker = map.createMarker({
		point: opt.current_point
	});
	// 产装位置标注
	var packing_marker = map.createBespokeMarker({
		point: opt.packing_point,
		html: packing_html,
		offset: {
			left: -50,
			top: -12
		}
	});
	// 落箱位置标注
	var drop_marker = map.createBespokeMarker({
		point: opt.drop_point,
		html: drop_html,
		offset: {
			left: -50,
			top: -12
		}
	});
	// 创建信息窗口
	var current_infowindow = map.createInfoWindow({
		point: opt.current_point,
		html: current_html,
		onOpenning: true,
		offset: {
			left: 2,
			top: 3
		}
	});

	// 获取当前位置
	map.getAddressByPoint({
		point: opt.current_point
	}, function(data){
		var h = DOM.trace_address.html(data.regeocode.formattedAddress);
	});
}

});
