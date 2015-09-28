define('common/widget/pager/pager', function(require, exports, module) {


var util = require('common/module/util.js');

jQuery.fn.pagination = function(maxentries, opts){
	
	opts = jQuery.extend({
	
		items_per_page:10,
		num_display_entries:10,
		current_page:0,
		num_edge_entries:0,
		link_to:"#",
		prev_text:" ",
		next_text:" ",
		ellipse_text:"..",
		prev_show_always:false,
		next_show_always:false,
        path:'',
		callback:function(){return false;}

	},opts||{});
	
	return this.each(function() {

		function numPages() {
			return Math.ceil(maxentries/opts.items_per_page);
		}
		/**
		 * 极端分页的起始和结束点，这取决于current_page 和 num_display_entries.
		 * @返回 {数组(Array)}
		 */
		function getInterval()  {
			var ne_half = Math.ceil(opts.num_display_entries/2);
			var np = numPages();
			var upper_limit = np-opts.num_display_entries;
			var start = current_page>ne_half?Math.max(Math.min(current_page-ne_half, upper_limit), 0):0;
			var end = current_page>ne_half?Math.min(current_page+ne_half, np):Math.min(opts.num_display_entries, np);
			return [start,end];
		}
		
		/**
		 * 分页链接事件处理函数
		 * @参数 {int} page_id 为新页码
		 */
		function pageSelected(page_id, evt){
			current_page = page_id;
			drawLinks();
			var continuePropagation = opts.callback(page_id, panel);
			if (!continuePropagation) {
				if (evt.stopPropagation) {
					evt.stopPropagation();
				}
				else {
					evt.cancelBubble = true;
				}
			}
			return continuePropagation;
		}
		
		/**
		 * 此函数将分页链接插入到容器元素中
		 */
		function drawLinks() {
			panel.empty();
			var interval = getInterval();
			var np = numPages();
			// 这个辅助函数返回一个处理函数调用有着正确page_id的pageSelected。
			var getClickHandler = function(page_id) {
				return function(evt){ return pageSelected(page_id,evt); }
			}
			//辅助函数用来产生一个单链接(如果不是当前页则产生span标签)
			var appendItem = function(page_id, appendopts){
				page_id = page_id<0?0:(page_id<np?page_id:np-1); // 规范page id值
				appendopts = jQuery.extend({text:page_id+1, classes:""}, appendopts||{});
				if(page_id == current_page){
					var lnk = jQuery("<span class='current'>"+(appendopts.text)+"</span>");
				}else{
                    var url = window.location.pathname + '?';
                    if(opts.path){
                        url = url+opts.path+'&page=';
                    }else{
                        url = url+'page=';
                    }
                    
					if(appendopts.classes == 'first' || appendopts.classes == 'last'){
						var lnk = jQuery("<a><div>"+(appendopts.text)+"</div></a>")
							.attr('href', url+(page_id+1));		
					}else{
						var lnk = jQuery("<a>"+(appendopts.text)+"</a>")
							//.bind("click", getClickHandler(page_id))
							.attr('href', url+(page_id+1));		
					}
				}
				if(appendopts.classes){
					lnk.addClass(appendopts.classes);
				}
				panel.append(lnk);
			}

			// First
			if(opts.prev_text && (current_page > 0 || opts.prev_show_always)){
                appendItem(0,{text:"", classes:"first"});
			}
			// 产生起始点
			if (interval[0] > 0 && opts.num_edge_entries > 0)
			{
				var end = Math.min(opts.num_edge_entries, interval[0]);
				for(var i=0; i<end; i++) {
					appendItem(i,{classes:"page-item"});
				}
				if(opts.num_edge_entries < interval[0] && opts.ellipse_text)
				{
					jQuery("<span>"+opts.ellipse_text+"</span>").appendTo(panel);
				}
			}
			// 产生内部的些链接
			for(var i=interval[0]; i<interval[1]; i++) {
				appendItem(i,{classes:"page-item"});
			}
			// 产生结束点
			if (interval[1] < np && opts.num_edge_entries > 0)
			{
				if(np-opts.num_edge_entries > interval[1]&& opts.ellipse_text)
				{
					jQuery("<span>"+opts.ellipse_text+"</span>").appendTo(panel);
				}
				var begin = Math.max(np-opts.num_edge_entries, interval[1]);
				for(var i=begin; i<np; i++) {
					appendItem(i,{classes:"page-item"});
				}
			}

			// Last
			if(opts.next_text && (current_page < np-1 || opts.next_show_always)){
                appendItem(np,{text:"", classes:"last"});
			}

            panel.append("<span class='total-page'>共"+np+"页</span>")
		}
		
		//从选项中提取current_page
		var current_page = opts.current_page;
        
		//创建一个显示条数和每页显示条数值
		maxentries = (!maxentries || maxentries < 0)?1:maxentries;
		opts.items_per_page = (!opts.items_per_page || opts.items_per_page < 0)?1:opts.items_per_page;
		//存储DOM元素，以方便从所有的内部结构中获取
		var panel = jQuery(this);
        
		// 获得附加功能的元素
		this.selectPage = function(page_id){ pageSelected(page_id);}
		this.prevPage = function(){ 
			if(current_page > 0){
				pageSelected(current_page - 1);
				return true;
			}
			else {
				return false;
			}
		}
		this.nextPage = function(){
			if(current_page < numPages()-1) {
				pageSelected(current_page+1);
				return true;
			}
			else {
				return false;
			}
		}

		drawLinks();
        opts.callback(current_page, this);
	});
}

module.exports = {

    init : function(total) {
    
        $(document).ready(function(){
            var page = util.getQuery('page') || 1,
                params = util.getParam(),
                total_page = total,
                type = type || '';
                
            if(params.hasOwnProperty("page")){
                delete params.page;
            }
            
            var path = util.getUrl(params);
            
            $(".pager").pagination(total_page, {
                num_edge_entries : 0,    // 边缘页数
                current_page : page - 1,
                num_display_entries : 5, // 主体页数
                path : path,
                items_per_page : 1       // 每页显示1项
            });
        
        })
        
    }
}





});
