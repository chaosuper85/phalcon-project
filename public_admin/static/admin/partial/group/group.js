define('admin/partial/group/group', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var popalluser = require('admin/module/group/pop_alluser');

var message = antd.message;
var pop = undefined;
var alluser = undefined;

(function boot() {
	var uls = document.querySelectorAll('.user_list'),
	    navs = document.querySelectorAll('.role_nav .item');
	if (location.href.split("#")[1]) {
		util.addClass(uls[location.href.split("#")[1]], 'active');
		util.addClass(navs[location.href.split("#")[1]], 'active');
	} else {
		util.addClass(uls[0], 'active');
		util.addClass(navs[0], 'active');
	}

	util.bind(document.querySelectorAll('.role_nav .item'), 'click', function () {
		var obj = this,
		    index = obj.getAttribute('data-index'),
		    ul = uls[index];

		util.removeSiblingsClass(obj, 'active');
		util.addClass(obj, 'active');

		util.removeSiblingsClass(ul, 'active');
		util.addClass(ul, 'active');

		window.location.href = location.pathname + '#' + index;
	});

	var loadUser = false;
	util.bind(document.querySelectorAll('.add_user'), 'click', function () {
		var obj = this,
		    id = obj.getAttribute('data-id');

		if (!pop) {
			if (loadUser) {
				message.info('请稍候，正在加载用户数据');
				return;
			}
			message.info('正在加载用户数据');
			loadUser = true;
			util.get('/api/account/userList').then(function (json) {
				if (json.error_code == 0) {
					pop = new popalluser(json.data.data);
					pop.show(id);
				} else {
					message.error(json.error_msg);
				}
				loadUser = false;
			}, function (error) {
				message.error(error);
				loadUser = false;
			});
		} else {
			pop.show(id);
		}
	});

	util.bind(document.querySelectorAll('.remove_user'), 'click', function () {
		var obj = this,
		    uid = obj.getAttribute('data-uid'),
		    id = obj.getAttribute('data-id');
		if (!uid || !id) {
			message.error('出错啦，请刷新重试');
			return;
		}
		util.post('/api/acl/delGroupUser', {
			group_id: id,
			id: uid
		}).then(function (json) {
			if (json.error_code == 0) {
				message.success('移除成功');
				window.location.reload();
			} else {
				message.error(json.error_msg);
			}
		}, function (error) {
			message.error('添加失败，请重试');
		});
	});
})();

});
