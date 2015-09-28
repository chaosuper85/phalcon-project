define('www/common/module/alert/alert', function(require, exports, module) {

/*
 *  通用alert
 */

var popup = require('www/common/module/popup/popup.js');
var tpl = [function(locals, filters, escape, rethrow) {
escape = escape || function (html){
  return String(html)
    .replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;');
};
var __stack = { lineno: 1, input: "<div id=\"alert\">\n\t未知错误\n</div>", filename: "www/common/module/alert/alert.ejs" };
function rethrow(err, str, filename, lineno){
  var lines = str.split('\n')
    , start = Math.max(lineno - 3, 0)
    , end = Math.min(lines.length, lineno + 3);

  // Error context
  var context = lines.slice(start, end).map(function(line, i){
    var curr = i + start + 1;
    return (curr == lineno ? ' >> ' : '    ')
      + curr
      + '| '
      + line;
  }).join('\n');

  // Alter exception message
  err.path = filename;
  err.message = (filename || 'ejs') + ':'
    + lineno + '\n'
    + context + '\n\n'
    + err.message;
  
  throw err;
}
try {
var buf = [];
with (locals || {}) { (function(){ 
 buf.push('<div id="alert">\n	未知错误\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0];


var alert_pop = function(){ 
	function A(){
		this.pop.css('z-index', '102');
	    bind();
	}

	A.prototype = new popup({
	    title :'提示信息',
	    height:180,
	    width :380,
	    tpl:tpl
	});

	A.prototype.constructor = A;

	A.prototype.setData = function(msg){
	    $('#alert').html(msg);
	}

	function bind(){
		
	}
	return new A();
}


module.exports = alert_pop;

});
