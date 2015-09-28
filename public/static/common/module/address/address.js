define('common/module/address/address', function(require, exports, module) {

var storage = require('common/module/storage.js');
var SelectBox = require('common/module/select/select.js');

/**
 * [地址选择]
 * @param  {[type]} opt [description]
 * @return {[type]}     [description]
 * by weiqi
 */
function _Address(opt){
	var select_province,
		select_city,
		select_area,
		address_data,
		onProvinceSelectChange,
		onCitySelectChange,
		onAreaSelectChange,
		_level;

	/**
	 * [地址选择框]
	 * @param {[type]} _option [description]
	 */
	function Address(_option){
		var option = {
			wrap: _option.wrap || '',
	        width: _option.width || 80,
	        height: _option.height || 30,
	        font_size: _option.font_size || 14,
	        onProvinceSelectChange: _option.onProvinceSelectChange || '',
	        onCitySelectChange: _option.onCitySelectChange || '',
	        onAreaSelectChange: _option.onAreaSelectChange || '',
	        options_max_height: _option.options_max_height || 200,
	        level: _option.level || 2,
	        text_align: _option.text_align || 'left'
		}
		var dom = {
			wrap : $(option.wrap) || null,
			province : $('<div class="address_province"></div>'),
			city : $('<div class="address_city"></div>'),
			area : $('<div class="address_area"></div>')
		};

		this.select = {};
		this.province = {};
		this.city = {};
		this.area = {};

		this.dom = dom;

		this.level = _level = option.level;

		this.select.style = {
			width: option.width,
			height: option.height,
			font_size: option.font_size,
			options_max_height: option.options_max_height,
			text_align: option.text_align
		}

		onProvinceSelectChange = option.onProvinceSelectChange;
		onCitySelectChange = option.onCitySelectChange;
		onAreaSelectChange = option.onAreaSelectChange;

		this._render();

	}

	Address.prototype._render = function(){

		this.dom.wrap
			.append(this.dom.province)
		this.level >= 2 && this.dom.wrap
			.append(this.dom.city)
		this.level >= 3 && this.dom.wrap
			.append(this.dom.area)

		this.province.select = select_province = new SelectBox({
			container: this.dom.wrap.find('.address_province'),
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			text_align: this.select.style.text_align,
			options_max_height: this.select.style.options_max_height,
			placeholder: '省/直辖市',
			onSelectChange: provinceChange
		});
		if(this.level === 2){
			this.city.select = select_city = new SelectBox({
				container: this.dom.wrap.find('.address_city'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择省',
				onSelectChange: onCitySelectChange
			});
		} else if(this.level > 2){
			this.city.select = select_city = new SelectBox({
				container: this.dom.wrap.find('.address_city'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择省',
				onSelectChange: cityChange
			});
		}
		if(this.level >= 3){
			this.area.select = select_area = new SelectBox({
				container: this.dom.wrap.find('.address_area'),
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				text_align: this.select.style.text_align,
				options_max_height: this.select.style.options_max_height,
				placeholder: '请先选择市',
				onSelectChange: areaChange
			});
		}

		this._getCity();
	}

	Address.prototype.setData = function(_address_opt){
		var opt = _address_opt || {};
		var pid = opt.pid || this.dom.wrap.attr('data-pid'),
			cid = opt.pid || this.dom.wrap.attr('data-cid'),
			aid = opt.pid || this.dom.wrap.attr('data-aid');

		if(!pid || !cid || !aid) return;

		this.province.select.setSelectedVal(pid);
		setProvince(pid, cid, aid, this);
	}

	function setProvince(pid, cid, aid, that){
		var province_name = '';
		for(var p in that.data[0]){
			if(that.data[0][p].id === pid){
				province_name = that.data[0][p].cityName;
			}
		}
		that.province.select.setSelectedText(province_name);

		setCity(pid, cid, aid, that)
	}

	function setCity(pid, cid, aid, that){
		that.city.select.setSelectedText('请选择市');
		that.city.select.setSelectedVal('');

		var city_name = '',
			cities = [];
		
		for(var i in address_data[0]){
			var codeid,
				city_data = {};
			if(address_data[0][i].id == pid){
				codeid = address_data[0][i].codeid;				
			}
		}
		
		for(var k in address_data[codeid]){
			if(cid == address_data[codeid][k].id){

				city_name = address_data[codeid][k].cityName;
			}
			city_data = {
				text: address_data[codeid][k].cityName,
				value: address_data[codeid][k].id
			}
			cities.push(city_data);
		}

		select_city.setOptions(cities);

		that.city.select.setSelectedText(city_name);
		that.city.select.setSelectedVal(cid);
		if(_level <= 2) return;


		that.area.select.setSelectedText('请先选择市');
		that.area.select.setSelectedVal('');
		that.area.select.setOptions([]);

		setArea(pid, cid, aid, that);
	}

	function setArea(pid, cid, aid, that){
		var city_name = that.city.select.text();
		that.city.select.setSelectedText('正在加载中...');
		XDD.Request({
			url : "/city/getSubLocation",
			data : {
				id : cid
			},
			success : function(res){
				if(res.error_code == 0){
					var area = [];
					var area_name = '';
					for(var i in res.data){
						var area_data = {
							text: res.data[i].cityName,
							value: res.data[i].id
						}
						if(aid == res.data[i].id){
							area_name = res.data[i].cityName;
						}
						area.push(area_data);
					}
					that.area.select.setOptions(area);

					that.area.select.setSelectedText(area_name);
				}else{
					_alert(res.error_msg);
					that.area.select.setSelectedText('网络错误，请刷新页面！');
				}
				that.area.select.setSelectedVal(aid);
				
				that.city.select.setSelectedText(city_name);
			}
		});
	}

	Address.prototype._getCity = function(){

		var me = this;
		var _cities = storage.get({'key':'address_cities'});
		if(_cities){
			me._initData($.evalJSON(_cities));
			me.setData();
		}else{
			XDD.Request({
				url : "/city/getProvinceCitys",
				success : function(res){
					if(res.error_code == 0){
						_cities = $.toJSON(res.data);
						storage.set({'key':'address_cities','value':_cities,'expires':72*3600*1000});
						me._initData(res.data);

						me.setData();
					}else{
						_alert('res.error_msg')
					}
				}
			})
		}
	}

	/**
	 * [获取地址]
	 * @return {[type]} [description]
	 */
	Address.prototype.val = function(){
		var province = {},
			city = {},
			area = {};

		this.province.select ? province.id = this.province.select.val() : province.id = '';
		this.province.select ? province.name = this.province.select.text() : province.name = '';

		this.city.select ? city.id = this.city.select.val() : city.id = '';
		this.city.select ? city.name = this.city.select.text() : city.name = '';

		this.area.select ? area.id = this.area.select.val() : area.id = '';
		this.area.select ? area.name = this.area.select.text() : area.name = '';
		return {
			province: province,
			city: city,
			area: area
		}
	}

	/**
	 * [获取省]
	 * @return {[type]} [description]
	 */
	Address.prototype.getProvince = function(){
		var province = {};
		this.province.select ? province.id = this.province.select.val() : province.id = '';
		this.province.select ? province.name = this.province.select.text() : province.name = '';

		return province
	}

	/**
	 * [获取市]
	 * @return {[type]} [description]
	 */
	Address.prototype.getCity = function(){
		var city = {};
		this.city.select ? city.id = this.city.select.val() : city.id = '';
		this.city.select ? city.name = this.city.select.text() : city.name = '';

		return city
	}

	/**
	 * [获取地区]
	 * @return {[type]} [description]
	 */
	Address.prototype.getArea = function(){
		var area = {};
		this.area.select ? area.id = this.area.select.val() : area.id = '';
		this.area.select ? area.name = this.area.select.text() : area.name = '';

		return area
	}

	Address.prototype._initData = function(data){

		var cities = {};
		for(var i in data){
			var item = data[i],
				pid = item.parentid;
			if(!cities.hasOwnProperty(pid))
				cities[pid] = [];
			cities[pid].push(item);
		}
		var cities_option = [];

		for(var j in cities[0]){
			var city_data = {
				text: cities[0][j].cityName,
				value: cities[0][j].id
			}
			cities_option.push(city_data);
		}

		this.province.select.setOptions(cities_option);
		this.data = address_data = cities;
	}

	function provinceChange(id, province){
		select_city.setSelectedText('请选择市');
		select_city.setSelectedVal('');

		var cities = [];
		
		for(var i in address_data[0]){
			var codeid,
				city_data = {};
			if(address_data[0][i].id == id){
				codeid = address_data[0][i].codeid;				
			}
		}

		for(var k in address_data[codeid]){
			city_data = {
				text: address_data[codeid][k].cityName,
				value: address_data[codeid][k].id
			}
			cities.push(city_data);
		}
		

		select_city.setOptions(cities);
		if(_level <= 2) return;


		select_area.setSelectedText('请先选择市');
		select_area.setSelectedVal('');
		select_area.setOptions(new Array());

		if(typeof onProvinceSelectChange === 'function'){
			onProvinceSelectChange(id, province, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	function cityChange(id, city){
		select_area.setSelectedText('请选择区');
		select_area.setSelectedVal('');

		select_city.setSelectedText('正在加载中...');
		XDD.Request({
			url : "/city/getSubLocation",
			data : {
				id : id
			},
			success : function(res){
				if(res.error_code == 0){
					var area = [];
					for(var i in res.data){
						var area_data = {
							text: res.data[i].cityName,
							value: res.data[i].id
						}
						area.push(area_data);
					}
					select_area.setOptions(area);
				}else{
					_alert(res.error_msg);
				}
				select_city.setSelectedText(city);
			}
		});
		if(typeof onCitySelectChange === 'function'){
			onCitySelectChange(id, city, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	function areaChange(id, area){
		if(typeof onAreaSelectChange === 'function'){
			onAreaSelectChange(id, area, {
				province: select_province,
				city: select_city,
				area: select_area
			});
		};
	}

	return new Address(opt);
}

module.exports = _Address;

});
