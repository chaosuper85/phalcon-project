define('user/widget/view/employee/employee', function(require, exports, module) {


function bind(){
	var group={
		groupWindow:$(".group"),
		groupMsg:$(".group-msg"),
		groupList:$(".user_group"),
		groupSubmit:$(".submit-group")
	}
	var DOM={
		allEmployee:$("#all_employee"),
		applyEmployee:$("#apply_employee"),
		searchEmployee:$("#search_result"),
		tab1:$("#tab1"),
		tab2:$("#tab2"),
		tab3:$("#tab3"),
		appealWin:$(".appeal-window"),
		appeal:$(".appeal"),
		cancle:$(".cancle"),
		confirmInvit:$(".invit-confirm"),
		invitWin:$(".invit-window"),
		btnInvit:$(".btn-invit")
	}
	
	group.groupMsg.click(function(){
		var obj = $(this);
		obj.siblings().slideToggle();
		return false;
	});

	DOM.appeal.click(function(){
		var obj = $(this);
		obj.parents().siblings(".appeal-window").show();
		return false;
	});

	DOM.confirmInvit.click(function(){
		var obj = $(this);
		obj.parents().siblings(".invit-window").slideToggle();
	});

	DOM.btnInvit.click(function(){
		DOM.invitWin.slideToggle();
	});
	DOM.cancle.click(function(){
		DOM.appealWin.hide();
	});


	group.groupSubmit.click(function(){
		radioCheck();
	});

	DOM.tab2.on('click',function(){
		DOM.allEmployee.hide();
		DOM.tab1.removeClass("cur").addClass("employee-hide");

		DOM.applyEmployee.show();
		DOM.tab2.removeClass("employee-hide").addClass("cur");

		DOM.searchEmployee.hide();
		DOM.tab3.css("background","#eff4f7");
		DOM.tab3.find('.search-li').removeClass("search-li").addClass("search");
	});

	DOM.tab1.on('click',function(){
		DOM.applyEmployee.hide();
		DOM.tab2.removeClass("cur").addClass("employee-hide");

		DOM.allEmployee.show();
		DOM.tab1.removeClass("employee-hide").addClass("cur");

		DOM.searchEmployee.hide();
		DOM.tab3.css("background","#eff4f7");
		DOM.tab3.find('.search-li').removeClass("search-li").addClass("search");
	});

	DOM.tab3.on('click',function(){
		DOM.allEmployee.hide();
		DOM.tab1.removeClass("cur").addClass("employee-hide");

		DOM.applyEmployee.hide();
		DOM.tab2.removeClass("cur").addClass("employee-hide");

		DOM.searchEmployee.show();
		DOM.tab3.find('.search').removeClass("search").addClass("search-li");
		DOM.tab3.css("background","#ffffff");
	});

	$(".apply-header ul li").click(function(){
    var obj = $(this);
    var liIndex= $(this).index();

    obj.addClass("cur").siblings().removeClass("cur");
    if(liIndex==0){
        $("#wait_apply").show().siblings().hide();
    }
    if(liIndex==1){
        $("#send_apply").show().siblings().hide();
    }
    if(liIndex==2){
        $("#recommend_apply").show().siblings().hide();
    }

});




}

function chooeseGroup(obj){
	var o=$(".group-msg");
	for(var i=0;i<o.length;i++){
		j=i+1;
		if(obj==o[i]){
			$("#all_employee").find(".group-msg").eq(j).css("display","block");
		}
	}
}

function radioCheck(){
	var radio=$("input[name='group']");

	for(var i=0;i<radio.length;i++){
		if(radio[i].checked){
			radio[i].value;
		}
	}
}


















module.exports = {
	init : function(){
		bind();
	}
}

});
