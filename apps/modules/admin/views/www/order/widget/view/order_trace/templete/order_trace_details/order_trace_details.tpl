{%$box_time_line = $data.data.box_time_line%}
<div id="trace_details_content">
	<div class="details">
		{%if count($box_time_line) !== 0%}
			{%foreach $box_time_line as $i => $item%}
				{%$detail_length = count($item.detail)%}
				<div class="box_detail hidden" data-num="{%$i + 1%}">
					<h3>您的货物已完成产装，预计9月07日完成落箱</h3>
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
								<p>{%$content.content%}{%$j%}</p>
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
			<span>廊坊市109国道100.438</span>
		</div>
		<!-- 物流地图开始 -->
		<div id="trace_map"></div>
		<!-- 物流地图结束 -->
	</div>
</div>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=d27d23298c924c68b45ee1f9ef2eddb7"></script>
{%script type="text/javascript"%}
	require('www/order/widget/view/order_trace/templete/order_trace_details/order_trace_details').init({%json_encode($box_time_line)%});
{%/script%}