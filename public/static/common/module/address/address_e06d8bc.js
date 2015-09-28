define("common/module/address/address",function(e,t,i){function s(e){function t(e){var t={wrap:e.wrap||"",width:e.width||80,height:e.height||30,font_size:e.font_size||14,onProvinceSelectChange:e.onProvinceSelectChange||"",onCitySelectChange:e.onCitySelectChange||"",onAreaSelectChange:e.onAreaSelectChange||"",options_max_height:e.options_max_height||200,level:e.level||2,text_align:e.text_align||"left"},i={wrap:$(t.wrap)||null,province:$('<div class="address_province"></div>'),city:$('<div class="address_city"></div>'),area:$('<div class="address_area"></div>')};this.select={},this.province={},this.city={},this.area={},this.dom=i,this.level=f=t.level,this.select.style={width:t.width,height:t.height,font_size:t.font_size,options_max_height:t.options_max_height,text_align:t.text_align},y=t.onProvinceSelectChange,_=t.onCitySelectChange,g=t.onAreaSelectChange,this._render()}function i(e,t,i,a){var c="";for(var l in a.data[0])a.data[0][l].id===e&&(c=a.data[0][l].cityName);a.province.select.setSelectedText(c),s(e,t,i,a)}function s(e,t,i,s){s.city.select.setSelectedText("请选择市"),s.city.select.setSelectedVal("");var a="",c=[];for(var n in v[0]){var r,o={};v[0][n].id==e&&(r=v[0][n].codeid)}for(var h in v[r])t==v[r][h].id&&(a=v[r][h].cityName),o={text:v[r][h].cityName,value:v[r][h].id},c.push(o);d.setOptions(c),s.city.select.setSelectedText(a),s.city.select.setSelectedVal(t),2>=f||(s.area.select.setSelectedText("请先选择市"),s.area.select.setSelectedVal(""),s.area.select.setOptions([]),l(e,t,i,s))}function l(e,t,i,s){var a=s.city.select.text();s.city.select.setSelectedText("正在加载中..."),XDD.Request({url:"/city/getSubLocation",data:{id:t},success:function(e){if(0==e.error_code){var t=[],c="";for(var l in e.data){var n={text:e.data[l].cityName,value:e.data[l].id};i==e.data[l].id&&(c=e.data[l].cityName),t.push(n)}s.area.select.setOptions(t),s.area.select.setSelectedText(c)}else _alert(e.error_msg),s.area.select.setSelectedText("网络错误，请刷新页面！");s.area.select.setSelectedVal(i),s.city.select.setSelectedText(a)}})}function n(e,t){d.setSelectedText("请选择市"),d.setSelectedVal("");var i=[];for(var s in v[0]){var a,c={};v[0][s].id==e&&(a=v[0][s].codeid)}for(var l in v[a])c={text:v[a][l].cityName,value:v[a][l].id},i.push(c);d.setOptions(i),2>=f||(p.setSelectedText("请先选择市"),p.setSelectedVal(""),p.setOptions(new Array),"function"==typeof y&&y(e,t,{province:h,city:d,area:p}))}function r(e,t){p.setSelectedText("请选择区"),p.setSelectedVal(""),d.setSelectedText("正在加载中..."),XDD.Request({url:"/city/getSubLocation",data:{id:e},success:function(e){if(0==e.error_code){var i=[];for(var s in e.data){var a={text:e.data[s].cityName,value:e.data[s].id};i.push(a)}p.setOptions(i)}else _alert(e.error_msg);d.setSelectedText(t)}}),"function"==typeof _&&_(e,t,{province:h,city:d,area:p})}function o(e,t){"function"==typeof g&&g(e,t,{province:h,city:d,area:p})}var h,d,p,v,y,_,g,f;return t.prototype._render=function(){this.dom.wrap.append(this.dom.province),this.level>=2&&this.dom.wrap.append(this.dom.city),this.level>=3&&this.dom.wrap.append(this.dom.area),this.province.select=h=new c({container:this.dom.wrap.find(".address_province"),width:this.select.style.width,height:this.select.style.height,font_size:this.select.style.font_size,text_align:this.select.style.text_align,options_max_height:this.select.style.options_max_height,placeholder:"省/直辖市",onSelectChange:n}),2===this.level?this.city.select=d=new c({container:this.dom.wrap.find(".address_city"),width:this.select.style.width,height:this.select.style.height,font_size:this.select.style.font_size,text_align:this.select.style.text_align,options_max_height:this.select.style.options_max_height,placeholder:"请先选择省",onSelectChange:_}):this.level>2&&(this.city.select=d=new c({container:this.dom.wrap.find(".address_city"),width:this.select.style.width,height:this.select.style.height,font_size:this.select.style.font_size,text_align:this.select.style.text_align,options_max_height:this.select.style.options_max_height,placeholder:"请先选择省",onSelectChange:r})),this.level>=3&&(this.area.select=p=new c({container:this.dom.wrap.find(".address_area"),width:this.select.style.width,height:this.select.style.height,font_size:this.select.style.font_size,text_align:this.select.style.text_align,options_max_height:this.select.style.options_max_height,placeholder:"请先选择市",onSelectChange:o})),this._getCity()},t.prototype.setData=function(e){var t=e||{},s=t.pid||this.dom.wrap.attr("data-pid"),a=t.pid||this.dom.wrap.attr("data-cid"),c=t.pid||this.dom.wrap.attr("data-aid");s&&a&&c&&(this.province.select.setSelectedVal(s),i(s,a,c,this))},t.prototype._getCity=function(){var e=this,t=a.get({key:"address_cities"});t?(e._initData($.evalJSON(t)),e.setData()):XDD.Request({url:"/city/getProvinceCitys",success:function(i){0==i.error_code?(t=$.toJSON(i.data),a.set({key:"address_cities",value:t,expires:2592e5}),e._initData(i.data),e.setData()):_alert("res.error_msg")}})},t.prototype.val=function(){var e={},t={},i={};return e.id=this.province.select?this.province.select.val():"",e.name=this.province.select?this.province.select.text():"",t.id=this.city.select?this.city.select.val():"",t.name=this.city.select?this.city.select.text():"",i.id=this.area.select?this.area.select.val():"",i.name=this.area.select?this.area.select.text():"",{province:e,city:t,area:i}},t.prototype.getProvince=function(){var e={};return e.id=this.province.select?this.province.select.val():"",e.name=this.province.select?this.province.select.text():"",e},t.prototype.getCity=function(){var e={};return e.id=this.city.select?this.city.select.val():"",e.name=this.city.select?this.city.select.text():"",e},t.prototype.getArea=function(){var e={};return e.id=this.area.select?this.area.select.val():"",e.name=this.area.select?this.area.select.text():"",e},t.prototype._initData=function(e){var t={};for(var i in e){var s=e[i],a=s.parentid;t.hasOwnProperty(a)||(t[a]=[]),t[a].push(s)}var c=[];for(var l in t[0]){var n={text:t[0][l].cityName,value:t[0][l].id};c.push(n)}this.province.select.setOptions(c),this.data=v=t},new t(e)}var a=e("common/module/storage.js"),c=e("common/module/select/select.js");i.exports=s});