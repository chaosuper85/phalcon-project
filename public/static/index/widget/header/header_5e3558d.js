define("index/widget/header/header",function(o,n,e){function i(){$("#login-btn").click(function(){c.show()}),$("#logout").click(function(){XDD.Request({type:"GET",url:"/index/logout",success:function(o){0==o.error_code?location.href="/":_alert(o.error_msg)}})})}var t=o("common/module/login/login.js"),c=t();e.exports={init:function(){i()}}});