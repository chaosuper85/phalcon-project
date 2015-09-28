define('common/module/upload/upload', function(require, exports, module) {

/*
 * kenny
 */

'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var util = require('common/module/util/util');

var uploader = (function () {
    function uploader(opt) {
        var _this = this;

        _classCallCheck(this, uploader);

        if (!window.File || !window.FileReader || !window.FormData) {
            alert('浏览器不支持上传文件，请用最新版本chrome');
            return;
        }
        this.opt = opt;

        util.bind(document.querySelectorAll(opt.target), 'change', function (e) {
            var file = e.target.files[0];
            if (file) {
                if (/^image\//i.test(file.type)) {}
                _this.readFile(file);
            }
        });
    }

    _createClass(uploader, [{
        key: 'readFile',
        value: function readFile(file) {
            var _this2 = this;

            var reader = new FileReader();
            reader.onloadend = function () {
                _this2.processFile(reader.result, file.type);
            };

            reader.onerror = function () {
                alert('读取文件失败!');
            };

            reader.readAsDataURL(file);
        }
    }, {
        key: 'processFile',
        value: function processFile(dataURL, fileType) {
            this.sendFile(dataURL);
        }
    }, {
        key: 'sendFile',
        value: function sendFile(fileData) {
            var formData = new FormData();
            var me = this;

            formData.append('drivers', fileData);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: me.opt.url,
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function success(data) {
                    me.opt.uploadSuccess && me.opt.uploadSuccess(data);
                },
                error: function error(data) {
                    alert('上传出错!');
                }
            });
        }
    }]);

    return uploader;
})();

module.exports = uploader;

});
