define("user/widget/view/account_security/change_pass",function(e,t,n){function a(){function e(e,t){var n=$(e).val(),i=n.length,o=r.isPassword(n);return i?6>i?(a(e),s(e,"密码为6-16位"),!1):o?(s(e,""),n):(a(e),s(e,"密码必须包含字母和数字"),!1):t?(a(e),s(e,"请输入密码"),!1):void 0}function t(e,t,n){var r=$(e).val(),i=$(t).val();return i.length?i!==r?(a(t),s(t,"两次输入密码不一致"),!1):(s(t,""),i):n?(a(t),s(t,"请再次输入密码"),!1):void 0}function n(n,a,r){var s=n,i=e("#new-pass",1);if(i)var o=t("#new-pass","#new-repass",1);i&&o&&XDD.Request({url:"/account/changePwd ",data:{oldPwd:s,newPwd:i},success:function(e){0==e.error_code?(_alert("修改密码成功！"),setTimeout("location.href = '/account/personalInfo'",2e3)):_alert(e.error_msg),a.html(r),a.removeAttr("disabled")},error:function(){a.html(r),a.removeAttr("disabled")}})}function a(e){$(e).animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}function s(e,t){var n=$(e).siblings(".tip");$(n).addClass("red").html(t)}$("#new-pass").on("blur",function(){e("#new-pass")}),$("#new-repass").on("blur",function(){t("#new-pass","#new-repass")}),$(".next-step").on("click",function(){var t=$(this),a=e("#pass",1);if(a){var r=t.text();t.html("正在提交..."),t.attr("disabled","disabled"),XDD.Request({url:"/user/validatePwd",data:{pwd:a},success:function(e){0==e.error_code?n(a,t,r):_alert(e.error_msg),t.html(r),t.removeAttr("disabled")},error:function(){t.html(r),t.removeAttr("disabled")}})}})}var r=e("common/module/util.js");n.exports={init:function(){a()}}});