var split;split=split||function(e){var t,n=String.prototype.split,r=/()??/.exec("")[1]===e;return t=function(t,l,i){if("[object RegExp]"!==Object.prototype.toString.call(l))return n.call(t,l,i);var s,p,g,o,c=[],u=(l.ignoreCase?"i":"")+(l.multiline?"m":"")+(l.extended?"x":"")+(l.sticky?"y":""),a=0,l=new RegExp(l.source,u+"g");for(t+="",r||(s=new RegExp("^"+l.source+"$(?!\\s)",u)),i=i===e?-1>>>0:i>>>0;(p=l.exec(t))&&(g=p.index+p[0].length,!(g>a&&(c.push(t.slice(a,p.index)),!r&&p.length>1&&p[0].replace(s,function(){for(var t=1;t<arguments.length-2;t++)arguments[t]===e&&(p[t]=e)}),p.length>1&&p.index<t.length&&Array.prototype.push.apply(c,p.slice(1)),o=p[0].length,a=g,c.length>=i)));)l.lastIndex===p.index&&l.lastIndex++;return a===t.length?(o||!l.test(""))&&c.push(""):c.push(t.slice(a)),c.length>i?c.slice(0,i):c},String.prototype.split=function(e,n){return t(this,e,n)},t}();