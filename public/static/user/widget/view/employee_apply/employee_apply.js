define('user/widget/view/employee_apply/employee_apply', function(require, exports, module) {


function bind(){
	var DOM={
		checkall:$("#checkall"),
		_accept:$(".btn-accept"),
		refuse:$(".btn-refuse"),
		selectStatus:$(".data-select-status")
	}

	DOM.selectStatus.click(function(){
		var obj = $(this);

		if(obj.hasClass("apply-data")){
			obj.removeClass("apply-data").addClass("apply-data-selected");

			obj.children(".chooese").find(".no-select").removeClass("no-select").addClass("selected").val("已选择");
		}
		else if(obj.hasClass("apply-data-selected")){
			obj.removeClass("apply-data-selected").addClass("apply-data");

			obj.children(".chooese").find(".selected").removeClass("selected").addClass("no-select").val("选择");
		}

	});

	DOM.checkall.click(function(){

		$("input[name='checkbox']").prop("checked",this.checked);
	});

	$("input[name='checkbox']").click(function(){

		var $checkbox=$("input[name='checkbox']");
		$("#checkall").prop("checked",$checkbox.length==$checkbox.filter(":checked").length?true:false);
	});

	DOM._accept.click(function(){
		// var checkLength=$("input[name='checkbox']").length;
		// var checkedLength=$(":checked").length;

		// $(":checkbox").each(function(){
		// 	if($(this).attr("checked")){
		// 		$(this).val();
		// 	}
		// });

		// if(checkedLength==0){
		// 	_alert("请至少选中一项");
		// }
		var checkedLength=$(".apply-data-selected").length;
		DOM.selectStatus.each(function(){
			if($(this).hasClass("apply-data-selected")){
				
			}
		});

		if(checkedLength==0){
			_alert("请至少选中一项");
		}
	});






	DOM.refuse.click(function(){

	});
}






















module.exports={
	init:function(){
		bind();
	}
}

});
