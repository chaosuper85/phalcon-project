define("common/module/util/util",function(e,t,n){"use strict";var s=antd.message,r={post:function(e,t,n){var r=this,i=new Promise(function(i,o){function a(){200===this.status?0==this.response.error_code?(s.success("操作成功"),i(this.response)):s.error(this.response.error_msg):(s.error("网络错误"),o(new Error(this.statusText)))}var u=new XMLHttpRequest;u.open("POST",e,!0),u.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"),u.setRequestHeader("X-Requested-With","XMLHttpRequest"),u.setRequestHeader("Accept","application/json"),u.onload=a,u.responseType="json",u.send(n?JSON.stringify(t):r.getUrl(t))});return i},get:function(e){var t=new Promise(function(t,n){function s(){200===this.status?t(this.response):n(new Error(this.statusText))}var r=new XMLHttpRequest;r.open("GET",e,!0),r.onload=s,r.responseType="json",r.setRequestHeader("X-Requested-With","XMLHttpRequest"),r.setRequestHeader("Accept","application/json"),r.send()});return t},getParam:function(){var e=window.location.href;if(e.indexOf("?")>-1)var t=e.slice(e.indexOf("?")+1);else{if(!(e.indexOf("#")>-1))return{};var t=e.slice(e.indexOf("#")+1)}if(""==t)return{};for(var n={},s=t.split("&"),r=0;r<s.length;r++){var i=s[r].split("=");if(i[1]){var o=i[1].indexOf("#");i[1]=o>-1?i[1].slice(0,o):i[1],n[i[0]]=i[1]}}return n},getTheParam:function(e){var t=new RegExp("(^|&)"+e+"=([^&]*)(&|$)","i"),n=window.location.search.substr(1).match(t);return null!=n?unescape(n[2]):null},redirectParam:function(e){location.href=window.location.pathname+"?"+r.getUrl(e)},getUrl:function(e,t){var n=[];t=t||function(e){return e};for(var s in e){var r=e[s];""!=r&&null!=r&&"undefined"!=typeof r&&n.push(s+"="+t(r))}return n.join("&")},getDate:function(e){var t=e.getDate(),n=e.getMonth()+1,s=e.getFullYear();return s+"-"+n+"-"+t},bind:function(e,t,n){if(e)if(void 0!==e.length)if("undefined"!=typeof Array.from)Array.from(e,function(e){e.addEventListener(t,n)});else for(var s=0;s<e.length;s++)e[s].addEventListener(t,n);else e.addEventListener(t,n)},unbind:function(e,t,n){if(e)if(void 0!==e.length)if("undefined"!=typeof Array.from)Array.from(e,function(e){e.removeEventListener(t,n)});else for(var s=0;s<e.length;s++)e[s].removeEventListener(t,n);else e.removeEventListener(t,n)},removeSiblingsClass:function(e,t){var n=this,s=n.siblings(e);s.forEach(function(e){n.hasClass(e,t)&&n.removeClass(e,t)})},hasClass:function(e,t){return e.classList?e.classList.contains(t):new RegExp("(^| )"+t+"( |$)","gi").test(e.className)},addClass:function(e,t){e.classList?e.classList.add(t):e.className+=" "+t},removeClass:function(e,t){e.classList?e.classList.remove(t):e.className=e.className.replace(new RegExp("(^|\\b)"+t.split(" ").join("|")+"(\\b|$)","gi")," ")},siblings:function(e,t){var n=this;return Array.prototype.filter.call(e.parentNode.children,function(s){return t?n.hasClass(s,t)?s!==e:void 0:s!==e})},trigger:function(e,t){var n=document.createEvent("HTMLEvents");n.initEvent(t,!0,!1),e.dispatchEvent(n)}};n.exports=r});