define('common/module/selectTimeBox/selectTimeBox', function(require, exports, module) {

var util = require("common/module/util.js");
var SelectBox = require('common/module/select/select.js');

/**
 * [时间选择]
 * @param  {[type]} opt [description]
 * @return {[type]}     [description]
 * by weiqi
 */
function _SelectTimeBox(opt){
	var CONST = {
		st_year: 2000,
		ed_year: 2020,
		min: [00, 30]
	}
	var select_year,
		select_month,
		select_day,
		select_hour,
		select_min,
		select_sec,
		_level;
	function SelectTimeBox(_option){
		var option = {
			wrap: _option.wrap || '',
	        width: _option.width || 48,
	        height: _option.height || 30,
	        font_size: _option.font_size || 14,
	        level: _option.level || 3,
	        st_year: _option.st_year || CONST.st_year,
	        ed_year: _option.ed_year || CONST.ed_year
		}
		var dom = {
			wrap : $(option.wrap) || null,
			year : $('<div class="select_time_year"></div>'),
			month : $('<div class="select_time_month"></div>'),
			day : $('<div class="select_time_day"></div>'),
			hour : $('<div class="select_time_hour"></div>'),
			min : $('<div class="select_time_min"></div>'),
			sec : $('<div class="select_time_sec"></div>')
		};

		this.st_year = option.st_year;
		this.ed_year = option.ed_year;

		this.select = {};
		this.year = {};
		this.month = {};
		this.day = {};
		this.hour = {};
		this.min = {};
		this.sec = {};

		this.dom = dom;

		this.select.style = {
			width: option.width,
			height: option.height,
			font_size: option.font_size,
			options_max_height: option.options_max_height
		}

		this.level = _level = option.level;

		this._render();
	}


	SelectTimeBox.prototype._render = function(){

		var nowData = new Date();
		var nowMonth = nowData.getMonth() + 1;
		if(nowMonth < 10){
			nowMonth = '0' + nowMonth;
		}
		var lineHeight_str = 'style="line-height:' + this.select.style.height + 'px;"';
		this.dom.wrap
			.append(this.dom.year).append('<div class="select_year_text" ' + lineHeight_str + '>年</div>')
			.append(this.dom.month).append('<div class="select_month_text" ' + lineHeight_str + '>月</div>')
			.append(this.dom.day).append('<div class="select_day_text" ' + lineHeight_str + '>日</div>')
		this.level > 3 && this.dom.wrap
			.append(this.dom.hour).append('<div class="select_hours_text" ' + lineHeight_str + '>时</div>')
		this.level > 4 && this.dom.wrap
			.append(this.dom.min).append('<div class="select_min_text" ' + lineHeight_str + '>分</div>')
		this.level > 5 && this.dom.wrap
			.append(this.dom.sec).append('<div class="select_sec_text" ' + lineHeight_str + '>秒</div>')

		// 年
		this.year.select = select_year = new SelectBox({
			container: this.dom.year,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowData.getFullYear(),
			defaultValue: nowData.getFullYear(),
			onSelectChange: initMonth,
			text_align: 'center',
			options: initData(this.st_year, this.ed_year)
		});
		// 月
		this.month.select = select_month = new SelectBox({
			container: this.dom.month,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowMonth,
			defaultValue: nowMonth,
			onSelectChange: initMonth,
			text_align: 'center',
			options: initData(1, 12)
		});
		// 日
		this.day.select = select_day = new SelectBox({
			container: this.dom.day,
			width: this.select.style.width,
			height: this.select.style.height,
			font_size: this.select.style.font_size,
			options_max_height: this.select.style.options_max_height,
			defaultText: nowData.getDate(),
			defaultValue: nowData.getDate(),
			onSelectChange: '',
			text_align: 'center'
		});
		if(this.level > 3){
			this.hour.select = select_hour = new SelectBox({
				container: this.dom.hour,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: nowData.getHours(),
				defaultValue: nowData.getHours(),
				onSelectChange: '',
				text_align: 'center',
				options: initHours()
			});
		}
		if(this.level > 4){
			this.min.select = select_min = new SelectBox({
				container: this.dom.min,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: '00',
				defaultValue: '00',
				onSelectChange: '',
				text_align: 'center',
				options: initMin()
			});
		}
		if(this.level === 6){
			this.sec.select = select_sec = new SelectBox({
				container: this.dom.sec,
				width: this.select.style.width,
				height: this.select.style.height,
				font_size: this.select.style.font_size,
				options_max_height: this.select.style.options_max_height,
				defaultText: '00',
				defaultValue: '00',
				onSelectChange: '',
				text_align: 'center',
				options: initData(1, 60)
			});
		}
		// this._getCity();
		initMonth('notOnChange');
		this.setTime();
	}

	SelectTimeBox.prototype.setTime = function(_time){
		var time = _time || this.dom.wrap.attr('data-time');
		if(!time) return;

		try{
			var gDate = new Date(time.replace(/-/g,"/"));
		} catch(e){
			console.log(e);
			var gDate = new Date();
		}

		var gYear = gDate.getFullYear(),
			gMonth = gDate.getMonth() + 1,
			gDay = gDate.getDate(),
			gHours = gDate.getHours(),
			gMinutes = gDate.getMinutes(),
			gSeconds = gDate.getSeconds();

		this.year.select.setSelectedVal(gYear);
		this.year.select.setSelectedText(gYear);

		this.month.select.setSelectedVal(gMonth);
		this.month.select.setSelectedText(gMonth);

		initMonth('notOnChange');

		this.day.select.setSelectedVal(gDay);
		this.day.select.setSelectedText(gDay);

		if(!this.hour.select) return;
		this.hour.select.setSelectedVal(gHours);
		this.hour.select.setSelectedText(gHours);

		if(!this.min.select) return;
		this.min.select.setSelectedVal(gMinutes);
		this.min.select.setSelectedText(gMinutes);

		if(!this.sec.select) return;
		this.sec.select.setSelectedVal(gSeconds);
		this.sec.select.setSelectedText(gSeconds);

	}

	SelectTimeBox.prototype.val = function(){
		var year = '',
			month = '',
			day = '',
			min = '',
			hour = '',
			sec = '';

		var time = '';

		this.year.select ? year = this.year.select.val() : year = '';
		this.month.select ? month = this.month.select.val() : month = '';
		this.day.select ? day = this.day.select.val() : day = '';

		time = year + '-' + month + '-' + day;

		if(_level > 3){
			this.hour.select ? hour = this.hour.select.val() : hour = '';
			time += ' ' + hour;
		}
		if(_level > 4){
			this.min.select ? min = this.min.select.val() : min = '';
			time += ':' + min;
		}
		if(_level > 5){
			this.sec.select ? sec = this.sec.select.val() : sec = '';
			time += ':' + sec;
		}
		return time;
	}

	// 加载数据
	function initData(st, ed){
		var array = [];

		var i = st;
		while(i){
			var data = {};
			var num = i;

			if(i < 10){
				var l = '0',
					num_str = num.toString();

				data = {
					value: l + num_str,
					text: l + num_str
				}
			} else {
				data = {
					value: i,
					text: i
				}
			}
			array.push(data);
			if(i === ed) break;
			i++;
		}
		return array;
	}

	// 加载时
	function initHours(){
		var hours = [];
		var i = 1;
		while(i){
			var data = {};
			var num = i;

			if(i < 10){
				var l = '0',
					num_str = num.toString();

				data = {
					value: l + num_str,
					text: l + num_str
				}
			} else {
				data = {
					value: i,
					text: i
				}
			}
			hours.push(data);
			if(i === 23) break;
			i++;
		}
		return hours;
	}

	// 加载分
	function initMin(){
		var length = CONST.min.length;
		var min = [];
		for (var i = 0; i < length; i++) {
			
			var data = {
				value: CONST.min[i],
				text: CONST.min[i]
			}
			min.push(data);
		};
		return min;
	}

	// 加载月
	function initMonth(type){
		var year_str = select_year.val(),
			month_str = select_month.val();

		if(!util.isNumber(year_str)) return;
		if(!util.isNumber(month_str)) return;

		if(typeof type !== 'string' || type !== 'notOnChange'){
			select_day.setSelectedVal('01');
			select_day.setSelectedText('01');
		}

		var year = parseInt(year_str),
			month = parseInt(month_str);

		var i = 1;
		var days = [];
		if(month === 1 || month === 3 || month === 5 || month === 7 || month === 8 || month === 10 || month === 12){
			while(i){
				days.push(setMonthValue(i));
				if(i === 31) break;
				i++;
			}
		} else if(month === 4 || month === 6 || month === 9 || month === 11){
			while(i){
				days.push(setMonthValue(i));
				if(i === 30) break;
				i++;
			}
		} else if(month === 2){
			if((year%4 === 0 && year%100 !== 0) || (year%100 === 0 && year%400 === 0)){
				while(i){
					days.push(setMonthValue(i));
					if(i === 29) break;
					i++;
				}
			}else{
				while(i){
					days.push(setMonthValue(i));
					if(i === 28) break;
					i++;
				}
			}
		}
		select_day.setOptions(days);
	}

	function setMonthValue(i){
		var num = i;
		var data = {};
		if(i < 10){
			var l = '0',
				num_str = num.toString();

			return {
				value: l + num_str,
				text: l + num_str
			}
		}
		return {
			value: i,
			text: i
		}
	}
	return new SelectTimeBox(opt);
}

module.exports = _SelectTimeBox;

});
