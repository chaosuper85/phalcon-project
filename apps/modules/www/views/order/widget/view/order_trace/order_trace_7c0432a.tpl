{%$order_time_line = $data.data.order_time_line%}
{%$tidan = $data.data.tidan%}
{%$freight = $data.data.freight%}
{%$IS_Ok = 1%}
<div id="order_trace">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><h3>陆运服务</h3></li>
			<li><a href="/order/list">订单列表</a></li>
			<li>></li>
			<li class="active">物流详情</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 物流流程开始 -->
	<div class="trace_procedure clearfix">
		<ul>
			<li class="step_1 {%if isset($order_time_line[1]) && $order_time_line[1].ok === $IS_Ok%}active{%/if%}">
				<dl>
					<dt>提箱</dt>
					<dd>{%if isset($order_time_line[1])%}{%$order_time_line[1].create_time%}{%/if%}</dd>
				</dl>
			</li>
			<li class="line_1 {%if isset($order_time_line[2]) && $order_time_line[2].ok === $IS_Ok%}active{%/if%}">
				<div class="line"></div>
			</li>
			<li class="step_2 {%if isset($order_time_line[2]) && $order_time_line[2].ok === $IS_Ok%}active{%/if%}">
				<dl>
					<dt>产装</dt>
					<dd>{%if isset($order_time_line[2])%}{%$order_time_line[2].create_time%}{%/if%}</dd>
				</dl>
			</li>
			<li class="line_2 {%if isset($order_time_line[3]) && $order_time_line[3].ok === $IS_Ok%}active{%/if%}">
				<div class="line"></div>
			</li>
			<li class="step_3 {%if isset($order_time_line[3]) && $order_time_line[3].ok === $IS_Ok%}active{%/if%}">
				<dl>
					<dt>落箱</dt>
					<dd>{%if isset($order_time_line[3])%}{%$order_time_line[3].create_time%}{%/if%}</dd>
				</dl>
			</li>
			<li class="line_3 {%if isset($order_time_line[4]) && $order_time_line[4].ok === $IS_Ok%}active{%/if%}">
				<div class="line"></div>
			</li>
			<li class="step_4 {%if isset($order_time_line[4]) && $order_time_line[4].ok === $IS_Ok%}active{%/if%}">
				<dl>
					<dt>运抵</dt>
					<dd>{%if isset($order_time_line[4])%}{%$order_time_line[4].create_time%}{%/if%}</dd>
				</dl>
			</li>
		</ul>
	</div>
	<!-- 物流流程结束 -->
	<!-- 提单信息开始 -->
	<div class="trace_info clearfix">
		<div class="num left">
			<label>提单号：</label>
			<span>{%$tidan%}</span>
		</div>
		<div class="company right">
			<label>承运方：</label>
			<span>{%$freight.name%}</span>
			<a href="tel:{%$freight.contactNumber%}">{%$freight.contactNumber%}</a>
		</div>
	</div>
	<!-- 提单信息结束 -->
	<!-- 物流详情开始 -->
	<div class="trace_details clearfix">
	
		<!-- 侧边栏开始 -->
		{%widget name="order/widget/view/order_trace/templete/order_trace_sidebar/order_trace_sidebar.tpl"%}
		<!-- 侧边栏结束 -->

		<!-- 物流详情开始 -->
		{%widget name="order/widget/view/order_trace/templete/order_trace_details/order_trace_details.tpl"%}
		<!-- 物流详情结束 -->
	</div>
	<!-- 物流详情结束 -->
</div>


{%script type="text/javascript"%}
	
{%/script%}
























