define('www/common/module/storage', function(require, exports, module) {

/**
 * 前端缓存组件
 * userData + LocalStorage
 */

var storage = {};

/**
 * 验证字符串是否合法的键名
 */
storage._isValidKey = function (key) {
    return (new RegExp("^[^\\x00-\\x20\\x7f\\(\\)<>@,;:\\\\\\\"\\[\\]\\?=\\{\\}\\/\\u0080-\\uffff]+\x24")).test(key);
};

/**
 * 在IE7及其以下版本中，采用UserData的方式进行本地存储
 */
storage.userData = (function () {

    // 所有的key
    var _clearAllKey = "_ALL.KEY_";

    // 创建并获取这个input:hidden实例
    var _getInstance = function () {
        //把UserData绑定到input:hidden上
        var _input = null;
        //是的，不要惊讶，这里每次都会创建一个input:hidden并增加到DOM树种
        //目的是避免数据被重复写入，提早造成“磁盘空间写满”的Exception
        _input = document.createElement("input");
        _input.type = "hidden";
        _input.addBehavior("#default#userData");
        document.body.appendChild(_input);
        return _input;
    };

    /**
     * 将数据通过UserData的方式保存到本地，文件名为：文件名为：config.key[1].xml
     * @param {String} key 待存储数据的key，和config参数中的key是一样的
     * @param {Object} config 待存储数据相关配置
     * @cofnig {String} key 待存储数据的key
     * @config {String} value 待存储数据的内容
     * @config {String|Object} [expires] 数据的过期时间，可以是数字，单位是毫秒；也可以是日期对象，表示过期时间
     * @private
     */
    var __setItem = function (key, config) {
        try {
            var input = _getInstance();
            //创建一个Storage对象
            var storageInfo = config || {};
            //设置过期时间
            if (storageInfo.expires) {
                var expires;
                //如果设置项里的expires为数字，则表示数据的能存活的毫秒数
                if ('number' == typeof storageInfo.expires) {
                    expires = new Date();
                    expires.setTime(expires.getTime() + storageInfo.expires);
                }
                input.expires = expires.toUTCString();
            }
            //存储之前，先移除storageInfo对象中的expires值
            storageInfo.expires = null;
            //存储数据
            input.setAttribute(storageInfo.key, storageInfo.value);
            //存储到本地文件，文件名为：storageInfo.key[1].xml
            input.save(storageInfo.key);
        } catch(e) {}
    };

    /**
     * 将数据通过UserData的方式保存到本地，文件名为：文件名为：config.key[1].xml
     * @param {String} key 待存储数据的key，和config参数中的key是一样的
     * @param {Object} config 待存储数据相关配置
     * @cofnig {String} key 待存储数据的key
     * @config {String} value 待存储数据的内容
     * @config {String|Object} [expires] 数据的过期时间，可以是数字，单位是毫秒；也可以是日期对象，表示过期时间
     * @private
     */
    var _setItem = function (key, config) {
        //保存有效内容
        __setItem(key, config);

        //下面的代码用来记录当前保存的key，便于以后clearAll
        result = _getItem({
            key: _clearAllKey
        });
        if (result) {
            result = {
                value: result
            };
        } else {
            result = {
                key: _clearAllKey,
                value: ""
            };
        }
        if (! (new RegExp("(^|\|)" + key + "(\||$)")).test(result.value)) {
            result.value += "|" + key;
            //保存键
            __setItem(_clearAllKey, result);
        }
    };

    /**
     * 提取本地存储的数据
     * @param {String} config 待获取的存储数据相关配置
     * @cofnig {String} key 待获取的数据的key
     * @return {String} 本地存储的数据，获取不到时返回null
     * @example 
     * storage.get({
     *    key : "username"
     * });
     * @private
     */
    var _getItem = function (config) {
        try {
            var input = _getInstance();
            //载入本地文件，文件名为：config.key[1].xml
            input.load(config.key);
            //取得数据
            return input.getAttribute(config.key) || null;
        } catch(e) {
            return null;
        }
    };

    /**
     * 移除某项存储数据
     * @param {Object} config 配置参数
     * @cofnig {String} key 待存储数据的key
     * @private
     */
    var _removeItem = function (config) {
        try {
            var input = _getInstance();
            //载入存储区块
            input.load(config.key);
            //移除配置项
            input.removeAttribute(config.key);
            //强制使其过期
            var expires = new Date();
            expires.setTime(expires.getTime() - 1);
            input.expires = expires.toUTCString();
            input.save(config.key);
        } catch(e) {}
    };

    /**
     * 移除所有的本地数据
     * @return 无
     * @private 
     */
    var _clearAll = function () {
        result = _getItem({
            key: _clearAllKey
        });
        if (result) {
            result = {
                value: result
            };
            var allKeys = (result.value || "").split("|");
            var count = allKeys.length;
            for (var i = 0; i < count; i++) {
                _removeItem({
                    key: allKeys[i]
                });
            }
        }
    };

    return {
        getItem: _getItem,
        setItem: _setItem,
        removeItem: _removeItem,
        clearAll: _clearAll
    };
})();

/**
 * 判断当前浏览器是否支持本地存储：window.localStorage
 */
storage._isSupportLocalStorage = function () {
    return ('localStorage' in window) && (window['localStorage'] !== null);
};

storage.isSupportStorage = function () {
    return ('localStorage' in window) && (window['localStorage'] !== null) || document.all;
};

/**
 * 将数据进行本地存储（只能存储字符串信息）
 * storage.set({
 *    key : "username",
 *    value : "kenny",
 *    expires : 3600 * 1000
 * });
 */
storage.set = function (config) {

    if (!storage._isValidKey(config.key)) {
        return;
    }

    var storageInfo = config || {};
    //支持本地存储的浏览器：IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.setItem(storageInfo.key, storageInfo.value);
        if (storageInfo.expires) {
            // 仅允许传毫秒
            var _date = new Date();
            
            window.localStorage.setItem(storageInfo.key + ".expires", _date.getTime() + storageInfo.expires);
        }
    } else { //IE7及以下版本，采用UserData方式
        storage.userData.setItem(storageInfo.key, storageInfo);
    }
};

/**
 * 提取本地存储的数据
 * storage.get({
 *    key : "username"
 * });
 */
storage.get = function (config) {

    var result = null;
    
    if (!storage._isValidKey(config.key)) {
        return result;
    }
    //IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        var _value = window.localStorage.getItem(config.key);
        //过期时间判断，如果过期了，则移除该项
        if (_value) {
            var expire = window.localStorage.getItem(config.key + ".expires");

            if (expire) {
                var _expire = new Date(parseInt(expire, 10));
            } else {
                var _expire = null;
            }

            result = {
                'value': _value,
                'expires': _expire
            };
            if (result && result.expires && result.expires < new Date()) {
                result = null;
                window.localStorage.removeItem(config.key);
            }
        }
    } else {
        //这里不用单独判断其expires，因为UserData本身具有这个判断
        result = storage.userData.getItem(config);
        if (result) {
            result = {
                value: result
            };
        }
    }

    return result ? result.value : null;
};

/**
 * 移除某一项本地存储的数据
 * storage.remove({
 *    key : "username"
 * });
 */
storage.remove = function (config) {
    //IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.removeItem(config.key);
        window.localStorage.removeItem(config.key + ".expires");
    } else {
        storage.userData.removeItem(config);
    }
};

/**
 * 清除所有本地存储的数据
 * storage.clearALl();
 */
storage.clearAll = function () {
    //支持本地存储的浏览器：IE8+、Firefox3.0+、Opera10.5+、Chrome4.0+、Safari4.0+、iPhone2.0+、Andrioid2.0+
    if (storage._isSupportLocalStorage()) {
        window.localStorage.clear();
    } else {
        storage.userData.clearAll();
    }
};

module.exports = storage;

});
