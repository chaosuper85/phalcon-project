define("common/module/upload/uploader",function(e){var t=e("common/module/util.js");!function(e){e.fn.AjaxFileUpload=function(n){function a(t){var a=e(t.target),m=a.attr("id"),c=a.removeAttr("id").clone().attr("id",m).AjaxFileUpload(n),u=a.val().replace(/.*(\/|\\)/,""),f=i(),p=l(f);c.insertBefore(a),d.onChange.call(c[0],u),f.bind("load",{element:c,form:p,filename:u},r),p.append(a).bind("submit",{element:c,iframe:f,filename:u},o).submit()}function o(t){var n=d.onSubmit.call(t.data.element,t.data.filename);if(n===!1)return e(t.target).remove(),t.data.iframe.remove(),!1;for(var a in n)e("<input />").attr("type","hidden").attr("name",a).val(n[a]).appendTo(t.target)}function r(n){var a=e(n.target),o=(a[0].contentWindow||a[0].contentDocument).document,r=t.delHtmlTag(o.body.innerHTML),i=0;if(r)try{r=e.parseJSON(r)}catch(l){_alert("您上传的文件格式过大或其他未知错误！详情请联系箱典典官方客服400-969-6790"),i=1}else r={};0==i?d.onComplete.call(n.data.element,n.data.filename,r):d.onError.call(n.data.element,n.data.filename,r),n.data.form.remove(),a.remove()}function i(){var t=c();return e("body").append('<iframe src="javascript:false;" name="'+t+'" id="'+t+'" style="display: none;"></iframe>'),e("#"+t)}function l(t){return e("<form />").attr({method:"post",action:d.action,enctype:"multipart/form-data",target:t[0].name}).hide().appendTo("body")}var m={action:"/api/account/uploadPic",onChange:function(){},onSubmit:function(){},onComplete:function(){},onError:function(){}},d=e.extend({},m,n),c=function(){var e=0;return function(){return"_AjaxFileUpload"+e++}}();return this.each(function(){var t=e(this);t.is("input")&&"file"===t.attr("type")&&t.bind("change",a)})}}(jQuery)});