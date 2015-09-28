define('www/common/module/cards/cards', function(require, exports, module) {

function Cards(_options){
    function _Cards(_options){
        var defaults = {
            width: 500
        },
        settings = this.settings = $.extend({}, defaults, _options),
        $body = $('body'),
        $card = $('<div class="card"></div>'),
        $square = $('<div class="square"></div>'),
        $content = $('<div class="content"></div>');

        this.card = $card;
        this.content = $content;

        $card.append($square).append($content);
        $body.append($card);

        $card.css('width', settings.width).css('height', 500);
    }

    _Cards.prototype = {
        show: function(e){
            var obj = e.target;
            var $body = $('body');
            var pos = {
                top: 0,
                left: 0
            };
            while(obj){
                pos.left += obj.offsetLeft;
                pos.top += obj.offsetTop;
                obj = obj.offsetParent;
            }

            var _top, _left;

            if(pos.left - this.settings.width < 1){
                _left = pos.left;
            } else {
                _left = pos.left - this.settings.width + 20;
            }
            _top = pos.top + 25;
            
            this.card.show();
            this.card.css('left', _left).css('top', _top);
        },
        hide: function(){
            this.card.hide();
        },
        setContent: function(html){
            this.content.html(html);
        }
    }
    return new _Cards(_options);
}

module.exports = Cards;

});
