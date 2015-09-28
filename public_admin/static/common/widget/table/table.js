define('common/widget/table/table', function(require, exports, module) {

/*
 * 通用table事件
 */

"use strict";

module.exports = (function () {
	var table = $(".x-table");

	// radio事件
	var radios = table.find("tbody input[type=checkbox]"),
	    radio_all = table.find(".radio-all"),
	    trs = table.find("tbody tr");

	radio_all.click(function () {
		var val = this.checked;
		radios.prop("checked", val);
		if (val) {
			trs.addClass('active');
		} else {
			trs.removeClass('active');
		}
	});

	radios.change(function () {
		var obj = $(this),
		    val = this.checked,
		    tr = obj.parents('tr');

		if (val) {
			tr.addClass('active');
		} else {
			tr.removeClass('active');
		}
	});
})();

});
