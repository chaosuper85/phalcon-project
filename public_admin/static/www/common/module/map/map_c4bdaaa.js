define('www/common/module/map/map', function(require, exports, module) {

/**
 * [地图]
 * @type {Object}
 * by weiqi
 */
module.exports = Map;

function Map(container, current_point){
	var point;
	try {
		point = new AMap.LngLat(current_point.lng, current_point.lat);
	} catch(e){
		console.log(e);
	}
	
	// 初始化地图
	this.map = new AMap.Map(container, {
		resizeEnable: true, 
		view:new AMap.View2D({
			zoom: 8,
			center: point || null
		})
	});
	this.infoWindow = [];

	//地图中添加地图操作ToolBar插件
	this.map.plugin(["AMap.ToolBar"], function(){		
		var toolBar = new AMap.ToolBar(); 
		this.map.addControl(toolBar);		
	});
}

// 暴露地图对象
Map.prototype.getMap = function(){
	return this.map;
}

/**
 * [设置marker以及图标]
 * @param  {point,img,size,offset} option [description]
 * @return marker [返回一个marker对象]
 */
Map.prototype.createMarker = function(_option){
	if(!_option.point){
		console.log('创建标注时，未添加point！');
		return
	};
	var option = {
		point: _option.point,
		img: _option.img || 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAdCAYAAAAgqdWEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OEIxQkM3RUYzQ0RFMTFFNUIzMjdCRkYxOTQ0RTdBRDYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OEIxQkM3RjAzQ0RFMTFFNUIzMjdCRkYxOTQ0RTdBRDYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0QzU4RTkzODNDREUxMUU1QjMyN0JGRjE5NDRFN0FENiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4QjFCQzdFRTNDREUxMUU1QjMyN0JGRjE5NDRFN0FENiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pi8TLeUAAAQNSURBVHjaxFdpSFRRFD5PR0ecNJU2ojIjiJIo2sykMrUoK7UFoZKiKPoXSVJUP6IoIykqCFqIgpiSDMSl0hZbKSwpsrL+SDoUNT9axDDmzeLrO+OZGoc39hyaOvB55t1z7rnfvefcRYVEvq2ZNx1qJ5AN7Em8ePcYhUkw1laovUADcBBjNXF7hBg3QTUCy4B4+jcSL+M1yvgUgR9p0Kd8xP6D8LinmAf/2P0fifgT2m3Cn8wgeY2EmgDMBGYAXcjt1hDqg2vPAjyVUngTxDWTycTpGLYB+yWITx6FOOtpQAawUb67mKOOXxwvj03HMCKACMu7EMkE9rNI/ECxMZkKg0GvhEjGaL8KBTlNwo/nQHIfjtdQL0v4h6ZpmeR2Z3tsrcPVhhrV+aCea8sMqNFzFnrM2XnmyOSxH8lkalAU5Z7UzVWoxX3E5+xMUcR5DFQlMEmPMbAhwXrHpamOKsUcs8jf6LxfRz/OH6XY9cUUPbeXieBfB/+CjqKsKHyeAwp14jcDyzHZd4pf1XPKlsoJnAC8B2rg9AQ2xVJSejNq8swcvWm5nj+mqCmzdKfsetF4u+vwrgWIo8mZlgeMBDrkBK6Frdvo1owDrnk6vmgs7TablrdyhbZyzWrtk93ubet2Or2av7md7ezHwv24P8f501gmGXAI1AOA6ycHTF9KOx/Zt/iciRiY5O3wpKmJvn7r2ZkvX72iYUOHkmIy/fq22+2//JJHjSLplwvcRrz5iN0pscdB3eFMA7PR/sF38k4F2DhY0sTOnMJyOfCo2/7B65iTlUXpaWk0OyODMtLTfbXh1fzN7WxnP/9+Eqdc4pIctsOB0XKw9qyMMDwLDASs0rZFZuQVx/UrFLuhmJISE6nsQGmv5XU21JJ5cSFZLBYq3buvl437+UmuxD0OXAbmyMpcZ6MSpE54bdsCb/CY/CKKWb6WKLJnDppTJUf5aVJvVZF5fgHFrNpMSrS5x9njJkflBXJUWwPDc5pSkJavgYZgZLZDHdKzKfEJZBo7wTuYu/UtaV3ff9sscbCN95J1t74hrbMjWK3uAJky3QLWkfxgUXgA3sq6NhBzNT81skk5fpne1a0nqWF+MqQGe0cE3fJhFFN/yLSEmUxLf8hYw0zG2h8yZ8K4Oi0S3xgZbDsVqlheZX9TPMBDuXaMkcE5cxLqBh+gfOX8JSLPgFXAOn6/yFlmKE2azCJb7q0SaQtF3MAXvn+w4nw3FAH8vlGNbuFqgG/YZvk+gpnwW6YdGCAP7JQ+CLTJA/6SvJFUxHKLrVYmVm2UDD8XXwe0VQF2BK2WVA4SQolALPBDXv1t8Pnsl/KJ8m+Krx5daKuHbjdEhgsYHQIrnmc0xM+HB/xsIE0vpGj95YSe408BBgDTqYczw6S9bQAAAABJRU5ErkJggg==',
		offset: _option.offset || {},
		size: _option.size || {}
	}
	var offset = {
		left: option.offset.left || 0,
		top: option.offset.top || 0
	};
	var size = {
		width: option.size.width || 35,
		height: option.size.height || 29
	}
	var point = new AMap.LngLat(option.point.lng, option.point.lat);

	var marker = new AMap.Marker({
		icon: new AMap.Icon({    
			//图标大小
			size: new AMap.Size(size.width, size.height),
			//大图地址
			image: option.img
		}),
		position: point,
		offset: new AMap.Pixel(offset.left, offset.top) //相对于基点的偏移位置
	});

	marker.setMap(this.map);
	return marker;
}


/**
 * [自定义marker]
 * @param  {point,html,offset} option [description]
 * @return marker [返回一个marker对象]
 */
Map.prototype.createBespokeMarker = function(_option){
	if(!_option.point){
		console.log('创建信息窗口时，未添加point！');
		return
	};
	var option = {
		point: _option.point,
		html: _option.html || '',
		offset: _option.offset || {}
	}
	var offset = {
		left: option.offset.left || 0,
		top: option.offset.top || 0
	};
	var point = new AMap.LngLat(option.point.lng, option.point.lat);

	var marker = new AMap.Marker({
		map:this.map,
		position: point, //基点位置
		offset: new AMap.Pixel(offset.left, offset.top), //相对于基点的偏移位置
		draggable: false,  //是否可拖动
		content: option.html   //自定义点标记覆盖物内容
	});

	marker.setMap(this.map);
	return marker;
}

/**
 * [创建自定义信息窗口]
 * @param  {html,point,onOpenning, offset} option [description]
 * @return infoWindow [返回一个infoWindow对象]
 */
Map.prototype.createInfoWindow = function(_option){
	if(!_option.point){
		console.log('创建信息窗口时，未添加point！');
		return
	};
	var option = {
		point: _option.point,
		html: _option.html || '',
		offset: _option.offset || {},
		onOpenning: _option.onOpenning || true
	}
	var offset = {
		left: option.offset.left || 0,
		top: option.offset.top || 0
	};
	var point = new AMap.LngLat(option.point.lng, option.point.lat);

	var infoWindow = new AMap.InfoWindow({
		isCustom: true,  //使用自定义窗体
		content: option.html,
		offset: new AMap.Pixel(offset.left, offset.top),
		showShadow: true //显示阴影
	});
	if(option.onOpenning){
		infoWindow.open(this.map, point);
	}

	return infoWindow;
}

Map.prototype.clear = function(){
	this.map.clearMap();
	this.map.clearInfoWindow();
}

/**
 * [根据坐标获取位置]
 * @param  {point}   _option  [description]
 * @param  {Function} callback [回调函数]
 */
Map.prototype.getAddressByPoint = function(_option, callback){
	if(!_option.point){
		console.log('获取位置时，未添加point！');
		return
	};
	var option = {
		point: _option.point
	}
	var point = new AMap.LngLat(option.point.lng, option.point.lat);
	AMap.service(["AMap.Geocoder"], function() {        
        MGeocoder = new AMap.Geocoder({ 
            radius: 1000,
            extensions: "all"
        });
        //逆地理编码
        MGeocoder.getAddress(point, function(status, result){
        	if(status === 'complete' && result.info === 'OK'){
        		if(typeof callback !== 'undefined'){
        			callback(result);
        		}
        	}
        });
    });
}

});
