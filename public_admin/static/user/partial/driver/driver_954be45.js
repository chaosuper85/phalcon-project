define("user/partial/driver/driver",function(e,n,r){"use strict";function o(){var e=WebUploader.create({server:"/api/carTeam/importDriver?team_id="+l,pick:"#upload",accept:{extensions:"xlsx,xls"},fileNumLimit:1,fileSingleSizeLimit:2097152});e.on("fileQueued",function(e){c.innerHTML=e.name}),e.on("beforeFileQueued",function(){var n=e.getFiles();n.length&&(e.reset(),c.innerHTML="")}),e.on("uploadProgress",function(e,n){c.innerHTML="正在上传"+100*n+"%"}),e.on("uploadSuccess",function(n,r){0==r.error_code?(t.success(r.error_msg,3e3),setTimeout(function(){location.reload()},2e3)):(t.error(r.error_msg,3e3),e.reset(),c.innerHTML="请重新选择")}),e.on("uploadError",function(){console.log("上传出错")}),e.on("error",function(e){t.error(e in u?u[e]:"出错啦，错误信息："+e)}),e.on("uploadComplete",function(){}),document.getElementById("submit").addEventListener("click",function(){return e.getFiles().length?void e.upload():void t.error("请选择上传文件")})}var i=e("common/module/util/util"),t=antd.message,u={Q_TYPE_DENIED:"只允许上传excel格式文件",F_EXCEED_SIZE:"文件大小不得超过2M",Q_EXCEED_NUM_LIMIT:"一次只能上传一个文件"},c=document.querySelector(".info"),l=i.getTheParam("id");r.exports={init:function(){o()}}});