define("user/widget/view/account_company/company",function(e,n,t){function o(e){function n(e){{var n=e.split(".");n.length-1}return!0}function t(){var e=$("#licenseNumber").val();XDD.Request({url:"/account/licenceCodeExist",data:{licenceCode:e},success:function(e){0==e.error_code?h=1:10000001==e.error_code?(_alert(e.error_msg),h=0):(_alert(e.error_msg),h=0)}},!0)}function o(e,n){if(n)var t=_.val(),o=t.city.id;else var o=j.val();return o>0?(b=1,o):(b=0,!1)}function s(e){var n=$(e),t=n.val();return t?(b=1,t):(b=0,!1)}function u(){var e=C.val(),n=new Date(e.replace("-","/").replace("-","/")),t=new Date,o="";o+=t.getFullYear()+"-",o+=t.getMonth()+1+"-",o+=t.getDate()+"-";var c=new Date(o.replace("-","/").replace("-","/"));return n>c?(console.log(e),b=0,!1):(b=1,e)}function m(e){var n=$(e).val();return n&&a.isNumber(n)?(b=1,n):(b=0,!1)}function d(e){console.log("aa",$(e)),$(e).animate({marginLeft:"-1px"},40).animate({marginLeft:"2px"},40).animate({marginLeft:"-2px"},40).animate({marginLeft:"1px"},40).animate({marginLeft:"0px"},40).focus()}function f(e){var n=$(e).closest(".content").siblings(".tip");$(n).removeClass("invisible"),$(n).children("i").removeClass("i-right"),b=0}function v(e){var n=$(e).closest(".content").siblings(".tip"),t=n.children("i");$(n).removeClass("invisible"),$(t).addClass("i-right")}var p,g,b=0,h=0,_=new c({wrap:"#address-selector",level:2,width:160,height:40}),y="货代用户",M="车队用户";"2"==e?g=y:"1"==e&&(g=M);var w,x,j=new i({container:"#type-selector",options:[{text:"货代用户",value:"2"},{text:"车队用户",value:"1"}],defaultText:g,defaultValue:e,height:40}),N=new Date,B=+N.getFullYear(),C=new r({wrap:"#company_date_selectBox",ed_year:B,width:70,height:40});$(".upload-license").AjaxFileUpload({onComplete:function(e,t){console.log(e,t);var o=n(e);o&&($("#img-license").attr("src",t.data.pic_url),w=t.data.pic_url,$(".img-license-content").removeClass("hidden"),v(".img-license-content"))}}),$("#img-clean").on("click",function(){$("#img-license").attr("src",""),$(".img-license-content").addClass("hidden"),f(".img-license-content"),w="",b=0}),$(".license").on("click",function(){var e=$(".license").attr("src");window.open(e)}),$("#submitInfo").on("click",function(){function e(){$("#confirmBtn").on("click",function(){n(),p.hide()}),$("#cancelBtn").on("click",function(){console.log("cancle"),p.hide(),$("#confirmBtn").unbind("click")})}function n(){XDD.Request({url:"/account/do_apply",data:{enterpriseName:c,type:i,cityCode:r,builddate:a,contactMobile_city:g,contactMobile_number:_,contactMobile_fenji:y,licenceNumber:M,licencePic:x},success:function(e){0==e.error_code?(_alert("提交成功，请等待审核"),$(".pop-close").on("click",function(){location.href="/account/enterpriseInfo"}),$(".popup-bg").on("click",function(){location.href="/account/enterpriseInfo"})):_alert(e.error_msg)}},!0)}var c=s("#enterpriseName");if(!c)return d("#enterpriseName"),void f("#enterpriseName");v("#enterpriseName");var i=o("#type-selector");if(!i)return d("#type-selector"),void f("#type-selector");console.log("选择了",i),v("#type-selector"),"1"==i?i="carteam":"2"==i&&(i="freight_agent"),console.log("类型 = "+i);var r=o("#address-selector","1");if(!r)return d("#address-selector"),void f("#address-selector");v("#address-selector");var a=u("#company_date_selectBox");if(!a)return d("#company_date_selectBox"),void f("#company_date_selectBox");v("#company_date_selectBox");var g=m("#contactMobile-city");if(!g)return d("#contactMobile-city"),void f("#contactMobile-city");if(3!=g.length&&4!=g.length)return d("#contactMobile-city"),void f("#contactMobile-city");v("#contactMobile-city");var _=m("#contactMobile-number");if(!_)return d("#contactMobile-number"),void f("#contactMobile-number");if(7!=_.length&&8!=_.length)return d("#contactMobile-number"),void f("#contactMobile-number");v("#contactMobile-number");var y=m("#contactMobile-fenji");if(y){if(y.length<3||y.length>5)return alert("000"),d("#contactMobile-fenji"),void f("#contactMobile-fenji");v("#contactMobile-fenji")}else v("#contactMobile-fenji"),console.log("fenji=",y);var M=m("#licenseNumber");return M?(t(),0==h?(console.log("aaa"),d("#licenseNumber"),void f("#licenseNumber")):(v("#licenseNumber"),x=w?w:$("#img-license").attr("src"),console.log(a+"---------"+x),x?(b=1,v("#img-license"),0==b?!1:void(1==b&&(p?p.show():(p=new l,p.setData("公司信息一经提交，不能修改"),p.show()),e()))):(f("#img-license"),void(b=0)))):(d("#licenseNumber"),void f("#licenseNumber"))})}e("common/module/upload/uploader.js");var c=e("common/module/address/address.js"),i=e("common/module/select/select.js"),r=e("common/module/selectTimeBox/selectTimeBox.js"),l=e("common/module/confirm/confirm.js"),a=e("common/module/util.js");t.exports={init:function(e){o(e)}}});