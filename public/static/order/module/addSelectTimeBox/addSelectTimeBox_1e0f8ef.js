define("order/module/addSelectTimeBox/addSelectTimeBox",function(e,a,t){function i(e){function a(e){var a={container:e.container||$("body"),addName:e.addName||".add-date",height:e.height||30},t=a.container.$scope||a.container,i=a.addName,o=this.selectTimeBox=[],c=1,d=$('<a class="delTimeBtn" href="javaScript:" title="删除装箱时间"><i class="icon-del-timeBox" data-flag=""></i></a>'),l=t.find(".package_date_selectBox");0!==l.length?(c=l.length,l.each(function(e){var i=e+1;o.push({container:".package_date_selectBox.data_"+i,flag:i,box:new n({wrap:t.find(".package_date_selectBox.data_"+i),level:5,height:a.height}),timeData:{id:t.find(".package_date_selectBox.data_"+i).attr("data-timeId"),can_change:t.find(".package_date_selectBox.data_"+i).attr("data-canChange")||1}})})):o.push({container:".package_date_selectBox.data_1",flag:c,box:new n({wrap:t.find(".package_date_selectBox.data_1"),level:5,height:a.height})}),t.on("click",i,function(){var e=t.find(".package_date_selectBox");if(!(e.length>9)){c++;var i=$(this),d=$('<div class="package_date_selectBox data_'+c+' clearfix" data-flag="'+c+'"></div>');i.before(d),o.push({container:d,flag:c,box:new n({wrap:t.find(".package_date_selectBox.data_"+c),level:5,height:a.height}),timeData:{id:"",can_change:1}})}}),t.on("mouseover",".package_date_selectBox",function(){var e=$(this),a=e.attr("data-flag");if(1!=a){var t=e.find(".delTimeBtn");if(t.length>0)return t.show(),void t.attr("data-flag",a);e.append(d),d.show(),d.attr("data-flag",a)}}),t.on("mouseleave",".package_date_selectBox",function(){var e=$(this),a=e.attr("data-flag");1!=a&&(e.append(d),d.hide(),d.removeAttr("data-flag"))}),t.on("click",".delTimeBtn",function(){for(var e=$(this),a=e.attr("data-flag"),i=o.length,n=0;i>n;n++)if(o[n].flag==a)return o.splice(n,1),void t.find(".package_date_selectBox.data_"+a).remove()})}return a.prototype.getTimeBox=function(){var e,a=this.selectTimeBox.length,t=[];for(e=0;a>e;e++)t.push(this.selectTimeBox[e].box.val());return t},a.prototype.getTimeBoxId=function(){var e,a=this.selectTimeBox.length,t=[];for(e=0;a>e;e++)t.push({time:this.selectTimeBox[e].box.val(),time_id:this.selectTimeBox[e].timeData.id,can_change:this.selectTimeBox[e].timeData.can_change});return t},new a(e)}var n=e("common/module/selectTimeBox/selectTimeBox.js");t.exports=i});