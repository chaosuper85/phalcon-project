define("common/module/select/select",function(require,exports,module){function SelectBox(e){var t,a={container:e.container||"",width:e.width||160,height:e.height||30,font_size:e.font_size||14,onSelectChange:e.onSelectChange||"",onSelectClick:e.onSelectClick||"",options:e.options||[],options_max_height:e.options_max_height||200,placeholder:e.placeholder||"",defaultValue:e.defaultValue||"",defaultText:e.defaultText||"",text_align:e.text_align||"left",searchBox:e.searchBox||!1,searchKeyup:e.searchKeyup||function(){}};if(!$)return void console.log("依赖jquery！请引入jquery1.7+");if(a.container){var l=this;this.placeholder=a.placeholder||"",this.defaultValue=a.defaultValue||"",this.defaultText=a.defaultText||"",this.select={},this.select.style={},this.select.style.text_align=a.text_align,this.select.style.width=a.width,this.select.style.height=a.height,this.select.style.font_size=a.font_size,this.select.style.options_max_height=a.options_max_height,"string"==typeof a.container&&(this.select.select_container=$(a.container)),"object"==typeof a.container&&(this.select.select_container=a.container),t=tpl({options:a.options,defaultValue:a.defaultValue,defaultText:a.defaultText,placeholder:a.placeholder,searchBox:a.searchBox}),this.select.select_container.html(t),this.select.select_box=this.select.select_container.find(".select-box"),this.select.selected_container=this.select.select_container.find(".select-box .selected"),this.select.selected_option=this.select.selected_container.find(".selected-text"),this.select.arrow=this.select.selected_container.find(".select-box-icon-drop"),this.select.options_container_c=this.select.select_container.find(".select-box .select-option-container"),this.select.options_container=this.select.select_container.find(".select-box .select-option"),a.searchBox&&(this.select.searchBox=this.select.options_container_c.find(".select_search_box"),this.select.searchTip=this.select.options_container_c.find("p.select_search_tip"),this.select.searchReset=this.select.options_container_c.find(".select_search_box_reset"),this.select.searchBox.on("keyup",function(e){var t=$(this).val(),s=e.keyCode;13!==s&&38!==s&&40!==s&&39!==s&&37!==s&&a.searchKeyup(l,t)}),this.select.searchReset.on("click",function(){l.select.searchBox.val(),l.setSelectedVal(""),l.setSelectedText(a.placeholder),l.hide()})),this.setSelectTextAlign(),this.setSelectFontSize(),this.setSelectWidth(),this.setSelectHeight(),this.setOptionsHeight(),bindBoxClick(this,a.onSelectClick),bindBoxLeave(this),bindSelect(this,a.onSelectChange)}}var tpl=[function(_template_object){var _template_fun_array=[],fn=function(__data__){var _template_varName="";for(var name in __data__)_template_varName+="var "+name+'=__data__["'+name+'"];';eval(_template_varName),_template_fun_array.push('<div class="select-box"><div class="selected"><span class="selected-text" data-value="',"undefined"==typeof defaultValue?"":baidu.template._encodeHTML(defaultValue),'" title="'),defaultText&&defaultValue?_template_fun_array.push("","undefined"==typeof defaultText?"":baidu.template._encodeHTML(defaultText),""):_template_fun_array.push("","undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),""),_template_fun_array.push('">'),defaultText&&defaultValue?_template_fun_array.push("","undefined"==typeof defaultText?"":baidu.template._encodeHTML(defaultText),""):_template_fun_array.push("","undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),""),_template_fun_array.push('</span><i class="select-box-icon-drop down"></i></div><div class="select-option-container">'),searchBox&&_template_fun_array.push('<div class="select-option-search"><input placeholder="请输入您要搜索的关键字" class="select_search_box"/><a href="javaScript:" class="select_search_box_reset">x</a></div><p class="select_search_tip">正在搜索...</p>'),_template_fun_array.push('<ul class="select-option">');var length=options.length;if(_template_fun_array.push(""),length>0){_template_fun_array.push(""),placeholder&&(_template_fun_array.push('<li class="'),defaultText&&defaultValue||_template_fun_array.push("active"),_template_fun_array.push('" data-value="" title="',"undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),'"><a href="javaScript:">',"undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),"</a></li>")),_template_fun_array.push("");for(var i=0;length>i;i++)_template_fun_array.push('<li class="'),defaultValue&&defaultValue==options[i].value&&_template_fun_array.push("active"),_template_fun_array.push('" data-value="',"undefined"==typeof options[i].value?"":baidu.template._encodeHTML(options[i].value),'" title="',"undefined"==typeof options[i].text?"":baidu.template._encodeHTML(options[i].text),'"><a href="javaScript:">',"undefined"==typeof options[i].text?"":baidu.template._encodeHTML(options[i].text),"</a></li>");_template_fun_array.push("")}else _template_fun_array.push(""),placeholder&&(_template_fun_array.push('<li class="'),defaultText&&defaultValue||_template_fun_array.push("active"),_template_fun_array.push('" data-value="',"undefined"==typeof defaultValue?"":baidu.template._encodeHTML(defaultValue),'" title="',"undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),'"><a href="javaScript:">',"undefined"==typeof(placeholder||"请选择")?"":baidu.template._encodeHTML(placeholder||"请选择"),"</a></li>")),_template_fun_array.push("");_template_fun_array.push("</ul></div></div>"),_template_varName=null}(_template_object);return fn=null,_template_fun_array.join("")}][0];SelectBox.prototype.setSelectTextAlign=function(e){var e=e||this.select.style.text_align;this.select.select_container.css("text-align",e)},SelectBox.prototype.setSelectFontSize=function(e){var t=e||this.select.style.font_size;this.select.select_container.css("font-size",t)},SelectBox.prototype.setSelectWidth=function(e){var t=e||this.select.style.width;this.select.select_container.css("width",t)},SelectBox.prototype.setSelectHeight=function(e){var t=e||this.select.style.height;this.select.select_container.css({"line-height":t+"px",height:t+"px"}),this.select.options_container_c.css("top",t+"px")},SelectBox.prototype.setOptionsHeight=function(e){var t=e||this.select.style.options_max_height;this.select.options_container.css({"max-height":t+"px"})},SelectBox.prototype.show=function(){var e=this.select.arrow;this.select.select_box.css("z-index","9999"),e.addClass("up").removeClass("down"),this.select.options_container_c.slideDown("fast")},SelectBox.prototype.hide=function(){var e=this.select.arrow;this.select.select_box.css("z-index","1"),e.addClass("down").removeClass("up"),this.select.options_container_c.slideUp("fast")},SelectBox.prototype.setOptions=function(e){if("object"==typeof e){for(var t=this.select.options_container,a="",l=e.length,s=0;l>s;s++){var i=e[s];if(i.text)var o=i.text;else var o="";if(i.value)var c=i.value;else var c="";a+=this.val()==c?'<li class="active" data-value="'+c+'" title="'+o+'"><a href="javaScript:">'+o+"</a></li>":'<li data-value="'+c+'" title="'+o+'"><a href="javaScript:">'+o+"</a></li>"}t.html(a)}},SelectBox.prototype.appendOptions=function(e){if("object"==typeof e){for(var t=this.select.options_container,a="",l=e.length,s=0;l>s;s++){var i=e[s];if(i.text)var o=i.text;else var o="";if(i.value)var c=i.value;else var c="";a+=this.val()==c?'<li class="active" data-value="'+c+'" title="'+o+'"><a href="javaScript:">'+o+"</a></li>":'<li data-value="'+c+'" title="'+o+'"><a href="javaScript:">'+o+"</a></li>"}t.append(a)}},SelectBox.prototype.val=function(){return this.select.selected_option.attr("data-value")},SelectBox.prototype.text=function(){return this.select.selected_option.text()},SelectBox.prototype.setSelectedText=function(e){this.select.selected_option.attr("title",e),this.select.selected_option.text(e)},SelectBox.prototype.setSelectedVal=function(e){this.select.selected_option.attr("data-value",e);var t=this.select.options_container.find("li");0!==t.length&&t.each(function(){var t=$(this),a=t.attr("data-value");t.removeClass("active"),e==a&&t.addClass("active")})};var bindBoxClick=function(e,t){e.select.selected_container.on("click",function(a){a.preventDefault();var l=e.select.arrow;l.hasClass("down")?e.show():l.hasClass("up")&&e.hide(),"function"==typeof t&&t(e)})},bindBoxLeave=function(e){e.select.select_container.on("mouseleave",function(){e.hide()})},bindSelect=function(e,t){e.select.options_container.on("click","li",function(){{var a=$(this),l=a.attr("data-value"),s=a.attr("title"),i=e.select.selected_option.attr("data-value");e.select.selected_option.attr("title")}e.select.selected_option.attr("data-value",l),e.select.selected_option.attr("title",s),e.select.selected_option.text(s);var o=e.select.options_container.find("li");o.each(function(){var e=$(this),t=e.attr("data-value");e.removeClass("active"),l==t&&e.addClass("active")}),e.hide(),i!==l&&"function"==typeof t&&t(l,s,e)})};module.exports=SelectBox});