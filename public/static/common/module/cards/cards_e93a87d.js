define("common/module/cards/cards",function(t,s,e){function i(t){function s(t){var s={width:500},e=this.settings=$.extend({},s,t),i=$("body"),n=$('<div class="card"></div>'),d=$('<div class="square"></div>'),o=$('<div class="content"></div>');this.card=n,this.content=o,n.append(d).append(o),i.append(n),n.css("width",e.width).css("height",500)}return s.prototype={show:function(t){for(var s=t.target,e=($("body"),{top:0,left:0});s;)e.left+=s.offsetLeft,e.top+=s.offsetTop,s=s.offsetParent;var i,n;n=e.left-this.settings.width<1?e.left:e.left-this.settings.width+20,i=e.top+25,this.card.hide(),this.card.slideDown("fast"),this.card.css("left",n).css("top",i)},hide:function(){this.card.slideUp("fast")},setContent:function(t){this.content.html(t)}},new s(t)}e.exports=i});