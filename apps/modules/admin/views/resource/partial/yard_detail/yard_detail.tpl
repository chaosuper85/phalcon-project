{%$yard = array()%}
{%if isset($data.yardinfo)%}
	{%$yard = $data.yardinfo%}
{%/if%}

<div class="yard">
	<div class="form-wrap">
		<dl class="x-form">
			<dd>
				<span class="title"><i>*</i>堆场名称</span>
				<div class="content">
					<input type="text" placeholder="请输入堆场名" value="{%if isset($yard.yard_name)%}{%$yard.yard_name%}{%/if%}" id="data_name"/>
				</div>
			</dd>
			<dd class="clearfix">
				<span class="title"><i>*</i>所在城市</span>
				<div class="content">
					<div id="yard-city"></div>
				</div>
			</dd>
			<dd>
				<span class="title"><i>*</i>堆场位置</span>
				<div class="content">
					<ul class="locations">
						<li class="clearfix">
							{%if isset($yard.location_type_5) && $yard.location_type_5%}
								<span type="location_type_5" class="mark type5" data-lat={%$yard.location_type_5.latitude%} data-lng={%$yard.location_type_5.longitude%}>堆场中心</span>
								<p class="txt">{%$yard.location_type_5.longitude%},{%$yard.location_type_5.latitude%}</span>
							{%else%}
								<span type="location_type_5" class="mark type5">堆场中心</span>
								<p class="txt">添加坐标</span>
							{%/if%}
						</li>
						<li class="clearfix">
							{%if isset($yard.location_type_1) && $yard.location_type_1%}
								<span type="location_type_1" class="mark type1" data-lat={%$yard.location_type_1.latitude%} data-lng={%$yard.location_type_1.longitude%}>重车入口</span>
								<p class="txt">{%$yard.location_type_1.longitude%},{%$yard.location_type_1.latitude%}</p>
							{%else%}
								<span type="location_type_1" class="mark type1">重车入口</span>
								<p class="txt">添加坐标</p>
							{%/if%}
						</li>
						<li class="clearfix">
							{%if isset($yard.location_type_2) && $yard.location_type_2%}
								<span type="location_type_2" class="mark type2" data-lat={%$yard.location_type_2.latitude%} data-lng={%$yard.location_type_2.longitude%}>重车出口</span>
								<p class="txt">{%$yard.location_type_2.longitude%},{%$yard.location_type_2.latitude%}</p>
							{%else%}
								<span type="location_type_2" class="mark type2">重车出口</span>
								<p class="txt">添加坐标</p>
							{%/if%}
						</li>
						<li class="clearfix">
							{%if isset($yard.location_type_3) && $yard.location_type_3%}
								<span type="location_type_3" class="mark type3" data-lat={%$yard.location_type_3.latitude%} data-lng={%$yard.location_type_3.longitude%}>轻车入口</span>
								<p class="txt">{%$yard.location_type_3.longitude%},{%$yard.location_type_3.latitude%}</p>
							{%else%}
								<span type="location_type_3" class="mark type3">轻车入口</span>
								<p class="txt">添加坐标</p>
							{%/if%}
						</li>
						<li class="clearfix">
							{%if isset($yard.location_type_4) && $yard.location_type_4%}
								<span type="location_type_4" class="mark type4" data-lat={%$yard.location_type_4.latitude%} data-lng={%$yard.location_type_4.longitude%}>轻车出口</span>
								<p class="txt">{%$yard.location_type_4.longitude%},{%$yard.location_type_4.latitude%}</p>
							{%else%}
								<span type="location_type_4" class="mark type4">轻车出口</span>
								<p class="txt">添加坐标</p>
							{%/if%}
						</li>
						<li class="clearfix">
							Tips: 请直接在地图上拖拽图标修改位置
						</li>
					</ul>
				</div>
			</dd>
			<dd class="submit-wrap">
				<a href="javascript:;" class="submit" id="yard-submit">提 交</a>
			</dd>
			<dd class="submit-wrap">
				<a href="javascript:;" class="cancle">取消修改</a>
			</dd>
		</dl>
	</div>
	<div class="map-wrap">
		<div id="map"></div>
		<div id="map-search">
			<div id="yard-city-map"></div>
			<div class="input-wrap">
				<div id="search-input"></div>
				<div class="iconfont icon-search" id="search-btn"></div>
			</div>
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('resource/partial/yard_detail/yard_detail')({%json_encode($yard)%});
{%/script%}