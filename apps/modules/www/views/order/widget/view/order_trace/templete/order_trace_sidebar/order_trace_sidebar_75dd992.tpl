{%$box_time_line = $data.data.box_time_line%}
{%$BOX_TYPE = ['','开顶箱','框架箱','冷冻箱','挂衣箱']%}
{%$BOX_SIZE_TYPE = ['','20GP','40GP','40HQ']%}
{%$BOX_STATUS = ['', '待提箱', '待产装', '待运抵', '已落箱，待运抵', '已运抵', '取消']%}
<!-- 侧边栏开始 -->
<div id="order_trace_sidebar">
	<ul class="clearfix">
		{%if count($box_time_line) !== 0%}
			{%foreach $box_time_line as $i => $item%}
				<li class="clearfix">
					<a href="#/{%$i + 1%}">
						<div class="sequenced">{%$i + 1%}</div>
						<div class="status">{%if $item.box_status != -1 && !empty($item.box_status) && $item.box_status != 100%}{%$BOX_STATUS[$item.box_status]%}{%/if%}{%if $item.box_status == 100%}司机已产装{%/if%}</div>
						<div class="info clearfix">
							<div class="box_driver item">
								<label>箱号</label>
								<span>{%if isset($item.box_code)%}{%$item.box_code%}{%/if%}</span>
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
	require('order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar').init();
{%/script%}


















