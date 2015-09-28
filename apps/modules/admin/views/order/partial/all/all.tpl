{%$orders = $data.data%}
{%$orderType = ['全部','待确认','待分派','待提箱','待产装','待运抵','待评价','交易成功','交易关闭']%}
{%$param = $data.paras%}
{%$active = array(10)%}
{%for $i = 0; $i < 10; $i++%}
	{%$active[$i] = ''%}
{%/for%}
{%if isset($param.order_status)%}
	{%$active[$param.order_status] = "active"%}
{%else%}
	{%$active[0] = "active"%}
{%/if%}

<div id="example"></div>
<ul class="table-filter clearfix">
	<li>
		<span class="title">查找</span>
		<div id="filter_search_type"></div>
	</li>
	<li>
		<input type="text" class="filter-input"/>
	</li>
	<li>
		<a href="javascript:;" class="btn" role="button" id="search">搜索</a>
	</li>
	<li>
		{%widget name="common/widget/table/date_filter.tpl/date.tpl" from="order_create_start_time" to="order_create_end_time"%}
	</li>
	<li>
		<a href="javascript:;" role="button" id="clearAll">清除条件</a>
	</li>
</ul>
<table class="x-table order-all-table" border="1">
	<ul class="x-table-nav">
		{%foreach $orderType as $i => $type%}
			{%if $i == 0%}
				<a href="/ordersuper/orders" class="item {%$active[0]%}">{%$type%}</a>
			{%else%}
				<a href="/ordersuper/orders?order_status={%$i%}" class="item {%$active[$i]%}">{%$type%}</a>
			{%/if%}
		{%/foreach%}
	</ul>
	<thead>
		<tr>
			<th width="12%">创建时间</th>
			<th width="15%">委托方</th>
			<th width="15%">承运方</th>
			<th width="14%">装货地</th>
			<th width="14%">船信息</th>
			<th width="10%">箱形/箱量</th>
			<th width="10%">订单状态</th>
			<th width="10%">跟单员</th>
		</tr>
	</thead>
	<tbody>
		{%foreach $orders as $i => $order%}
		{%if $i%2 == 1%}
			<tr class="double">
		{%else%}
			<tr>
		{%/if%}
				<td>{%$order.create_time%}</td>
				<td>
					{%if isset($order.freight_agent_company_info.unverify_enterprisename)%}
						{%$order.freight_agent_company_info.unverify_enterprisename%}
					{%/if%}
				</td>
				<td>
					{%if isset($order.carrier_company_info.unverify_enterprisename)%}
						{%$order.carrier_company_info.unverify_enterprisename%}
					{%/if%}
				</td>
				<td>
					{%if !empty($order.order_product_info)%}
						{%$order.order_product_info[0].address%}
					{%/if%}
				</td>
				<td>{%$order.ship_info.full_china_name%}</td>
				<td>
					{%if $order.box_20gp_count%}
						<p>20GP * {%$order.box_20gp_count%}</p>
					{%/if%}
					{%if $order.box_40gp_count%}
						<p>40GP * {%$order.box_40gp_count%}</p>
					{%/if%}
					{%if $order.box_40hq_count%}
						<p>40HQ * {%$order.box_40hq_count%}</p>
					{%/if%}
				</td>
				<td>
					{%$orderType[$order.order_status]%}
					<a href="/orderDetail?order_id={%$order.id%}" target="_blank" class="txtbtn">订单详情</a>
					{%if $order.order_status !== '1'%}
						<a href="/logisticDetail?order_id={%$order.id%}" target="_blank" class="txtbtn">物流追踪</a>
					{%/if%}
				</td>
				<td class="func">
					{%if $order.supervisor_info%}
						<p>{%$order.supervisor_info.username%}</p>
						<a href="javascript:;" data-id="{%$order.id%}" data-uid="{%$order.supervisor_info.id%}" class="btn style2 editManager">更换</a>
					{%else%}
						<a href="javascript:;" data-id="{%$order.id%}" class="btn style1 editManager">添加</a>
					{%/if%}
				</td>
			</tr>
		{%/foreach%}
	</tbody>
</table>
{%if $data.data_sum == 0%}
	<div class="table-nodata"><span><i class="iconfont icon-tixing"></i>暂无数据</span></div>
{%/if%}

{%if $data.page_sum >= 2%}
	{%widget name="common/widget/pager/pager.tpl" total=$data.data_sum current=$data.page_no%}
{%/if%}

{%script type="text/javascript"%}
	require('order/partial/all/all').init({%json_encode($param)%});
{%/script%}
