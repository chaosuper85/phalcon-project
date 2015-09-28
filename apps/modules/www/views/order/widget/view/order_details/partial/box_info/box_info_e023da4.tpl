{%$status = $data.data.orderStatus%}
{%$utype = $data.user.usertype%}

{%$box = $data.data.address_assign_info%}
{%$box = $data.data.address_assign_info%}
{%$boxInfo = $data.data.order_info.product_info%}
{%$addressInfo = $data.data.order_info.address_info%}
{%$boxType = ['','20GP','40GP','40HQ']%}

{%$boxStatus = ['', '待提箱', '待产装', '待运抵', '已落箱，待运抵', '已运抵', '取消']%}

<div class="boxInfo-wrapper">
	<!-- <h3>箱/车/产装信息</h3> -->
	<table>
		<thead>
			<tr>
				<th width="8%">箱型</th>
				<th width="12%">箱号</th>
				<th width="12%">铅封号</th>
				<th width="10%">司机</th>
				<th width="10%">车牌号</th>
				<th width="32%">装箱详情</th>
				<th width="16%">操作状态</th>
			</tr>
		</thead>
		<tbody>
			{%if count($box) > 0 && !empty($box[0])%}
				{%foreach $box as $i => $item%}
					<tr>
						<td>{%$boxType[$item.box_type]%}</td>
						<td>{%$item.box_code%}</td>
						<td>{%$item.box_ensupe%}</td>
						<td class="driver">
							<p>{%$item.driver_info.name%}</p>
							<p>{%$item.driver_info.mobile%}</p>
						</td>
						<td>{%$item.driver_info.car_number%}</td>
						<td class="address-details">
							{%if isset($item.address_info) && !empty($item.address_info) &&  count($item.address_info) !== 0%}
								{%foreach $item.address_info as $j => $box_address_details%}

									<ul class="box-complete {%if $j + 1 == count($item.address_info)%}last{%/if%}">
										<li class="clearfix">
											<div class="address-content">
												<p class="address" title="{%$box_address_details.box_address_detail%}">{%$box_address_details.box_address_detail%}</p>
												<p class="time" title="{%$box_address_details.box_time%}">{%$box_address_details.box_time%}</p>
											</div>
											<div class="address-status {%if $box_address_details.assign_status == 100%}driver_status{%/if%}">
												{%if $utype == 1%}
													{%if $box_address_details.assign_status == 2 || $box_address_details.assign_status == 100%}
														<a href="javascript:;" class="complete clearfix" data-aid="{%$box_address_details.assign_id%}" data-type="{%$box_address_details.assign_status%}">产装完成</a>
														{%if $box_address_details.assign_status == 100%}<span class="status">司机已产装</span>{%/if%}
													{%else%}
														<span class="complete" title="{%$boxStatus[$box_address_details.assign_status]%}">{%$boxStatus[$box_address_details.assign_status]%}</span>
													{%/if%}
												{%else%}
													<span class="complete clearfix" title="{%if $box_address_details.assign_status < 0 && $box_address_details.assign_status > 7%}{%$boxStatus[$box_address_details.assign_status]%}{%else%}待产装{%/if%}">
													{%if $box_address_details.assign_status < 0 && $box_address_details.assign_status > 7%}
														{%$boxStatus[$box_address_details.assign_status]%}
													{%else%}
														待产装
													{%/if%}
													</span>
												{%/if%}
											</div>
										</li>
									</ul>
								{%/foreach%}
							{%/if%}
						</td>
						{%if $utype == 1%}
							<td class="funcs control hidden">
								{%if empty($item.box_code) && empty($item.box_ensupe)%}
									<a href="javascript:;" class="add-ensupe" data-id="{%$item.box_id%}">添加箱号/铅封号</a>
								{%else%}
									<a href="javascript:;" class="edit-ensupe" data-id="{%$item.box_id%}">修改箱号/铅封号</a>
								{%/if%}
								{%if $item.all_can_change%}
									{%if count($item.address_info) == 0%}
										<a href="javascript:;" class="add-assign" data-id="{%$item.box_id%}">添加司机/装箱信息</a>
									{%else%}
										<a href="javascript:;" class="edit-assign" data-id="{%$item.box_id%}">修改司机/装箱信息</a>
									{%/if%}
								{%/if%}
								<a href="{%if $item.box_status > 2%}/carteam/order/download/product_address?boxid={%$item.box_id%}{%else%}javascript:;{%/if%}" class="print {%if $item.box_status < 3%}disable{%/if%}">打印产装联系单</a>
							</td>
						{%/if%}
						<td class="funcs look {%if $utype == 2%}hidden{%/if%}">
							<div class="status-box clearfix">
								<span>{%if $item.box_status != 100%}{%$boxStatus[$item.box_status]%}{%else%}司机产装完成{%/if%}</span>
								{%if $item.box_status > 1%}<a href="/order/trace?orderid={%$data.data.order_fregiht_id%}#/{%$i + 1%}" target="_blank"><i class="icon-status-message" data-boxid="{%$item.box_id%}"></i></a>{%/if%}
							</div>
						</td>
					</tr>
				{%/foreach%}
			{%/if%}
		</tbody>
	</table>
	{%if $status < 3 && $status != 8%}
		<div class="clearfix btn-wrap export_box_info">
			<a href="/freight/order/export_box_info?orderid={%$data.data.order_fregiht_id%}" target="_blank" id="download_box_number" class="right">导出箱号/铅封号</a>
		</div>
	{%/if%}
</div>
{%script type="text/javascript"%}
	require('order/widget/view/order_details/partial/box_info/box_info').init({%json_encode($box)%},'{%$data.data.order_fregiht_id%}', {%json_encode($addressInfo)%}, '{%$data.data.carteam.carTeam_id%}');
{%/script%}