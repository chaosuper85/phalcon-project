define("common/module/alert/alert",function(require,exports,module){var popup=require("common/module/popup/popup.js"),tpl=[function(locals,filters,escape,rethrow){function rethrow(e,t,n,r){var o=t.split("\n"),p=Math.max(r-3,0),a=Math.min(o.length,r+3),i=o.slice(p,a).map(function(e,t){var n=t+p+1;return(n==r?" >> ":"    ")+n+"| "+e}).join("\n");throw e.path=n,e.message=(n||"ejs")+":"+r+"\n"+i+"\n\n"+e.message,e}escape=escape||function(e){return String(e).replace(/&(?!#?[a-zA-Z0-9]+;)/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#39;").replace(/"/g,"&quot;")};var __stack={lineno:1,input:'<div id="alert">\n	未知错误\n</div>',filename:"common/module/alert/alert.ejs"};try{var buf=[];with(locals||{})!function(){buf.push('<div id="alert">\n	未知错误\n</div>')}();return buf.join("")}catch(err){rethrow(err,__stack.input,__stack.filename,__stack.lineno)}}][0],alert_pop=function(){function e(){this.pop.css("z-index","102"),t()}function t(){}return e.prototype=new popup({title:"提示信息",height:180,width:380,tpl:tpl}),e.prototype.constructor=e,e.prototype.setData=function(e){$("#alert").html(e)},new e};module.exports=alert_pop});