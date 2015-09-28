define('common/module/confirm/confirm', function(require, exports, module) {

/**
 * 通用confirm
 * Created by wll on 15/8/21.
 */

var popup = require('common/module/popup/popup.js');
var tpl = [function(locals, filters, escape, rethrow) {
escape = escape || function (html){
  return String(html)
    .replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;');
};
var __stack = { lineno: 1, input: "<div id=\"confirm\">\n    请点击确定，继续执行!\n</div>\n<div class=\"confirm-pop-btn confirm-btn \">\n    <a href=\"javascript:;\" class=\"user-btn\" id=\"confirmBtn\">确认</a>\n    <a href=\"javascript:;\" class=\"user-btn\" id=\"cancelBtn\">取消</a>\n</div>", filename: "common/module/confirm/confirm.ejs" };
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
 buf.push('<div id="confirm">\n    请点击确定，继续执行!\n</div>\n<div class="confirm-pop-btn confirm-btn ">\n    <a href="javascript:;" class="user-btn" id="confirmBtn">确认</a>\n    <a href="javascript:;" class="user-btn" id="cancelBtn">取消</a>\n</div>'); })();
} 
return buf.join('');
} catch (err) {
  rethrow(err, __stack.input, __stack.filename, __stack.lineno);
}
}][0];

var confirm_pop = function(){
    function A(){
        bind(this);
    }

    A.prototype = new popup({
        title :'确认信息',
        height:180,
        width :380,
        tpl:tpl
    });

    A.prototype.constructor = A;

    A.prototype.setData = function(cnt){
        $('#confirm').html(cnt);
    }

    A.prototype.onConfirm = function(){

    }

    function bind(that){
        that.pop.find('#confirmBtn').on('click',function(event) {
            that.hide();
            that.onConfirm();
        });

        that.pop.find('#cancelBtn').on('click',function(event) {
            that.hide();
        });
    }
    return new A();
}


module.exports = confirm_pop;

});
