define("user/widget/view/employee_apply/employee_apply",function(e,a,c){function l(){var e={checkall:$("#checkall"),_accept:$(".btn-accept"),refuse:$(".btn-refuse"),selectStatus:$(".data-select-status")};e.selectStatus.click(function(){var e=$(this);e.hasClass("apply-data")?(e.removeClass("apply-data").addClass("apply-data-selected"),e.children(".chooese").find(".no-select").removeClass("no-select").addClass("selected").val("已选择")):e.hasClass("apply-data-selected")&&(e.removeClass("apply-data-selected").addClass("apply-data"),e.children(".chooese").find(".selected").removeClass("selected").addClass("no-select").val("选择"))}),e.checkall.click(function(){$("input[name='checkbox']").prop("checked",this.checked)}),$("input[name='checkbox']").click(function(){var e=$("input[name='checkbox']");$("#checkall").prop("checked",e.length==e.filter(":checked").length?!0:!1)}),e._accept.click(function(){var a=$(".apply-data-selected").length;e.selectStatus.each(function(){$(this).hasClass("apply-data-selected")}),0==a&&_alert("请至少选中一项")}),e.refuse.click(function(){})}c.exports={init:function(){l()}}});