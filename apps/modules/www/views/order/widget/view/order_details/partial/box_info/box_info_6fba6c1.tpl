 {%$status = $data.data.orderStatus%}
{%$utype = $data.user.usertype%}

{%$box = $data.data.address_assign_info%}
{%$box = $data.data.address_assign_info%}
{%$boxInfo = $data.data.order_info.product_info%}
{%$addressInfo = $data.data.order_info.address_info%}
{%$boxType = ['','20GP','40GP','40HQ','' ]%}

{%$boxStatus = ['', '待提箱', '待产装', '待运抵', '已落箱，待运抵', '已运抵', '交易关闭']%}

<div class="boxInfo-wrapper">
	<!-- <h3>箱/车/产装信息</h3> -->
	<table>
		<thead>
			<tr>
				<th width="8%">箱型</th>
				<th width="8%">箱号</th>
				<th width="8%">铅封号</th>
				<th width="10%">司机</th>
				<th width="10%">车牌号</th>
				<th width="28%">装货详情</th>
				{%if $utype == 2%}
					<th width="16%">操作/状态</th>
				{%/if%}
				{%if $utype == 1%}
					{%if $data.dispatch == 1%}
						<th class="status-thead" width="14%">操作/状态</th>
						<th class="dispatch-thead hidden" width="14%">调度</th>
					{%else%}
						<th class="status-thead hidden" width="14%">操作/状态</th>
						<th class="dispatch-thead" width="14%">调度</th>
					{%/if%}
				{%/if%}
			</tr>
		</thead>
		<tbody>
			{%if count($box) > 0 && !empty($box[0])%}
				{%foreach $box as $i => $item%}
					<tr id="box_item_hash_{%$i%}">
						<td rowspan="{%count($item.address_info)%}">{%$boxType[$item.box_type]%}</td>
						<td rowspan="{%count($item.address_info)%}">{%$item.box_code%}</td>
						<td rowspan="{%count($item.address_info)%}">{%$item.box_ensupe%}</td>
						<td class="driver" rowspan="{%count($item.address_info)%}">
							<p>{%$item.driver_info.name%}</p>
							<p>{%$item.driver_info.mobile%}</p>
						</td>
						<td rowspan="{%count($item.address_info)%}">{%$item.driver_info.car_number%}</td>
						<td class="address-details">
							{%if {%count($item.address_info)%} > 0%}
								<p>{%$item.address_info[0].box_address_detail%}</p>
								<p>{%$item.address_info[0].box_time%}</p>
							{%/if%}
						</td>
						{%if $utype == 2%}
							<td class="funcs">
							{%if {%count($item.address_info)%} > 0%}
								{%if $item.address_info[0].assign_status == 2 || $item.address_info[0].assign_status == 100%}
									<span class="complete" title="待产装">待产装</span>
									{%if $item.box_status > 1 && $item.box_status != 8%}
										<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
									{%/if%}
								{%else%}
									<span class="complete" title="{%$boxStatus[$item.address_info[0].assign_status]%}">{%$boxStatus[$item.address_info[0].assign_status]%}</span>
									{%if $item.box_status > 1 && $item.box_status != 8%}
										<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
									{%/if%}
								{%/if%}
							{%/if%}
							</td>
						{%/if%}
						{%if $utype == 1%}
							<td class="funcs status-td {%if $data.dispatch == 2%}hidden{%/if%}">
								{%if {%count($item.address_info)%} > 0%}
									{%if $item.address_info[0].assign_status == 2 || $item.address_info[0].assign_status == 100%}
										{%if $item.address_info[0].assign_status == 100%}
											<span class="complete" title="司机已产装">司机已产装</span>
										{%else%}
											<span class="complete" title="待产装">待产装</span>
										{%/if%}
										{%if $item.box_status > 1 && $item.box_status != 8%}
											<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
										{%/if%}
										<a href="javascript:;" class="complete clearfix" data-aid="{%$item.address_info[0].assign_id%}" data-type="{%$item.address_info[0].assign_status%}">产装完成</a>
									{%else%}
										<span class="complete" title="{%$boxStatus[$item.address_info[0].assign_status]%}">{%$boxStatus[$item.address_info[0].assign_status]%}</span>
										{%if $item.box_status > 1 && $item.box_status != 8%}
											<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
										{%/if%}
									{%/if%}
								{%/if%}
							</td>
							<td class="funcs control-td control {%if $data.dispatch != 2%}hidden{%/if%}" rowspan="{%count($item.address_info)%}">
								{%if empty($item.box_code) && empty($item.box_ensupe)%}
									<a href="javascript:;" class="add-ensupe" data-id="{%$item.box_id%}">添加箱号/铅封号</a>
								{%else%}
									<a href="javascript:;" class="edit-ensupe" data-id="{%$item.box_id%}">修改箱号/铅封号</a>
								{%/if%}
								{%if $item.all_can_change%}
									{%if count($item.address_info) == 0%}
										<a href="javascript:;" class="add-assign" data-id="{%$item.box_id%}">添加司机/装货信息</a>
									{%else%}
										<a href="javascript:;" class="edit-assign" data-id="{%$item.box_id%}">修改司机/装货信息</a>
									{%/if%}
								{%/if%}
								<a href="{%if count($item.address_info) != 0%}/carteam/order/product_address_list?orderid={%$data.data.order_fregiht_id%}%}{%else%}javascript:;{%/if%}" class="print {%if count($item.address_info) == 0%}disable{%/if%}" target="_blank">查看装货单</a>
							</td>
						{%/if%}
					</tr>
					{%if isset($item.address_info) && !empty($item.address_info) &&  count($item.address_info) !== 0%}
						{%foreach $item.address_info as $j => $box_address_details%}
							{%if $j > 0%}
								<tr>
									<td>
										<p>{%$box_address_details.box_address_detail%}</p>
										<p>{%$box_address_details.box_time%}</p>
									</td>
									<td class="funcs status-td {%if $data.dispatch == 2%}hidden{%/if%}">
										{%if $box_address_details.assign_status == 2 || $box_address_details.assign_status == 100%}
											{%if $utype == 1 && $box_address_details.assign_status == 100%}
												<span class="complete" title="司机已产装">司机已产装</span>
											{%else%}
												<span class="complete" title="待产装">待产装</span>
											{%/if%}
											{%if $item.box_status > 1 && $item.box_status != 8%}
												<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
											{%/if%}
											{%if $utype == 1%}
												<a href="javascript:;" class="complete clearfix" data-aid="{%$box_address_details.assign_id%}" data-type="{%$box_address_details.assign_status%}">产装完成</a>
											{%/if%}
										{%else%}
											<span class="complete" title="{%$boxStatus[$box_address_details.assign_status]%}">{%$boxStatus[$box_address_details.assign_status]%}</span>
											{%if $item.box_status > 1 && $item.box_status != 8%}
												<a class="box_trace" href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>
											{%/if%}
										{%/if%}
									</td>
								</tr>
							{%/if%}
						{%/foreach%}
					{%/if%}
				{%/foreach%}
			{%/if%}
		</tbody>
	</table>
	{%if $utype == 2 && ($status > 2 && $status != 8)%}
		<div class="clearfix btn-wrap export_box_info">
			<a href="/freight/order/export_box_info?orderid={%$data.data.order_fregiht_id%}" target="_blank" id="download_box_number" class="right">导出箱号/铅封号</a>
		</div>
	{%/if%}
</div>
{%script type="text/javascript"%}
	require('order/widget/view/order_details/partial/box_info/box_info').init({%json_encode($box)%},'{%$data.data.order_fregiht_id%}', {%json_encode($addressInfo)%}, '{%$data.data.carteam.carTeam_id%}');
{%/script%}