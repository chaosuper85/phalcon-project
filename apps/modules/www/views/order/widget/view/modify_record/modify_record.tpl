<div id="modify_record">
	<div class="breadcrumb">
		<ol>
			<li><h3>陆运服务</h3></li>
			<li><a href="/order/list?orderid={%$data.data.order_fregiht_id%}">订单列表></a></li>
			<li><a href="/order/details?orderid={%$data.data.order_fregiht_id%}">交易订单></a></li>
			<li class="active">运单</li>
		</ol>
	</div>
	<div class="record">
		<h4>修改记录：</h4>
		<div class="modify-content">
			{%foreach $data.data as $record%}
			<div class="content">
				<p class="date">{%$record.date%}</p>
				<p class="user">{%$record.user%}<span>{%$record.operateType%}</span></p>
				<p class="operations">{%$record.content%}</p>
			</div>
			{%/foreach%}
		</div>
	</div>
</div>
