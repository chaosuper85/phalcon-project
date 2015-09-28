{%$box_time_line = $data.data.box_time_line%}
{%$BOX_STATUS = ['','待产装','待落箱','待运抵', '已落箱，待运抵', '已运抵', '取消']%}
<!-- 侧边栏开始 -->
<div id="order_trace_sidebar">
	<ul class="clearfix">
		{%if count($box_time_line) !== 0%}
			{%foreach $box_time_line as $i => $item%}
				<li class="clearfix">
					<a href="#/{%$i + 1%}">
						<div class="sequenced">{%$i + 1%}</div>
						<div class="status">{%if $item.box_status != -1 && !empty($item.box_status)%}{%$BOX_STATUS[$item.box_status]%}{%/if%}</div>
						<div class="info clearfix">
							<div class="box_type item">
								<label>箱型</label>
								<span>{%if $item.box_type == 1 || $item.box_type == 2 || $item.box_type == 3 || $item.box_type == 4 || $item.box_type == 5 || $item.box_type == 6%}{%$item.box_type%}{%/if%}</span>
							</div>
							<div class="box_address item">
								<label>装箱地</label>
								<span title="{%if isset($item.box_address)%}{%$item.box_address%}{%/if%}">{%if isset($item.box_address)%}{%$item.box_address%}{%/if%}</span>
							</div>
							<div class="box_driver item">
								<label>司机</label>
								<span>{%if isset($item.driver_name)%}{%$item.driver_name%}{%/if%}</span>
							</div>
							<div class="box_mobile item">
								<label>手机</label>
								<span>{%if isset($item.driver_mobile)%}{%$item.driver_mobile%}{%/if%}</span>
							</div>
						</div>
					</a>
				</li>
			{%/foreach%}
		{%/if%}
	</ul>
</div>
<!-- 侧边栏结束 -->
{%script type="text/javascript"%}
	require('www/order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar').init();
{%/script%}


















