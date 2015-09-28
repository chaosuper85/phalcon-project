{%$box_time_line = $data.data.box_time_line%}
<div id="trace_details_content">
	<div class="details">
		{%if count($box_time_line) !== 0%}
			{%foreach $box_time_line as $i => $item%}
				{%$detail_length = count($item.detail)%}
				<div class="box_detail hidden" data-num="{%$i + 1%}">
					<div class="box_driver_detail clearfix">
						<div class="ensupe"> 
							<h3>箱&nbsp;&nbsp;&nbsp;&nbsp;号：{%$item.box_code%}</h3>
							<h3>铅封号：{%$item.box_ensupe%}</h3>
						</div>
						<div class="driver">
							<ul>
								<li>
									<label>司机名称：</label>
									<span>{%$item.driver_name%}</span>
								</li>
								<li>
									<label>联系电话：</label>
									<span>{%$item.driver_mobile%}</span>
								</li>
								<li>
									<label>车牌号：</label>
									<span>{%$item.car_number%}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="box_address_info clearfix">
						<div class="box_address_info_title">装箱地址</div>
						<ul class="box_address_info_content">
							{%foreach $item.chanzhuanginfo as $ad => $address%}
								<li>{%$address%}</li>
							{%/foreach%}
						</ul>
					</div>
					<h3>{%$item.detail_last.content%}</h3>
					<ul class="content">
						{%foreach $item.detail as $j => $content%}
						<li class="{%if $j == 0%}start{%else if $detail_length == $j+1%}end{%else%}process{%/if%} clearfix">
							<div class="timeline">
								<p>{%$content.create_time%}</p>
							</div>
							<div class="traceline">
								<div class="dot"></div>
								{%if $detail_length != $j+1 && $detail_length !== 1%}<div class="line"></div>{%/if%}
							</div>
							<div class="info">
								<p>{%$content.content%}</p>
							</div>
						</li>
						{%/foreach%}
					</ul>
				</div>
			{%/foreach%}
		{%/if%}
		<div class="box_address">
			<i class="icon-mark"></i>
			<label>当前位置：</label>
			<span></span>
		</div>
		<!-- 物流地图开始 -->
		<div class="box_address_map">
			<div id="trace_map"></div>
		</div>\
		<!-- 物流地图结束 -->
	</div>
</div>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=d27d23298c924c68b45ee1f9ef2eddb7"></script>
{%script type="text/javascript"%}
	require('order/widget/view/order_trace/templete/order_trace_details/order_trace_details').init({%json_encode($box_time_line)%});
{%/script%}