define('index/partial/login/login', function(require, exports, module) {

'use strict';

module.exports = function (data) {

	var submit = $('#login-submit'),
	    account = $('#login-user'),
	    password = $('#login-pass'),
	    tip = $('#login-tip'),
	    errorType = '';

	$(document).keyup(function (event) {
		if (event.keyCode == 13) {
			submit.trigger("click");
		}
	});

	submit.click(function () {

		var _account = checkAccount();
		if (!_account) return;

		var _password = checkPass();
		if (!_password) return;
		submit.html('正在登录...');
		$.ajax({
			url: "/api/dologin",
			type: "post",
			dataType: "json",
			data: {
				username: _account,
				password: _password
			},
			success: function success(result) {
				if (result.error_code == 0) {
					location.href = data.from || '/';
				} else {
					alert(result.error_msg);
					submit.html('登 录');
				}
			},
			error: function error() {
				submit.html('登 录');
			}
		});
	});

	function checkAccount() {
		var _account = account.val();

		if (_account.length == 0) {
			showError('请输入管理员用户名', account, 'account');return;
		}

		if (errorType == 'account') {
			hideError();
		}

		return _account;
	}

	function checkPass() {
		var _pass = password.val();
		if (_pass.length == 0) {
			showError('请输入密码', password, 'password');return;
		}
		if (_pass.length < 4) {
			showError('密码不少于4位', password, 'password');return;
		}
		if (errorType == 'password') {
			hideError();
		}

		return _pass;
	}

	var timer = null;

	function showError(msg, obj, type) {

		errorType = type;

		tip.html(msg);
		tip.removeClass('invisible');
		shake(obj);
		clearTimeout(timer);
		timer = setTimeout(function () {
			hideError();
		}, 5000);
	}

	function hideError() {
		tip.addClass('invisible');
	}

	function shake(obj) {
		obj.animate({ marginLeft: "-1px" }, 40).animate({ marginLeft: "2px" }, 40).animate({ marginLeft: "-2px" }, 40).animate({ marginLeft: "1px" }, 40).animate({ marginLeft: "0px" }, 40).focus();
	}
};

});
