define('common/module/addSelectTimeBox/addSelectTimeBox', function(require, exports, module) {

/**
 * [添加时间控件]
 * 传入container（jq对象）、addName添加按钮（string）
 * @param {[type]} _option [description]
 * by weiqi
 */
var SelectTimeBox = require("common/module/selectTimeBox/selectTimeBox.js");
function AddSelectTimeBox(_option){
    function _AddSelectTimeBox(_option){
        var option = {
            container: _option.container || $('body'),
            addName: _option.addName || '.add-date',
            height:  _option.height || 30
        };
        var $scope = option.container.$scope || option.container;
        var addName = option.addName;

        var selectTimeBox = this.selectTimeBox = [];
        var flag = 1;
        var del_Btn = $('<a class="delTimeBtn" href="javaScript:" title="删除装箱时间"><i class="icon-del-timeBox" data-flag=""></i></a>');
        
        var $items = $scope.find('.package_date_selectBox');
        if($items.length !== 0){
            flag = $items.length;
            $items.each(function(index, el) {
                var f = index + 1;
                selectTimeBox.push({
                    container: '.package_date_selectBox.data_' + f,
                    flag: f,
                    box: new SelectTimeBox({
                        wrap: $scope.find('.package_date_selectBox.data_' + f),
                        level: 5,
                        height: option.height
                    }),
                    timeData: {
                        id: $scope.find('.package_date_selectBox.data_' + f).attr('data-timeId'),
                        can_change: $scope.find('.package_date_selectBox.data_' + f).attr('data-canChange') || 1
                    }
                });
            });
        } else {
            selectTimeBox.push({
                container: '.package_date_selectBox.data_1',
                flag: flag,
                box: new SelectTimeBox({
                    wrap: $scope.find('.package_date_selectBox.data_1'),
                    level: 5,
                    height: option.height
                })
            });
        }

        

        // 添加按钮
        $scope.on('click', addName, function(event) {
            var select_time_list = $scope.find('.package_date_selectBox');
            if(select_time_list.length > 9) return;

            flag++;
            var addBtn = $(this);
            var timeSelectBox = $('<div class="package_date_selectBox data_' + flag + ' clearfix" data-flag="' + flag + '"></div>');

            addBtn.before(timeSelectBox);
            selectTimeBox.push({
                container: timeSelectBox,
                flag: flag,
                box: new SelectTimeBox({
                    wrap: $scope.find('.package_date_selectBox.data_' + flag),
                    level: 5,
                    height: option.height
                }),
                timeData: {
                    id: '',
                    can_change: 1
                }
            });
        });
        // 鼠标悬浮删除按钮显示
        $scope.on('mouseover', '.package_date_selectBox', function(event) {
            var $selectBox = $(this);
            var getFlag = $selectBox.attr('data-flag');
            if(getFlag == 1) return;
            $selectBox.append(del_Btn);
            del_Btn.show();
            del_Btn.attr('data-flag', getFlag);
        });

        // 鼠标离开删除按钮消失
        $scope.on('mouseleave', '.package_date_selectBox', function(event) {
            var $selectBox = $(this);
            var getFlag = $selectBox.attr('data-flag');
            if(getFlag == 1) return;
            $selectBox.append(del_Btn);
            del_Btn.hide();
            del_Btn.removeAttr('data-flag');
        });

        // 点击删除按钮
        $scope.on('click', '.delTimeBtn', function(event) {
            var del_Btn_obj = $(this);
            var getFlag = del_Btn_obj.attr('data-flag');
            var length = selectTimeBox.length;
            for (var i = 0; i < length; i++) {
                if(selectTimeBox[i].flag == getFlag){
                    selectTimeBox.splice(i, 1);
                    $scope.find('.package_date_selectBox.data_' + getFlag).remove();
                    return
                }
            };
            
        });
    }
    // 获取时间选择对象，返回数组
    _AddSelectTimeBox.prototype.getTimeBox = function(){
        var i,
            length = this.selectTimeBox.length;
        var timeList = [];
        for (i = 0; i < length; i++) {
            timeList.push(this.selectTimeBox[i].box.val());
        };
        return timeList;
    }
    // 获取时间选择对象，返回数组
    _AddSelectTimeBox.prototype.getTimeBoxId = function(){
        var i,
            length = this.selectTimeBox.length;
        var timeList = [];
        for (i = 0; i < length; i++) {
            timeList.push({
                time: this.selectTimeBox[i].box.val(),
                time_id: this.selectTimeBox[i].timeData.id,
                can_change: this.selectTimeBox[i].timeData.can_change
            });
        };
        return timeList;
    }
    return new _AddSelectTimeBox(_option);
}
module.exports = AddSelectTimeBox;

});
