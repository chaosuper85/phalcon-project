define('user/partial/driver/driver', function(require, exports, module) {

'use strict';

var util = require('common/module/util/util');
var message = antd.message;
var msg = {
	Q_TYPE_DENIED: '只允许上传excel格式文件',
	F_EXCEED_SIZE: '文件大小不得超过2M',
	Q_EXCEED_NUM_LIMIT: '一次只能上传一个文件'
};

var info = document.querySelector('.info');

var team_id = util.getTheParam('id');

function uploadInit() {

	var uploader = WebUploader.create({
		server: '/api/carTeam/importDriver?team_id=' + team_id,
		pick: '#upload',
		accept: {
			extensions: 'xlsx,xls'
		},
		fileNumLimit: 1,
		fileSingleSizeLimit: 1024 * 1024 * 2
	});
	uploader.on('fileQueued', function (file) {
		info.innerHTML = file.name;
	});

	uploader.on('beforeFileQueued', function (file) {
		var files = uploader.getFiles();
		if (files.length) {
			uploader.reset();
			info.innerHTML = '';
		}
	});

	uploader.on('uploadProgress', function (file, percentage) {
		info.innerHTML = '正在上传' + percentage * 100 + '%';
	});

	uploader.on('uploadSuccess', function (file, response) {
		if (response.error_code == 0) {
			message.success(response.error_msg, 3000);
			setTimeout(function () {
				location.reload();
			}, 2000);
		} else {
			message.error(response.error_msg, 3000);
			uploader.reset();
			info.innerHTML = '请重新选择';
		}
	});

	uploader.on('uploadError', function (file) {
		console.log('上传出错');
	});

	uploader.on('error', function (type) {
		if (type in msg) {
			message.error(msg[type]);
		} else {
			message.error('出错啦，错误信息：' + type);
		}
	});
	uploader.on('uploadComplete', function (file) {});
	document.getElementById('submit').addEventListener('click', function () {
		if (!uploader.getFiles().length) {
			message.error('请选择上传文件');
			return;
		}
		uploader.upload();
	});
}

module.exports = {
	init: function init() {
		uploadInit();
	}
};

});
